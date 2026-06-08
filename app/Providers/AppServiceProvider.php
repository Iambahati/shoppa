<?php

namespace App\Providers;

use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\CheckRole;
use App\Http\Responses\LoginResponse;
use App\Http\Responses\RegisterResponse;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use App\View\Components\Card\StatCard;
use App\View\Components\Form\Field;
use App\View\Components\Nav\Icon;
use App\View\Components\Nav\Sidebar;
use App\View\Components\Nav\Topbar;
use App\View\Components\Trust\CertBadge;
use App\View\Components\Trust\VerifiedPill;
use App\View\Components\UI\Alert;
use App\View\Components\UI\Badge;
use App\View\Components\UI\Button;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
        $this->app->singleton(RegisterResponseContract::class, RegisterResponse::class);
    }

    public function boot(): void
    {
        $this->registerBladeComponents();
        $this->registerMiddlewareAliases();
    }

    // Blade components

    private function registerBladeComponents(): void
    {
        // UI primitives — x-ui-button, x-ui-badge, x-ui-alert
        Blade::component('ui-button', Button::class);
        Blade::component('ui-badge',  Badge::class);
        Blade::component('ui-alert',  Alert::class);

        // Form helpers — x-form-field
        Blade::component('form-field', Field::class);

        // Navigation — x-nav-sidebar, x-nav-topbar, x-nav-icon
        Blade::component('nav-sidebar', Sidebar::class);
        Blade::component('nav-topbar',  Topbar::class);
        Blade::component('nav-icon',    Icon::class);

        // Trust signals — x-trust-verified-pill, x-trust-cert-badge
        Blade::component('trust-verified-pill', VerifiedPill::class);
        Blade::component('trust-cert-badge',    CertBadge::class);

        // Cards — x-card-stat-card
        Blade::component('card-stat-card', StatCard::class);

        // Layouts are auto-discovered as anonymous components from
        // resources/views/components/layouts (x-layouts.* notation).
    }

    // ── Middleware aliases ─────────────────────────────────────────────────────

    private function registerMiddlewareAliases(): void
    {
        // Usage: Route::middleware('role:Admin,Vendor Manager')
        Route::aliasMiddleware('role',       CheckRole::class);

        // Usage: Route::middleware('permission:approve_vendors')
        Route::aliasMiddleware('permission', CheckPermission::class);
    }
}
