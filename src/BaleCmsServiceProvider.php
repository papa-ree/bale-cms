<?php

namespace Paparee\BaleCms;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use Paparee\BaleCms\Commands\CreateTenantCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class BaleCmsServiceProvider extends PackageServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/bale-cms.php', 'bale-cms');

        $this->commands([
            CreateTenantCommand::class,
        ]);
    }

    public function boot()
    {
        // middleware
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('recaptcha', \Paparee\BaleCms\App\Middleware\VerifyCaptcha::class);
        $router->aliasMiddleware('view logs', \Paparee\BaleCms\App\Middleware\ViewLogs::class);
        $router->aliasMiddleware('set locale', \Paparee\BaleCms\App\Middleware\SetLocale::class);

        // Registering package views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'bale-cms');
        
        Volt::mount([
            'bale-script' => __DIR__.'/../resources/views/scripts/bale-script.blade.php',
        ]);
        
        // register bale script
        Blade::component('bale-cms::scripts.bale-script', 'bale-cms::script');

        // Config publish
        $this->publishes([
            __DIR__.'/../config/authentication-log.php' => config_path('authentication-log.php'),
            __DIR__.'/../config/bale-cms.php' => config_path('bale-cms.php'),
            __DIR__.'/../config/blade-lucide-icons.php' => config_path('blade-lucide-icons.php'),
            __DIR__.'/../config/livewire-alert.php' => config_path('livewire-alert.php'),
        ], 'bale-cms-config');

        $this->publishes([
            __DIR__.'/../resources/views/components/bale' => resource_path('views/components/bale'),
            __DIR__.'/../resources/views/livewire' => resource_path('views/livewire'),
        ], 'bale-cms-views');

        $this->publishes([
            __DIR__.'/../database/migrations' => base_path('database/migrations'),
            __DIR__.'/../database/migrations/tenant' => base_path('database/migrations/tenant'),
            __DIR__.'/../database/seeders/RolesAndPermissionsSeeder.php' => base_path('database/seeders/RolesAndPermissionsSeeder.php'),
            __DIR__.'/../database/seeders/UserSeeder.php' => base_path('database/seeders/UserSeeder.php'),
        ], 'bale-cms-migrations');

        foreach (glob(__DIR__ . '/../routes/*.php') as $routeFile) {
            Route::middleware('web')->group($routeFile);
        }

    }

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('bale-cms')
            ->hasConfigFile()
            ->discoversMigrations()
            ->hasCommand(CreateTenantCommand::class);
    }
}
