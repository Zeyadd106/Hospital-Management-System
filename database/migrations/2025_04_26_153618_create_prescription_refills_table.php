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
        Schema::create('prescription_refills', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->index('prescription_refills_user_id_foreign');
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('prescription_number')->nullable();
            $table->text('medication_details');
            $table->boolean('delivery_requested')->default(false);
            $table->text('delivery_address')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
            $table->string('confirmation_code')->unique();
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
        Schema::dropIfExists('prescription_refills');
    }
};
