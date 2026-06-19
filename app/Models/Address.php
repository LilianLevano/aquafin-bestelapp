<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Address model.
 *
 * Represents a physical address that can be attached to a {@see Site}.
 * Supports soft deletion via the SoftDeletes trait.
 *
 * @property int         $id           Primary key.
 * @property string|null $type         Optional address type (e.g. "billing", "delivery").
 * @property string      $street       Street name.
 * @property int      $house_number House or building number.
 * @property string      $city         City name.
 * @property string      $postal_code  Postal code.
 * @property string|null $country_iso  Optional ISO country code (e.g. "BE", "NL").
 * @property string|null $unit_number  Optional unit or apartment number.
 *
 * @property-read Site|null $site The site associated with this address.
 */
#[Fillable(['type', 'street', 'house_number', 'city', 'postal_code', 'country_iso', 'unit_number'])]
#[Table('addresses')]
class Address extends Model
{
    /** @use HasFactory<\Database\Factories\AddressFactory> */
    use HasFactory;
    use SoftDeletes;

    /**
     * Get the site associated with this address.
     *
     * One-to-one: an address belongs to at most one site (via "address_id" on the sites table).
     *
     * @return HasOne<Site>
     */
    public function site()
    {
        return $this->hasOne(Site::class, 'address_id');
    }
}
