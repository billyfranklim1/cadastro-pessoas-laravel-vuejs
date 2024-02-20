<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 'name', 'social_name', 'cpf', 'father_name', 'mother_name', 'phone', 'email'

        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('social_name')->nullable();
            $table->string('email')->unique();
            $table->string('cpf')->unique();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
