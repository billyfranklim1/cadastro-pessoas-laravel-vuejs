<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpdatePersonRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {
        $this->merge(['person' => $this->route('person')]);
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'social_name' => 'sometimes|string|max:255',
            'cpf' => 'sometimes|string|size:11|unique:people,cpf,' . $this->person,
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:15',
            'email' => 'sometimes|string|email|max:255|unique:people,email,' . $this->person
        ];
    }
}
