<?php

namespace App\Repositories\Eloquent;

use App\Models\Person;
use App\Repositories\Contracts\PersonRepositoryInterface;

class PersonRepository implements PersonRepositoryInterface
{
    public function getAll(array $filters = [])
    {
        $query = Person::query();
        if (isset($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
            $query->orWhere('email', 'like', '%' . $filters['search'] . '%');
            $query->orWhere('cpf', 'like', '%' . $filters['search'] . '%');
        }
        return $query->orderBy('id', 'desc')->paginate($filters['itemsPerPage'] ?? 10);
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
