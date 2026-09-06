## Laravel DataForSEO

### Overview

A Laravel client for the DataForSEO API (`https://api.dataforseo.com/v3`). A
fluent `DataForSeo` facade groups endpoints behind resource accessors
(`serp()`, `keywords()`, `backlinks()`, `onPage()`, `labs()`) mirroring
DataForSEO's own API structure, authenticating every request with HTTP Basic
Auth via `DATAFORSEO_LOGIN` / `DATAFORSEO_PASSWORD`.

### Key Concepts

- **DataForSeoClient**: Builds the authenticated HTTP request and decodes the JSON response for every resource
- **Resources**: `Serp`, `Keywords`, `Backlinks`, `OnPage`, `Labs` — one class per DataForSEO API group, each returning raw decoded JSON arrays
- **DataForSeoException**: Thrown on a non-2xx HTTP response, carrying the API's error message and status code

### Public API

@verbatim
<code-snippet name="dataforseo-client" lang="php">
use JeffersonGoncalves\DataForSeo\Facades\DataForSeo;

DataForSeo::serp()->google('laravel');                          // POST /serp/google/organic/live/regular
DataForSeo::serp()->locations();                                // GET /serp/google/locations
DataForSeo::serp()->languages();                                // GET /serp/google/languages

DataForSeo::keywords()->volume(['laravel', 'php']);              // POST /keywords_data/google_ads/search_volume/live
DataForSeo::keywords()->forSite('example.com');                  // POST /keywords_data/google_ads/keywords_for_site/live
DataForSeo::keywords()->forKeywords(['laravel']);                // POST /keywords_data/google_ads/keywords_for_keywords/live
DataForSeo::keywords()->trends(['laravel']);                     // POST /keywords_data/google_trends/explore/live

DataForSeo::backlinks()->summary('example.com');                 // POST /backlinks/summary/live
DataForSeo::backlinks()->list('example.com');                    // POST /backlinks/backlinks/live
DataForSeo::backlinks()->referringDomains('example.com');        // POST /backlinks/referring_domains/live
DataForSeo::backlinks()->anchors('example.com');                 // POST /backlinks/anchors/live
DataForSeo::backlinks()->index();                                // GET /backlinks/index

DataForSeo::onPage()->audit('https://example.com');               // POST /on_page/instant_pages

DataForSeo::labs()->competitors('example.com');                   // POST /dataforseo_labs/google/competitors_domain/live
DataForSeo::labs()->rankedKeywords('example.com');                 // POST /dataforseo_labs/google/ranked_keywords/live
DataForSeo::labs()->domainIntersection(['example.com', 'rival.com']); // POST /dataforseo_labs/google/domain_intersection/live
</code-snippet>
@endverbatim

### Error Handling

@verbatim
<code-snippet name="dataforseo-exception" lang="php">
use JeffersonGoncalves\DataForSeo\Exceptions\DataForSeoException;
use JeffersonGoncalves\DataForSeo\Facades\DataForSeo;

try {
    $result = DataForSeo::serp()->google('laravel');
} catch (DataForSeoException $e) {
    // $e->getMessage() — the API's status_message/message, or raw body
    // $e->statusCode  — the HTTP status code
}
</code-snippet>
@endverbatim

### Configuration

@verbatim
<code-snippet name="config-keys" lang="php">
// config/dataforseo.php
'login'    => env('DATAFORSEO_LOGIN'),
'password' => env('DATAFORSEO_PASSWORD'),
'base_url' => env('DATAFORSEO_BASE_URL', 'https://api.dataforseo.com/v3'),
</code-snippet>
@endverbatim

### Conventions

- All POST bodies are wrapped in a top-level array (DataForSEO's "tasks" convention), even for a single-task live call
- Every method returns the raw decoded JSON as an `array<string, mixed>` — no DTOs
- `domainIntersection()` encodes its `$targets` list as `target1`, `target2`, ... keys in the payload
- Non-2xx responses throw `DataForSeoException` instead of returning an error array
