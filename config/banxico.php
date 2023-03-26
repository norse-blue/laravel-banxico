<?php

declare(strict_types=1);

return [

    /**
     * Set to the classname of the settings to use instead of config.
     * The settings object has to replicate this config array except for the use_settings entry.
     * Default value: false (use config values).
     *
     * @see https://www.github.com/spatie/laravel-settings
     */
    'use_settings' => false,

    /**
     * Banxico's SIE API base url.
     *
     * @see https://www.banxico.org.mx/SieAPIRest/service/v1/
     */
    'api_url' => env('BANXICO_API_URL', 'https://www.banxico.org.mx/SieAPIRest/service/v1'),

    /**
     * Banxico's SIE API token.
     *
     * @see https://www.banxico.org.mx/SieAPIRest/service/v1/token
     */
    'api_token' => env('BANXICO_API_TOKEN', null),

    /**
     * Banxico's API locale
     */
    'api_locale' => env('BANXICO_API_LOCALE', 'es'),

];
