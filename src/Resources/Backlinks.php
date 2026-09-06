<?php

namespace JeffersonGoncalves\DataForSeo\Resources;

use JeffersonGoncalves\DataForSeo\DataForSeoClient;

/**
 * Backlinks endpoints (`/backlinks/*`).
 */
class Backlinks
{
    public function __construct(private readonly DataForSeoClient $client) {}

    /**
     * @return array<string, mixed>
     */
    public function summary(string $target): array
    {
        return $this->client->post('/backlinks/summary/live', [[
            'target' => $target,
            'backlinks_status_type' => 'live',
        ]]);
    }

    /**
     * @return array<string, mixed>
     */
    public function list(string $target, string $mode = 'as_is', int $limit = 100): array
    {
        return $this->client->post('/backlinks/backlinks/live', [[
            'target' => $target,
            'mode' => $mode,
            'limit' => $limit,
            'backlinks_status_type' => 'live',
        ]]);
    }

    /**
     * @return array<string, mixed>
     */
    public function referringDomains(string $target, int $limit = 100): array
    {
        return $this->client->post('/backlinks/referring_domains/live', [[
            'target' => $target,
            'limit' => $limit,
        ]]);
    }

    /**
     * @return array<string, mixed>
     */
    public function anchors(string $target, int $limit = 100): array
    {
        return $this->client->post('/backlinks/anchors/live', [[
            'target' => $target,
            'limit' => $limit,
        ]]);
    }

    /**
     * @return array<string, mixed>
     */
    public function index(): array
    {
        return $this->client->get('/backlinks/index');
    }
}
