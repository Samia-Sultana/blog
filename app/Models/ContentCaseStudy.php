<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentCaseStudy extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_study_id',
        'header_title',
        'client',
        'completed_on',
        'po_title',
        'po_desc',
        'challenge_title',
        'challenge_desc',
        'helped_title',
        'helped_desc',
        'problem_title',
        'problem_desc',
        'challenge_2_title',
        'challenge_2_desc',
        'result_title',
        'result_desc',
        'index_status',
        'meta_title',
        'meta_description',
        
        'featured_image',
        'problem_image',
        'challenge_2_image',
        'result_image',
    ];


}
