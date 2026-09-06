<?php

return [
    /*
    |--------------------------------------------------------------------------
    | DataForSEO Login
    |--------------------------------------------------------------------------
    |
    | Your DataForSEO account login, used as the HTTP Basic Auth username on
    | every request. Create an account at https://app.dataforseo.com/register
    |
    */
    'login' => env('DATAFORSEO_LOGIN'),

    /*
    |--------------------------------------------------------------------------
    | DataForSEO Password
    |--------------------------------------------------------------------------
    |
    | Your DataForSEO account password (or API password), used as the HTTP
    | Basic Auth password on every request.
    |
    */
    'password' => env('DATAFORSEO_PASSWORD'),

    /*
    |--------------------------------------------------------------------------
    | Base URL
    |--------------------------------------------------------------------------
    |
    | The DataForSEO REST API base URL. Override only if DataForSEO gives you
    | a dedicated endpoint.
    |
    */
    'base_url' => env('DATAFORSEO_BASE_URL', 'https://api.dataforseo.com/v3'),
];
