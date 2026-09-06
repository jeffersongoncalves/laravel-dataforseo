<?php

namespace JeffersonGoncalves\DataForSeo;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class DataForSeoServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('dataforseo')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(DataForSeoClient::class);
        $this->app->alias(DataForSeoClient::class, 'dataforseo');
    }
}
