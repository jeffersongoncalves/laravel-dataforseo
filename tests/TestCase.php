<?php

namespace JeffersonGoncalves\DataForSeo\Tests;

use JeffersonGoncalves\DataForSeo\DataForSeoServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            DataForSeoServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('dataforseo.login', 'fake-login');
        $app['config']->set('dataforseo.password', 'fake-password');
        $app['config']->set('dataforseo.base_url', 'https://api.dataforseo.com/v3');
    }
}
