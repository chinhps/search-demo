<?php

namespace App\Jobs;

use App\Models\MeasurementKeyword;
use App\Modules\Measurements\SearchModule;
use App\Repositories\MeasurementRanking\MeasurementRankingInterface;
use App\Repositories\RankingSource\RankingSourceInterface;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class MeasurementSeachGoogleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private MeasurementKeyword $measurementKeyword,
        private string $urlTarget,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(
        SearchModule $searchModule,
        RankingSourceInterface $rankingSourceRepository,
        MeasurementRankingInterface $measurementRankingRepository
    ): void {
        // get data in api
        $result = $searchModule->google($this->measurementKeyword->keyword, $this->urlTarget);
        $source = $rankingSourceRepository->getSourceByName("Google");

        // attributes
        $attributes = [
            "rank"              => $result['top'],
            "results_counter"   => $result['totalResults'],
            "retrieved_at"      => Carbon::now()
        ];

        // insert to database
        $measurementRankingRepository->createRank(
            attributes: $attributes,
            rankSource: $source,
            measurementKeyword: $this->measurementKeyword
        );
    }

    public function failed(?Throwable $exception): void
    {
        Log::channel("queueSearch")->info($exception->getMessage());
        // push error to dev use slack, telegram,...
    }
}
