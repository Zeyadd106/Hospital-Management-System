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
        Schema::create('medication_management_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->index('medication_management_requests_user_id_foreign');
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->text('medications')->nullable();
            $table->json('services');
            $table->text('special_requests')->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
            $table->string('confirmation_code')->unique();
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
        Schema::dropIfExists('medication_management_requests');
    }
};
