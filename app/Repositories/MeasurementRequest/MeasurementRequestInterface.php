<?php

namespace App\Repositories\MeasurementRequest;

use App\Repositories\RepositoryInterface;

interface MeasurementRequestInterface extends RepositoryInterface
{
    public function getListKeywordByIP(float $id, string $ip);
    public function listFilter(array $filter = [], float $paginate = 15);
}
