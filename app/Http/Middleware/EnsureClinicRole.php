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
        if (!auth()->check()) {
            return redirect('/clinic/login');
        }

        $user = auth()->user();
        
        // Allow admin, clinic_nurse, doctor, and principal_readonly
        if (!$user->hasAnyRole(['admin', 'clinic_nurse', 'doctor', 'principal_readonly'])) {
            abort(403, 'Unauthorized. Clinic access required.');
        }

        return $next($request);
    }
}
