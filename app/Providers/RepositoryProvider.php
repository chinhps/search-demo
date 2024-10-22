<?php

namespace App\Providers;

use App\Repositories\BaseRepository;
use App\Repositories\MeasurementKeyword\MeasurementKeywordInterface;
use App\Repositories\MeasurementKeyword\MeasurementKeywordRepository;
use App\Repositories\MeasurementRanking\MeasurementRankingInterface;
use App\Repositories\MeasurementRanking\MeasurementRankingRepository;
use App\Repositories\MeasurementRequest\MeasurementRequestInterface;
use App\Repositories\MeasurementRequest\MeasurementRequestRepository;
use App\Repositories\RepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(RepositoryInterface::class, BaseRepository::class);
        $this->app->bind(MeasurementRequestInterface::class, MeasurementRequestRepository::class);
        // $this->app->bind(MeasurementRankingInterface::class, MeasurementRankingRepository::class);
        // $this->app->bind(MeasurementKeywordInterface::class, MeasurementKeywordRepository::class);
    }
}
