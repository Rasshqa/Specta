<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OneSignalService
{
    /**
     * Send a notification to all subscribed users (Admins).
     *
     * @param string $title The title of the notification
     * @param string $message The body of the notification
     * @return void
     */
    public static function sendNewPaymentNotification(string $title, string $message): void
    {
        $appId = env('ONESIGNAL_APP_ID');
        $restApiKey = env('ONESIGNAL_REST_API_KEY');

        if (empty($appId) || empty($restApiKey)) {
            Log::warning('OneSignal credentials are not set in .env. Skipping push notification.');
            return;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $restApiKey,
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ])->post('https://onesignal.com/api/v1/notifications', [
                'app_id'            => $appId,
                'included_segments' => ['Total Subscriptions'],
                'headings'          => ['en' => $title],
                'contents'          => ['en' => $message],
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
