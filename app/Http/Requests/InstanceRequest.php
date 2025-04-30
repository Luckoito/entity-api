<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InstanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required_if:method,[PUT,DELETE]|integer',
            'entity' => 'required_if:method,POST|string|exists:entities,name',
            'properties' => 'required|array',
            'properties.*.name' => 'required|string|exists:properties,name',
            'properties.*.value' => 'required',
        ];
    }
}
