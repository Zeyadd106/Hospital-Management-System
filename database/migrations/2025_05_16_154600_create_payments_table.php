<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('payment_method_id')->nullable()->constrained()->onDelete('set null');
                $table->decimal('amount', 10, 2);
                $table->string('description');
                $table->string('status')->default('pending'); // pending, completed, failed, refunded
                $table->string('transaction_id')->nullable()->unique();
                $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('set null');
                $table->foreignId('vaccination_booking_id')->nullable()->constrained('vaccination_bookings')->onDelete('set null');
                $table->foreignId('health_screening_booking_id')->nullable()->constrained('health_screening_bookings')->onDelete('set null');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
}
