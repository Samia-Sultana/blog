<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoServiceInAllCountry extends Model
{
    use HasFactory;
    protected $fillable = [
        'slug',
        'title_1st_section',
        'description_1st_section',
        'image_1st_section',
        'section_1st_is_active',

        'title_2nd_section',
        'description_2nd_section',
        'section_2nd_video_link',
        'section_2nd_is_active',

        'title_3rd_section',
        'description_3rd_section',
        'section_3rd_image',
        'section_3rd_is_active',

        'case_study_1st_image',
        'case_study_2nd_image',
        'case_stydy_section_is_active',

        'is_published',
        'meta_title',
        'meta_description',
        'canonical',
        'index_status',
    ];

}
