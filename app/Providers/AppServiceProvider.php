<?php

namespace App\Providers;

use App\Composers\AlertComposer;
use App\Composers\KioskComposer;
use App\Composers\LayoutComposer;
use Illuminate\Support\ServiceProvider;
use Laravel\Telescope\TelescopeServiceProvider;

/**
 * Class AppServiceProvider
 *
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->composer('*', LayoutComposer::class);
        view()->composer('kiosk', KioskComposer::class);
        view()->composer('notifications.kiosk._partials.sidenav', AlertComposer::class);
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->isLocal()) {
            $this->app->register(TelescopeServiceProvider::class);
        }
    }
}
