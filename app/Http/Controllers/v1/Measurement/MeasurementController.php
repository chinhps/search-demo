<?php

namespace App\Http\Controllers\v1\Measurement;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Measurement\MeasureRegisterRequest;
use App\Http\Resources\v1\Measurement\MeasureResultResource;
use App\Jobs\MeasurementSeachGoogleJob;
use App\Jobs\MeasurementSeachYahooJpJob;
use App\Modules\Measurements\SearchModule;
use App\Repositories\MeasurementKeyword\MeasurementKeywordInterface;
use App\Repositories\MeasurementRequest\MeasurementRequestInterface;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;

use function App\Helper\getUserIP;

#[OA\Info(
    title: "Measurement search rankings",
    version: "1.0.0",
    description: "API documentation for my application."
)]
class MeasurementController extends Controller
{

    public function __construct(
        private SearchModule $searchModule,
        private MeasurementRequestInterface $measurementRequestRepository,
        private MeasurementKeywordInterface $measurementKeywordRepository
    ) {}

    #[OA\Post(path: '/api/v1/measurement')]
    #[OA\Response(response: 200, description: 'AOK')]
    #[OA\Response(response: 401, description: 'Not allowed')]
    public function register(
        MeasureRegisterRequest $request
    ) {
        $validated = $request->validated();

        $url = $validated['url_target'];
        $keywords = $validated['keywords'];

        try {
            DB::beginTransaction();
            $measurementRequest = $this->measurementRequestRepository->create([
                "url" => $url,
                "status" => 0,
                "ip" => getUserIP(),
            ]);

            $formatKeywords = collect($keywords)->map(
                fn(string $keyword) => ["keyword" => $keyword]
            );
            $measurementKeywords = $this->measurementKeywordRepository->createKeyword(
                attributes: $formatKeywords,
                measureRequest: $measurementRequest
            );

            foreach($measurementKeywords as $measurementKeyword) {
                MeasurementSeachGoogleJob::dispatch($measurementKeyword, $url);
                MeasurementSeachYahooJpJob::dispatch($measurementKeyword, $url);
            }

            DB::commit();
            return 123;
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
        }
    }

    #[OA\Get(path: '/api/v1/measurement/result')]
    #[OA\Response(response: 200, description: 'AOK')]
    #[OA\Response(response: 401, description: 'Not allowed')]
    public function resultRetrieval()
    {
        return 123;
    }
}
