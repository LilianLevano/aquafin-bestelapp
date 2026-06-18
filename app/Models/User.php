<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * User model.
 *
 * Represents an authenticated user of the application.
 * Each user is assigned a {@see Role} that controls access via {@see RoleMiddleware},
 * and optionally a {@see Site} that determines their delivery location and
 * the coordinates used for flood risk calculations.
 * Passwords are stored hashed and hidden from serialization via the Hidden attribute.
 * Supports soft deletion via the SoftDeletes trait.
 *
 * @property int         $id                Primary key.
 * @property int         $role_id           Foreign key referencing the user's {@see Role}.
 * @property int|null    $site_id           Optional foreign key referencing the user's {@see Site}.
 * @property string      $first_name        User's first name (max 40 characters).
 * @property string      $last_name         User's last name (max 40 characters).
 * @property string      $email             User's email address. Must be unique.
 * @property string      $phone_number      User's Belgian phone number. Must be unique.
 * @property string      $password          Hashed password. Hidden from serialization.
 * @property Carbon|null $email_verified_at Timestamp of email verification; null if unverified.
 * @property string|null $remember_token    Token for "remember me" sessions. Hidden from serialization.
 * @property Carbon      $created_at        Timestamp of record creation.
 * @property Carbon      $updated_at        Timestamp of last update.
 * @property Carbon|null $deleted_at        Soft-delete timestamp; null if not deleted.
 *
 * @property-read Role|null                 $role   The role assigned to this user.
 * @property-read Collection<int, Order>    $orders The orders placed by this user.
 * @property-read Site|null                 $site   The site assigned to this user.
 */
#[Fillable(['first_name', 'last_name', 'email', 'password', 'phone_number', 'role_id', 'site_id'])]
#[Hidden(['password', 'remember_token'])]
#[Table('users')]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;
    use SoftDeletes;


    /**
     * Define attribute casting rules.
     *
     * Casts "email_verified_at" to a Carbon datetime instance and
     * automatically hashes "password" on assignment.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the role assigned to this user.
     *
     * Many-to-one: each user has exactly one role (via "role_id").
     *
     * @return BelongsTo<Role, User>
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Get the orders placed by this user.
     *
     * One-to-many: a user can place multiple orders (via "user_id" on the orders table).
     *
     * @return HasMany<Order>
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    /**
     * Get the site assigned to this user.
     *
     * Many-to-one: a user optionally belongs to one site (via "site_id").
     *
     * @return BelongsTo<Site, User>
     */
    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }
}
