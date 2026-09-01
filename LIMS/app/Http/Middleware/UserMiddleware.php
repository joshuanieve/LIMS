<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if ((int) session('role_id') !== 2) {
            return redirect()->route('welcome')->with('status', 'Access denied.');
        }

        return $next($request);
    }
}