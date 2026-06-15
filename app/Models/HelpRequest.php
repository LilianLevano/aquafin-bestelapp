<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['user_id', 'title', 'description', 'answer', 'is_completed'])]
#[Table('help_requests')]
class HelpRequest extends Model
{
    /** @use HasFactory<\Database\Factories\HelpRequestFactory> */
    use HasFactory;
    use SoftDeletes;

    /**
     * Get the user that made the help request.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
