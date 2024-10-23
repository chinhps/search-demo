<?php

namespace App\Repositories\MeasurementKeyword;

use App\Models\MeasurementKeyword;
use App\Models\MeasurementRequest;
use App\Repositories\BaseRepository;
use Illuminate\Support\Collection;

class MeasurementKeywordRepository extends BaseRepository implements MeasurementKeywordInterface
{
    public function __construct(MeasurementKeyword $model)
    {
        parent::__construct($model);
    }

    public function createKeyword(Collection $attributes, MeasurementRequest $measureRequest)
    {
        $keywords = $attributes->map(function ($data) use ($measureRequest) {
            $keyword = new $this->model($data);
            $keyword->measurementRequest()->associate($measureRequest);
            $keyword->save();
            return $keyword;
        });
        return $keywords;
    }
}
