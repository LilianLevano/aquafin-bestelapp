<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['address_id, description, longitude, latitude'])]
#[Table('sites')]
class Site extends Model
{
    /** @use HasFactory<\Database\Factories\SiteFactory> */
    use HasFactory;
    use SoftDeletes;

    /**
     * Get the orders that're assigned to the site.
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'site_id');
    }

    /**
     * Get the users that're assigned to the site.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'user_id');
    }

    /**
     * Get the address that's assigned to the site.
     */
    public function address()
    {
        return $this->hasOne(Address::class, 'id');
    }
}
