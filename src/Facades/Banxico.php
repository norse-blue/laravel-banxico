<?php

namespace NorseBlue\Banxico\Facades;

use DateTimeInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use NorseBlue\Banxico\BanxicoApiClient;
use NorseBlue\Banxico\Series\BanxicoSeriesData;
use NorseBlue\Banxico\Series\BanxicoSeriesMetadata;

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
