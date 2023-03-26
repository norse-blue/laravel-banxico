<?php

declare(strict_types=1);

namespace NorseBlue\Banxico\Tests;

use NorseBlue\Banxico\BanxicoServiceProvider;
use NorseBlue\Banxico\Facades\Banxico;
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
}
