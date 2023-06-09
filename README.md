<div align="center">
    <h1>Laravel Banxico</h1>
    <p align="center">
        <a href="https://packagist.org/packages/norse-blue/laravel-banxico"><img alt="Stable Release" src="https://img.shields.io/packagist/v/norse-blue/laravel-banxico.svg?style=flat-square&label=release&logo=packagist&logoColor=eceff4&colorA=4c566a&colorB=5e81ac"></a>
        <a href="https://github.com/norse-blue/laravel-banxico/actions?query=workflow%3Arun-tests"><img alt="Build Status" src="https://img.shields.io/github/workflow/status/norse-blue/laravel-banxico/run-tests.svg?style=flat-square&label=build&logo=github&logoColor=eceff4&colorA=4c566a&colorB=88c0d0"></a>
        <a href="https://php.net/releases"><img alt="PHP Version" src="https://img.shields.io/packagist/php-v/norse-blue/laravel-banxico.svg?style=flat-square&label=php&logo=php&logoColor=eceff4&colorA=4c566a&colorB=b48ead"></a>
        <a href="https://codecov.io/gh/norse-blue/laravel-banxico"><img alt="Test Coverage" src="https://img.shields.io/codecov/c/github/norse-blue/laravel-banxico.svg?style=flat-square&label=coverage&logo=codecov&logoColor=eceff4&colorA=4c566a&colorB=88c0d0"></a>
        <a href="https://codeclimate.com/github/norse-blue/laravel-banxico"><img alt="Maintainability" src="https://img.shields.io/codeclimate/maintainability/norse-blue/laravel-banxico.svg?style=flat-square&label=maintainability&logo=code-climate&logoColor=eceff4&colorA=4c566a&colorB=88c0d0"></a>
        <a href="https://packagist.org/packages/norse-blue/laravel-banxico"><img alt="Total Downloads" src="https://img.shields.io/packagist/dt/norse-blue/laravel-banxico.svg?style=flat-square&label=downloads&logoColor=eceff4&colorA=4c566a&colorB=88c0d0"></a>
        <a href="https://github.com/norse-blue/laravel-banxico/blob/master/LICENSE.md"><img alt="License" src="https://img.shields.io/github/license/norse-blue/laravel-banxico.svg?style=flat-square&label=license&logoColor=eceff4&colorA=4c566a&colorB=a3be8c"></a>
    </p>
</div>
<hr>

**Laravel Banxico** is a Laravel package that contains an HTTP Client implementation for [Banxico's SIE API](https://www.banxico.org.mx/SieAPIRest/service/v1/) to request series data and metadata.

## Installation

>Requirements:
>- [PHP 8.2+](https://php.net/releases)

Install this package using Composer:

```bash
composer require norse-blue/laravel-banxico
```

You can publish the config file with:

````php
php artisan vendor:publish --provider="NorseBlue\LaravelBanxico\BanxicoServiceProvider" --tag="settings"
````

Using [Spatie\LaravelSettings](https://github.com/spatie/laravel-settings) package for configuration is also possible.

## Usage

The easiest way to use the package is with the facade:

```php
Banxico::getSeriesData(
    BanxicoSeries::combine(
        BanxicoSeries::ExchangeRate_USD_SettleObligationsDate,
        BanxicoSeries::ExchangeRate_EUR_BasketSDR,
    ),
    now()->subDays(7),
    now(),
);
```

This example retrieves the last 7 days of data for the specified series.

## Changelog

Please refer to the [CHANGELOG.md](CHANGELOG.md) file for more information about what has changed recently.

## Contributing

Contributions to this project are accepted and encouraged. Please read the [CONTRIBUTING.md](.github/CONTRIBUTING.md) file for details on contributions.

## Credits

- [Axel Pardemann](https://github.com/axelitus)
- [All Contributors](../../contributors)

## Security

Please review our [security policy](https://github.com/norse-blue/laravel-banxico/security/policy) to know how to report security vulnerabilities within this package.

## Support the development

**Do you like this project? Support it by donating**

<a href="https://www.buymeacoffee.com/axelitus"><img src=".assets/buy-me-a-coffee.svg" width="180" alt="Buy me a coffee"></img></a>

## License

This package is open-sourced software licensed under the [MIT](LICENSE.md) license.
