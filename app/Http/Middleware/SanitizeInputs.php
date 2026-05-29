<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeInputs
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isJson()) {
            $this->clean($request->json());
        } else {
            $this->clean($request->request);
        }

        return $next($request);
    }

    /**
     * Recursively clean the parameter bag.
     *
     * @param \Symfony\Component\HttpFoundation\ParameterBag $bag
     */
    private function clean($bag)
    {
        $bag->replace($this->cleanArray($bag->all()));
    }

    /**
     * Recursively clean an array of inputs.
     *
     * @param array $data
     * @return array
     */
    private function cleanArray(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->cleanArray($value);
            } else {
                // Keep values untouched if they aren't strings (e.g. files, booleans, null)
                if (is_string($value)) {
                    $data[$key] = trim(strip_tags($value));
                }
            }
        }
        return $data;
    }
}
