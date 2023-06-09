<?php

namespace NorseBlue\LaravelBanxico;

use DateTimeInterface;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use NorseBlue\LaravelBanxico\Enums\RequestType;
use NorseBlue\LaravelBanxico\Exceptions\BanxicoApiInvalidTokenException;
use NorseBlue\LaravelBanxico\Exceptions\BanxicoInvalidSettingsClassException;
use NorseBlue\LaravelBanxico\Series\BanxicoSeriesData;
use NorseBlue\LaravelBanxico\Series\BanxicoSeriesMetadata;
use Spatie\LaravelSettings\Settings;

/**
 * @see https://www.banxico.org.mx/SieAPIRest/service/v1/
 */
final class BanxicoApiClient
{
    const SERIES_DATA_URI = 'series/{idSerie}/datos';

    const SERIES_DATA_RANGE_URI = 'series/{idSerie}/datos/{fechaIni}/{fechaFin}';

    const SERIES_METADATA_URI = 'series/{idSerie}';

    public function __construct(private readonly PendingRequest $request)
    {
        $this->configureRequest($request);
    }

    /**
     * @return Collection<int, BanxicoSeriesData>
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function getSeriesData(string $id_serie, ?DateTimeInterface $start_date = null, ?DateTimeInterface $end_date = null): Collection
    {
        $response = match (! is_null($start_date) && ! is_null($end_date)) {
            true => $this->request
                ->withUrlParameters([
                    'idSerie' => $id_serie,
                    'fechaIni' => $start_date->format('Y-m-d'),
                    'fechaFin' => $end_date->format('Y-m-d'),
                ])
                ->get(self::SERIES_DATA_RANGE_URI),
            false => $this->request
                ->withUrlParameters([
                    'idSerie' => $id_serie,
                ])
                ->get(self::SERIES_DATA_URI),
        };

        /** @phpstan-ignore-next-line */
        return $this->processResponse(RequestType::Data, $response);
    }

    /**
     * @return Collection<int, BanxicoSeriesMetadata>
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function getSeriesMetadata(string $id_serie): Collection
    {
        $response = $this->request
            ->withUrlParameters([
                'idSerie' => $id_serie,
            ])
            ->get(self::SERIES_METADATA_URI);

        /** @phpstan-ignore-next-line */
        return $this->processResponse(RequestType::Metadata, $response);
    }

    private function configureRequest(PendingRequest $request): void
    {
        [
            'url' => $url,
            'token' => $token,
            'locale' => $locale,
        ] = $this->getConfigParameters();

        if ($token === null || $token === '') {
            throw new BanxicoApiInvalidTokenException('Banxico\'s API token is not set.');
        }

        $request
            ->acceptJson()
            ->baseUrl($url)
            ->withHeaders([
                'Bmx-Token' => $token,
            ])
            ->withUrlParameters([
                'locale' => match ($locale) {
                    'en', 'es' => $locale,
                    default => 'en'
                },
            ]);
    }

    /**
     * @return array{
     *     url: string,
     *     token: ?string,
     *     locale: string,
     * }
     */
    private function getConfigParameters(): array
    {
        $parent_class = Settings::class;
        if (is_string($use_settings = config('banxico.use_settings')) && class_exists($parent_class)) {
            if (! is_subclass_of($use_settings, $parent_class)) {
                throw new BanxicoInvalidSettingsClassException("The class '$use_settings' does not extend '$parent_class'.");
            }

            $settings = app($use_settings);

            return [
                'url' => $settings->api_url,
                'token' => $settings->api_token,
                'locale' => $settings->api_locale,
            ];
        }

        /** @phpstan-ignore-next-line */
        return [
            'url' => config('banxico.api_url'),
            'token' => config('banxico.api_token'),
            'locale' => config('banxico.locale') ?? app()->getLocale(),
        ];
    }

    /**
     * @return Collection<int, BanxicoSeriesData>|Collection<int, BanxicoSeriesMetadata>
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    private function processResponse(RequestType $request_type, Response $response): Collection
    {
        $response->throwUnlessStatus(HttpResponse::HTTP_OK);
        $body = json_decode($response->body(), true, 512, JSON_BIGINT_AS_STRING);

        return match ($request_type) {
            RequestType::Data => $this->parseData(Arr::get($body, 'bmx.series')),   // @phpstan-ignore-line
            RequestType::Metadata => $this->parseMetadata(Arr::get($body, 'bmx.series')),   // @phpstan-ignore-line
        };
    }

    /**
     * @param  array{
     *     idSerie: string,
     *     titulo: string,
     *     datos: array<int, array{
     *         fecha: string,
     *         dato: string,
     *     }>
     * }  $body
     * @return Collection<int, BanxicoSeriesData>
     */
    private function parseData(array $body): Collection
    {
        /** @phpstan-ignore-next-line */
        return collect($body)->mapWithKeys(fn (array $item) => [
            $item['idSerie'] => BanxicoSeriesData::create(
                id: $item['idSerie'],
                name: $item['titulo'],
                data: $item['datos'],
            ),
        ]);
    }

    /**
     * @param  array{
     *     idSerie: string,
     *     titulo: string,
     *     fechaInicio: string,
     *     fechaFin: string,
     *     periodicidad: string,
     *     cifra: string,
     *     unidad: string,
     *     versionada: bool,
     *  }  $body
     * @return Collection<int, BanxicoSeriesMetadata>
     */
    private function parseMetadata(array $body): Collection
    {
        /** @phpstan-ignore-next-line */
        return collect($body)->mapWithKeys(fn (array $item) => [
            $item['idSerie'] => BanxicoSeriesMetadata::create(
                id: $item['idSerie'],
                name: $item['titulo'],
                start_date: $item['fechaInicio'],
                end_date: $item['fechaFin'],
                periodicity: $item['periodicidad'],
                figure: $item['cifra'],
                unit: $item['unidad'],
                versioned: $item['versionada'],
            ),
        ]);
    }
}
