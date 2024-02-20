<?php

namespace App\Repositories\Eloquent;

use App\Models\Person;
use App\Repositories\Contracts\PersonRepositoryInterface;

class PersonRepository implements PersonRepositoryInterface
{
    public function getAll()
    {
        return Person::all();
    }

    public function findById($id)
    {
        return Person::find($id);
    }

    public function create(array $data)
    {
        return Person::create($data);
    }

    public function update($id, array $data)
    {
        $person = Person::find($id);
        $person->update($data);
        return $person;
    }

    public function delete($id)
    {
        $person = Person::findOrFail($id);
        return $person->delete();
    }
}
