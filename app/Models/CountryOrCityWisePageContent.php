<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryOrCityWisePageContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_title',
        'page_url',

        'section_1_content_1',
        'section_1_content_2',
        'section_1_content_3',
        'section_1_content_4',
        'section_1_content_5',
        'section_1_content_6',
        'section_1_content_7',

        'section_2_content_1',

        'section_3_content_1',
        'section_3_content_2',

        'section_4_content_1',

        'section_5_content_1',
        'section_5_content_2',
        'section_5_content_3',
        'section_5_content_4',

        'section_6_case_studies',

        'section_7_content_1',
        'section_7_content_2',
        'section_7_content_3',
        'section_7_content_4',
        'section_7_content_5',
        'section_7_content_6',
        'section_7_content_7',
        'section_7_content_8',

        'section_8_content_1',
        'section_8_content_2',
        'section_8_content_3',

        'section_9_content_1',
        'section_9_content_2',
        'section_9_content_3',

        'section_10_content_1',

        'index',
        'meta',
        'link',
        'script',

        'locations',

        'section_4_content_2',
        'section_4_content_3',
        'section_4_content_4',
        'section_4_content_5',
        'section_4_content_6',
        'section_4_content_7',

        'ai_model',

        'section_11_content_1_faq',
        'section_11_content_2_faq',

        'is_published',
        'status',

        'published_date_time_by_job',
        'published_date_time',
        'created_by',
        'updated_by',
        'index_date_time',
        'index_published_latest_date_time',

    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
