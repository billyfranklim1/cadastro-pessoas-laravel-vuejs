<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id', 'type', 'zipcode', 'street', 'number', 'complement', 'neighborhood', 'state', 'city', 'is_active'
    ];

    /**
     * Relacionamento muitos-para-um com Person.
     */
    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
