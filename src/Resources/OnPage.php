<?php

namespace JeffersonGoncalves\DataForSeo\Resources;

use JeffersonGoncalves\DataForSeo\DataForSeoClient;

/**
 * OnPage endpoints (`/on_page/*`).
 */
class OnPage
{
    public function __construct(private readonly DataForSeoClient $client) {}

    /**
     * @return array<string, mixed>
     */
    public function audit(string $url, bool $enableJavascript = true): array
    {
        return $this->client->post('/on_page/instant_pages', [[
            'url' => $url,
            'enable_javascript' => $enableJavascript,
        ]]);
    }
}
