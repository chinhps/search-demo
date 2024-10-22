<?php

namespace App\Repositories\MeasurementKeyword;


interface MeasurementKeywordInterface
{
    public function list(array $filter, float $paginate = 15);
    public function updateOrInsert(?float $id, array $params);
}
