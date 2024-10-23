<?php

namespace App\Http\Controllers\v1\Measurement;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Measurement\MeasureRegisterRequest;
use App\Http\Resources\v1\Measurement\MeasurementRequestsResource;
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

    #[OA\Post(
        path: '/api/v1/measurement',
        tags: ['register measurement'],
        summary: 'Register url for measurement search',
        description: 'Register and push to queue',
        requestBody: new OA\RequestBody(
            required: true,
            content: [
                'application/json' => new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(
                            property: 'url_target',
                            type: 'string',
                            description: 'Url website (Exact match for top domain)',
                            example: 'https://chinhdev.com'
                        ),
                        new OA\Property(
                            property: 'keywords',
                            type: 'array',
                            items: new OA\Items(
                                type: 'string',
                                example: 'Allgrow-labo'
                            )
                        )
                    ]
                )
            ]
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'status', type: 'integer', example: 200),
                        new OA\Property(property: 'error', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Thao tác thành công!'),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Error',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'status', type: 'integer', example: 400),
                        new OA\Property(property: 'error', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Có lỗi gì đó trong quá trình xử lý! Vui lòng thử lại!'),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validated',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'msg',
                                    type: 'array',
                                    items: new OA\Items(
                                        type: 'string',
                                        example: 'Bạn cần phải nhập keywords'
                                    )
                                )
                            ]
                        ),
                    ]
                )
            ),
        ]
    )]
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

    #[OA\Schema(
        schema: 'ItemMeasurementRequest',
        type: 'object',
        properties: [
            new OA\Property(property: 'id', type: 'integer'),
            new OA\Property(property: 'url', type: 'string'),
            new OA\Property(property: 'status', type: 'integer'),
            new OA\Property(property: 'status_name', type: 'string'),
            new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        ]
    )]
    #[OA\Schema(
        schema: 'ItemPaginate',
        type: 'object',
        properties: [
            new OA\Property(property: 'current_page', type: 'integer'),
            new OA\Property(property: 'last_page', type: 'integer'),
            new OA\Property(property: 'total', type: 'integer'),
        ]
    )]
    #[OA\Schema(
        schema: 'ListMeasurementRequest',
        type: 'object',
        properties: [
            new OA\Property(
                property: 'data',
                type: 'array',
                items: new OA\Items(ref: '#/components/schemas/ItemMeasurementRequest'),
            ),
            new OA\Property(
                property: 'paginate',
                type: 'array',
                items: new OA\Items(ref: '#/components/schemas/ItemPaginate'),
            )
        ],
        example: [
            "data" => [
                [
                    "id" => 4,
                    "url" => "https://chinhdev.com",
                    "status_name" => "Đang xử lý",
                    "status" => 0,
                    "created_at" => "2024-10-23T04:54:58.000000Z"
                ],
                [
                    "id" => 5,
                    "url" => "https://allgrow-labo.jp/",
                    "status_name" => "Đã hoàn thành",
                    "status" => 1,
                    "created_at" => "2024-10-23T04:54:58.000000Z"
                ]
            ],
            "paginate" => [
                "current_page" => 1,
                "last_page" => 1,
                "total" => 10
            ]
        ]
    )]
    #[OA\Get(
        path: '/api/v1/measurement',
        tags: ['results measurement'],
        summary: 'List measurement request by your IP',
        description: 'List measurement request by your IP',
        parameters: [
            new OA\Parameter(
                name: 'page',
                in: 'query',
                description: 'page',
                required: false,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Response with status: 1: success, 0: pending, 2: error',
                content: new OA\JsonContent(ref: '#/components/schemas/ListMeasurementRequest')
            ),
        ]
    )]
    public function resultRetrieval()
    {
        // Get current ip
        $ip = getUserIP();
        // paginate
        $paginate = 15;
        // return data by ip user
        $data = $this->measurementRequestRepository->listFilter([
            ["ip", $ip]
        ], $paginate);
        return MeasurementRequestsResource::collection($data);
    }

    #[OA\Schema(
        schema: 'DetailItem',
        type: 'object',
        properties: [
            new OA\Property(property: 'rank', type: 'number', nullable: true, example: 1),
            new OA\Property(property: 'results_counter', type: 'number', example: 9820),
            new OA\Property(property: 'ranking_source', type: 'string', example: 'YahooJp'),
        ]
    )]
    #[OA\Schema(
        schema: 'measurementDetailResponse',
        type: 'object',
        properties: [
            new OA\Property(
                type: 'array',
                property: 'data',
                items: new OA\Items(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'number', example: 1),
                        new OA\Property(property: 'keyword', type: 'string', example: 'Allgrow-labo'),
                        new OA\Property(
                            property: 'results',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/DetailItem')
                        ),
                    ]
                )
            )
        ]
    )]
    #[OA\Get(
        path: '/api/v1/measurement/{id}',
        tags: ['results measurement'],
        summary: 'Get detail result',
        description: 'Get detail result',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID get in list measurement',
                schema: new OA\Schema(type: 'number')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Result counter in search engine and Top',
                content: new OA\JsonContent(ref: '#/components/schemas/measurementDetailResponse')
            )
        ]
    )]
    public function resultDetail($id)
    {
        try {
            // Get current ip
            $ip = getUserIP();
            // get data by ip user
            $data = $this->measurementRequestRepository->getListKeywordByIP(id: $id, ip: $ip);
            return new MeasureResultResource($data);
        } catch (\Exception $e) {
            // log error
            Log::error(json_encode([
                "f"         => "resultDetail",
                "message"   => $e->getMessage(),
            ]));
            // push error to dev use slack, telegram,...

            return $this->responseError(
                message: "Không tồn tại kết quả! Vui lòng thử lại!",
                status: 404
            );
        }
    }
}
