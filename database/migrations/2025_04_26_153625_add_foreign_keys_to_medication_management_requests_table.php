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
        Schema::table('medication_management_requests', function (Blueprint $table) {
            $table->foreign(['user_id'])->references(['id'])->on('users')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medication_management_requests', function (Blueprint $table) {
            $table->dropForeign('medication_management_requests_user_id_foreign');
        });
    }
};
