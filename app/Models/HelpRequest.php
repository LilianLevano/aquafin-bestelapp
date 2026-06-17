<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'title', 'description', 'answer', 'is_completed', 'first_name', 'last_name', 'email', 'posted_on'])]
#[Table('help_requests')]
class HelpRequest extends Model
{
    /** @use HasFactory<\Database\Factories\HelpRequestFactory> */
    use HasFactory;
}
