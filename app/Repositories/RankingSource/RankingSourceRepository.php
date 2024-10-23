<?php

namespace App\Repositories\RankingSource;

use App\Models\RankingSource;
use App\Repositories\BaseRepository;

class RankingSourceRepository extends BaseRepository implements RankingSourceInterface
{
    public function __construct(RankingSource $model)
    {
        parent::__construct($model);
    }

    /**
     * getSourceByName
     *
     * @param  string $name
     * @return RankingSource
     */
    public function getSourceByName(string $name)
    {
        return $this->model->where('source_name', $name)->firstOrFail();
    }
}
