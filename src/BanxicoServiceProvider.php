<?php

namespace NorseBlue\Banxico;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\Facades\Http;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class BanxicoServiceProvider extends PackageServiceProvider implements DeferrableProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-banxico')
            ->hasConfigFile()
            ->publishesServiceProvider(BanxicoServiceProvider::class);
    }

    public function register(): void
    {
        $this->app->singleton(BanxicoApiClient::class, fn () => new BanxicoApiClient(Http::acceptJson()));

        parent::register();
    }

    /**
     * @return array<string>
     */
    public function provides(): array
    {
        return [BanxicoApiClient::class];
    }
}
