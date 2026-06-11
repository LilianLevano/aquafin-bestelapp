<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name'])]
#[Table('roles')]
class Role extends Model
{
    /** @use HasFactory<\Database\Factories\RoleFactory> */
    use HasFactory;

    public const ADMIN = 'Admin';
    public const TECHNIEKER = 'Technieker';
    public const MANAGER = 'Manager';
    public const MAGAZIJNIER = 'Magazijnier';

    /**
     * Get the users that have the role.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}
