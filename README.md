<div class="filament-hidden">

![Laravel DataForSEO](https://raw.githubusercontent.com/jeffersongoncalves/laravel-dataforseo/master/art/jeffersongoncalves-laravel-dataforseo.png)

</div>

# Laravel DataForSEO

[![Tests](https://github.com/jeffersongoncalves/laravel-dataforseo/actions/workflows/run-tests.yml/badge.svg)](https://github.com/jeffersongoncalves/laravel-dataforseo/actions/workflows/run-tests.yml)
[![PHPStan](https://github.com/jeffersongoncalves/laravel-dataforseo/actions/workflows/phpstan.yml/badge.svg)](https://github.com/jeffersongoncalves/laravel-dataforseo/actions/workflows/phpstan.yml)
[![Code Style](https://github.com/jeffersongoncalves/laravel-dataforseo/actions/workflows/fix-php-code-style-issues.yml/badge.svg)](https://github.com/jeffersongoncalves/laravel-dataforseo/actions/workflows/fix-php-code-style-issues.yml)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffersongoncalves/laravel-dataforseo.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-dataforseo)
[![Total Downloads](https://img.shields.io/packagist/dt/jeffersongoncalves/laravel-dataforseo.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-dataforseo)
[![License](https://img.shields.io/packagist/l/jeffersongoncalves/laravel-dataforseo.svg?style=flat-square)](LICENSE.md)

A Laravel client for the [DataForSEO](https://dataforseo.com) API. A fluent `DataForSeo` facade groups the SERP, Keywords Data, Backlinks, OnPage, and DataForSEO Labs endpoints behind resource accessors, authenticates every request with HTTP Basic Auth, and throws a `DataForSeoException` on a non-2xx response instead of returning a silent error array.

## Features

- **SERP** — `serp()->google()`, `locations()`, `languages()`
- **Keywords Data** — `keywords()->volume()`, `forSite()`, `forKeywords()`, `trends()`
- **Backlinks** — `backlinks()->summary()`, `list()`, `referringDomains()`, `anchors()`, `index()`
- **OnPage** — `onPage()->audit()`
- **DataForSEO Labs** — `labs()->competitors()`, `rankedKeywords()`, `domainIntersection()`
- **Thin by design** — every method returns the raw decoded JSON response as an array, no DTOs
- **Fails loud** — a non-2xx response throws `DataForSeoException` carrying the API's error message and HTTP status code

## Installation

```bash
composer require jeffersongoncalves/laravel-dataforseo
```

Optionally publish the config file:

```bash
php artisan vendor:publish --tag="dataforseo-config"
```

## Configuration

Add to your `.env`:

```env
DATAFORSEO_LOGIN=your-login
DATAFORSEO_PASSWORD=your-password
```

Create an account at [https://app.dataforseo.com/register](https://app.dataforseo.com/register) to get your login and password.

### Config Options

```php
// config/dataforseo.php
return [
    'login' => env('DATAFORSEO_LOGIN'),
    'password' => env('DATAFORSEO_PASSWORD'),
    'base_url' => env('DATAFORSEO_BASE_URL', 'https://api.dataforseo.com/v3'),
];
```

## Usage

```php
use JeffersonGoncalves\DataForSeo\Facades\DataForSeo;
use JeffersonGoncalves\DataForSeo\Exceptions\DataForSeoException;
```

### SERP

```php
DataForSeo::serp()->google('laravel', 'United States', 'English');
DataForSeo::serp()->locations();
DataForSeo::serp()->languages();
```

### Keywords Data

```php
DataForSeo::keywords()->volume(['laravel', 'php'], 2840, 'en');
DataForSeo::keywords()->forSite('example.com', 2840, 'en');
DataForSeo::keywords()->forKeywords(['laravel', 'php'], 2840, 'en');
DataForSeo::keywords()->trends(['laravel', 'php'], 2840, 'en');
```

### Backlinks

```php
DataForSeo::backlinks()->summary('example.com');
DataForSeo::backlinks()->list('example.com', 'as_is', 100);
DataForSeo::backlinks()->referringDomains('example.com', 100);
DataForSeo::backlinks()->anchors('example.com', 100);
DataForSeo::backlinks()->index();
```

### OnPage

```php
DataForSeo::onPage()->audit('https://example.com', enableJavascript: true);
```

### DataForSEO Labs

```php
DataForSeo::labs()->competitors('example.com', 2840, 'en', 100);
DataForSeo::labs()->rankedKeywords('example.com', 2840, 'en', 100);
DataForSeo::labs()->domainIntersection(['example.com', 'rival.com'], 2840, 'en', 100);
```

### Handling errors

```php
try {
    $result = DataForSeo::serp()->google('laravel');
} catch (DataForSeoException $e) {
    // $e->getMessage() — the API's status_message/message, or the raw response body
    // $e->statusCode  — the HTTP status code returned by DataForSEO
}
```

## Testing

```bash
composer test
```

## Static Analysis

```bash
composer analyse
```

## Code Formatting

```bash
composer format
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [Jefferson Gonçalves](https://github.com/jeffersongoncalves)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
