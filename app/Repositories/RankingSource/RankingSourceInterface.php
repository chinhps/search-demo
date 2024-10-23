<?php

namespace App\Repositories\RankingSource;

use App\Repositories\RepositoryInterface;

interface RankingSourceInterface extends RepositoryInterface
{
    /**
     * getSourceByName
     *
     * @param  string $name
     * @return \App\Models\RankingSource
     */
    public function getSourceByName(string $name);
}
