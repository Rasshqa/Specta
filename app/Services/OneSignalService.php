<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OneSignalService
{
    /**
     * Send a notification to all subscribed users (Admins).
     *
     * OneSignal REST API v1:
     *   - For legacy REST API keys: Authorization: Basic <key>
     *   - For OAuth tokens (os_v2_app_...): Authorization: Bearer <token>
     *
     * @param string $title   The title of the notification
     * @param string $message The body of the notification
     * @return void
     */
    public static function sendNewPaymentNotification(string $title, string $message): void
    {
        $appId      = env('ONESIGNAL_APP_ID');
        $restApiKey = env('ONESIGNAL_REST_API_KEY');

        if (empty($appId) || empty($restApiKey)) {
            Log::warning('OneSignal credentials are not set in .env. Skipping push notification.');
            return;
        }

        // Detect token type: OAuth tokens start with 'os_v2_app_'
        $authHeader = str_starts_with($restApiKey, 'os_v2_')
            ? 'Key ' . $restApiKey      // OAuth format (newer OneSignal)
            : 'Basic ' . $restApiKey;   // Legacy REST API key

        try {
            $response = Http::withHeaders([
                'Authorization' => $authHeader,
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ])->post('https://api.onesignal.com/notifications', [
                'app_id'            => $appId,
                'included_segments' => ['All'],     // 'All' targets every subscriber
                'headings'          => ['en' => $title],
                'contents'          => ['en' => $message],
                'android_channel_id' => 'transaction_alerts_channel', // matches Flutter channel
                'priority'          => 10,          // max priority for heads-up display
            ]);

            if ($response->failed()) {
                Log::error('OneSignal Notification Failed', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
            } else {
                Log::info('OneSignal Notification Sent Successfully', [
                    'response' => $response->json(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('OneSignal Exception', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
