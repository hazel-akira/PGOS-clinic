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
            ->login()
            ->colors([
                'primary' => Color::Blue,
            ])
            ->brandName('School Clinic Portal')
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
}
