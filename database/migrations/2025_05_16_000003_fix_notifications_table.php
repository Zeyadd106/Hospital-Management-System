<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create a temporary table to store the existing data
        Schema::create('notifications_temp', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->string('notifiable_type');
            $table->unsignedBigInteger('notifiable_id');
            $table->json('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['notifiable_type', 'notifiable_id']);
        });

        // Copy data from the old table to the new one if it exists
        if (Schema::hasTable('notifications')) {
            DB::table('notifications')->orderBy('id')->chunk(100, function ($notifications) {
                $data = [];
                foreach ($notifications as $notification) {
                    $data[] = [
                        'id' => $notification->uuid ?? (string) \Illuminate\Support\Str::uuid(),
                        'type' => $notification->type,
                        'notifiable_type' => $notification->notifiable_type,
                        'notifiable_id' => $notification->notifiable_id,
                        'data' => $notification->data,
                        'read_at' => $notification->read_at,
                        'created_at' => $notification->created_at,
                        'updated_at' => $notification->updated_at,
                    ];
                }
                DB::table('notifications_temp')->insert($data);
            });

            // Drop the old table
            Schema::dropIfExists('notifications');
        }

        // Rename the temporary table
        Schema::rename('notifications_temp', 'notifications');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // This migration is not reversible
        throw new \RuntimeException('This migration cannot be reversed.');
    }
};
