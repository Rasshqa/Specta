<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * VerifyMootaWebhookSignature
 *
 * Moota sends a secret token in the Authorization header.
 * We compare it against our configured MOOTA_WEBHOOK_SECRET.
 * Any request that does not supply the correct token is immediately
 * rejected with 403 so the endpoint cannot be abused.
 */
class VerifyMootaWebhookSignature
{
    public function handle(Request $request, Closure $next): Response
    {
        $expected = config('services.moota.webhook_secret');

        // Only enforce verification when a secret is configured
        if ($expected) {
            // Moota sends the token as a plain Bearer token in Authorization
            $provided = $request->bearerToken()
                     ?? $request->header('X-Moota-Signature')
                     ?? $request->input('token');

            if (! hash_equals($expected, (string) $provided)) {
                \Illuminate\Support\Facades\Log::warning('Moota webhook: invalid signature', [
                    'ip'      => $request->ip(),
                    'headers' => $request->headers->all(),
                ]);

                return response()->json(['message' => 'Unauthorized.'], 403);
            }
        }

        return $next($request);
    }
}
