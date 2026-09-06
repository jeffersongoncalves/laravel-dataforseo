<?php

namespace JeffersonGoncalves\DataForSeo\Resources;

use JeffersonGoncalves\DataForSeo\DataForSeoClient;

/**
 * DataForSEO Labs endpoints (`/dataforseo_labs/*`).
 */
class Labs
{
    public function __construct(private readonly DataForSeoClient $client) {}

    /**
     * @return array<string, mixed>
     */
    public function competitors(string $target, int $locationCode = 2840, string $languageCode = 'en', int $limit = 100): array
    {
        return $this->client->post('/dataforseo_labs/google/competitors_domain/live', [[
            'target' => $target,
            'location_code' => $locationCode,
            'language_code' => $languageCode,
            'limit' => $limit,
        ]]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rankedKeywords(string $target, int $locationCode = 2840, string $languageCode = 'en', int $limit = 100): array
    {
        return $this->client->post('/dataforseo_labs/google/ranked_keywords/live', [[
            'target' => $target,
            'location_code' => $locationCode,
            'language_code' => $languageCode,
            'limit' => $limit,
        ]]);
    }

    /**
     * @param  array<int, string>  $targets  At least two domains; encoded as target1, target2, ... in the payload.
     * @return array<string, mixed>
     */
    public function domainIntersection(array $targets, int $locationCode = 2840, string $languageCode = 'en', int $limit = 100): array
    {
        $payload = [
            'location_code' => $locationCode,
            'language_code' => $languageCode,
            'limit' => $limit,
        ];

        foreach (array_values($targets) as $index => $target) {
            $payload['target'.($index + 1)] = $target;
        }

        return $this->client->post('/dataforseo_labs/google/domain_intersection/live', [$payload]);
    }
}
