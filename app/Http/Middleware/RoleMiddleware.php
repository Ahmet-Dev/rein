<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!$request->user() || $request->user()->role->name !== $role) {
            return response()->json(['message' => 'Yetkisiz erişim.'], 403);
        }

        return $next($request);
    }
}
