<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    // (A) the role names and their numbers, in one place
    private const ROLES = [
        'superadmin'    => 1,
        'user'          => 2,
        'adminofficer'  => 3,
        'analyst'       => 4,
        'sectionchief'  => 5,
        'divisionchief' => 6,
    ];

    // (B) ...$roles receives the names written in the route
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // (C) not logged in → back to the login page
        if (session('logged_in') !== true) {
            return redirect()->route('welcome');
        }

        // (D) the user's role number from the session
        $roleId = (int) session('role_id');

        // (E) turn the route's role names into numbers
        $allowed = array_map(fn ($r) => self::ROLES[strtolower($r)] ?? -1, $roles);

        // (F) not one of the allowed roles → "no access" page
        if (!in_array($roleId, $allowed, true)) {
            abort(403, 'You do not have access to this page.');
        }

        if ($roleId === self::ROLES['superadmin']) {
            return $next($request);
        }

        // (G) allowed → continue to the controller
        return $next($request);
    }
}