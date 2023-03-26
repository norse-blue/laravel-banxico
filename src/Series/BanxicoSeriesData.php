<?php

namespace NorseBlue\Banxico\Series;

use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use NorseBlue\Banxico\Exceptions\BanxicoDateTimeFormatException;

final readonly class BanxicoSeriesData
{
    public string $id;

    public string $name;

    /** @var Collection<string, BanxicoSeriesDataValue> */
    public Collection $data;

    /**
     * @param  array<int, array{
     *     fecha: string,
     *     dato: string,
     * }>  $data
     */
    private function __construct(string $id, string $name, array $data)
    {
        $this->id = $id;
        $this->name = $name;
        $this->data = collect($data)
            ->mapWithKeys(function (array $item) {
                $date = $this->parseDate($item['fecha']);

                return [
                    $date->format('Y-m-d') => BanxicoSeriesDataValue::create(
                        date: $date,
                        exists: $item['dato'] !== 'N/E',
                        value: $item['dato'] !== 'N/E' ? (int) str($item['dato'])->replace('.', '')->value() : null,    /** @phpstan-ignore-line */
                    ),
                ];
            });
    }

    /**
     * @param  array<int, array{
     *     fecha: string,
     *     dato: string,
     * }>  $data
     */
    public static function create(string $id, string $name, array $data): self
    {
        return new self($id, $name, $data);
    }

    private function parseDate(string $date): CarbonImmutable
    {
        $date_parsed = CarbonImmutable::createFromFormat('d/m/Y', $date);
        if ($date_parsed === false) {
            throw new BanxicoDateTimeFormatException("Cannot parse date '$date'. Expected format: 'd/m/Y'.");
        }

        return $date_parsed;
    }
}
