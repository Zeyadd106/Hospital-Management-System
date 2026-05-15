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
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->enum('type', ['message', 'contact', 'appointment', 'system'])->default('system');
            $table->string('title');
            $table->text('content');
            $table->boolean('is_read')->default(false);
            $table->unsignedBigInteger('related_id')->nullable();
            $table->timestamps();

            $table->index(['doctor_id', 'is_read']);
            $table->index(['user_id', 'is_read']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};
