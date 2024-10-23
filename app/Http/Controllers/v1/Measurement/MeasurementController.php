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
use Illuminate\Support\Facades\Log;
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
        // Validated
        $validated = $request->validated();

        $url = $validated['url_target'];
        $keywords = $validated['keywords'];

        try {
            DB::beginTransaction();

            // Insert measurement request
            $measurementRequest = $this->measurementRequestRepository->create([
                "url" => $url,
                "status" => 0,
                "ip" => getUserIP(),
            ]);

            // format data keywords
            $formatKeywords = collect($keywords)->map(
                fn(string $keyword) => ["keyword" => $keyword]
            );
            // Insert keywords
            $measurementKeywords = $this->measurementKeywordRepository->createKeyword(
                attributes: $formatKeywords,
                measureRequest: $measurementRequest
            );

            // Push job to queue
            foreach ($measurementKeywords as $measurementKeyword) {
                MeasurementSeachGoogleJob::dispatch($measurementKeyword, $url);
                MeasurementSeachYahooJpJob::dispatch($measurementKeyword, $url);
            }

            DB::commit();
            return $this->responseSuccess(
                message: "Thao tác thành công!",
                status: 200
            );
        } catch (\Exception $e) {
            DB::rollBack();
            // log error
            Log::channel("registerMeasurement")->error(json_encode([
                "message"   => $e->getMessage(),
                "url"       => $url,
                "keywords"  => $keywords
            ]));
            // push error to dev use slack, telegram,...

            return $this->responseError(
                message: "Có lỗi gì đó trong quá trình xử lý! Vui lòng thử lại!",
                status: 400
            );
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
