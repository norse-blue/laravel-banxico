<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use NorseBlue\LaravelBanxico\Enums\BanxicoSeries;
use NorseBlue\LaravelBanxico\Exceptions\BanxicoApiInvalidTokenException;
use NorseBlue\LaravelBanxico\Facades\Banxico;
use NorseBlue\LaravelBanxico\Series\BanxicoSeriesData;
use NorseBlue\LaravelBanxico\Series\BanxicoSeriesDataValue;

test('Banxico\'s API client returns a collection of series data', function () {
    Http::preventStrayRequests();
    Http::fake([
        '*' => Http::response(
            body: '{"bmx":{"series":[{"idSerie":"SF46410","titulo":"Cotización de las divisas que conforman la canasta del DEG Respecto al peso mexicano Euro","datos":[{"fecha":"04/01/1999","dato":"11.6054"},{"fecha":"05/01/1999","dato":"11.5586"},{"fecha":"06/01/1999","dato":"11.4891"}]},{"idSerie":"SF60653","titulo":"Tipo de cambio pesos por dólar E.U.A. Tipo de cambio para solventar obligaciones denominadas en moneda extranjera Fecha de liquidación","datos":[{"fecha":"14/11/1991","dato":"3.0735"},{"fecha":"15/11/1991","dato":"3.0712"},{"fecha":"16/11/1991","dato":"3.0718"}]}]}}',
            status: 200,
            headers: [
                'Content-Language' => 'es',
                'Content-Type' => 'application/json;charset=UTF-8',
            ]),
    ]);

    $series = Banxico::getSeriesData(BanxicoSeries::combine(BanxicoSeries::ExchangeRate_USD_SettleObligationsDate, BanxicoSeries::ExchangeRate_EUR_BasketSDR));

    expect($series)->toBeCollection()
        ->and($series)->toHaveCount(2)
        ->and($series->has(['SF60653', 'SF46410']))->toBeTrue()
        ->and($series)->each(function ($item) {
            $item->toBeInstanceOf(BanxicoSeriesData::class)
                ->and($item->value->data)->toBeCollection()
                ->and($item->value->data)->toHaveCount(3)
                ->and($item->value->data)->each(function ($data_value) {
                    $data_value->toBeInstanceOF(BanxicoSeriesDataValue::class);
                });
        });
});

test('Banxico\'s API client throws an exception when no API token is defined', function () {
    Config::partialMock()
        ->shouldReceive('get')
        ->with('banxico.api_token', null)
        ->andReturn(null);

    Http::preventStrayRequests();

    Banxico::getSeriesData('SF60653,SF46410');
})->throws(BanxicoApiInvalidTokenException::class, 'Banxico\'s API token is not set');

test('Banxico\'s API client throws an exception when no API token is an empty string', function () {
    Config::partialMock()
        ->shouldReceive('get')
        ->with('banxico.api_token', null)
        ->andReturn('');

    Http::preventStrayRequests();

    Banxico::getSeriesData('SF60653,SF46410');
})->throws(BanxicoApiInvalidTokenException::class, 'Banxico\'s API token is not set');
