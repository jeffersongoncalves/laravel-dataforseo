<?php

namespace JeffersonGoncalves\DataForSeo\Resources;

use JeffersonGoncalves\DataForSeo\DataForSeoClient;

/**
 * SERP endpoints (`/serp/google/*`).
 */
class Serp
{
    public function __construct(private readonly DataForSeoClient $client) {}

    /**
     * @return array<string, mixed>
     */
    public function google(string $keyword, string $locationName = 'United States', string $languageName = 'English'): array
    {
        return $this->client->post('/serp/google/organic/live/regular', [[
            'keyword' => $keyword,
            'location_name' => $locationName,
            'language_name' => $languageName,
        ]]);
    }

    /**
     * @return array<string, mixed>
     */
    public function locations(): array
    {
        return $this->client->get('/serp/google/locations');
    }

    /**
     * @return array<string, mixed>
     */
    public function languages(): array
    {
        return $this->client->get('/serp/google/languages');
    }
}
