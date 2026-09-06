<?php

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\DataForSeo\Exceptions\DataForSeoException;
use JeffersonGoncalves\DataForSeo\Facades\DataForSeo;

it('fetches google organic serp results', function () {
    Http::fake([
        'api.dataforseo.com/v3/serp/google/organic/live/regular' => Http::response(['status_code' => 20000, 'tasks' => []], 200),
    ]);

    expect(DataForSeo::serp()->google('laravel'))->toBe(['status_code' => 20000, 'tasks' => []]);

    Http::assertSent(fn (Request $request) => $request->data()[0]['keyword'] === 'laravel'
        && $request->data()[0]['location_name'] === 'United States'
        && $request->data()[0]['language_name'] === 'English');
});

it('fetches google locations', function () {
    Http::fake([
        'api.dataforseo.com/v3/serp/google/locations' => Http::response(['status_code' => 20000], 200),
    ]);

    expect(DataForSeo::serp()->locations())->toBe(['status_code' => 20000]);
});

it('fetches google languages', function () {
    Http::fake([
        'api.dataforseo.com/v3/serp/google/languages' => Http::response(['status_code' => 20000], 200),
    ]);

    expect(DataForSeo::serp()->languages())->toBe(['status_code' => 20000]);
});

it('throws a DataForSeoException on a non-2xx response', function () {
    Http::fake([
        'api.dataforseo.com/v3/serp/google/organic/live/regular' => Http::response(['status_message' => 'Invalid Login/Password.'], 401),
    ]);

    expect(fn () => DataForSeo::serp()->google('laravel'))
        ->toThrow(DataForSeoException::class, 'Invalid Login/Password.');
});
