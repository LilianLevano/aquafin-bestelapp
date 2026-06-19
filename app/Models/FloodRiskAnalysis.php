<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * FloodRiskAnalysis model.
 *
 * Represents a flood risk analysis for a specific site, year, and month.
 * Stores aggregated risk metrics and precipitation/humidity data per period.
 * Does not use soft deletion.
 *
 * @property int    $id                  Primary key.
 * @property int    $site_id             Foreign key referencing the associated site.
 * @property int    $year                The year this analysis covers.
 * @property int    $month               The month (1–12) this analysis covers.
 * @property float  $min_risk            Minimum flood risk score for the period.
 * @property float  $max_risk            Maximum flood risk score for the period.
 * @property float  $avg_risk            Average flood risk score for the period.
 * @property float  $total_precipitation Total precipitation (mm) for the period.
 * @property float  $avg_humidity        Average relative humidity (%) for the period.
 * @property string $season              Season label (e.g. "winter", "spring", "summer", "autumn").
 * @property bool   $is_extreme          Whether the period is flagged as an extreme flood risk.
 * @property Carbon $created_at          Timestamp of record creation.
 * @property Carbon $updated_at          Timestamp of last update.
 *
 * @property-read Site                    $site       The site this analysis belongs to.
 * @property-read Collection<int, RiskMonth> $riskMonths The individual risk months for this analysis.
 */
#[Fillable(['site_id', 'year', 'month', 'min_risk', 'max_risk', 'avg_risk', 'total_precipitation', 'avg_humidity', 'season', 'is_extreme'])]
#[Table('flood_risk_analyses')]
class FloodRiskAnalysis extends Model
{
    use HasFactory;

    /**
     * Get the site this analysis belongs to.
     *
     * Many-to-one: each analysis is associated with exactly one site.
     *
     * @return BelongsTo<Site, FloodRiskAnalysis>
     */
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Get the individual risk months associated with this analysis.
     *
     * One-to-many: an analysis can have multiple risk month records.
     *
     * @return HasMany<RiskMonth>
     */
    public function riskMonths()
    {
        return $this->hasMany(RiskMonth::class);
    }
}
