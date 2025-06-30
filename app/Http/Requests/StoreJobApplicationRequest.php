<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobApplicationRequest extends FormRequest
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
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|max:255',
            'contact'          => 'required|string|max:20',
            'location'         => 'required|string|max:255',
            'position_id'      => 'required|exists:status_labels,id',
            'expected_salary'  => 'required|string|max:255',
            'experience'       => 'required|string|max:255',
            'cv_file'          => 'required|file|mimes:pdf,doc,docx|max:2048',
            'label_status_id'  => 'nullable|exists:status_labels,id',
        ];
    }

     /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Handle CV file upload and add to validated data
        if ($this->hasFile('cv_file')) {
            $file = $this->file('cv_file');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/cv_files', $filename);
            $this->merge([
                'cv_file' => 'storage/cv_files/' . $filename
            ]);
        }
    }
}
