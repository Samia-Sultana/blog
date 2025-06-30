<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobVacancy extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'slug',
        'image',
        'job_type',
        'address',
        'salary',
        'deadline',
        'no_of_vacancy',
        'about_company',
        'educations',
        'experiences',
        'employment_statuses',
        'responsibilities',
        'requirements',
        'benefits',
        'is_active',
        'description',
        'address',
        'position_id',
    ];

    public function positionLabel()
    {
        return $this->belongsTo(StatusLabel::class, 'position_id');
    }
}
