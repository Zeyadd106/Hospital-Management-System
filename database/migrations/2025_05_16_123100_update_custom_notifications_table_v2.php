<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCustomNotificationsTableV2 extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('notifications')) {
            Schema::table('notifications', function (Blueprint $table) {
                // Add missing columns if they don't exist
                if (!Schema::hasColumn('notifications', 'user_id')) {
                    $table->unsignedBigInteger('user_id');
                    $table->foreign('user_id')->references('id')->on('users');
                }
                
                if (!Schema::hasColumn('notifications', 'doctor_id')) {
                    $table->unsignedBigInteger('doctor_id')->nullable();
                    $table->foreign('doctor_id')->references('id')->on('users');
                }
                
                if (!Schema::hasColumn('notifications', 'type')) {
                    $table->string('type');
                }
                
                if (!Schema::hasColumn('notifications', 'title')) {
                    $table->string('title');
                }
                
                if (!Schema::hasColumn('notifications', 'content')) {
                    $table->text('content');
                }
                
                if (!Schema::hasColumn('notifications', 'is_read')) {
                    $table->boolean('is_read')->default(false);
                }
                
                if (!Schema::hasColumn('notifications', 'related_id')) {
                    $table->unsignedBigInteger('related_id')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('notifications')) {
            Schema::table('notifications', function (Blueprint $table) {
                // Drop columns if they exist
                if (Schema::hasColumn('notifications', 'user_id')) {
                    $table->dropForeign(['user_id']);
                    $table->dropColumn('user_id');
                }
                
                if (Schema::hasColumn('notifications', 'doctor_id')) {
                    $table->dropForeign(['doctor_id']);
                    $table->dropColumn('doctor_id');
                }
                
                if (Schema::hasColumn('notifications', 'type')) {
                    $table->dropColumn('type');
                }
                
                if (Schema::hasColumn('notifications', 'title')) {
                    $table->dropColumn('title');
                }
                
                if (Schema::hasColumn('notifications', 'content')) {
                    $table->dropColumn('content');
                }
                
                if (Schema::hasColumn('notifications', 'is_read')) {
                    $table->dropColumn('is_read');
                }
                
                if (Schema::hasColumn('notifications', 'related_id')) {
                    $table->dropColumn('related_id');
                }
            });
        }
    }
}
