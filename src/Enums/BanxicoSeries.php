<?php

namespace NorseBlue\Banxico\Enums;

enum BanxicoSeries: string
{
    case ExchangeRate_USD_SettleObligationsDate = 'SF60653';
    case ExchangeRate_EUR_BasketSDR = 'SF46410';

    public static function combine(self ...$series): string
    {
        return collect($series)
            ->map(fn (self $item) => $item->value)
            ->join(',');
    }
}
