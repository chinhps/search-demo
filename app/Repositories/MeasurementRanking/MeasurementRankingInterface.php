<?php

namespace App\Repositories\MeasurementRanking;


interface MeasurementRankingInterface
{
    public function list(array $filter, float $paginate = 15);
    public function updateOrInsert(?float $id, array $params);
}
