<?php

namespace NorseBlue\Banxico\Series;

use Carbon\CarbonImmutable;
use NorseBlue\Banxico\Exceptions\BanxicoDateTimeFormatException;

final readonly class BanxicoSeriesMetadata
{
    public string $id;

    public string $name;

    public CarbonImmutable $start_date;

    public CarbonImmutable $end_date;

    public string $periodicity;

    public string $figure;

    public string $unit;

    public bool $versioned;

    private function __construct(string $id, string $name, string|CarbonImmutable $start_date, string|CarbonImmutable $end_date, string $periodicity, string $figure, string $unit, bool $versioned)
    {
        [$start_date, $end_date] = $this->parseDates($start_date, $end_date);

        $this->id = $id;
        $this->name = $name;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->periodicity = $periodicity;
        $this->figure = $figure;
        $this->unit = $unit;
        $this->versioned = $versioned;
    }

    public static function create(string $id, string $name, string|CarbonImmutable $start_date, string|CarbonImmutable $end_date, string $periodicity, string $figure, string $unit, bool $versioned): self
    {
        return new self($id, $name, $start_date, $end_date, $periodicity, $figure, $unit, $versioned);
    }

    /**
     * @return array<CarbonImmutable>
     *
     * @throws BanxicoDateTimeFormatException
     */
    private function parseDates(string|CarbonImmutable $start_date, string|CarbonImmutable $end_date): array
    {
        if (! $start_date instanceof CarbonImmutable) {
            $start_date_parsed = CarbonImmutable::createFromFormat('d/m/Y', $start_date);
            if ($start_date_parsed === false) {
                throw new BanxicoDateTimeFormatException("Cannot parse date '$start_date'. Expected format: 'd/m/Y'.");
            }

            $start_date = $start_date_parsed;
        }

        if (! $end_date instanceof CarbonImmutable) {
            $end_date_parsed = CarbonImmutable::createFromFormat('d/m/Y', $end_date);
            if ($end_date_parsed === false) {
                throw new BanxicoDateTimeFormatException("Cannot parse date '$end_date'. Expected format: 'd/m/Y'.");
            }

            $end_date = $end_date_parsed;
        }

        return [$start_date, $end_date];
    }
}
