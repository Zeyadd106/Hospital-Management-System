<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPharmacyIdToMedicationsTable extends Migration
{
    public function up()
    {
        Schema::table('medications', function (Blueprint $table) {
            $table->foreignId('pharmacy_id')->nullable()->after('id');
            $table->foreign('pharmacy_id')->references('id')->on('pharmacies')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('medications', function (Blueprint $table) {
            $table->dropForeign(['pharmacy_id']);
            $table->dropColumn('pharmacy_id');
        });
    }
}
