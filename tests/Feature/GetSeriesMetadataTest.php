<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use NorseBlue\Banxico\Enums\BanxicoSeries;
use NorseBlue\Banxico\Exceptions\BanxicoApiInvalidTokenException;
use NorseBlue\Banxico\Facades\Banxico;
use NorseBlue\Banxico\Series\BanxicoSeriesMetadata;

test('Banxico\'s API client returns a collection of series metadata', function () {
    Config::partialMock()
        ->shouldReceive('get')
        ->with('banxico.api_token', null)
        ->andReturn('fake-token');

    Http::preventStrayRequests();
    Http::fake([
        '*' => Http::response(
            body: '{"bmx":{"series":[{"idSerie":"SF60653","titulo":"Tipo de cambio pesos por dólar E.U.A. Tipo de cambio para solventar obligaciones denominadas en moneda extranjera Fecha de liquidación","fechaInicio":"14/11/1991","fechaFin":"22/03/2023","periodicidad":"Diaria","cifra":"Tipo de Cambio","unidad":"Pesos por Dólar","versionada":false},{"idSerie":"SF46410","titulo":"Cotización de las divisas que conforman la canasta del DEG Respecto al peso mexicano Euro","fechaInicio":"04/01/1999","fechaFin":"17/03/2023","periodicidad":"Diaria","cifra":"Tipo de Cambio","unidad":"Pesos","versionada":false}]}}',
            status: 200,
            headers: [
                'Content-Language' => 'es',
                'Content-Type' => 'application/json;charset=UTF-8',
            ]),
    ]);

    $series = Banxico::getSeriesMetadata(BanxicoSeries::combine(BanxicoSeries::ExchangeRate_USD_SettleObligationsDate, BanxicoSeries::ExchangeRate_EUR_BasketSDR));

    expect($series)->toBeCollection()
        ->and($series)->toHaveCount(2)
        ->and($series->has(['SF60653', 'SF46410']))->toBeTrue()
        ->and($series)->each(function ($item) {
            $item->toBeInstanceOf(BanxicoSeriesMetadata::class);
        });
});

test('Banxico\'s API client throws an exception when no API token is defined', function () {
    Config::partialMock()
        ->shouldReceive('get')
        ->with('banxico.api_token', null)
        ->andReturn(null);

    Http::preventStrayRequests();

    Banxico::getSeriesMetadata('SF60653,SF46410');
})->throws(BanxicoApiInvalidTokenException::class, 'Banxico\'s API token is not set');

test('Banxico\'s API client throws an exception when no API token is an empty string', function () {
    Config::partialMock()
        ->shouldReceive('get')
        ->with('banxico.api_token', null)
        ->andReturn('');

    Http::preventStrayRequests();

    Banxico::getSeriesMetadata('SF60653,SF46410');
})->throws(BanxicoApiInvalidTokenException::class, 'Banxico\'s API token is not set');
