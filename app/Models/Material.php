<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['category_id', 'name', 'description', 'image_path', 'type'])]
#[Table('materials')]
class Material extends Model
{
    /** @use HasFactory<\Database\Factories\MaterialFactory> */
    use HasFactory;
    use SoftDeletes;

    /**
     * Get the category for the material.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get the orders for the material.
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_materials', 'order_id', 'material_id')->withPivot('quantity');
    }
}
