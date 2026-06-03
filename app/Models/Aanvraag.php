<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aanvraag extends Model
{
    use HasFactory;
    protected $table = 'aanvraagen';
   protected $fillable = [
       'name',
       'email',
       'title',
       'description',
       'posted_on',
       'is_completed',
   ];


}
