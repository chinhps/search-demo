<?php

namespace App\Repositories\MeasurementRanking;

use App\Models\MeasurementKeyword;
use App\Models\RankingSource;
use App\Repositories\RepositoryInterface;

interface MeasurementRankingInterface extends RepositoryInterface
{
    /**
     * createRank
     *
     * @param  array $attributes
     * @param  RankingSource $rankSource
     * @param  MeasurementKeyword $measurementKeyword
     * @return MeasurementRanking
     */
    public function createRank(array $attributes = [], RankingSource $rankSource, MeasurementKeyword $measurementKeyword);
}
