<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RankingSource extends Model
{
    use HasFactory;

    protected $table  = "ranking_sources";
    protected $fillable = ["source_name"];
    public $timestamps = false;

    public function measurementRankings(): HasMany
    {
        return $this->hasMany(MeasurementRanking::class, "ranking_source_id");
    }
}
