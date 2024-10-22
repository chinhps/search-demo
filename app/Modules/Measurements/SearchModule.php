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

    public function google(string $keyword, string $urlTarget): array
    {
        return $this->googleSearchService->search($keyword, $urlTarget);
    }

    public function yahooJapan(string $keyword, string $urlTarget): array
    {
        return $this->yahooJapanSearchService->search($keyword, $urlTarget);
    }
}
