<?php

namespace App\Modules\Measurements;

use App\Modules\Measurements\Services\GoogleSearchService;
use App\Modules\Measurements\Services\YahooJapanSearchService;

class SearchModule
{
    public function __construct(
        protected GoogleSearchService $googleSearchService,
        protected YahooJapanSearchService $yahooJapanSearchService,
    ) {}

    /**
     * google
     *
     * @param string $keyword
     * @param string $urlTarget
     * @return array
     * - number|null totalResults: results counter
     * - number|null top: top in results
     */
    public function google(string $keyword, string $urlTarget): array
    {
        return $this->googleSearchService->search($keyword, $urlTarget);
    }

    /**
     * yahooJapan
     *
     * @param string $keyword
     * @param string $urlTarget
     * @return array
     * - number|null totalResults: results counter
     * - number|null top: top in results
     */
    public function yahooJapan(string $keyword, string $urlTarget): array
    {
        return $this->yahooJapanSearchService->search($keyword, $urlTarget);
    }
}
