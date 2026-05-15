<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePharmaciesTable extends Migration
{
    public function up()
    {
        Schema::create('pharmacies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->string('phone');
            $table->string('email')->unique();
            $table->string('license_number')->unique();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // Add pharmacy_id to users table
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('pharmacy_id')->nullable()->after('is_admin');
            $table->foreign('pharmacy_id')->references('id')->on('pharmacies')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pharmacies');
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['pharmacy_id']);
            $table->dropColumn('pharmacy_id');
        });
    }
}
