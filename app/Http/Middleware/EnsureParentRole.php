<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureParentRole
{
    public function handle(Request $request, Closure $next): Response
    {
        // Allow access to login/logout pages and Azure SSO callbacks without authentication
        if ($request->is('parent/login') || $request->is('parent/logout') || 
            $request->is('parent/login/azure/*') || 
            $request->is('parent/login/auth/azure/*') ||
            $request->routeIs('filament.parent.auth.login') || 
            $request->routeIs('filament.parent.auth.logout') ||
            $request->routeIs('filament.parent.auth.azure.*')) {
            return $next($request);
        }

        if (!Auth::check()) {
            return redirect('/parent/login');
        }

        $user = Auth::user();

        // Allow users with parent role or users linked to a guardian
        if (!$user->hasRole('parent') && !$user->guardian) {
            abort(403, 'Unauthorized. Parent access required.');
        }

        return $next($request);
    }
}
