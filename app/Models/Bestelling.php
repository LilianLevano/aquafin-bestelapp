<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bestelling extends Model
{
    /** @use HasFactory<\Database\Factories\BestellingFactory> */
    use HasFactory;
    protected $fillable = ['user_id', 'delivery_date', 'site_id'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function materiaal(){
        return $this->belongsToMany(Materiaal::class, 'bestelling-materiaal', 'bestelling_id', 'materiaal_id' )->withPivot('quantity');
    }

    public function site(){
        return $this->belongsTo(Site::class, 'site_id', 'id');
    }
}
