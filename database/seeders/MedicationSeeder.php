<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MedicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Delete all existing stock items first
        DB::table('stock_items')->delete();

        // Delete all existing medications
        DB::table('medications')->delete();

        // Sample medications
        $medications = [
            [
                'id' => 1,
                'name' => 'Paracetamol',
                'description' => 'Pain reliever and fever reducer',
                'dosage' => '500mg',
                'price' => 5.99,
                'requires_prescription' => false,
                'in_stock' => true,
                'stock_quantity' => 100,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'name' => 'Amoxicillin',
                'description' => 'Antibiotic for bacterial infections',
                'dosage' => '250mg',
                'price' => 12.50,
                'requires_prescription' => true,
                'in_stock' => true,
                'stock_quantity' => 50,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 3,
                'name' => 'Ibuprofen',
                'description' => 'Non-steroidal anti-inflammatory drug (NSAID)',
                'dosage' => '400mg',
                'price' => 7.99,
                'requires_prescription' => false,
                'in_stock' => true,
                'stock_quantity' => 75,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 4,
                'name' => 'Metformin',
                'description' => 'Diabetes medication to control blood sugar',
                'dosage' => '500mg',
                'price' => 15.75,
                'requires_prescription' => true,
                'in_stock' => true,
                'stock_quantity' => 60,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 5,
                'name' => 'Losartan',
                'description' => 'Medication for treating high blood pressure',
                'dosage' => '50mg',
                'price' => 18.50,
                'requires_prescription' => true,
                'in_stock' => true,
                'stock_quantity' => 40,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        // Insert medications
        DB::table('medications')->insert($medications);

        // Create stock items for each medication
        $stockItems = [];
        foreach ($medications as $medication) {
            $stockItems[] = [
                'medication_id' => $medication['id'],
                'quantity' => $medication['stock_quantity'],
                'min_stock' => max(10, $medication['stock_quantity'] / 4), // Set minimum stock to 10 or 25% of current stock
                'last_updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // Insert stock items
        DB::table('stock_items')->insert($stockItems);

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}