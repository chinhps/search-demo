<?php

namespace App\Modules\Measurements\Services;

use App\Modules\Measurements\Contracts\SearchServiceInterface;
use Illuminate\Support\Facades\Http;

class GoogleSearchService implements SearchServiceInterface
{
    private string $URL = "https://customsearch.googleapis.com/customsearch/v1";

    public function search(string $keyword, string $urlTarget): array
    {
        $top = 20;
        // Google console api get only 10 results for one page
        $maxPage = ceil($top / 10);
        
        // Query params
        $payload = [
            "q"             => $keyword, // Keyword
            "key"           => env("GOOGLE_CONSOLE_API", ""), // Api key
            "cx"            => env("SEARCH_ENGINE_ID", ""), // Search engine id
        ];

        $result = [
            "totalResults"  => null,
            "top"           => null
        ];

        // Loop to get data of page
        for ($i = 1; $i <= $maxPage; $i++) {
            // Use google console api
            $response = Http::get($this->URL, [
                ...$payload,
                "start" => ($i - 1) * 10 + 1 // page1: 10, page2: 11, page3: 21,...
            ])->json();

            // Get total results
            $result['totalResults'] = (int)$response["searchInformation"]["totalResults"] ?? 0;

            // dont have results
            if ($top > $result['totalResults'] || !isset($response['items'])) {
                return $result;
            }

            // Get top results
            foreach ($response['items'] as $key => $item) {

                // clear domain
                $domainTarget = parse_url($urlTarget, PHP_URL_HOST);
                $clearDomainTarget = preg_replace("/^www\./", "", $domainTarget);
                $clearDomainCurrent = preg_replace("/^www\./", "", $item['displayLink']);
                
                // Check 2 domains
                if ($clearDomainTarget == $clearDomainCurrent) {
                    $result["top"] = ($key + 1) + ($i > 1 ? ($i - 1) * 10 : 0);
                    return $result;
                }
            }
        }
        return $result;
    }
}
