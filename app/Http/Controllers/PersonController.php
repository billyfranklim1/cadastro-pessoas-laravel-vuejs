<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersonRequest;
use App\Http\Requests\UpdatePersonRequest;
use App\Http\Requests\PersonRequest;
use App\Http\Resources\PersonResource;
use App\Services\PersonService;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    protected $personService;

    public function __construct(PersonService $personService)
    {
        $this->personService = $personService;
    }

    public function index(Request $request)
    {
        $people = $this->personService->getAllPeople($request->all());
        return PersonResource::collection($people);
    }

    public function store(StorePersonRequest $request)
    {
        $person = $this->personService->createPerson($request->validated());
        return new PersonResource($person);
    }

    public function show($id)
    {
        $person = $this->personService->getPersonById($id);
        if (!$person) {
            return response()->json(['message' => 'Person not found'], 404);
        }
        return new PersonResource($person);
    }


    public function update(UpdatePersonRequest $request, $id)
    {
        $person = $this->personService->updatePerson($id, $request->validated());
        return new PersonResource($person);
    }

    public function destroy($id)
    {
        $person = $this->personService->getPersonById($id);
        if (!$person) {
            return response()->json(['message' => 'Person not found'], 404);
        }
        $this->personService->deletePerson($id);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
