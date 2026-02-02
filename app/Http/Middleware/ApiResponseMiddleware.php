<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ApiResponseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Start execution time tracking
        $startTime = microtime(true);

        // Generate request ID
        $requestId = Str::uuid()->toString();

        // Add request ID to request for potential use in controllers
        $request->merge(['request_id' => $requestId]);

        $response = $next($request);

        // Calculate execution time
        $executionTime = round((microtime(true) - $startTime) * 1000, 2); // in milliseconds

        // Only modify JSON responses
        if ($response->headers->get('Content-Type') === 'application/json') {
            $originalContent = json_decode($response->getContent(), true);

            // If it's already our standard format, just add metadata
            if (isset($originalContent['success'])) {
                $enhancedContent = [
                    'success' => $originalContent['success'],
                    'data' => $originalContent['data'] ?? null,
                    'message' => $originalContent['message'] ?? null,
                    'meta' => [
                        'timestamp' => now()->toISOString(),
                        'execution_time_ms' => $executionTime,
                        'request_id' => $requestId,
                        'api_version' => 'v1',
                        'path' => $request->path(),
                        'method' => $request->method(),
                        'status_code' => $response->getStatusCode(),
                    ],
                ];

                // Add pagination info if present
                if (isset($originalContent['data']) && is_array($originalContent['data']) && isset($originalContent['data']['current_page'])) {
                    $enhancedContent['meta']['pagination'] = [
                        'current_page' => $originalContent['data']['current_page'],
                        'per_page' => $originalContent['data']['per_page'],
                        'total' => $originalContent['data']['total'],
                        'last_page' => $originalContent['data']['last_page'],
                        'from' => $originalContent['data']['from'],
                        'to' => $originalContent['data']['to'],
                    ];
                }
            } else {
                // Wrap non-standard responses
                $enhancedContent = [
                    'success' => $response->isSuccessful(),
                    'data' => $originalContent,
                    'message' => $response->isSuccessful() ? 'Request processed successfully' : 'Request failed',
                    'meta' => [
                        'timestamp' => now()->toISOString(),
                        'execution_time_ms' => $executionTime,
                        'request_id' => $requestId,
                        'api_version' => 'v1',
                        'path' => $request->path(),
                        'method' => $request->method(),
                        'status_code' => $response->getStatusCode(),
                    ],
                ];
            }

            $response->setContent(json_encode($enhancedContent));
        }

        // Add common headers
        $response->headers->set('X-API-Version', 'v1');
        $response->headers->set('X-Request-ID', $requestId);
        $response->headers->set('X-Execution-Time', $executionTime . 'ms');

        return $response;
    }
}
