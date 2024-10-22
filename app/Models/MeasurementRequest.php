<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MeasurementRequest extends Model
{
    use HasFactory;

    protected $table  = "measurement_requests";
    protected $fillable = ["url", "status", "ip"];

    public function measurementKeywords(): HasMany
    {
        return $this->hasMany(MeasurementKeyword::class, "measurement_request_id");
    }
}
