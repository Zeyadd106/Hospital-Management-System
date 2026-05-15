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
            $table->foreign(['user_id'])->references(['id'])->on('users')->onDelete('CASCADE');
            $table->foreign(['vaccine_id'])->references(['id'])->on('vaccines')->onDelete('CASCADE');
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
            $table->dropForeign('vaccination_bookings_user_id_foreign');
            $table->dropForeign('vaccination_bookings_vaccine_id_foreign');
        });
    }
};
