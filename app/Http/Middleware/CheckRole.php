<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Compare actual user role to the role required by the route
        if (!$request->user() || $request->user()->role !== $role) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized. This action requires ' . ucfirst($role) . ' privileges.'
            ], 403);
        }

        return $next($request);
    }
}
