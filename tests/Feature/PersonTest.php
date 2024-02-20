<?php

namespace Tests\Feature;

use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_person()
    {
        $personData = Person::factory()->make()->toArray();

        $response = $this->postJson('/api/people', $personData);

        $response->assertStatus(201);
        $response->assertJsonFragment($personData);
    }

    public function test_can_retrieve_person()
    {
        $person = Person::factory()->create();

        $response = $this->getJson("/api/people/{$person->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => $person->name,
            'email' => $person->email,
        ]);
    }

    public function test_can_update_person()
    {
        $person = Person::factory()->create();

        $updateData = ['name' => 'Updated Name', 'email' => 'updated@example.com'];
        $response = $this->putJson("/api/people/{$person->id}", $updateData);

        $response->assertStatus(200);
        $response->assertJsonFragment($updateData);
    }

    public function test_can_delete_person()
    {
        $person = Person::factory()->create();

        $response = $this->deleteJson("/api/people/{$person->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('people', ['id' => $person->id]);
    }

    public function test_can_list_people()
    {
        Person::factory()->count(10)->create();

        $response = $this->getJson('/api/people');

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
    }

    public function test_validation_errors_when_creating_person()
    {
        $response = $this->postJson('/api/people', []);

        $response->assertStatus(422);

        $errors = $response->json('errors');

        $this->assertEquals('The name field is required.', $errors['name'][0]);
        $this->assertEquals('The cpf field is required.', $errors['cpf'][0]);
        $this->assertEquals('The email field is required.', $errors['email'][0]);
    }


    public function test_cpf_must_be_unique_when_creating_person()
    {
        $existingPerson = Person::factory()->create(['cpf' => '12345678901']);

        $response = $this->postJson('/api/people', [
            'name' => 'Another Name',
            'cpf' => '12345678901',
            'email' => 'another@example.com',
        ]);

        $response->assertStatus(422);
        $errors = $response->json('errors');

        $this->assertEquals('The cpf has already been taken.', $errors['cpf'][0]);
    }

    public function test_email_must_be_a_string_when_creating_person()
    {
        $response = $this->postJson('/api/people', [
            'name' => 'New Person',
            'cpf' => '12345678901',
            'email' => 123,
        ]);

        $response->assertStatus(422);
        $errors = $response->json('errors');

        $this->assertEquals('The email field must be a string.', $errors['email'][0]);
        $this->assertEquals('The email field must be a valid email address.', $errors['email'][1]);
    }

    public function test_email_must_not_exceed_maximum_length_when_creating_person()
    {
        $response = $this->postJson('/api/people', [
            'name' => 'New Person',
            'cpf' => '12345678901',
            'email' => str_repeat('a', 246) . '@example.com',
        ]);

        $response->assertStatus(422);
        $errors = $response->json('errors');

        $this->assertEquals('The email field must not be greater than 255 characters.', $errors['email'][0]);
    }

    public function test_email_must_be_unique_when_creating_person()
    {
        $existingPerson = Person::factory()->create(['email' => 'existing@example.com']);

        $response = $this->postJson('/api/people', [
            'name' => 'New Person',
            'cpf' => '12345678901',
            'email' => 'existing@example.com',
        ]);

        $response->assertStatus(422);
        $errors = $response->json('errors');

        $this->assertEquals('The email has already been taken.', $errors['email'][0]);
    }

}
