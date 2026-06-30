<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\UmUser;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Create a notification record and send a push notification.
     * This is the main entry point for sending notifications.
     *
     * @param int $userId
     * @param string $title
     * @param string $body
     * @param string|null $type       e.g., 'order_request', 'payment', 'daily_load'
     * @param string|null $action     e.g., 'approved', 'dispatched', 'completed'
     * @param int|null $referenceId   e.g., order_request.id
     * @param array $data             Extra data for frontend routing
     * @return Notification|null
     */
    public function createAndSend($userId, $title, $body, $type = null, $action = null, $referenceId = null, $data = [])
    {
        try {
            // 1. Save to DB
            $notification = Notification::create([
                'user_id' => $userId,
                'title' => $title,
                'body' => $body,
                'type' => $type,
                'action' => $action,
                'reference_id' => $referenceId,
                'data' => $data,
            ]);

            // 2. Send push notification
            $this->sendPushNotification($userId, $title, $body, array_merge($data, [
                'notification_id' => $notification->id,
                'type' => $type,
                'action' => $action,
                'reference_id' => $referenceId,
            ]));

            return $notification;
        } catch (\Exception $e) {
            Log::error("Failed to create and send notification: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Send notifications to multiple users.
     *
     * @param array $userIds
     * @param string $title
     * @param string $body
     * @param string|null $type
     * @param string|null $action
     * @param int|null $referenceId
     * @param array $data
     * @return array Created notification records
     */
    public function sendToMultipleUsers(array $userIds, $title, $body, $type = null, $action = null, $referenceId = null, $data = [])
    {
        $notifications = [];
        foreach ($userIds as $userId) {
            $notification = $this->createAndSend($userId, $title, $body, $type, $action, $referenceId, $data);
            if ($notification) {
                $notifications[] = $notification;
            }
        }
        return $notifications;
    }

    /**
     * Send a push notification to a specific user.
     *
     * @param int $userId
     * @param string $title
     * @param string $body
     * @param array $data
     * @return bool
     */
    public function sendPushNotification($userId, $title, $body, $data = [])
    {
        $user = UmUser::find($userId);

        if (!$user || !$user->fcm_token) {
            Log::info("Notification not sent: User {$userId} not found or has no FCM token.");
            return false;
        }

        return $this->sendToToken($user->fcm_token, $title, $body, $data);
    }

    /**
     * Send a push notification to a Firebase FCM Device Token.
     *
     * @param string $token
     * @param string $title
     * @param string $body
     * @param array $data
     * @return bool
     */
    public function sendToToken($token, $title, $body, $data = [])
    {
        $accessToken = $this->getFcmAccessToken();
        if (!$accessToken) {
            Log::error("Unable to send push notification: FCM access token could not be generated.");
            return false;
        }

        $relPath = env('FIREBASE_CREDENTIALS_PATH', 'storage/app/firebase-credentials.json');
        $credentialsPath = base_path($relPath);
        if (!file_exists($credentialsPath)) {
            Log::error("Firebase credentials file missing, cannot fetch project ID.");
            return false;
        }
        $json = json_decode(file_get_contents($credentialsPath), true);
        $projectId = $json['project_id'] ?? null;

        if (!$projectId) {
            Log::error("FCM Project ID is missing from credentials file.");
            return false;
        }

        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        $formattedData = [];
        foreach ($data as $key => $value) {
            $formattedData[$key] = is_array($value) ? json_encode($value) : (string) $value;
        }

        $payload = [
            'message' => [
                'token' => $token,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'data' => (object) $formattedData,
                'android' => [
                    'priority' => 'high',
                    'notification' => [
                        'sound' => 'default',
                    ],
                ],
                'apns' => [
                    'payload' => [
                        'aps' => [
                            'sound' => 'default',
                        ],
                    ],
                ],
            ],
        ];

        try {
            Log::info("Sending FCM push notification", ["payload" => $payload]);
            $response = Http::withoutVerifying()
                ->withToken($accessToken)
                ->asJson()
                ->post($url, $payload);

            if ($response->successful()) {
                Log::info("Push notification sent successfully via FCM to token: {$token}");
                return true;
            } else {
                Log::error("Failed to send FCM push notification. Response: " . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error("Error sending FCM push notification: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate OAuth2 Access Token for Firebase Cloud Messaging using Service Account credentials.
     *
     * @return string|null
     */
    private function getFcmAccessToken()
    {
        try {
            $relPath = env('FIREBASE_CREDENTIALS_PATH', 'storage/app/firebase-credentials.json');
            $credentialsPath = base_path($relPath);

            if (!file_exists($credentialsPath)) {
                Log::error("Firebase credentials file not found at: {$credentialsPath}");
                return null;
            }

            $json = json_decode(file_get_contents($credentialsPath), true);
            if (!$json || !isset($json['private_key']) || !isset($json['client_email']) || !isset($json['project_id'])) {
                Log::error("Invalid Firebase credentials JSON format.");
                return null;
            }

            $privateKey = $json['private_key'];
            $clientEmail = $json['client_email'];

            $header = json_encode(['alg' => 'RS256', 'typ' => 'JWT']);
            $now = time();
            $payload = json_encode([
                'iss' => $clientEmail,
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                'aud' => 'https://oauth2.googleapis.com/token',
                'exp' => $now + 3600,
                'iat' => $now
            ]);

            $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
            $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

            $signature = '';
            if (!openssl_sign($base64UrlHeader . "." . $base64UrlPayload, $signature, $privateKey, 'SHA256')) {
                Log::error("Failed to sign JWT with private key using OpenSSL.");
                return null;
            }
            $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

            $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

            $response = Http::withoutVerifying()->asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt
            ]);

            if ($response->successful()) {
                return $response->json()['access_token'];
            }

            Log::error('Failed to obtain Google OAuth token: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Exception while obtaining Firebase access token: ' . $e->getMessage());
            return null;
        }
    }
}
