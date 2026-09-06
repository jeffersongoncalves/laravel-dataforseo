<?php

namespace JeffersonGoncalves\DataForSeo;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\DataForSeo\Exceptions\DataForSeoException;
use JeffersonGoncalves\DataForSeo\Resources\Backlinks;
use JeffersonGoncalves\DataForSeo\Resources\Keywords;
use JeffersonGoncalves\DataForSeo\Resources\Labs;
use JeffersonGoncalves\DataForSeo\Resources\OnPage;
use JeffersonGoncalves\DataForSeo\Resources\Serp;

/**
 * Thin fluent client for the DataForSEO REST API
 * (https://api.dataforseo.com/v3). Groups endpoints behind resource
 * accessors that mirror DataForSEO's own API structure (serp, keywords_data,
 * backlinks, on_page, dataforseo_labs) and authenticates every request with
 * HTTP Basic Auth via `DATAFORSEO_LOGIN` / `DATAFORSEO_PASSWORD`.
 */
class DataForSeoClient
{
    public function serp(): Serp
    {
        return new Serp($this);
    }

    public function keywords(): Keywords
    {
        return new Keywords($this);
    }

    public function backlinks(): Backlinks
    {
        return new Backlinks($this);
    }

    public function onPage(): OnPage
    {
        return new OnPage($this);
    }

    public function labs(): Labs
    {
        return new Labs($this);
    }

    /**
     * DataForSEO wraps every POST body in a top-level array of "tasks", even
     * for a single-task live call.
     *
     * @param  array<int, mixed>  $body
     * @return array<string, mixed>
     *
     * @throws DataForSeoException
     */
    public function post(string $uri, array $body): array
    {
        return $this->handle($this->http()->post($uri, $body));
    }

    /**
     * @return array<string, mixed>
     *
     * @throws DataForSeoException
     */
    public function get(string $uri): array
    {
        return $this->handle($this->http()->get($uri));
    }

    private function http(): PendingRequest
    {
        return Http::baseUrl($this->baseUrl())
            ->withBasicAuth($this->login(), $this->password())
            ->acceptJson();
    }

    /**
     * @return array<string, mixed>
     *
     * @throws DataForSeoException
     */
    private function handle(Response $response): array
    {
        if ($response->failed()) {
            throw new DataForSeoException($this->errorMessage($response), $response->status());
        }

        $data = $response->json();

        return is_array($data) ? $data : [];
    }

    private function errorMessage(Response $response): string
    {
        $data = $response->json();

        if (is_array($data) && is_string($data['status_message'] ?? null)) {
            return $data['status_message'];
        }

        if (is_array($data) && is_string($data['message'] ?? null)) {
            return $data['message'];
        }

        return $response->body() !== ''
            ? $response->body()
            : "DataForSEO API request failed with status {$response->status()}.";
    }

    private function login(): string
    {
        return (string) config('dataforseo.login');
    }

    private function password(): string
    {
        return (string) config('dataforseo.password');
    }

    private function baseUrl(): string
    {
        return (string) config('dataforseo.base_url', 'https://api.dataforseo.com/v3');
    }
}
