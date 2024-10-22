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
     */
    public function search(string $keyword, string $urlTarget): array;
}
