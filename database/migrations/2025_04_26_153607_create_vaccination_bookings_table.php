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
        Schema::create('vaccination_bookings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('vaccination_bookings_user_id_foreign');
            $table->string('vaccination_type');
            $table->date('appointment_date');
            $table->unsignedBigInteger('vaccine_id')->nullable()->index('vaccination_bookings_vaccine_id_foreign');
            $table->integer('dose_number')->default(1);
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->text('notes')->nullable();
            $table->string('confirmation_code', 20)->nullable();
            $table->boolean('is_paid')->default(false);
            $table->timestamps();
            $table->time('appointment_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vaccination_bookings');
    }
};
