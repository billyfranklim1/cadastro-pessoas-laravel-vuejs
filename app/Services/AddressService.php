<?php

namespace App\Services;

use App\Repositories\Contracts\AddressRepositoryInterface;
use App\Repositories\Contracts\PersonRepositoryInterface;
use Exception;

class AddressService
{
    protected $addressRepository;
    protected $personRepository;

    public function __construct(
        AddressRepositoryInterface $addressRepository,
        PersonRepositoryInterface $personRepository
    )
    {
        $this->addressRepository = $addressRepository;
        $this->personRepository = $personRepository;
    }

    public function getActiveAddress($personId)
    {
        return $this->addressRepository->findActiveAddress($personId);
    }

    public function addAddressToPerson($personId, array $data)
    {
        $person = $this->personRepository->findById($personId);

        if (!$person) {
            throw new Exception("Person not found", 404);
        }
        $this->addressRepository->desactivateAllAddresses($personId);

        $data['person_id'] = $personId;
        return $this->addressRepository->create($data);
    }

    public function getAddressHistoryForPerson($personId)
    {
        $person = $this->personRepository->findById($personId);

        if (!$person) {
            throw new Exception("Person not found", 404);
        }

        return $this->addressRepository->findAddressesByPersonId($personId);
    }

}
