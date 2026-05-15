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
        Schema::create('vaccinations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('vaccinations_user_id_foreign');
            $table->unsignedBigInteger('vaccine_id')->index('vaccinations_vaccine_id_foreign');
            $table->date('vaccination_date');
            $table->time('vaccination_time');
            $table->enum('status', ['pending', 'scheduled', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->string('location')->nullable();
            $table->decimal('total_cost', 10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vaccinations');
    }
};
