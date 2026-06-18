<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Category model.
 *
 * Represents a category used to group multiple materials.
 * Supports soft deletion via the SoftDeletes trait.
 *
 * @property int    $id   Primary key.
 * @property string $name Category name. Must be unique.
 *
 * @property-read Collection<int, Material> $materials The materials belonging to this category.
 */
#[Fillable(['name'])]
#[Table('categories')]
class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;
    use SoftDeletes;

    /**
     * Get the materials belonging to this category.
     *
     * One-to-many: a category can have multiple materials (via "category_id" on the materials table).
     *
     * @return HasMany<Material>
     */
    public function materials()
    {
        return $this->hasMany(Material::class, 'category_id');
    }
}
