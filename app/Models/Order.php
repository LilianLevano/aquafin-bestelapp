<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'site_id', 'delivery_date'])]
#[Table('orders')]
class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    /**
     * Get the user that made the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the materials that're assigned with the order.
     */
    public function materials()
    {
        return $this->belongsToMany(Material::class, 'order_materials', 'order_id', 'material_id')->withPivot('quantity');
    }

    /**
     * Get the site that's assigned with the order.
     */
    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }
}
