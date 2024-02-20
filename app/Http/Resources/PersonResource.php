<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PersonResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'social_name' => $this->social_name,
            'cpf' => $this->cpf,
            'father_name' => $this->father_name,
            'mother_name' => $this->mother_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'addresses' => AddressResource::collection($this->whenLoaded('addresses')),
        ];
    }
}
