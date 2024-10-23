<?php

namespace App\Modules\Measurements\Contracts;

interface SearchServiceInterface
{
    /**
     * search
     *
     * @param string $keyword
     * @param string $urlTarget
     * @return array
     * - number|null totalResults: results counter
     * - number|null top: top in results
     */
    public function search(string $keyword, string $urlTarget): array;
}
