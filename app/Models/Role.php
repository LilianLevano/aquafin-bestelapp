<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /** @use HasFactory<\Database\Factories\RoleFactory> */
    use HasFactory;

    public const ADMIN = 'Admin';
    public const TECHNIEKER = 'Technieker';
    public const MANAGER = 'Manager';
    public const MAGAZIJNIER = 'Magazijnier';

    protected $fillable = ['name'];

    public function users(){
        return $this->hasMany(User::class, 'role_id', 'id'); // 1:M One to Many relation
    }

}
