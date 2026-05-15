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
        Schema::table('health_screening_bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('clinic_id')->nullable()->index('health_screening_bookings_clinic_id_foreign');
            
            $table->foreign('clinic_id')
                  ->references('id')
                  ->on('clinics')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('health_screening_bookings', function (Blueprint $table) {
            $table->dropForeign('health_screening_bookings_clinic_id_foreign');
            $table->dropColumn('clinic_id');
        });
    }
};
