<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
        // Allow access to login/logout pages without authentication
        if ($request->is('clinic/login') || $request->is('clinic/logout') || 
            $request->routeIs('filament.clinic.auth.login') || 
            $request->routeIs('filament.clinic.auth.logout')) {
            return $next($request);
        }

        if (!auth()->check()) {
            return redirect('/clinic/login');
        }

        $user = auth()->user();
        
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
