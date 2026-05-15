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
        Schema::table('messages', function (Blueprint $table) {
            $table->foreign(['doctor_id'])->references(['id'])->on('doctors')->onDelete('CASCADE');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onDelete('CASCADE');
            $table->foreign(['thread_id'])->references(['id'])->on('message_threads')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign('messages_doctor_id_foreign');
            $table->dropForeign('messages_user_id_foreign');
            $table->dropForeign('messages_thread_id_foreign');
        });
    }
};
