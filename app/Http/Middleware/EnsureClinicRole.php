<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureClinicRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // FIRST: Check if this is a login/logout page - allow access to everyone
        // This must be checked FIRST before any authentication or role checks
        $pathInfo = trim($request->getPathInfo(), '/');
        $path = $request->path();
        
        // Check if this is the login or logout page using multiple methods
        // Be very explicit and check all possible variations
        $isLoginOrLogout = false;
        
        // Check route name (if route is resolved)
        try {
            if ($request->route()) {
                $routeName = $request->route()->getName();
                $isLoginOrLogout = $isLoginOrLogout || 
                    str_contains($routeName, 'login') || 
                    str_contains($routeName, 'logout');
            }
        } catch (\Exception $e) {
            // Route might not be resolved yet
        }
        
        // Check path patterns
        $isLoginOrLogout = $isLoginOrLogout ||
            $request->routeIs('filament.clinic.auth.login') || 
            $request->routeIs('filament.clinic.auth.logout') ||
            $request->is('clinic/login') ||
            $request->is('clinic/logout') ||
            $path === 'clinic/login' ||
            $path === 'clinic/logout' ||
            $pathInfo === 'clinic/login' ||
            $pathInfo === 'clinic/logout' ||
            str_contains($pathInfo, 'clinic/login') ||
            str_contains($pathInfo, 'clinic/logout') ||
            preg_match('#clinic/(login|logout)#', $pathInfo) ||
            preg_match('#clinic/(login|logout)#', $path);
        
        // Always allow access to login/logout pages, regardless of authentication status
        if ($isLoginOrLogout) {
            return $next($request);
        }

        // For all other routes, check authentication
        if (!Auth::check()) {
            return redirect('/clinic/login');
        }

        // Check if user has required role
        $user = Auth::user();
        
        // Allow admin, clinic_nurse, doctor, and principal_readonly
        if (!$user->hasAnyRole(['admin', 'clinic_nurse', 'doctor', 'principal_readonly'])) {
            abort(403, 'Unauthorized. Clinic access required.');
        }

        return $next($request);
    }
}
