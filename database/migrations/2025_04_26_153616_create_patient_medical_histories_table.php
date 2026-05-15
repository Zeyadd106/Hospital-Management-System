<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_medical_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('patient_medical_histories_user_id_foreign');
            $table->text('medical_conditions')->nullable();
            $table->text('allergies')->nullable();
            $table->text('current_medications')->nullable();
            $table->text('family_medical_history')->nullable();
            $table->text('previous_surgeries')->nullable();
            $table->enum('blood_type', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])->nullable();
            $table->date('last_physical_exam_date')->nullable();
            $table->unsignedInteger('health_screening_bookings_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_medical_histories');
    }
};
