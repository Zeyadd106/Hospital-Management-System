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
        Schema::create('doctors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('specialty')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('department')->nullable();
            $table->string('specialization');
            $table->text('bio')->nullable();
            $table->string('working_hours')->nullable();
            $table->string('avatar')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->boolean('is_available')->default(true);
            $table->unsignedBigInteger('department_id')->nullable()->index('doctors_department_id_foreign');
            $table->unsignedBigInteger('clinic_id')->nullable()->index('doctors_clinic_id_foreign');
            $table->timestamps();
            $table->string('qualification')->nullable();
            $table->integer('experience')->nullable();
            $table->decimal('consultation_fee')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctors');
    }
};
