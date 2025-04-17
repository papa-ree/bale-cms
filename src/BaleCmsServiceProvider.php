<?php

namespace Paparee\BaleCms;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\View\AnonymousComponent;
use Livewire\Livewire;
use Livewire\Volt\Volt;
use Paparee\BaleCms\App\Livewire\SharedComponents\Pages\UserProfile\Section\UpdatePhotoProfile;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Paparee\BaleCms\Commands\BaleCmsCommand;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Illuminate\Support\Str;
use Paparee\BaleCms\App\Livewire\SharedComponents\Pages\UserProfile\Section\BrowserSession;
use Paparee\BaleCms\App\Livewire\SharedComponents\Pages\UserProfile\Section\TwoFactorAuthentication;
use Paparee\BaleCms\App\Livewire\SharedComponents\Pages\UserProfile\Section\UpdatePassword;
use Paparee\BaleCms\App\Livewire\SharedComponents\Pages\UserProfile\Section\UpdateProfileInformation;

class BaleCmsServiceProvider extends PackageServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/bale-cms.php', 'bale-cms');
    }

    public function boot()
    {
        // Registering package views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'bale-cms');
        
        Volt::mount([
            'shared-components.volt' => __DIR__.'/../resources/views/livewire/shared-components/volt',
        ]);

        Volt::mount([
            'bale-script' => __DIR__.'/../resources/views/scripts/bale-script.blade.php',
        ]);

        // Load folder extend
        $this->loadViewsFrom(__DIR__.'/../resources/views/extend', 'bale-cms-extend');

        
        Blade::component('bale-cms::scripts.bale-script', 'bale-cms::script');

        Livewire::component('pages.user-profile.section.update-photo-profile', UpdatePhotoProfile::class);
        Livewire::component('pages.user-profile.section.update-profile-information', UpdateProfileInformation::class);
        Livewire::component('pages.user-profile.section.update-password', UpdatePassword::class);
        Livewire::component('pages.user-profile.section.two-factor-authentication', TwoFactorAuthentication::class);
        Livewire::component('pages.user-profile.section.browser-session', BrowserSession::class);

        // Config publish
        $this->publishes([
            __DIR__.'/../config/bale-cms.php' => config_path('bale-cms.php'),
        ], 'bale-cms-config');

        $this->publishes([
            __DIR__.'/../resources/views/layouts/app.blade.php' => resource_path('views/components/layouts/app.blade.php'),
            __DIR__.'/../resources/views/extend' => resource_path('views/components/extend'),
            __DIR__.'/../resources/views/livewire' => resource_path('views/livewire'),
            __DIR__.'/../src/App/Livewire' => app_path('Livewire'),
        ], 'bale-cms-views');

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
            ->hasCommand(BaleCmsCommand::class);
    }

    protected function registerAnonymousBladeComponents(string $basePath, string $viewPrefix): void
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($basePath)
        );

        foreach ($iterator as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $relativePath = Str::after($file->getPath(), $basePath);
            $filename = $file->getBasename('.blade.php');

            $pathSegments = trim(str_replace(DIRECTORY_SEPARATOR, '.', $relativePath), '.');
            $componentAlias = $pathSegments
                ? "extend.{$pathSegments}.{$filename}"
                : "extend.{$filename}";

            $viewPath = "{$viewPrefix}::extend" . ($pathSegments ? ".{$pathSegments}" : '') . ".{$filename}";

            Blade::component($viewPath, $componentAlias);
        }
    }
}
