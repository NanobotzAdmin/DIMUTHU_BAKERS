<?php

namespace App\Services;

use App\Models\UmUser;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationService
{
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
     * Send a push notification to an FCM token.
     *
     * @param string $token
     * @param string $title
     * @param string $body
     * @param array $data
     * @return bool
     */
    public function sendToToken($token, $title, $body, $data = [])
    {
        return false; // Temporarily disabled

        // For Expo Push Notifications (recommended for Expo apps)
        // URL: https://exp.host/--/api/v2/push/send
        
        $payload = [[
            'to' => $token,
            'title' => $title,
            'body' => $body,
            'data' => (object) $data,
            'sound' => 'default',
        ]];

        try {
            Log::info("Sending Expo push notification", ["payload" => $payload]);
            $response = Http::asJson()->post('https://exp.host/--/api/v2/push/send', $payload);

            if ($response->successful()) {
                Log::info("Push notification sent successfully to token: {$token}");
                return true;
            } else {
                Log::error("Failed to send push notification. Payload: " . json_encode($payload) . " Response: " . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error("Error sending push notification: " . $e->getMessage());
            return false;
        }
    }
}
