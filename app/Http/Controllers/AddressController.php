<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAddressRequest;
use App\Http\Resources\AddressResource;
use App\Services\AddressService;

class AddressController extends Controller
{
    protected $addressService;

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    public function store(StoreAddressRequest $request, $personId)
    {
        $address = $this->addressService->addAddressToPerson($personId, $request->validated());
        return new AddressResource($address);
    }

    public function history($personId)
    {
        $addresses = $this->addressService->getAddressHistoryForPerson($personId);
        return AddressResource::collection($addresses);
    }

    public function activate($personId)
    {
        $address = $this->addressService->getActiveAddress($personId);
        return new AddressResource($address);
    }

}
