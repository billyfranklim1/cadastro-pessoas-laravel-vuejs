<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Person extends Model
{
    use HasFactory;

    protected $table = 'people';

    protected $fillable = [
        'name', 'social_name', 'cpf', 'father_name', 'mother_name', 'phone', 'email'
    ];

    /**
     * Relacionamento um-para-muitos com Address.
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
}
