<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNotificationsTableV2 extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('notifications')) {
            Schema::table('notifications', function (Blueprint $table) {
                // Add notification system columns if they don't exist
                if (!Schema::hasColumn('notifications', 'notifiable_type')) {
                    $table->string('notifiable_type');
                }
                
                if (!Schema::hasColumn('notifications', 'notifiable_id')) {
                    $table->bigInteger('notifiable_id');
                }
                
                // Add index for better performance
                $table->index(['notifiable_type', 'notifiable_id']);
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
                if (Schema::hasColumn('notifications', 'notifiable_type')) {
                    $table->dropColumn('notifiable_type');
                }
                
                if (Schema::hasColumn('notifications', 'notifiable_id')) {
                    $table->dropColumn('notifiable_id');
                }
            });
        }
    }
}
