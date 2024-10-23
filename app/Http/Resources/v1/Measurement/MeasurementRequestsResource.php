<?php

namespace App\Http\Resources\v1\Measurement;

use App\Http\Resources\BaseResource;
use App\Traits\StatusTrait;
use Illuminate\Http\Request;

class MeasurementRequestsResource extends BaseResource
{
    use StatusTrait;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"                => $this->id,
            "url"               => $this->url,
            "status_name"       => $this->measurementRequestStatus($this->status),
            "status"            => $this->status,
            "created_at"        => $this->created_at
        ];
    }
}
