<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['site_id', 'year', 'month', 'min_risk', 'max_risk', 'avg_risk', 'total_precipitation', 'avg_humidity', 'season', 'is_extreme'])]
#[Table('flood_risk_analyses')]
class FloodRiskAnalysis extends Model
{
    use HasFactory;

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function riskMonths()
    {
        return $this->hasMany(RiskMonth::class);
    }
}