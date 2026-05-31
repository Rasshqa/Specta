<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class FirebaseService
{
    /**
     * Send a push notification to all admin devices via FCM HTTP v1 API.
     *
     * Flow:
     *   Laravel → FCM HTTP v1 API (using Service Account OAuth2 token)
     *   → Google → Android device
     *
     * Admin devices subscribe to the 'admin_alerts' FCM topic in the Flutter app.
     * Laravel broadcasts to that topic so all admins are notified simultaneously.
     *
     * @param string $title   Notification title
     * @param string $body    Notification body
     * @return void
     */
    public static function sendNewPaymentNotification(string $title, string $body): void
    {
        $projectId     = config('services.firebase.project_id');
        $credentialPath = config('services.firebase.credentials');

        if (empty($projectId) || empty($credentialPath)) {
            Log::warning('[FCM] Firebase credentials not configured in services.php. Skipping push.');
            return;
        }

        if (!file_exists($credentialPath)) {
            Log::error("[FCM] Service account file not found at: {$credentialPath}");
            return;
        }

        try {
            $accessToken = self::getAccessToken($credentialPath);

            $response = Http::withToken($accessToken)
                ->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", [
                    'message' => [
                        'topic'        => 'admin_alerts',   // matches subscribeToTopic() in Flutter
                        'notification' => [
                            'title' => $title,
                            'body'  => $body,
                        ],
                        'data' => [
                            'title' => $title,
                            'body'  => $body,
                            'type'  => 'payment_alert',
                        ],
                        'android' => [
                            'priority' => 'high',
                            'notification' => [
                                'channel_id'             => 'transaction_alerts_channel',
                                'notification_priority'  => 'PRIORITY_MAX',
                                'default_sound'          => true,
                                'default_vibrate_timings'=> true,
                                'visibility'             => 'PUBLIC',
                            ],
                        ],
                    ],
                ]);

            if ($response->failed()) {
                Log::error('[FCM] Notification failed', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
            } else {
                Log::info('[FCM] Notification sent successfully', [
                    'message_id' => $response->json('name'),
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('[FCM] Exception when sending notification', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Generate a short-lived OAuth2 Bearer token from the Service Account JSON.
     * Token is cached for 55 minutes (Google tokens expire in 60 minutes).
     *
     * @param string $credentialPath Absolute path to the Firebase service account JSON
     * @return string Bearer access token
     */
    private static function getAccessToken(string $credentialPath): string
    {
        return Cache::remember('firebase_access_token', now()->addMinutes(55), function () use ($credentialPath) {
            $credentials = json_decode(file_get_contents($credentialPath), true);

            $now = time();
            $header = base64_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
            $claim  = base64_encode(json_encode([
                'iss'   => $credentials['client_email'],
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                'aud'   => 'https://oauth2.googleapis.com/token',
                'iat'   => $now,
                'exp'   => $now + 3600,
            ]));

            $signingInput = "{$header}.{$claim}";

            openssl_sign(
                $signingInput,
                $signature,
                $credentials['private_key'],
                'SHA256'
            );

            $jwt = $signingInput . '.' . base64_encode($signature);

            $tokenResponse = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion'  => $jwt,
            ]);

            if ($tokenResponse->failed()) {
                throw new \RuntimeException('[FCM] Failed to fetch access token: ' . $tokenResponse->body());
            }

            return $tokenResponse->json('access_token');
        });
    }
}
