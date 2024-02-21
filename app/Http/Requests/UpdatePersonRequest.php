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
        $this->merge([
            'cpf' => preg_replace('/\D/', '', $this->cpf),
            'phone' => preg_replace('/\D/', '', $this->phone),
            'person' => $this->route('person')
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'social_name' => 'sometimes|string|max:255',
            'cpf' => 'sometimes|string|size:11|unique:people,cpf,' . $this->person,
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|size:11',
            'email' => 'sometimes|string|email|max:255|unique:people,email,' . $this->person
        ];
    }
}
