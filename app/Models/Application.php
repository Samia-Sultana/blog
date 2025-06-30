<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'contact',
        'location',
        'position_id',
        'expected_salary',
        'experience',
        'cv_file',
        'label_status_id',
        'applying_position',
        'application_type',
        'department_id'
    ];

    public function positionLabel()
    {
        return $this->belongsTo(StatusLabel::class, 'position_id');
    }

    public function statusLabel()
    {
        return $this->belongsTo(StatusLabel::class, 'label_status_id');
    }

    public function deparmentLabel()
    {
        return $this->belongsTo(StatusLabel::class, 'department_id');
    }
}
