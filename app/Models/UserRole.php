<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', // Add other fields that are mass-assignable
    ];


    public function modules()
    {
        return $this->belongsToMany(Module::class, 'user_role_module');
    }
}
