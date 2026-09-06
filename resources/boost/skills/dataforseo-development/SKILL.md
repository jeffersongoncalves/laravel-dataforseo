---
name: dataforseo-development
description: Development guide for the Laravel DataForSEO package - a fluent client for the DataForSEO API covering SERP, Keywords Data, Backlinks, OnPage, and DataForSEO Labs endpoints
---

## When to use this skill

- Adding new DataForSEO endpoints to an existing resource, or a new resource group
- Adjusting the request/response handling in `DataForSeoClient`
- Tuning the login/password/base URL configuration
- Writing tests for DataForSEO API interactions with `Http::fake()`
- Understanding the `DataForSeoException` contract

## Setup

### Requirements

- PHP 8.2+
- Laravel 12 or 13
- spatie/laravel-package-tools ^1.14.0
- A DataForSEO account (login + password) — https://app.dataforseo.com/register

### Installation

```bash
composer require jeffersongoncalves/laravel-dataforseo
```

### Publish Config

```bash
php artisan vendor:publish --tag=dataforseo-config
```

### Environment Variables

```env
DATAFORSEO_LOGIN=your-login
DATAFORSEO_PASSWORD=your-password
DATAFORSEO_BASE_URL=https://api.dataforseo.com/v3
```

## Architecture

### Namespace Structure

```
JeffersonGoncalves\DataForSeo\
    DataForSeoServiceProvider   # Registers the config file, binds the client
    DataForSeoClient            # Builds the authenticated HTTP request, decodes JSON, throws on failure
    Facades\
        DataForSeo              # Facade accessor: 'dataforseo'
    Resources\
        Serp                    # /serp/google/*
        Keywords                # /keywords_data/*
        Backlinks                # /backlinks/*
        OnPage                   # /on_page/*
        Labs                     # /dataforseo_labs/*
    Exceptions\
        DataForSeoException      # Thrown on a non-2xx response
```

### Service Provider

```php
public function configurePackage(Package $package): void
{
    $package
        ->name('dataforseo')
        ->hasConfigFile();
}

public function packageRegistered(): void
{
    $this->app->singleton(DataForSeoClient::class);
    $this->app->alias(DataForSeoClient::class, 'dataforseo');
}
```

The package short name is `dataforseo`, so the published config lives at
`config/dataforseo.php` and the publish tag is `dataforseo-config`. The
facade resolves the `dataforseo` container binding to a shared
`DataForSeoClient` instance.

### Adding a resource method

Each `Resources\*` class takes the shared `DataForSeoClient` in its
constructor and delegates to `post()`/`get()`:

```php
public function summary(string $target): array
{
    return $this->client->post('/backlinks/summary/live', [[
        'target' => $target,
        'backlinks_status_type' => 'live',
    ]]);
}
```

POST bodies are always wrapped in an outer array — DataForSEO's "tasks"
convention, even for a single-task live call.

## Public API

```php
use JeffersonGoncalves\DataForSeo\Facades\DataForSeo;

DataForSeo::serp()->google('laravel', 'United States', 'English');
DataForSeo::serp()->locations();
DataForSeo::serp()->languages();

DataForSeo::keywords()->volume(['laravel', 'php'], 2840, 'en');
DataForSeo::keywords()->forSite('example.com', 2840, 'en');
DataForSeo::keywords()->forKeywords(['laravel'], 2840, 'en');
DataForSeo::keywords()->trends(['laravel'], 2840, 'en');

DataForSeo::backlinks()->summary('example.com');
DataForSeo::backlinks()->list('example.com', 'as_is', 100);
DataForSeo::backlinks()->referringDomains('example.com', 100);
DataForSeo::backlinks()->anchors('example.com', 100);
DataForSeo::backlinks()->index();

DataForSeo::onPage()->audit('https://example.com', true);

DataForSeo::labs()->competitors('example.com', 2840, 'en', 100);
DataForSeo::labs()->rankedKeywords('example.com', 2840, 'en', 100);
DataForSeo::labs()->domainIntersection(['example.com', 'rival.com'], 2840, 'en', 100);
```

Every method returns the raw decoded JSON body as `array<string, mixed>` —
there are no DTOs, keep it thin.

## Error Handling

`DataForSeoClient::handle()` throws `DataForSeoException` whenever
`$response->failed()` is true:

```php
private function handle(Response $response): array
{
    if ($response->failed()) {
        throw new DataForSeoException($this->errorMessage($response), $response->status());
    }

    $data = $response->json();

    return is_array($data) ? $data : [];
}
```

The message prefers `status_message`, then `message`, then the raw body.
`DataForSeoException::$statusCode` carries the HTTP status.

## Configuration

```php
// config/dataforseo.php
return [
    'login' => env('DATAFORSEO_LOGIN'),
    'password' => env('DATAFORSEO_PASSWORD'),
    'base_url' => env('DATAFORSEO_BASE_URL', 'https://api.dataforseo.com/v3'),
];
```

## Testing Patterns

### Mocking a DataForSEO response

```php
use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\DataForSeo\Facades\DataForSeo;

it('fetches a backlinks summary', function () {
    Http::fake([
        'api.dataforseo.com/v3/backlinks/summary/live' => Http::response(['status_code' => 20000], 200),
    ]);

    expect(DataForSeo::backlinks()->summary('example.com'))->toBe(['status_code' => 20000]);
});
```

### Asserting a thrown exception

```php
use JeffersonGoncalves\DataForSeo\Exceptions\DataForSeoException;

it('throws on a non-2xx response', function () {
    Http::fake([
        'api.dataforseo.com/v3/serp/google/organic/live/regular' => Http::response(['status_message' => 'Invalid Login/Password.'], 401),
    ]);

    expect(fn () => DataForSeo::serp()->google('laravel'))
        ->toThrow(DataForSeoException::class, 'Invalid Login/Password.');
});
```

### Asserting the request payload

```php
use Illuminate\Http\Client\Request;

Http::assertSent(fn (Request $request) => $request->data()[0]['keyword'] === 'laravel');
```

`data()[0]` because every POST body is wrapped in an outer array.

## Dev Commands

```bash
# Run tests
vendor/bin/pest

# Run static analysis (PHPStan level 5 + Larastan)
vendor/bin/phpstan analyse

# Format code (Pint, Laravel preset)
vendor/bin/pint
```
