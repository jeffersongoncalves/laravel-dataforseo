<?php

namespace JeffersonGoncalves\DataForSeo\Facades;

use Illuminate\Support\Facades\Facade;
use JeffersonGoncalves\DataForSeo\DataForSeoClient;
use JeffersonGoncalves\DataForSeo\Resources\Backlinks;
use JeffersonGoncalves\DataForSeo\Resources\Keywords;
use JeffersonGoncalves\DataForSeo\Resources\Labs;
use JeffersonGoncalves\DataForSeo\Resources\OnPage;
use JeffersonGoncalves\DataForSeo\Resources\Serp;

/**
 * @method static Serp serp()
 * @method static Keywords keywords()
 * @method static Backlinks backlinks()
 * @method static OnPage onPage()
 * @method static Labs labs()
 *
 * @see DataForSeoClient
 */
class DataForSeo extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'dataforseo';
    }
}
