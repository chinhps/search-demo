<?php

namespace App\Repositories\MeasurementRequest;

use App\Models\MeasurementRequest;
use App\Repositories\BaseRepository;

class MeasurementRequestRepository extends BaseRepository implements MeasurementRequestInterface
{

    public function __construct(MeasurementRequest $model)
    {
        parent::__construct($model);
    }

    public function listFilter(array $filter = [], float $paginate = 15)
    {
        return $this->model->where($filter)->paginate($paginate);
    }

    public function getListKeywordByIP(float $id, string $ip)
    {
        $model = $this->model
            ->with('measurementKeywords.measurementRankings.rankingSource')
            ->where('ip', $ip)
            ->findOrFail($id);
        return $model;
    }
}
