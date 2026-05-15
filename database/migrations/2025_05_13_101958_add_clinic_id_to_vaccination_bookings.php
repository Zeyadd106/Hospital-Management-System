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
        Schema::table('vaccination_bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('clinic_id')->nullable()->index('vaccination_bookings_clinic_id_foreign');
            
            // Add foreign key constraint
            $table->foreign('clinic_id', 'vaccination_bookings_clinic_id_foreign')
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
        Schema::table('vaccination_bookings', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign('vaccination_bookings_clinic_id_foreign');
            
            // Then drop the column
            $table->dropColumn('clinic_id');
        });
    }
};
