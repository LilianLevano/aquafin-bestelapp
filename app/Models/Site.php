<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * Site model.
 *
 * Represents a physical delivery site used in orders.
 * Each site has an associated {@see Address} and geographic coordinates
 * used for flood risk calculations.
 * Supports soft deletion via the SoftDeletes trait.
 *
 * @property int         $id          Primary key.
 * @property int         $address_id  Foreign key referencing the associated {@see Address}.
 * @property string      $description Name or label of the site.
 * @property float       $longitude   Geographic longitude coordinate.
 * @property float       $latitude    Geographic latitude coordinate.
 * @property Carbon      $created_at  Timestamp of record creation.
 * @property Carbon      $updated_at  Timestamp of last update.
 * @property Carbon|null $deleted_at  Soft-delete timestamp; null if not deleted.
 *
 * @property-read Collection<int, Order> $orders  The orders to be delivered to this site.
 * @property-read Collection<int, User>  $users   The users assigned to this site.
 * @property-read Address|null           $address The address associated with this site.
 */
#[Fillable(['address_id', 'description', 'longitude', 'latitude'])]
#[Table('sites')]
class Site extends Model
{
    /** @use HasFactory<\Database\Factories\SiteFactory> */
    use HasFactory;
    use SoftDeletes;


    /**
     * Get the orders assigned to this site.
     *
     * One-to-many: a site can have multiple orders (via "site_id" on the orders table).
     *
     * @return HasMany<Order>
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'site_id');
    }

    /**
     * Get the users assigned to this site.
     *
     * One-to-many: a site can have multiple users (via "site_id" on the users table).
     *
     * @return HasMany<User>
     */
    public function users()
    {
        return $this->hasMany(User::class, 'user_id');
    }

    /**
     * Get the address associated with this site.
     *
     * Many-to-one: the site holds the foreign key "address_id" referencing the addresses table.
     *
     * @return BelongsTo<Address, Site>
     */
    public function address()
    {
        return $this->hasOne(Address::class, 'id');
    }
}
