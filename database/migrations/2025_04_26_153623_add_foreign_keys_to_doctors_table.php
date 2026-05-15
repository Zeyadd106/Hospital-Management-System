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
        Schema::table('doctors', function (Blueprint $table) {
            $table->foreign(['clinic_id'])->references(['id'])->on('clinics')->onDelete('SET NULL');
            $table->foreign(['department_id'])->references(['id'])->on('departments')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropForeign('doctors_clinic_id_foreign');
            $table->dropForeign('doctors_department_id_foreign');
        });
    }
};
