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
        Schema::table('medications', function (Blueprint $table) {
            // Add is_active column with a default of true
            $table->boolean('is_active')->default(true)->after('stock_quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medications', function (Blueprint $table) {
            // Drop the is_active column if needed
            $table->dropColumn('is_active');
        });
    }
};
