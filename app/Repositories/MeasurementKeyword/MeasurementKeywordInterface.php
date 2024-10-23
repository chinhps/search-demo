<?php

namespace App\Repositories\MeasurementKeyword;

use App\Models\MeasurementRequest;
use App\Repositories\RepositoryInterface;
use Illuminate\Support\Collection;

interface MeasurementKeywordInterface extends RepositoryInterface
{
    /**
     * createKeyword
     *
     * @param  Collection $attributes
     * @param  MeasurementRequest $measureRequest
     * @return \Illuminate\Support\Collection<MeasurementKeyword>
     */
    public function createKeyword(Collection $attributes, MeasurementRequest $measureRequest);
}
