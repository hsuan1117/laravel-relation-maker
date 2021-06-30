<?php

namespace Hsuan\LaravelRelationMaker;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Hsuan\LaravelRelationMaker\Commands\LaravelRelationMakerCommand;

class LaravelRelationMakerServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-relation-maker')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-relation-maker_table')
            ->hasCommand(LaravelRelationMakerCommand::class);
    }
}
