<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\MaterialFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * Material model.
 *
 * Represents an orderable material belonging to a category.
 * Materials are linked to orders via the "order_materials" pivot table,
 * which carries a "quantity" column.
 * Supports soft deletion via the SoftDeletes trait.
 *
 * @property int         $id          Primary key.
 * @property int         $category_id Foreign key referencing {@see Category}.
 * @property string      $name        Material name. Must be unique.
 * @property string      $description Description of the material (5–255 characters).
 * @property string|null $image_path  Filename of the associated image stored under "pictures-materials".
 * @property string|null $type        Optional material type or subclassification.
 * @property Carbon      $created_at  Timestamp of record creation.
 * @property Carbon      $updated_at  Timestamp of last update.
 * @property Carbon|null $deleted_at  Soft-delete timestamp; null if not deleted.
 *
 * @property-read Category|null             $category The category this material belongs to.
 * @property-read Collection<int, Order>    $orders   The orders that include this material.
 */
#[Fillable(['category_id', 'name', 'description', 'image_path', 'type'])]
#[Table('materials')]
class Material extends Model
{
    /** @use HasFactory<MaterialFactory> */
    use HasFactory;
    use SoftDeletes;

    /**
     * Get the category this material belongs to.
     *
     * Many-to-one: each material belongs to exactly one category.
     *
     * @return BelongsTo<Category, Material>
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }


    /**
     * Get the orders that include this material.
     *
     * Many-to-many via the "order_materials" pivot table.
     * The pivot carries a "quantity" column representing how many units
     * of this material were ordered.
     *
     * @return BelongsToMany<Order>
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_materials', 'order_id', 'material_id')->withPivot('quantity');
    }
}
