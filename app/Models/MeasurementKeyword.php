<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MeasurementKeyword extends Model
{
    use HasFactory;

    protected $table  = "measurement_keywords";

    public function measurementRequest(): BelongsTo
    {
        return $this->belongsTo(MeasurementRequest::class, "measurement_request_id");
    }

    public function measurementRankings(): HasMany
    {
        return $this->hasMany(MeasurementRanking::class, "measurement_keyword_id");
    }
}
