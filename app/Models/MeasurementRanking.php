<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeasurementRanking extends Model
{
    use HasFactory;

    protected $table  = "measurement_rankings";
    protected $fillable = ["rank", "results_counter", "retrieved_at"];
    public $timestamps = false;

    public function measurementKeyword(): BelongsTo
    {
        return $this->belongsTo(MeasurementKeyword::class, "measurement_keyword_id");
    }

    public function rankingSoure(): BelongsTo
    {
        return $this->belongsTo(RankingSource::class, "ranking_source_id");
    }
}
