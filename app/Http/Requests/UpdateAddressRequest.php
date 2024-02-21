<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpdateAddressRequest extends BaseRequest
{
    public function authorize()
    {
        return false;
    }

    public function rules(): array
    {
        return [
            'type' => 'sometimes|in:residential,commercial',
            'zip_code' => 'sometimes|string|max:10',
            'street' => 'sometimes|string|max:255',
            'number' => 'sometimes|string|max:10',
            'complement' => 'nullable|string|max:255',
            'neighborhood' => 'sometimes|string|max:255',
            'state' => 'sometimes|string|max:2',
            'city' => 'sometimes|string|max:255'
        ];
    }
}
