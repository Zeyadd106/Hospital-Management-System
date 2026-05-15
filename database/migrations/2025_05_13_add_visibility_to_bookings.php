<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->boolean('is_visible')->default(true);
            $table->unsignedBigInteger('linked_booking_id')->nullable();
            $table->string('linked_booking_type')->nullable();
        });

        Schema::table('health_screening_bookings', function (Blueprint $table) {
            $table->boolean('is_visible')->default(true);
            $table->unsignedBigInteger('linked_booking_id')->nullable();
            $table->string('linked_booking_type')->nullable();
        });

        Schema::table('vaccination_bookings', function (Blueprint $table) {
            $table->boolean('is_visible')->default(true);
            $table->unsignedBigInteger('linked_booking_id')->nullable();
            $table->string('linked_booking_type')->nullable();
        });
    }

    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['is_visible', 'linked_booking_id', 'linked_booking_type']);
        });

        Schema::table('health_screening_bookings', function (Blueprint $table) {
            $table->dropColumn(['is_visible', 'linked_booking_id', 'linked_booking_type']);
        });

        Schema::table('vaccination_bookings', function (Blueprint $table) {
            $table->dropColumn(['is_visible', 'linked_booking_id', 'linked_booking_type']);
        });
    }
};
