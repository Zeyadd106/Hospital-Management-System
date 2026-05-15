<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePrescriptionRefillsTableV3 extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('prescription_refills')) {
            Schema::table('prescription_refills', function (Blueprint $table) {
                // Add missing columns if they don't exist
                if (!Schema::hasColumn('prescription_refills', 'prescription_id')) {
                    $table->foreignId('prescription_id')->nullable()->after('user_id');
                    $table->foreign('prescription_id')->references('id')->on('prescriptions')->onDelete('set null');
                }
                
                if (!Schema::hasColumn('prescription_refills', 'rejection_reason')) {
                    $table->text('rejection_reason')->nullable()->after('status');
                }
                
                if (!Schema::hasColumn('prescription_refills', 'approved_at')) {
                    $table->timestamp('approved_at')->nullable()->after('status');
                }
                
                if (!Schema::hasColumn('prescription_refills', 'rejected_at')) {
                    $table->timestamp('rejected_at')->nullable()->after('status');
                }
                
                if (!Schema::hasColumn('prescription_refills', 'completed_at')) {
                    $table->timestamp('completed_at')->nullable()->after('status');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('prescription_refills')) {
            Schema::table('prescription_refills', function (Blueprint $table) {
                // Drop columns if they exist
                $columns = ['prescription_id', 'rejection_reason', 'approved_at', 'rejected_at', 'completed_at'];
                
                foreach ($columns as $column) {
                    if (Schema::hasColumn('prescription_refills', $column)) {
                        if ($column === 'prescription_id') {
                            $table->dropForeign(['prescription_id']);
                        }
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
}
