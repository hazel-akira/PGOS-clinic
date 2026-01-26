<?php

namespace App\Providers\Filament;

use App\Filament\Clinic\Pages\ClinicDashboard;
use App\Http\Middleware\EnsureClinicRole;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class ClinicPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('clinic')
            ->path('clinic')
            ->login(\App\Filament\Clinic\Pages\Auth\Login::class)
            ->colors([
                'primary' => Color::hex('#1e3a5f'),
                'secondary' => Color::hex('#df8811'),
            ])
            ->brandName('PGoS Clinic Management System')
            ->brandLogo(asset('pgos_logo.webp'))
            ->favicon(asset('favicon.ico'))
            ->discoverResources(in: app_path('Filament/Clinic/Resources'), for: 'App\\Filament\\Clinic\\Resources')
            ->discoverPages(in: app_path('Filament/Clinic/Pages'), for: 'App\\Filament\\Clinic\\Pages')
            ->pages([
                ClinicDashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Clinic/Widgets'), for: 'App\\Filament\\Clinic\\Widgets')
            ->widgets([
               \App\Filament\Clinic\Widgets\QuickClinicActions::class,
               \App\Filament\Clinic\Widgets\ClinicStats::class,
               \App\Filament\Clinic\Widgets\EmergencyAlerts::class,
            ])

            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                EnsureClinicRole::class,
            ]);
    }

    public function register(): void
    {
        parent::register();
        
        // Register Azure SSO routes for clinic panel
        $this->app['router']->middleware('web')->group(function () {
            // Standard routes
            \Illuminate\Support\Facades\Route::get('/clinic/login/azure/redirect', [\App\Http\Controllers\Auth\AzureController::class, 'redirect'])
                ->name('filament.clinic.auth.azure.redirect')
                ->defaults('panel', 'clinic');
            
            \Illuminate\Support\Facades\Route::get('/clinic/login/azure/callback', [\App\Http\Controllers\Auth\AzureController::class, 'callback'])
                ->name('filament.clinic.auth.azure.callback');
            
            // Alternative routes with /auth/ for compatibility
            \Illuminate\Support\Facades\Route::get('/clinic/login/auth/azure/redirect', [\App\Http\Controllers\Auth\AzureController::class, 'redirect'])
                ->defaults('panel', 'clinic');
            
            \Illuminate\Support\Facades\Route::get('/clinic/login/auth/azure/callback', [\App\Http\Controllers\Auth\AzureController::class, 'callback']);
        });
    }
}
