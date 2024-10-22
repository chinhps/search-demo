<?php

namespace App\Repositories\MeasurementRequest;

use App\Repositories\RepositoryInterface;

interface MeasurementRequestInterface extends RepositoryInterface
{
    public function listFilter(array $filter, float $paginate = 15);
}
