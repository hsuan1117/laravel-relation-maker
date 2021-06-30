<?php

namespace Hsuan\LaravelRelationMaker;

use Hsuan\LaravelRelationMaker\Commands\LaravelRelationMakerCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelRelationMakerServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-relation-maker')
            ->hasCommand(LaravelRelationMakerCommand::class);
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/Commands/stubs' => resource_path('stubs'),
        ], 'laravel-relation-maker-stubs');

        return parent::boot();
    }
}
