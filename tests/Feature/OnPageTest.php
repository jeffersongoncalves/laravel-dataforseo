<?php

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\DataForSeo\Exceptions\DataForSeoException;
use JeffersonGoncalves\DataForSeo\Facades\DataForSeo;

it('audits a url', function () {
    Http::fake([
        'api.dataforseo.com/v3/on_page/instant_pages' => Http::response(['status_code' => 20000], 200),
    ]);

    expect(DataForSeo::onPage()->audit('https://example.com'))->toBe(['status_code' => 20000]);

    Http::assertSent(fn (Request $request) => $request->data()[0]['url'] === 'https://example.com'
        && $request->data()[0]['enable_javascript'] === true);
});

it('audits a url with javascript disabled', function () {
    Http::fake([
        'api.dataforseo.com/v3/on_page/instant_pages' => Http::response(['status_code' => 20000], 200),
    ]);

    DataForSeo::onPage()->audit('https://example.com', false);

    Http::assertSent(fn (Request $request) => $request->data()[0]['enable_javascript'] === false);
});

it('throws a DataForSeoException on a non-2xx response', function () {
    Http::fake([
        'api.dataforseo.com/v3/on_page/instant_pages' => Http::response(['status_message' => 'Url is invalid.'], 400),
    ]);

    expect(fn () => DataForSeo::onPage()->audit('not-a-url'))
        ->toThrow(DataForSeoException::class, 'Url is invalid.');
});
