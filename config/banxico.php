<?php

declare(strict_types=1);

return [

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
