<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Check if user is authenticated
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // 2. Check if user's role is in the allowed roles array
        if (!in_array($request->user()->role, $roles)) {
            return response()->json([
                'message' => 'Unauthorized access. Insufficient permissions for your role (' . $request->user()->role . ').'
            ], 403);
        }

        return $next($request);
    }
}