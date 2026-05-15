<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('findings');
            $table->text('diagnosis')->nullable();
            $table->text('treatment_plan')->nullable();
            $table->json('medications')->nullable();
            $table->boolean('follow_up_needed')->default(false);
            $table->enum('status', ['pending', 'completed', 'archived'])->default('pending');
            $table->json('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reports');
    }
};
