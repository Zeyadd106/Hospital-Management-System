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
        Schema::table('prescriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('prescriptions', 'appointment_id')) {
                $table->foreignId('appointment_id')->nullable()->constrained('appointments')->after('user_id');
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
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropForeign(['appointment_id']);
            $table->dropColumn('appointment_id');
        });
    }
};
