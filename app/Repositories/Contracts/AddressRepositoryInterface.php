<?php

namespace App\Repositories\Contracts;

interface AddressRepositoryInterface
{
    public function create(array $data);
    public function findAddressesByPersonId($personId);
    public function activateAddress($addressId, $personId);
    public function findActiveAddress($personId);
    public function findById($id);
    public function desactivateAllAddresses($personId);
}
