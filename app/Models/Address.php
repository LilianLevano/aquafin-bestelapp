<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['type', 'street', 'house_number', 'city', 'postal_code', 'country_iso', 'unit_number'])]
#[Table('addresses')]
class Address extends Model
{
    /** @use HasFactory<\Database\Factories\AddressFactory> */
    use HasFactory;
    use SoftDeletes;

    /**
     * Get the site for the address.
     */
    public function site()
    {
        return $this->hasOne(Site::class, 'address_id');
    }
}
