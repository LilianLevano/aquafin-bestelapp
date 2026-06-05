<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aanvraag extends Model
{
    protected $table = 'aanvraagen';
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'title',
        'description',
        'answer',
        'posted_on',
        'is_completed'
    ];
}
