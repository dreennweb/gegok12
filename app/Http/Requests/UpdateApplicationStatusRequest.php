<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateApplicationStatusRequest extends FormRequest
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
            'status' => ['required', 'in:pending,in_review,queried,approved,rejected'],
            'certificate_number' => ['nullable', 'string', 'required_if:status,approved'],
            'rejection_reason' => ['nullable', 'string', 'required_if:status,rejected', 'max:1000'],
            'query_notes' => ['nullable', 'string', 'required_if:status,queried', 'max:1000'],
            'query_documents' => ['nullable', 'array'],
            'query_documents.*' => ['string'],
        ];
    }
}
