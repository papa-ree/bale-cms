<?php

namespace Paparee\BaleCms;

use Illuminate\Support\Facades\Route;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Paparee\BaleCms\Commands\BaleCmsCommand;

class BaleCmsServiceProvider extends PackageServiceProvider
{
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
            ->hasViews()
            ->hasMigration('create_bale_cms_table')
            ->hasCommand(BaleCmsCommand::class);
    }

    public function bootingPackage()
    {
        foreach (glob(__DIR__ . '/../routes/*.php') as $routeFile) {
            Route::middleware('web')->group($routeFile);
        }
    }
}
