<?php

namespace NorseBlue\Banxico\Series;

use Carbon\CarbonImmutable;

final readonly class BanxicoSeriesDataValue
{
    public const VALUE_BASE = 10000;

    public CarbonImmutable $date;

    public ?int $value;

    public bool $exists;

    private function __construct(CarbonImmutable $date, bool $exists, ?int $value)
    {
        $this->date = $date;
        $this->exists = $exists;
        $this->value = $exists === true ? $value : null;
    }

    public static function create(CarbonImmutable $date, bool $exists, ?int $value): self
    {
        return new self($date, $exists, $value);
    }

    public function valueAsFloat(): ?float
    {
        return $this->exists ? $this->value / self::VALUE_BASE : null;
    }
}
