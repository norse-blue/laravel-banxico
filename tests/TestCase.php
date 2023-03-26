<?php

declare(strict_types=1);

namespace NorseBlue\LaravelBanxico\Tests;

use NorseBlue\LaravelBanxico\BanxicoServiceProvider;
use NorseBlue\LaravelBanxico\Facades\Banxico;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            BanxicoServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'Banxico' => Banxico::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('banxico.use_settings', false);
        $app['config']->set('banxico.api_url', 'https://www.banxico.org.mx/SieAPIRest/service/v1');
        $app['config']->set('banxico.api_token', 'fake-token');
        $app['config']->set('banxico.api_locale', 'es');
    }
}
