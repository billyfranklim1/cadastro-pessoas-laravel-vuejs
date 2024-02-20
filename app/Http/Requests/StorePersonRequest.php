<?php
namespace App\Http\Requests;

class StorePersonRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'social_name' => 'sometimes|string|max:255',
            'cpf' => 'required|string|size:11|unique:people',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:11',
            'email' => 'required|string|email|max:255|unique:people'
        ];
    }

}

