<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('doctor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('specialization');
            $table->string('qualification');
            $table->integer('experience_years');
            $table->decimal('consultation_fee', 10, 2);
            $table->text('about');
            $table->string('profile_image')->default('default-doctor.jpg');
            $table->decimal('rating', 3, 2)->default(0);
            $table->unsignedBigInteger('department_id')->nullable();
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            $table->json('availability');
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('doctor_profiles');
    }
};
