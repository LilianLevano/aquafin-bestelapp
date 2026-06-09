<?php

namespace App\Models;

use Database\Factories\AanvraagFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aanvraag extends Model
{
    /** @use HasFactory<AanvraagFactory> */
    use HasFactory;

    protected $table = 'aanvraagen';

    protected $fillable = [
        'name',
        'email',
        'title',
        'description',
        'posted_on',
        'is_completed'
    ];
}
