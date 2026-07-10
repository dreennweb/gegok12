<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadDocumentRequest extends FormRequest
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
            'document' => ['required', 'file', 'max:10240', 'mimes:pdf,doc,docx,xlsx,jpg,jpeg,png'],
            'document_type' => [
                'required',
                'in:cnic,ntn,business_registration,tax_certificate,partnership_deed,board_resolution,utility_bill,bank_statement,other'
            ],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'document.required' => 'Please select a file to upload',
            'document.max' => 'File size cannot exceed 10MB',
            'document_type.required' => 'Document type must be specified',
        ];
    }
}
