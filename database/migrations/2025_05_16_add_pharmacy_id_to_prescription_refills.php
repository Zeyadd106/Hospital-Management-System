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
        Schema::table('prescription_refills', function (Blueprint $table) {
            $table->unsignedBigInteger('pharmacy_id')->nullable()->after('user_id');
            $table->foreign('pharmacy_id')->references('id')->on('pharmacies')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prescription_refills', function (Blueprint $table) {
            $table->dropForeign(['pharmacy_id']);
            $table->dropColumn('pharmacy_id');
        });
    }
};
