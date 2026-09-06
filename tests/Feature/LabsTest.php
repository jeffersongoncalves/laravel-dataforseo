<?php

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\DataForSeo\Exceptions\DataForSeoException;
use JeffersonGoncalves\DataForSeo\Facades\DataForSeo;

it('fetches domain competitors', function () {
    Http::fake([
        'api.dataforseo.com/v3/dataforseo_labs/google/competitors_domain/live' => Http::response(['status_code' => 20000], 200),
    ]);

    expect(DataForSeo::labs()->competitors('example.com'))->toBe(['status_code' => 20000]);

    Http::assertSent(fn (Request $request) => $request->data()[0]['target'] === 'example.com'
        && $request->data()[0]['limit'] === 100);
});

it('fetches ranked keywords', function () {
    Http::fake([
        'api.dataforseo.com/v3/dataforseo_labs/google/ranked_keywords/live' => Http::response(['status_code' => 20000], 200),
    ]);

    expect(DataForSeo::labs()->rankedKeywords('example.com'))->toBe(['status_code' => 20000]);
});

it('fetches a domain intersection with target1/target2 keys', function () {
    Http::fake([
        'api.dataforseo.com/v3/dataforseo_labs/google/domain_intersection/live' => Http::response(['status_code' => 20000], 200),
    ]);

    expect(DataForSeo::labs()->domainIntersection(['example.com', 'rival.com']))->toBe(['status_code' => 20000]);

    Http::assertSent(fn (Request $request) => $request->data()[0]['target1'] === 'example.com'
        && $request->data()[0]['target2'] === 'rival.com');
});

it('throws a DataForSeoException on a non-2xx response', function () {
    Http::fake([
        'api.dataforseo.com/v3/dataforseo_labs/google/competitors_domain/live' => Http::response(['status_message' => 'Not enough credits.'], 402),
    ]);

    expect(fn () => DataForSeo::labs()->competitors('example.com'))
        ->toThrow(DataForSeoException::class, 'Not enough credits.');
});
