<?php

namespace App\Repositories\MeasurementRanking;

use App\Models\MeasurementKeyword;
use App\Models\RankingSource;
use App\Repositories\RepositoryInterface;

interface MeasurementRankingInterface extends RepositoryInterface
{
    public function createRank($attributes = [], RankingSource $rankSource, MeasurementKeyword $measurementKeyword);
}
