<?php

namespace App\Repositories\MeasurementRanking;

use App\Models\MeasurementKeyword;
use App\Models\MeasurementRanking;
use App\Models\RankingSource;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Log;

class MeasurementRankingRepository extends BaseRepository implements MeasurementRankingInterface
{
    public function __construct(MeasurementRanking $model)
    {
        parent::__construct($model);
    }
    
    /**
     * createRank
     *
     * @param  array $attributes
     * @param  RankingSource $rankSource
     * @param  MeasurementKeyword $measurementKeyword
     * @return MeasurementRanking
     */
    public function createRank(array $attributes = [], RankingSource $rankSource, MeasurementKeyword $measurementKeyword)
    {
        $rank = new $this->model($attributes);
        $rank->rankingSource()->associate($rankSource);
        $rank->measurementKeyword()->associate($measurementKeyword);
        $rank->save();
        return $rank;
    }
}
