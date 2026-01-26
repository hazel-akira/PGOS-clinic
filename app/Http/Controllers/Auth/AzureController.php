<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AzureController extends Controller
{
    public function redirect(Request $request): RedirectResponse
    {
        // Store the panel ID in session to redirect back to the correct panel
        $panel = $request->get('panel') ?? $request->route('panel') ?? 'clinic';
        session(['azure_redirect_panel' => $panel]);
        
        return Socialite::driver('azure')->redirect();
    }

    public function callback(): RedirectResponse
    {
        try {
            $azureUser = Socialite::driver('azure')->user();

            $user = User::firstOrCreate(
                ['email' => $azureUser->getEmail()],
                [
                    'name' => $azureUser->getName() ?: ($azureUser->getNickname() ?: $azureUser->getEmail()),
                    'password' => bcrypt(Str::random(40)),
                ]
            );

            // If this is a new user, assign default role based on email domain or provide instructions
            if ($user->wasRecentlyCreated) {
                // Option 1: Auto-assign admin role for specific domains (customize as needed)
                // if (str_ends_with($azureUser->getEmail(), '@yourschool.com')) {
                //     $user->assignRole('admin');
                // }
                
                // Option 2: Assign a default role (uncomment and customize)
                // $user->assignRole('clinic_nurse');
                
                // For now, we'll let the admin assign roles manually
                // The user will see a helpful error message
            }

            Auth::login($user, true);

            // Redirect to the appropriate panel based on session
            $panel = session('azure_redirect_panel', 'clinic');
            session()->forget('azure_redirect_panel');

            // Determine redirect URL based on panel and user role
            if ($panel === 'parent' && ($user->hasRole('parent') || $user->guardian)) {
                return redirect()->intended('/parent/dashboard');
            } elseif ($panel === 'clinic' && ($user->hasRole('clinic_nurse') || $user->hasRole('doctor') || $user->hasRole('principal_readonly') || $user->hasRole('admin'))) {
                return redirect()->intended('/clinic');
            } elseif ($user->hasRole('admin')) {
                return redirect()->intended('/admin');
            }

            // If user has no roles, redirect to login with helpful message
            if ($user->roles->isEmpty()) {
                $loginRoute = match($panel) {
                    'parent' => 'filament.parent.auth.login',
                    'clinic' => 'filament.clinic.auth.login',
                    default => 'filament.clinic.auth.login',
                };
                
                Auth::logout();
                
                return redirect()->route($loginRoute)
                    ->withErrors([
                        'email' => 'Your account has been created but no access role has been assigned. Please contact your administrator to assign the appropriate role.'
                    ]);
            }

            // Default fallback - redirect to login if no matching role
            $loginRoute = match($panel) {
                'parent' => 'filament.parent.auth.login',
                'clinic' => 'filament.clinic.auth.login',
                default => 'filament.clinic.auth.login',
            };
            
            return redirect()->route($loginRoute)
                ->withErrors(['email' => 'You do not have access to this panel. Please contact your administrator.']);
        } catch (\Exception $e) {
            \Log::error('Azure SSO callback error: ' . $e->getMessage());
            
            // Redirect to the appropriate panel's login page
            $panel = session('azure_redirect_panel', 'clinic');
            session()->forget('azure_redirect_panel');
            
            $loginRoute = match($panel) {
                'parent' => 'filament.parent.auth.login',
                'clinic' => 'filament.clinic.auth.login',
                default => 'filament.clinic.auth.login',
            };
            
            return redirect()->route($loginRoute)
                ->withErrors(['email' => 'Azure authentication failed. Please try again.']);
        }
    }
}

