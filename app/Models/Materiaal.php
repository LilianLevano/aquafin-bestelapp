<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materiaal extends Model
{
    /** @use HasFactory<\Database\Factories\MateriaalFactory> */
    use HasFactory;
    protected $table = 'materialen';
    protected $fillable = [
        'name',
        'categorie_id',
    ];

    public function category(){
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function bestelling(){
        return $this->belongsToMany(Bestelling::class, 'bestelling-materiaal', 'bestelling_id', 'materialen_id');
    }
}
