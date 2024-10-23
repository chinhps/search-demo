<?php

namespace App\Jobs;

use App\Repositories\MeasurementRanking\MeasurementRankingInterface;
use App\Repositories\RankingSource\RankingSourceInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Modules\Measurements\SearchModule;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\MeasurementKeyword;
use Illuminate\Bus\Queueable;
use Carbon\Carbon;
use Illuminate\Bus\Batchable;
use Illuminate\Support\Facades\Log;
use Throwable;

class MeasurementSeachYahooJpJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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

        // cancel if have fail job
        if ($this->batch()->cancelled()) {
            return;
        }

        // get data in api
        $result = $searchModule->yahooJapan($this->measurementKeyword->keyword, $this->urlTarget);
        $source = $rankingSourceRepository->getSourceByName("YahooJp");

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
