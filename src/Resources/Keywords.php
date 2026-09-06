<?php

namespace JeffersonGoncalves\DataForSeo\Resources;

use JeffersonGoncalves\DataForSeo\DataForSeoClient;

/**
 * Keywords Data endpoints (`/keywords_data/*`).
 */
class Keywords
{
    public function __construct(private readonly DataForSeoClient $client) {}

    /**
     * @param  array<int, string>  $keywords
     * @return array<string, mixed>
     */
    public function volume(array $keywords, int $locationCode = 2840, string $languageCode = 'en'): array
    {
        return $this->client->post('/keywords_data/google_ads/search_volume/live', [[
            'keywords' => $keywords,
            'location_code' => $locationCode,
            'language_code' => $languageCode,
        ]]);
    }

    /**
     * @return array<string, mixed>
     */
    public function forSite(string $target, int $locationCode = 2840, string $languageCode = 'en'): array
    {
        return $this->client->post('/keywords_data/google_ads/keywords_for_site/live', [[
            'target' => $target,
            'location_code' => $locationCode,
            'language_code' => $languageCode,
        ]]);
    }

    /**
     * @param  array<int, string>  $keywords
     * @return array<string, mixed>
     */
    public function forKeywords(array $keywords, int $locationCode = 2840, string $languageCode = 'en'): array
    {
        return $this->client->post('/keywords_data/google_ads/keywords_for_keywords/live', [[
            'keywords' => $keywords,
            'location_code' => $locationCode,
            'language_code' => $languageCode,
        ]]);
    }

    /**
     * @param  array<int, string>  $keywords
     * @return array<string, mixed>
     */
    public function trends(array $keywords, int $locationCode = 2840, string $languageCode = 'en'): array
    {
        return $this->client->post('/keywords_data/google_trends/explore/live', [[
            'keywords' => $keywords,
            'location_code' => $locationCode,
            'language_code' => $languageCode,
        ]]);
    }
}
