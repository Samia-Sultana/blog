<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solution extends Model
{
    use HasFactory;
    protected $fillable = [
        'seo_case_study_id',
        'name',
        'title',
        'sequence',
        'description',
        'image',
    ];
}
