<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medicine;
use App\Models\Pharmacy;

class MedicineSeeder extends Seeder
{
    public function run()
    {
        // First, create a default pharmacy if it doesn't exist
        $pharmacy = Pharmacy::firstOrCreate([
            'name' => 'Main Pharmacy',
            'address' => '123 Medical Street',
            'phone' => '555-1234',
            'email' => 'pharmacy@example.com',
            'license_number' => 'PHARM-001',
            'status' => true
        ]);

        $medicines = [
            [
                'name' => 'Paracetamol 500mg',
                'generic_name' => 'Paracetamol',
                'strength' => '500mg',
                'form' => 'Tablet',
                'description' => 'Pain reliever and fever reducer',
                'price' => 2.50,
                'stock' => 100,
                'category' => 'Pain Relief',
                'manufacturer' => 'Generic Pharmaceuticals',
                'expiry_date' => '2025-12-31',
                'status' => true,
                'pharmacy_id' => $pharmacy->id
            ],
            [
                'name' => 'Ibuprofen 400mg',
                'generic_name' => 'Ibuprofen',
                'strength' => '400mg',
                'form' => 'Tablet',
                'description' => 'Anti-inflammatory pain reliever',
                'price' => 3.00,
                'stock' => 80,
                'category' => 'Pain Relief',
                'manufacturer' => 'Generic Pharmaceuticals',
                'expiry_date' => '2025-12-31',
                'status' => true,
                'pharmacy_id' => $pharmacy->id
            ],
            [
                'name' => 'Amoxicillin 500mg',
                'generic_name' => 'Amoxicillin',
                'strength' => '500mg',
                'form' => 'Capsule',
                'description' => 'Antibiotic for bacterial infections',
                'price' => 7.99,
                'stock' => 50,
                'category' => 'Antibiotics',
                'manufacturer' => 'Generic Pharmaceuticals',
                'expiry_date' => '2025-12-31',
                'status' => true,
                'pharmacy_id' => $pharmacy->id
            ],
            [
                'name' => 'Loratadine 10mg',
                'generic_name' => 'Loratadine',
                'strength' => '10mg',
                'form' => 'Tablet',
                'description' => 'Antihistamine for allergies',
                'price' => 4.50,
                'stock' => 75,
                'category' => 'Allergy',
                'manufacturer' => 'Generic Pharmaceuticals',
                'expiry_date' => '2025-12-31',
                'status' => true,
                'pharmacy_id' => $pharmacy->id
            ],
            [
                'name' => 'Omeprazole 20mg',
                'generic_name' => 'Omeprazole',
                'strength' => '20mg',
                'form' => 'Capsule',
                'description' => 'Heartburn and acid reflux treatment',
                'price' => 6.99,
                'stock' => 60,
                'category' => 'Gastrointestinal',
                'manufacturer' => 'Generic Pharmaceuticals',
                'expiry_date' => '2025-12-31',
                'status' => true,
                'pharmacy_id' => $pharmacy->id
            ],
            [
                'name' => 'Metformin 500mg',
                'generic_name' => 'Metformin',
                'strength' => '500mg',
                'form' => 'Tablet',
                'description' => 'Diabetes treatment',
                'price' => 5.99,
                'stock' => 120,
                'category' => 'Diabetes',
                'manufacturer' => 'Generic Pharmaceuticals',
                'expiry_date' => '2025-12-31',
                'status' => true,
                'pharmacy_id' => $pharmacy->id
            ],
            [
                'name' => 'Atorvastatin 20mg',
                'generic_name' => 'Atorvastatin',
                'strength' => '20mg',
                'form' => 'Tablet',
                'description' => 'Cholesterol-lowering medication',
                'price' => 8.99,
                'stock' => 70,
                'category' => 'Cardiovascular',
                'manufacturer' => 'Generic Pharmaceuticals',
                'expiry_date' => '2025-12-31',
                'status' => true,
                'pharmacy_id' => $pharmacy->id
            ],
            [
                'name' => 'Losartan 50mg',
                'generic_name' => 'Losartan',
                'strength' => '50mg',
                'form' => 'Tablet',
                'description' => 'Blood pressure medication',
                'price' => 4.99,
                'stock' => 90,
                'category' => 'Cardiovascular',
                'manufacturer' => 'Generic Pharmaceuticals',
                'expiry_date' => '2025-12-31',
                'status' => true,
                'pharmacy_id' => $pharmacy->id
            ]
        ];

        foreach ($medicines as $medicine) {
            Medicine::create($medicine);
        }
    }
}
