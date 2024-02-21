<?php

namespace Tests\Feature;

use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Address;

class AddressTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_add_address_to_person()
    {
        $person = Person::factory()->create();

        $addressData = [
            'type' => 'residential',
            'zip_code' => '123456',
            'street' => 'Main St',
            'number' => '123',
            'complement' => 'Apt 1',
            'neighborhood' => 'Central',
            'state' => 'SP',
            'city' => 'São Paulo'
        ];

        $response = $this->postJson("/api/people/{$person->id}/addresses", $addressData);

        $response->assertStatus(201);
        $response->assertJsonFragment($addressData);
        $this->assertDatabaseHas('addresses', $addressData + ['person_id' => $person->id]);
    }

    public function test_can_retrieve_address_history_for_person()
    {
        $person = Person::factory()->create();
        Address::factory()->count(5)->create(['person_id' => $person->id]);

        $response = $this->getJson("/api/people/{$person->id}/address-history");

        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'type',
                    'zip_code',
                    'street',
                    'number',
                    'complement',
                    'neighborhood',
                    'state',
                    'city',
                    'is_active',
                ]
            ]
        ]);
    }

    public function test_can_retrieve_current_address_for_person()
    {
        $person = Person::factory()->create();

        Address::factory()->create(['person_id' => $person->id, 'is_active' => false]);
        $activeAddress = Address::factory()->create(['person_id' => $person->id, 'is_active' => true]);

        $response = $this->getJson("/api/people/{$person->id}/active-address");

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $activeAddress->id,
            'type' => $activeAddress->type,
            'zip_code' => $activeAddress->zip_code,
            'street' => $activeAddress->street,
            'number' => $activeAddress->number,
            'complement' => $activeAddress->complement,
            'neighborhood' => $activeAddress->neighborhood,
            'state' => $activeAddress->state,
            'city' => $activeAddress->city,
        ]);
    }
}
