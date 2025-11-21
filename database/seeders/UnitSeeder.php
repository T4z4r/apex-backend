<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $properties = Property::all();

        if ($properties->isEmpty()) {
            return; // Skip if no properties exist
        }

        $units = [
            [
                'property_id' => $properties->first()->id,
                'unit_label' => 'A101',
                'bedrooms' => 2,
                'bathrooms' => 1,
                'size_m2' => 75.5,
                'rent_amount' => 25000.00,
                'deposit_amount' => 25000.00,
                'is_available' => true,
                'photos' => json_encode(['photo1.jpg', 'photo2.jpg']),
            ],
            [
                'property_id' => $properties->first()->id,
                'unit_label' => 'A102',
                'bedrooms' => 3,
                'bathrooms' => 2,
                'size_m2' => 100.0,
                'rent_amount' => 35000.00,
                'deposit_amount' => 35000.00,
                'is_available' => true,
                'photos' => json_encode(['photo3.jpg', 'photo4.jpg']),
            ],
        ];

        // Add more units if we have multiple properties
        if ($properties->count() >= 2) {
            $units[] = [
                'property_id' => $properties->skip(1)->first()->id,
                'unit_label' => 'House 1',
                'bedrooms' => 4,
                'bathrooms' => 3,
                'size_m2' => 200.0,
                'rent_amount' => 60000.00,
                'deposit_amount' => 60000.00,
                'is_available' => false,
                'photos' => json_encode(['house1.jpg', 'house2.jpg']),
            ];
        }

        if ($properties->count() >= 3) {
            $units[] = [
                'property_id' => $properties->skip(2)->first()->id,
                'unit_label' => 'Office 101',
                'bedrooms' => 0,
                'bathrooms' => 1,
                'size_m2' => 50.0,
                'rent_amount' => 15000.00,
                'deposit_amount' => 15000.00,
                'is_available' => true,
                'photos' => json_encode(['office1.jpg']),
            ];
        }

        foreach ($units as $unitData) {
            Unit::create($unitData);
        }
    }
}