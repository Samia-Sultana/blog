<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoCaseStudy extends Model
{
    use HasFactory;
    protected $fillable = [
        'case_study_id',
        'header_title',
        'seo_conversion',
        'seo_incress',
        'revenue',
        'header_description',
        'featured_image',

        'sec_1_title',
        'sec_1_desc',
        'sec_1_image',

        'sec_2_title',
        'sec_2_desc',
        'sec_2_image',

        'sec_3_title',
        'sec_3_desc',
        'sec_3_image',

        'sec_result_title',
        'sec_result_desc',

        'sec_ranking_title',
        'sec_ranking_desc',
        'sec_ranking_image',

        'index_status',
        'meta_title',
        'meta_description',
        
    ];

    public function solution()
    {
        return $this->hasMany(Solution::class,  'seo_case_study_id', 'id');
    }

    public function slider()
    {
        return $this->hasMany(SliderImages::class,  'seo_case_study_id', 'id');
    }
}
