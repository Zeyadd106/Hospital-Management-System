<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('notifications', 'uuid')) {
                $table->uuid('uuid')->unique()->after('id');
            }
            
            if (!Schema::hasColumn('notifications', 'notifiable_type')) {
                $table->string('notifiable_type')->after('uuid');
            }
            
            if (!Schema::hasColumn('notifications', 'notifiable_id')) {
                $table->unsignedBigInteger('notifiable_id')->after('notifiable_type');
            }
            
            if (!Schema::hasColumn('notifications', 'data')) {
                $table->text('data')->after('notifiable_id');
            }
            
            if (!Schema::hasColumn('notifications', 'read_at')) {
                $table->timestamp('read_at')->nullable()->after('data');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (Schema::hasColumn('notifications', 'uuid')) {
                $table->dropColumn('uuid');
            }
            
            if (Schema::hasColumn('notifications', 'notifiable_type')) {
                $table->dropColumn('notifiable_type');
            }
            
            if (Schema::hasColumn('notifications', 'notifiable_id')) {
                $table->dropColumn('notifiable_id');
            }
            
            if (Schema::hasColumn('notifications', 'data')) {
                $table->dropColumn('data');
            }
            
            if (Schema::hasColumn('notifications', 'read_at')) {
                $table->dropColumn('read_at');
            }
        });
    }
};
