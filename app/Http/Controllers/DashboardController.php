<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Redirect user to appropriate dashboard based on their role
     */
    public function redirect(): RedirectResponse
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        // System administrators go to admin panel
        if ($user->hasRole('admin')) {
            return redirect('/admin');
        }

        // Clinic staff (nurse, doctor, principal) go to clinic panel
        if ($user->hasAnyRole(['clinic_nurse', 'doctor', 'principal_readonly'])) {
            return redirect('/clinic');
        }

        // Parents go to parent panel
        if ($user->hasRole('parent') || $user->guardian) {
            return redirect('/parent');
        }

        // If user has no roles, show error or redirect to appropriate place
        if ($user->roles->isEmpty() && !$user->guardian) {
            // User has no role assigned - redirect to login with message
            return redirect('/clinic/login')->with('error', 'No access role assigned. Please contact administrator.');
        }

        // Default fallback to clinic panel
        return redirect('/clinic');
    }
}
