<?php

namespace Paparee\BaleCms;

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
            ->hasRoute('web')
            ->hasMigration('create_bale_cms_table')
            ->hasCommand(BaleCmsCommand::class);
    }
}
