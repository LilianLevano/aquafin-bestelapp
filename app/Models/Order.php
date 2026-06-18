<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * Order model.
 *
 * Represents an order placed by a user for one or more materials,
 * to be delivered to a specific site.
 * Materials are linked via the "order_materials" pivot table,
 * which carries a "quantity" column.
 * Supports soft deletion via the SoftDeletes trait.
 *
 * @property int         $id            Primary key.
 * @property int         $user_id       Foreign key referencing the {@see User} who placed the order.
 * @property int         $site_id       Foreign key referencing the {@see Site} for delivery.
 * @property Carbon      $delivery_date Requested delivery date.
 * @property Carbon      $created_at    Timestamp of record creation.
 * @property Carbon      $updated_at    Timestamp of last update.
 * @property Carbon|null $deleted_at    Soft-delete timestamp; null if not deleted.
 *
 * @property-read User|null                  $user      The user who placed this order.
 * @property-read Collection<int, Material>  $materials The materials included in this order.
 * @property-read Site|null                  $site      The site this order is to be delivered to.
 */
#[Fillable(['user_id', 'site_id', 'delivery_date'])]
#[Table('orders')]
class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;
    use SoftDeletes;

    /**
     * Get the user who placed this order.
     *
     * Many-to-one: each order belongs to exactly one user.
     *
     * @return BelongsTo<User, Order>
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the materials included in this order.
     *
     * Many-to-many via the "order_materials" pivot table.
     * The pivot carries a "quantity" column representing how many units
     * of each material were ordered.
     *
     * @return BelongsToMany<Material>
     */
    public function materials()
    {
        return $this->belongsToMany(Material::class, 'order_materials', 'order_id', 'material_id')->withPivot('quantity');
    }

    /**
     * Get the site this order is to be delivered to.
     *
     * Many-to-one: each order is assigned to exactly one site.
     *
     * @return BelongsTo<Site, Order>
     */
    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }
}
