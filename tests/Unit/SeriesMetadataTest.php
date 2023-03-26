<?php

declare(strict_types=1);

use Carbon\CarbonImmutable;
use NorseBlue\LaravelBanxico\Series\BanxicoSeriesMetadata;

test('series metadata creation', function (string $series_id, string $series_name, string $series_start_date, string $series_end_date, string $series_periodicity, string $series_figure, string $series_unit, bool $series_versioned) {
    $series = BanxicoSeriesMetadata::create($series_id, $series_name, $series_start_date, $series_end_date, $series_periodicity, $series_figure, $series_unit, $series_versioned);

    expect($series)
        ->toBeInstanceOf(BanxicoSeriesMetadata::class)
        ->and($series->id)->toBe($series_id)
        ->and($series->name)->toBe($series_name)
        ->and($series->start_date)->toBeInstanceOf(CarbonImmutable::class)
        ->and($series->start_date->format('d/m/Y'))->toBe($series_start_date)
        ->and($series->end_date)->toBeInstanceOf(CarbonImmutable::class)
        ->and($series->end_date->format('d/m/Y'))->toBe($series_end_date)
        ->and($series->periodicity)->toBe($series_periodicity)
        ->and($series->figure)->toBe($series_figure)
        ->and($series->unit)->toBe($series_unit)
        ->and($series->versioned)->ToBe($series_versioned);
})->with([
    [
        'idSerie' => 'SF60653',
        'titulo' => 'Tipo de cambio pesos por dólar E.U.A. Tipo de cambio para solventar obligaciones denominadas en moneda extranjera Fecha de liquidación',
        'fechaInicio' => '14/11/1991',
        'fechaFin' => '22/03/2023',
        'periodicidad' => 'Diaria',
        'cifra' => 'Tipo de Cambio',
        'unidad' => 'Pesos por Dólar',
        'versionada' => false,
    ],
    [
        'idSerie' => 'SF46410',
        'titulo' => 'Cotización de las divisas que conforman la canasta del DEG Respecto al peso mexicano Euro',
        'fechaInicio' => '04/01/1999',
        'fechaFin' => '17/03/2023',
        'periodicidad' => 'Diaria',
        'cifra' => 'Tipo de Cambio',
        'unidad' => 'Pesos',
        'versionada' => false,
    ],
]);
