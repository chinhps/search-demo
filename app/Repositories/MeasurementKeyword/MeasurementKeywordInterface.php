<?php

namespace App\Repositories\MeasurementKeyword;

use App\Models\MeasurementRequest;
use App\Repositories\RepositoryInterface;
use Illuminate\Support\Collection;

interface MeasurementKeywordInterface extends RepositoryInterface
{
    public function createKeyword(Collection $attributes, MeasurementRequest $measureRequest);
}
