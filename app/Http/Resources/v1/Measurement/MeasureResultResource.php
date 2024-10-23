<?php

namespace App\Http\Resources\v1\Measurement;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;

class MeasureResultResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = $this->measurementKeywords->map(function ($keyword) {
            return [
                "id"        => $keyword->id,
                "keyword"   => $keyword->keyword,
                "results"   => $keyword->measurementRankings->map(function ($result) {
                    return [
                        "rank"              => $result->rank,
                        "results_counter"   => $result->results_counter,
                        "ranking_source"     => $result->rankingSource->source_name,

                    ];
                })
            ];
        });
        return $data->toArray();
    }
}
