<?php

namespace Hsuan\LaravelRelationMaker;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Hsuan\LaravelRelationMaker\Commands\LaravelRelationMakerCommand;

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
