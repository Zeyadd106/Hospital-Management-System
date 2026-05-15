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
        Schema::create('appointments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('appointments_user_id_foreign');
            $table->unsignedBigInteger('doctor_id')->index('appointments_doctor_id_foreign');
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->string('status')->default('pending');
            $table->string('verification_code')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->text('reason')->nullable();
            $table->text('notes')->nullable();
            $table->string('type')->default('regular');
            $table->boolean('is_confirmed')->default(false);
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancellation_reason')->nullable();
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
        Schema::dropIfExists('appointments');
    }
};
