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

        // Default fallback to clinic panel
        return redirect('/clinic');
    }
}
