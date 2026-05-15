<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->enum('gender', ['male', 'female', 'other']);
            $table->integer('age');
            $table->text('address')->nullable();
            $table->json('medical_history')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->string('emergency_phone')->nullable();
            $table->string('blood_type')->nullable();
            $table->json('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('patients');
    }
};
