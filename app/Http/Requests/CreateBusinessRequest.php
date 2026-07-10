<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBusinessRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'legal_name' => ['required', 'string', 'max:255'],
            'business_type' => ['required', 'in:company,aop,sole_proprietorship'],
            'ntn' => ['nullable', 'string', 'unique:businesses,ntn'],
            'cnic' => ['nullable', 'string'],
            'registration_number' => ['nullable', 'string'],
            'business_description' => ['nullable', 'string', 'max:1000'],
            'business_nature' => ['required', 'array', 'min:1'],
            'business_nature.*' => ['string'],
            'selected_departments' => ['required', 'array', 'min:1'],
            'selected_departments.*' => ['integer', 'exists:departments,id'],
            'has_workers' => ['required', 'boolean'],
            'worker_count' => ['required_if:has_workers,true', 'nullable', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'legal_name.required' => 'Business legal name is required',
            'business_type.required' => 'Business type must be selected',
            'business_nature.required' => 'At least one business nature must be selected',
            'selected_departments.required' => 'At least one department must be selected',
            'ntn.unique' => 'This NTN is already registered',
        ];
    }
}
