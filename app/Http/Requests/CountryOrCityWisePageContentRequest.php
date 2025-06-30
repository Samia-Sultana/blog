<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CountryOrCityWisePageContentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'page_title' => 'nullable|max:255',
            'page_url' => 'nullable|max:255',
            'section_1_content_2' => 'nullable|max:255',
            'section_1_content_3' => 'nullable',
            'section_1_content_4' => 'nullable|max:255',
            'section_1_content_5' => 'nullable|max:255',
            'section_1_content_6' => 'nullable|max:255',
            'section_1_content_7' => 'nullable|max:255',
            'section_2_content_1' => 'nullable|max:255',
            'section_3_content_1' => 'nullable|max:255',
            'section_4_content_1' => 'nullable|max:255',
            'section_5_content_1' => 'nullable|max:255',
            'section_5_content_3' => 'nullable|max:255',
            'section_5_content_4' => 'nullable|max:255',
            'section_6_slider_image' => 'nullable',
            'section_6_slider_heading' => 'nullable',
            'section_6_slider_description' => 'nullable',
            'section_7_content_1' => 'nullable|max:255',
            'section_7_content_2' => 'nullable|max:255',
            'section_7_content_3' => 'nullable|max:255',
            'section_7_content_4' => 'nullable|max:255',
            'section_7_content_5' => 'nullable|max:255',
            'section_7_content_6' => 'nullable|max:255',
            'section_7_content_7' => 'nullable|max:255',
            'section_7_content_8' => 'nullable|max:255',
            'section_8_content_2' => 'nullable|max:255',
            'section_9_content_2' => 'nullable|max:255',
            'section_10_content_1' => 'nullable|max:255',

            'index' => 'nullable',
            'meta' => 'nullable',
            'link' => 'nullable',
            'script' => 'nullable',

            'section_5_content_2' => 'nullable|max:255',
            'section_5_content_3' => 'nullable|max:255',
            'section_5_content_4' => 'nullable|max:255',
            'section_5_content_5' => 'nullable|max:255',
            'section_5_content_6' => 'nullable|max:255',
            'section_5_content_7' => 'nullable|max:255',

            'section_11_content_1_faq' => 'nullable|max:255',
            'section_11_content_2_faq' => 'nullable',
        ];
    }
}
