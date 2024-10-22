<?php

namespace App\Http\Controllers\v1\Measurement;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Measurement\MeasureRegisterRequest;
use App\Http\Resources\v1\Measurement\MeasureResultResource;
use App\Modules\Measurements\SearchModule;
use OpenApi\Attributes as OA;

#[OA\Info(
    title: "Measurement search rankings",
    version: "1.0.0",
    description: "API documentation for my application."
)]
class MeasurementController extends Controller
{

    public function __construct(
        private SearchModule $searchModule
    ) {}

    #[OA\Post(path: '/api/v1/measurement')]
    #[OA\Response(response: 200, description: 'AOK')]
    #[OA\Response(response: 401, description: 'Not allowed')]
    public function register(
        MeasureRegisterRequest $request
    ) {
        $validated = $request->validated();
    }

    #[OA\Get(path: '/api/v1/measurement/result')]
    #[OA\Response(response: 200, description: 'AOK')]
    #[OA\Response(response: 401, description: 'Not allowed')]
    public function resultRetrieval() {}
}
