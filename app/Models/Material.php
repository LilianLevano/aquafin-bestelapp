<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['category_id', 'name'])]
#[Table('materials')]
class Material extends Model
{
    /** @use HasFactory<\Database\Factories\MaterialFactory> */
    use HasFactory;

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
