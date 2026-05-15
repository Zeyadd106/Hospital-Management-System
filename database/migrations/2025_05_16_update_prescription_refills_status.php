<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // MySQL doesn't allow direct modification of enum values
        // We need to modify the column type completely
        
        // First, get the current enum values
        $currentEnumValues = DB::select("SHOW COLUMNS FROM prescription_refills WHERE Field = 'status'")[0]->Type;
        
        // Extract the values from enum('value1','value2',...)
        preg_match('/^enum\((.*)\)$/', $currentEnumValues, $matches);
        $values = str_getcsv($matches[1], ',', "'");
        
        // Add 'approved' and 'rejected' if they don't exist
        if (!in_array('approved', $values)) {
            $values[] = 'approved';
        }
        
        if (!in_array('rejected', $values)) {
            $values[] = 'rejected';
        }
        
        // Format the values back to enum syntax
        $newEnumValues = "enum('" . implode("','" , $values) . "')";
        
        // Update the column type
        DB::statement("ALTER TABLE prescription_refills MODIFY COLUMN status $newEnumValues DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values (pending, processing, completed, cancelled)
        DB::statement("ALTER TABLE prescription_refills MODIFY COLUMN status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending'");
    }
};
