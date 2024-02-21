<?php

namespace App\Services;

use App\Repositories\Contracts\PersonRepositoryInterface;

class PersonService
{
    protected $personRepository;

    public function __construct(PersonRepositoryInterface $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    public function getAllPeople(array $filters = [])
    {
        return $this->personRepository->getAll($filters);
    }

    public function createPerson(array $data)
    {
        return $this->personRepository->create($data);
    }

    public function getPersonById($id)
    {
        return $this->personRepository->findById($id);
    }

    public function updatePerson($id, array $data)
    {
        return $this->personRepository->update($id, $data);
    }

    public function deletePerson($id)
    {
        return $this->personRepository->delete($id);
    }
}
