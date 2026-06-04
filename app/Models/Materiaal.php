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
}
