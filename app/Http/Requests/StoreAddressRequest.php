<?php

namespace App\Http\Requests;


class StoreAddressRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {
        $this->merge(['person_id' => $this->route('person')]);
    }

    public function rules(): array
    {
        return [
            'person_id' => 'required|exists:people,id',
            'type' => 'required|in:residential,commercial',
            'zipcode' => 'required|string|max:10',
            'street' => 'required|string|max:255',
            'number' => 'required|string|max:10',
            'complement' => 'nullable|string|max:255',
            'neighborhood' => 'required|string|max:255',
            'state' => 'required|string|max:2',
            'city' => 'required|string|max:255'
        ];
    }
}
