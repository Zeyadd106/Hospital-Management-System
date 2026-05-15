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
        Schema::table('appointments', function (Blueprint $table) {
            if (!Schema::hasColumn('appointments', 'clinic_id')) {
                $table->unsignedBigInteger('clinic_id')->after('doctor_id');
                $table->foreign('clinic_id')->references('id')->on('clinics');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            if (Schema::hasColumn('appointments', 'clinic_id')) {
                $table->dropForeign(['clinic_id']);
                $table->dropColumn('clinic_id');
            }
        });
    }
};
