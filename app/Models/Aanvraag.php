<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aanvraag extends Model
{
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