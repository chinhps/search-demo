<?php

namespace App\Modules\Measurements\Services;

use App\Modules\Measurements\Contracts\SearchServiceInterface;
use Illuminate\Support\Facades\Http;

class YahooJapanSearchService implements SearchServiceInterface
{
    private string $URL = "https://search.yahoo.co.jp/search";

    public function search(string $keyword, string $urlTarget): array
    {
        $top = 20;
        // YahooJp search get only 10 results for one page
        $maxPage = ceil($top / 10);

        // Query params
        $payload = [
            "p"             => $keyword, // Keyword
        ];

        $result = [
            "totalResults"  => null,
            "top"           => null
        ];

        // Loop to get data of page
        for ($i = 1; $i <= $maxPage; $i++) {
            // Use YahooJp search
            $response = Http::withHeaders([
                "user-agent" => YahooJapanSearchServiceHelper::randomUserAgent()
            ])->get($this->URL, [
                ...$payload,
                "b" => ($i - 1) * 10 + 1 // page1: 10, page2: 11, page3: 21,...
            ])->body();

            // Get next data in source html
            $nextData = YahooJapanSearchServiceHelper::getNextDataWithRegex($response);
            $data = YahooJapanSearchServiceHelper::getData($nextData);
            $result['totalResults'] = $data['totalResults'];
            // dont have results
            if ($top > $result['totalResults'] || !isset($data['items'])) {
                return $result;
            }

            // Get top results
            foreach ($data['items'] as $key => $item) {

                // clear domain
                $domainTarget = parse_url($urlTarget, PHP_URL_HOST);
                $domainCurrent = parse_url($item['url'], PHP_URL_HOST);
                $clearDomainTarget = preg_replace("/^www\./", "", $domainTarget);
                $clearDomainCurrent = preg_replace("/^www\./", "", $domainCurrent);

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


class YahooJapanSearchServiceHelper
{
    /**
     * getNextDataWithRegex
     * 
     * Get next data in source html
     *
     * @param string $html
     * @return mixed
     */
    public static function getNextDataWithRegex(string $html): mixed
    {
        if (preg_match(
            '/<script id="__NEXT_DATA__" type="application\/json">(.*?)<\/script>/s',
            $html,
            $matches
        )) {
            return json_decode($matches[1], true);
        }
        return null;
    }

    /**
     * randomUserAgent
     * 
     * Random user-agent for YahooJp search
     *
     * @return string
     */
    public static function randomUserAgent(): string
    {
        $data = [
            "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.5938.132 Safari/537.36",
            "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36 Edg/130.0.0.0",
            "Mozilla/5.0 (iPhone; CPU iPhone OS 16_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6 Mobile/15E148 Safari/604.1",
            "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.5938.132 Safari/537.36 Edg/117.0.2045.47",
            "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.5938.132 Safari/537.36 Edg/117.0.2045.47",
            "Mozilla/5.0 (Linux; Android 11; SM-G975F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.5938.132 Mobile Safari/537.36 OPR/75.0.3969.243"
        ];
        return $data[rand(0, count($data) - 1)];
    }

    /**
     * getData
     *
     * @param  mixed $data
     * @return array results
     * - int totalResults: results counter
     * - array items: items
     */
    public static function getData(array|null $data): array
    {
        $result = $data['props']['initialProps']['pageProps']['pageData'] ?? [];
        return [
            "totalResults" => $result['pager']['hits'] ?? null,
            "items" => $result['algos'] ?? [],
        ];
    }
}
