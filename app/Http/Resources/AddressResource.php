<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'person_id' => $this->person_id,
            'type' => $this->type,
            'zip_code' => $this->zip_code,
            'street' => $this->street,
            'number' => $this->number,
            'complement' => $this->complement,
            'neighborhood' => $this->neighborhood,
            'state' => $this->state,
            'city' => $this->city,
            'is_active' => $this->is_active,
            'updated_at' => $this->updated_at,
        ];
    }
}
