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
        // Allow access to login/logout pages and Azure SSO callbacks without authentication
        if ($request->is('clinic/login') || $request->is('clinic/logout') || 
            $request->is('clinic/login/azure/*') || 
            $request->is('clinic/login/auth/azure/*') ||
            $request->routeIs('filament.clinic.auth.login') || 
            $request->routeIs('filament.clinic.auth.logout') ||
            $request->routeIs('filament.clinic.auth.azure.*')) {
            return $next($request);
        }

        if (!Auth::check()) {
            return redirect('/clinic/login');
        }

        $user = Auth::user();
        
        // Allow admin, clinic_nurse, doctor, and principal_readonly
        if (!$user->hasAnyRole(['admin', 'clinic_nurse', 'doctor', 'principal_readonly'])) {
            // If user is a parent, redirect them to parent panel
            if ($user->hasRole('parent') || $user->guardian) {
                return redirect('/parent');
            }
            abort(403, 'Unauthorized. Clinic access required.');
        }

        return $next($request);
    }
}
