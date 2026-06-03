<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aanvraag extends Model
{
    use HasFactory;
    protected $table = 'aanvraagen';
   protected $fillable = [
       'posted_by',
       'title',
       'description',
       'posted_on',
       'is_completed',
   ];

   public function user(){
       return $this->belongsTo(User::class, 'posted_by'); // M:1 Many to One relation
   }
}
