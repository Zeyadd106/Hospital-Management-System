<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('notifications', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            }
            
            if (!Schema::hasColumn('notifications', 'doctor_id')) {
                $table->foreignId('doctor_id')->nullable()->constrained('doctors')->onDelete('cascade');
            }
            
            // Add other missing columns from the Notification model
            if (!Schema::hasColumn('notifications', 'title')) {
                $table->string('title')->nullable();
            }
            
            if (!Schema::hasColumn('notifications', 'content')) {
                $table->text('content')->nullable();
            }
            
            if (!Schema::hasColumn('notifications', 'is_read')) {
                $table->boolean('is_read')->default(false);
            }
            
            if (!Schema::hasColumn('notifications', 'related_id')) {
                $table->unsignedBigInteger('related_id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['doctor_id']);
            
            $table->dropColumn([
                'user_id',
                'doctor_id',
                'title',
                'content',
                'is_read',
                'related_id'
            ]);
        });
    }
};
