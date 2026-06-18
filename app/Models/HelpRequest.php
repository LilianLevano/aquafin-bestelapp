<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\HelpRequestFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * HelpRequest model.
 *
 * Represents a support request submitted by an unauthenticated visitor,
 * typically when they are unable to log in. An admin can answer the request
 * via the admin panel, which sets the "answer" field and marks "is_completed" as 1.
 * Supports soft deletion via the SoftDeletes trait.
 *
 * @property int         $id           Primary key.
 * @property string      $first_name   Submitter's first name.
 * @property string      $last_name    Submitter's last name.
 * @property string      $email        Submitter's email address.
 * @property string      $title        Short title describing the issue (max 50 characters).
 * @property string      $description  Detailed description of the issue (max 400 characters).
 * @property string|null $answer       Admin's response to the request; null until answered.
 * @property int|null    $is_completed Whether the request has been answered (0 = open, 1 = completed).
 * @property string|null $posted_on    Optional date the request was submitted.
 * @property Carbon      $created_at   Timestamp of record creation.
 * @property Carbon      $updated_at   Timestamp of last update.
 * @property Carbon|null $deleted_at   Soft-delete timestamp; null if not deleted.
 */
#[Fillable(['user_id', 'title', 'description', 'answer', 'is_completed', 'first_name', 'last_name', 'email', 'posted_on'])]
#[Table('help_requests')]
class HelpRequest extends Model
{
    /** @use HasFactory<HelpRequestFactory> */
    use HasFactory;
    use SoftDeletes;
}
