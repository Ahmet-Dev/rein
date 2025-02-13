<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || $request->user()->role->name !== 'admin') {
            return response()->json(['message' => 'Yetkisiz erişim.'], 403);
        }

        return $next($request);
    }
}

