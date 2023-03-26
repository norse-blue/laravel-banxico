<?php

declare(strict_types=1);

use NorseBlue\Banxico\Series\BanxicoSeriesData;
use NorseBlue\Banxico\Series\BanxicoSeriesDataValue;

test('series data creation', function (string $series_id, string $series_name, array $series_data) {
    $series = BanxicoSeriesData::create($series_id, $series_name, $series_data);
    expect($series)
        ->toBeInstanceOf(BanxicoSeriesData::class)
        ->and($series->id)->toBe($series_id)
        ->and($series->name)->toBe($series_name)
        ->and($series->data)->toBeCollection()
        ->and($series->data)->toHaveCount(3)
        ->and($series->data)->each(function ($series_data_value) {
            $series_data_value->toBeInstanceOf(BanxicoSeriesDataValue::class);
        })
        ->and($series->data)->when($series->id === 'SF46410', function ($data) {
            expect($data->value['2023-01-02']->exists)->toBeFalse()
                ->and($data->value['2023-01-02']->value)->toBeNull()
                ->and($data->value['2023-01-02']->valueAsFloat())->toBeNull()
            ->and($data->value['2023-01-03']->exists)->toBeTrue()
            ->and($data->value['2023-01-03']->value)->toBe(204504)
            ->and($data->value['2023-01-03']->valueAsFloat())->toBe(20.4504);
        });
})->with([
    [
        'idSerie' => 'SF46410',
        'titulo' => 'Cotización de las divisas que conforman la canasta del DEG Respecto al peso mexicano Euro',
        'datos' => [
            [
                'fecha' => '02/01/2023',
                'dato' => 'N/E',
            ],
            [
                'fecha' => '03/01/2023',
                'dato' => '20.4504',
            ],
            [
                'fecha' => '04/01/2023',
                'dato' => '20.5308',
            ],
        ],
    ],
    [
        'idSerie' => 'SF60653',
        'titulo' => 'Tipo de cambio pesos por dólar E.U.A. Tipo de cambio para solventar obligaciones denominadas en moneda extranjera Fecha de liquidación',
        'datos' => [
            [
                'fecha' => '02/01/2023',
                'dato' => '"19.3615',
            ],
            [
                'fecha' => '03/01/2023',
                'dato' => '19.4715',
            ],
            [
                'fecha' => '04/01/2023',
                'dato' => '19.4883',
            ],
        ],
    ],
]);
