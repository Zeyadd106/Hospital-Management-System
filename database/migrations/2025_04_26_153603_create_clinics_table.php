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
        Schema::create('clinics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('address')->nullable();
            $table->text('description')->nullable();
            $table->string('location');
            $table->string('phone');
            $table->string('status')->default('active');
            $table->unsignedBigInteger('department_id')->nullable()->index('clinics_department_id_foreign');
            $table->timestamps();
            $table->string('email')->nullable();
            $table->string('working_hours')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clinics');
    }
};
