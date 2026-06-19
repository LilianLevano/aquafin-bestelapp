<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * RiskMonth model.
 *
 * Represents a single high-risk month within a {@see FloodRiskAnalysis}.
 * Stores the risk value, the threshold it exceeded, and the reason
 * for the elevated flood risk for that month.
 * Does not use soft deletion.
 *
 * @property int    $id                     Primary key.
 * @property int    $site_id                Foreign key referencing the associated {@see Site}.
 * @property int    $flood_risk_analysis_id Foreign key referencing the parent {@see FloodRiskAnalysis}.
 * @property int    $year                   The year this risk month covers.
 * @property int    $month                  The month (1–12) this risk month covers.
 * @property float  $risk_value             The computed flood risk value for this month.
 * @property float  $threshold              The risk threshold that was exceeded.
 * @property string $reason                 Human-readable explanation of the elevated risk.
 * @property Carbon $created_at             Timestamp of record creation.
 * @property Carbon $updated_at             Timestamp of last update.
 *
 * @property-read Site              $site              The site this risk month belongs to.
 * @property-read FloodRiskAnalysis $floodRiskAnalysis The flood risk analysis this month is part of.
 */
#[Fillable(['site_id', 'flood_risk_analysis_id', 'year', 'month', 'risk_value', 'threshold', 'reason'])]
#[Table('risk_months')]
class RiskMonth extends Model
{
    use HasFactory;

    /**
     * Get the site this risk month belongs to.
     *
     * Many-to-one: each risk month is associated with exactly one site.
     *
     * @return BelongsTo<Site, RiskMonth>
     */
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Get the flood risk analysis this risk month belongs to.
     *
     * Many-to-one: each risk month is part of exactly one flood risk analysis.
     *
     * @return BelongsTo<FloodRiskAnalysis, RiskMonth>
     */
    public function floodRiskAnalysis()
    {
        return $this->belongsTo(FloodRiskAnalysis::class);
    }
}
