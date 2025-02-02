<?php

namespace App\Http\Controllers\Helpers;

use Exception;

class PushNotification {
    public static function SendOneSignalNotification($oneSignalIds, $heading, $content, $url = null, $additionalData = [])
    {
        try {
            // Validate inputs
            if (empty($oneSignalIds)) {
                throw new Exception('OneSignal IDs are required');
            }

            if (empty($heading) || empty($content)) {
                throw new Exception('Heading and content are required');
            }

            // Prepare notification data
            $payload = [
                'app_id' => "37ff0e3f-4bea-494a-b17f-0da06fa8bba4",
                'include_subscription_ids' => is_array($oneSignalIds) ? $oneSignalIds : [$oneSignalIds],
                'headings' => ['en' => $heading, 'id' => $heading],
                'contents' => ['en' => $content, 'id' => $content],
                "requireInteraction" => true,
            ];

            // Add URL if provided
            if (!empty($url)) {
                $payload['url'] = $url;
            }

            // Send request to OneSignal
            $ch = curl_init('https://onesignal.com/api/v1/notifications');
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                // 'Authorization: Basic ' . env('ONESIGNAL_REST_API_KEY')
                'Authorization: ' . "os_v2_app_g77q4p2l5jeuvml7bwqg7kf3uqzsxpqs6wwu664ewichkpjbrvqcdeulllt2zovbrfr3ttyet5qrrgehwi4sldrqqg3oxgghaetpirq",
            ]);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);

            if ($httpCode !== 200) {
                throw new Exception('Failed to send notification. Response: ' . $response);
            }
        } catch (Exception $e) {
            return $e;
        }
    }
}

