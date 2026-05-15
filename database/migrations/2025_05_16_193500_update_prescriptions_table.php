<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            // Add new columns
            if (!Schema::hasColumn('prescriptions', 'patient_id')) {
                $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            }
            if (!Schema::hasColumn('prescriptions', 'instructions')) {
                $table->text('instructions');
            }
            if (!Schema::hasColumn('prescriptions', 'valid_until')) {
                $table->date('valid_until');
            }

            // Drop old columns
            if (Schema::hasColumn('prescriptions', 'medicine_id')) {
                $table->dropForeign(['medicine_id']);
                $table->dropColumn('medicine_id');
            }
            if (Schema::hasColumn('prescriptions', 'dosage')) {
                $table->dropColumn('dosage');
            }
            if (Schema::hasColumn('prescriptions', 'frequency')) {
                $table->dropColumn('frequency');
            }
            if (Schema::hasColumn('prescriptions', 'duration')) {
                $table->dropColumn('duration');
            }
            if (Schema::hasColumn('prescriptions', 'notes')) {
                $table->dropColumn('notes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            // Drop new columns
            if (Schema::hasColumn('prescriptions', 'patient_id')) {
                $table->dropForeign(['patient_id']);
                $table->dropColumn('patient_id');
            }
            if (Schema::hasColumn('prescriptions', 'instructions')) {
                $table->dropColumn('instructions');
            }
            if (Schema::hasColumn('prescriptions', 'valid_until')) {
                $table->dropColumn('valid_until');
            }

            // Add back old columns
            $table->foreignId('medicine_id')->constrained('medicines')->onDelete('cascade');
            $table->string('dosage');
            $table->string('frequency');
            $table->string('duration');
            $table->text('notes')->nullable();
        });
    }
};
