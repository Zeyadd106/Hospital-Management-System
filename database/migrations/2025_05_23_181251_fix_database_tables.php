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
        // Create users table first
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
            });
        }

        // Create stock_items table
        if (!Schema::hasTable('stock_items')) {
            Schema::create('stock_items', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->integer('quantity')->default(0);
                $table->decimal('price', 10, 2)->default(0.00);
                $table->timestamps();
            });
        }

        // Create doctors table
        if (!Schema::hasTable('doctors')) {
            Schema::create('doctors', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('phone')->nullable();
                $table->string('specialization');
                $table->text('bio')->nullable();
                $table->string('image')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Create stock_adjustments table last (since it depends on users and stock_items)
        if (!Schema::hasTable('stock_adjustments')) {
            Schema::create('stock_adjustments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('stock_item_id')->constrained('stock_items')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->enum('adjustment_type', ['add', 'remove', 'set']);
                $table->integer('quantity');
                $table->integer('previous_quantity');
                $table->integer('new_quantity');
                $table->text('reason');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_adjustments');
        Schema::dropIfExists('doctors');
        Schema::dropIfExists('stock_items');
        Schema::dropIfExists('users');
    }
};
