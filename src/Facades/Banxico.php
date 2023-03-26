<?php

namespace NorseBlue\LaravelBanxico\Facades;

use DateTimeInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use NorseBlue\LaravelBanxico\BanxicoApiClient;
use NorseBlue\LaravelBanxico\Series\BanxicoSeriesData;
use NorseBlue\LaravelBanxico\Series\BanxicoSeriesMetadata;

/**
 * @method static Collection<int, BanxicoSeriesData> getSeriesData(string $id_serie, ?DateTimeInterface $startDate = null, ?DateTimeInterface $endDate = null)
 * @method static Collection<int, BanxicoSeriesMetadata> getSeriesMetadata(string $id_serie)
 */
final class Banxico extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return BanxicoApiClient::class;
    }
}
