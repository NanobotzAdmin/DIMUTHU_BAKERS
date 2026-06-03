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
     * Send a push notification to an Expo Push Token.
     *
     * @param string $token
     * @param string $title
     * @param string $body
     * @param array $data
     * @return bool
     */
    public function sendToToken($token, $title, $body, $data = [])
    {
        // Expo Push API endpoint
        $url = 'https://exp.host/--/api/v2/push/send';
        $payload = [[
            'to' => $token,
            'title' => $title,
            'body' => $body,
            'data' => (object) $data,
            'sound' => 'default',
            'priority' => 'high',
            'channelId' => 'default'
        ]];

        try {
            Log::info("Sending Expo push notification", ["payload" => $payload]);
            $response = Http::withoutVerifying()->asJson()->post('https://exp.host/--/api/v2/push/send', $payload);

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
