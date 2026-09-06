<?php

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\DataForSeo\Exceptions\DataForSeoException;
use JeffersonGoncalves\DataForSeo\Facades\DataForSeo;

it('fetches a backlinks summary', function () {
    Http::fake([
        'api.dataforseo.com/v3/backlinks/summary/live' => Http::response(['status_code' => 20000], 200),
    ]);

    expect(DataForSeo::backlinks()->summary('example.com'))->toBe(['status_code' => 20000]);

    Http::assertSent(fn (Request $request) => $request->data()[0]['target'] === 'example.com'
        && $request->data()[0]['backlinks_status_type'] === 'live');
});

it('fetches a backlinks list', function () {
    Http::fake([
        'api.dataforseo.com/v3/backlinks/backlinks/live' => Http::response(['status_code' => 20000], 200),
    ]);

    expect(DataForSeo::backlinks()->list('example.com'))->toBe(['status_code' => 20000]);

    Http::assertSent(fn (Request $request) => $request->data()[0]['mode'] === 'as_is'
        && $request->data()[0]['limit'] === 100);
});

it('fetches referring domains', function () {
    Http::fake([
        'api.dataforseo.com/v3/backlinks/referring_domains/live' => Http::response(['status_code' => 20000], 200),
    ]);

    expect(DataForSeo::backlinks()->referringDomains('example.com'))->toBe(['status_code' => 20000]);
});

it('fetches anchors', function () {
    Http::fake([
        'api.dataforseo.com/v3/backlinks/anchors/live' => Http::response(['status_code' => 20000], 200),
    ]);

    expect(DataForSeo::backlinks()->anchors('example.com'))->toBe(['status_code' => 20000]);
});

it('fetches the backlinks index', function () {
    Http::fake([
        'api.dataforseo.com/v3/backlinks/index' => Http::response(['status_code' => 20000], 200),
    ]);

    expect(DataForSeo::backlinks()->index())->toBe(['status_code' => 20000]);
});

it('throws a DataForSeoException on a non-2xx response', function () {
    Http::fake([
        'api.dataforseo.com/v3/backlinks/summary/live' => Http::response(['status_message' => 'Target is invalid.'], 400),
    ]);

    expect(fn () => DataForSeo::backlinks()->summary('not a domain'))
        ->toThrow(DataForSeoException::class, 'Target is invalid.');
});
