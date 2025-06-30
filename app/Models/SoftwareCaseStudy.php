<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoftwareCaseStudy extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_study_id',
        'header_title',
        'client',
        'completed_on',
        'featured_image_alt',
        'po_title',
        'po_desc',
        'challenge_title',
        'challenge_desc',
        'problem_title',
        'problem_desc',
        'middle_1st_image_alt',
        'middle_2nd_image_alt',
        'middle_3rd_image_alt',
        'middle_4th_image_alt',
        'workflow_title',
        'workflow_desc',
        'solution_title',
        'solution_desc',
        'bottom_1st_image_alt',
        'bottom_2nd_image_alt',
        'bottom_3rd_image_alt',
        'bottom_4th_image_alt',
        'bottom_5th_image_alt',
        'bottom_6th_image_alt',
        'conclusion_title',
        'conclusion_desc',
        'index_status',
        'meta_title',
        'meta_description',
        'featured_image', 
        'middle_1st_image',
        'middle_2nd_image',
        'middle_3rd_image',
        'middle_4th_image',
        'bottom_1st_image',
        'bottom_2nd_image',
        'bottom_3rd_image',
        'bottom_4th_image',
        'bottom_5th_image',
        'bottom_6th_image',
    ];

}
