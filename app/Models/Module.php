<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', // Add other fields that are mass-assignable
    ];


    public function userRoles()
    {
        return $this->belongsToMany(UserRole::class, 'user_role_module');
    }
}
