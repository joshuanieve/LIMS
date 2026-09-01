<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if ((int) session('role_id') !== 1) {
            return redirect()->route('welcome')->with('status', 'Access denied.');
        }

        return $next($request);
    }
}