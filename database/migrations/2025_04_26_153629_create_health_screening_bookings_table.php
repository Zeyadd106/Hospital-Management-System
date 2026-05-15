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
        Schema::create('health_screening_bookings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('health_screening_bookings_user_id_foreign');
            $table->string('screening_type');
            $table->date('appointment_date');
            $table->string('appointment_time');
            $table->unsignedBigInteger('health_screening_id')->nullable()->index('health_screening_bookings_health_screening_id_foreign');
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->text('notes')->nullable();
            $table->string('confirmation_code', 20)->nullable();
            $table->boolean('is_paid')->default(false);
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
        Schema::dropIfExists('health_screening_bookings');
    }
};
