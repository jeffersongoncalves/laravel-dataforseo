<?php

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\DataForSeo\Exceptions\DataForSeoException;
use JeffersonGoncalves\DataForSeo\Facades\DataForSeo;

it('fetches search volume for keywords', function () {
    Http::fake([
        'api.dataforseo.com/v3/keywords_data/google_ads/search_volume/live' => Http::response(['status_code' => 20000], 200),
    ]);

    expect(DataForSeo::keywords()->volume(['laravel', 'php']))->toBe(['status_code' => 20000]);

    Http::assertSent(fn (Request $request) => $request->data()[0]['keywords'] === ['laravel', 'php']
        && $request->data()[0]['location_code'] === 2840
        && $request->data()[0]['language_code'] === 'en');
});

it('fetches keywords for a site', function () {
    Http::fake([
        'api.dataforseo.com/v3/keywords_data/google_ads/keywords_for_site/live' => Http::response(['status_code' => 20000], 200),
    ]);

    expect(DataForSeo::keywords()->forSite('example.com'))->toBe(['status_code' => 20000]);

    Http::assertSent(fn (Request $request) => $request->data()[0]['target'] === 'example.com');
});

it('fetches keywords for keywords', function () {
    Http::fake([
        'api.dataforseo.com/v3/keywords_data/google_ads/keywords_for_keywords/live' => Http::response(['status_code' => 20000], 200),
    ]);

    expect(DataForSeo::keywords()->forKeywords(['laravel']))->toBe(['status_code' => 20000]);
});

it('fetches google trends for keywords', function () {
    Http::fake([
        'api.dataforseo.com/v3/keywords_data/google_trends/explore/live' => Http::response(['status_code' => 20000], 200),
    ]);

    expect(DataForSeo::keywords()->trends(['laravel']))->toBe(['status_code' => 20000]);
});

it('throws a DataForSeoException on a non-2xx response', function () {
    Http::fake([
        'api.dataforseo.com/v3/keywords_data/google_ads/search_volume/live' => Http::response(['status_message' => 'Not enough credits.'], 402),
    ]);

    expect(fn () => DataForSeo::keywords()->volume(['laravel']))
        ->toThrow(DataForSeoException::class, 'Not enough credits.');
});
