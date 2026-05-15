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
        Schema::table('vaccination_bookings', function (Blueprint $table) {
            // Modify the vaccination_type column to have a default value
            DB::statement("ALTER TABLE vaccination_bookings MODIFY COLUMN vaccination_type ENUM('Flu', 'COVID-19', 'Childhood', 'Travel', 'HPV', 'Other') NOT NULL DEFAULT 'Other'");
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
            // Revert the column to its previous state
            DB::statement("ALTER TABLE vaccination_bookings MODIFY COLUMN vaccination_type VARCHAR(255)");
        });
    }
};
