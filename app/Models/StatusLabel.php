<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusLabel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'text_color',
        'background_color',
    ];
}
