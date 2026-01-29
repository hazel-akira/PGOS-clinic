<?php

namespace App\Providers\Filament;

use App\Http\Middleware\EnsureAdminRole;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('PGOS ADMIN')
            ->colors([
                'primary' => Color::hex('#1e3a5f'),
                'secondary' => Color::hex('#df8811'),
            ])
            ->brandLogo(asset('pgos_logo.webp'))
            ->favicon(asset('favicon.ico'))
            ->brandLogoHeight('2.5vh')
            ->navigationGroups([
                'Inventory Control',
                'Access Control',
                'Administration',
                'System',
            ])
            ->discoverResources(
                in: app_path('Filament/Admin/Resources'),
                for: 'App\\Filament\\Admin\\Resources'
            )
            ->discoverPages(
                in: app_path('Filament/Admin/Pages'),
                for: 'App\\Filament\\Admin\\Pages'
            )
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(
                in: app_path('Filament/Admin/Widgets'),
                for: 'App\\Filament\\Admin\\Widgets'
            )
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
                \App\Filament\Admin\Widgets\AdminStats::class,
                \App\Filament\Admin\Widgets\StockAlertsOverview::class,
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
                EnsureAdminRole::class,
            ]);
    }
    public function register(): void
    {
        parent::register();
        
        // Register Azure SSO routes for admin panel
        $this->app['router']->middleware('web')->group(function () {
            // Standard routes
            \Illuminate\Support\Facades\Route::get('/admin/login/azure/redirect', [\App\Http\Controllers\Auth\AzureController::class, 'redirect'])
                ->name('filament.admin.auth.azure.redirect')
                ->defaults('panel', 'admin');
            
            \Illuminate\Support\Facades\Route::get('/admin/login/azure/callback', [\App\Http\Controllers\Auth\AzureController::class, 'callback'])
                ->name('filament.admin.auth.azure.callback');
            
            // Alternative routes with /auth/ for compatibility
            \Illuminate\Support\Facades\Route::get('/admin/login/auth/azure/redirect', [\App\Http\Controllers\Auth\AzureController::class, 'redirect'])
                ->defaults('panel', 'admin');
            
            \Illuminate\Support\Facades\Route::get('/admin/login/auth/azure/callback', [\App\Http\Controllers\Auth\AzureController::class, 'callback']);
        });
    }
}
