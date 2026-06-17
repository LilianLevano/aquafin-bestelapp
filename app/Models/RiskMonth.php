<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['site_id', 'flood_risk_analysis_id', 'year', 'month', 'risk_value', 'threshold', 'reason'])]
#[Table('risk_months')]
class RiskMonth extends Model
{
    use HasFactory;

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function floodRiskAnalysis()
    {
        return $this->belongsTo(FloodRiskAnalysis::class);
    }
}