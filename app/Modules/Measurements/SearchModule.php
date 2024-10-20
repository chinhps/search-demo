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

    public function google()
    {
        return $this->googleSearchService->search();
    }

    public function yahooJapan()
    {
        return $this->yahooJapanSearchService->search();
    }
}
