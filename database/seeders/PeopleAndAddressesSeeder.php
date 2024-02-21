<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Person;
use App\Models\Address;

class PeopleAndAddressesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Person::factory(100)->create()->each(function ($person) {
            for ($i = 0; $i < 5; $i++) {
                Address::factory()->create([
                    'person_id' => $person->id,
                    'is_active' => $i == 0
                ]);
            }
        });
    }
}
