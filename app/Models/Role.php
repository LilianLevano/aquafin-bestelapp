<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * Role model.
 *
 * Represents a user role used to control access throughout the application
 * (e.g. "Admin", "Manager", "Technieker").
 * Supports soft deletion via the SoftDeletes trait.
 *
 * @property int    $id   Primary key.
 * @property string $name Role name. Must be unique.
 *
 * @property-read Collection<int, User> $users The users assigned to this role.
 */
#[Fillable(['name'])]
#[Table('roles')]
class Role extends Model
{
    /** @use HasFactory<\Database\Factories\RoleFactory> */
    use HasFactory;
    use SoftDeletes;

    /**
     * Get the users assigned to this role.
     *
     * One-to-many: a role can be assigned to multiple users (via "role_id" on the users table).
     *
     * @return HasMany<User>
     */
    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}
