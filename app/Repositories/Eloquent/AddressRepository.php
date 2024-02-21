<?php

namespace App\Repositories\Eloquent;

use App\Models\Address;
use App\Repositories\Contracts\AddressRepositoryInterface;

class AddressRepository implements AddressRepositoryInterface
{
    public function create(array $data)
    {
        return Address::create($data);
    }

    public function findAddressesByPersonId($personId)
    {
        return Address::where('person_id', $personId)->orderBy('id', 'desc')->get();
    }

    public function activateAddress($addressId, $personId)
    {
        Address::where('person_id', $personId)->update(['is_active' => false]);

        $address = $this->findById($addressId);
        $address->is_active = true;
        $address->save();

        return $address;
    }

    public function findActiveAddress($personId)
    {
        return Address::where('person_id', $personId)->where('is_active', true)->first();
    }

    public function findById($id)
    {
        return Address::findOrFail($id);
    }

    public function desactivateAllAddresses($personId)
    {
        return Address::where('person_id', $personId)->update(['is_active' => false]);
    }

}
