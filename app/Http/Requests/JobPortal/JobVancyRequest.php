<?php

namespace App\Http\Requests\JobPortal;

use Illuminate\Foundation\Http\FormRequest;

class JobVancyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
  

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|string',
            'slug' => 'required|string|unique:jobs,slug',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // adjust max file size as needed
            'job_type' => 'required|string',
            'address' => 'required|string',
            'salary' => 'nullable|string',
            'deadline' => 'required|date',
            'no_of_vacancy' => 'required|integer|min:1',
            'about_company' => 'required|string',
            'educations' => 'required|string',
            'experiences' => 'required|string',
            'employment_statuses.*' => 'nullable|array',
            'responsibilities.*' => 'nullable|string',
            'requirements.*' => 'nullable|string',
            'benefits.*' => 'nullable|string',
        ];
    }
}
