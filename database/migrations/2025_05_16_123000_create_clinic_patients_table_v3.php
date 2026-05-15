<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClinicPatientsTableV3 extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('clinic_patients')) {
            Schema::create('clinic_patients', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('doctor_id');
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('clinic_id');
                $table->date('registration_date');
                $table->enum('status', ['active', 'inactive', 'pending'])->default('active');
                $table->timestamps();

                $table->foreign('doctor_id')->references('id')->on('users');
                $table->foreign('user_id')->references('id')->on('users');
                $table->foreign('clinic_id')->references('id')->on('clinics');

                $table->unique(['doctor_id', 'user_id', 'clinic_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('clinic_patients')) {
            Schema::dropIfExists('clinic_patients');
        }
    }
}
