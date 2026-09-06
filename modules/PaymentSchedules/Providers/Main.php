<?php

namespace Modules\PaymentSchedules\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider as Provider;

class Main extends Provider
{
    public function register()
    {
        $this->loadRoutes();
    }

    public function boot()
    {
        $this->loadViews();
        $this->loadViewComponents();
        $this->loadTranslations();
        $this->loadMigrations();

        $this->app['router']->pushMiddlewareToGroup('admin', \Modules\PaymentSchedules\Http\Middleware\EnsureModuleInstalled::class);
    }

    protected function loadViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'payment-schedules');
    }

    protected function loadViewComponents()
    {
        Blade::componentNamespace('Modules\PaymentSchedules\View\Components', 'payment-schedules');
    }

    protected function loadTranslations()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'payment-schedules');
    }

    protected function loadMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    protected function loadRoutes()
    {
        if (app()->routesAreCached()) {
            return;
        }

        foreach (['admin.php', 'portal.php'] as $route) {
            $this->loadRoutesFrom(__DIR__ . '/../Routes/' . $route);
        }
    }
}
