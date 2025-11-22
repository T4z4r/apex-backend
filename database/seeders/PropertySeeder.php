<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        // Get users who have the landlord role assigned
        $landlords = User::whereHas('roles', function($query) {
            $query->where('name', 'landlord');
        })->get();

        if ($landlords->isEmpty()) {
            return; // Skip if no landlords exist
        }

        $firstLandlord = $landlords->first();
        $properties = [
            [
                'landlord_id' => $firstLandlord->id,
                'tenant_id' => $firstLandlord->tenant_id,
                'title' => 'Luxury Apartment Complex',
                'description' => 'A modern apartment complex with premium amenities.',
                'address' => '123 Main Street, Nairobi',
                'neighborhood' => 'Westlands',
                'geo_lat' => -1.286389,
                'geo_lng' => 36.817223,
                'amenities' => json_encode(['gym', 'pool', 'parking', 'security']),
            ],
            [
                'landlord_id' => $firstLandlord->id,
                'tenant_id' => $firstLandlord->tenant_id,
                'title' => 'Cozy Family Home',
                'description' => 'Spacious family home in a quiet neighborhood.',
                'address' => '456 Oak Avenue, Nairobi',
                'neighborhood' => 'Karen',
                'geo_lat' => -1.316667,
                'geo_lng' => 36.783333,
                'amenities' => json_encode(['garden', 'parking', 'security']),
            ],
        ];

        // Only add third property if we have at least 2 landlords
        if ($landlords->count() >= 2) {
            $secondLandlord = $landlords->skip(1)->first();
            $properties[] = [
                'landlord_id' => $secondLandlord->id,
                'tenant_id' => $secondLandlord->tenant_id,
                'title' => 'Downtown Office Space',
                'description' => 'Prime office location in the city center.',
                'address' => '789 Business District, Nairobi',
                'neighborhood' => 'CBD',
                'geo_lat' => -1.283333,
                'geo_lng' => 36.816667,
                'amenities' => json_encode(['elevator', 'conference room', 'parking']),
            ];
        }

        foreach ($properties as $propertyData) {
            Property::create($propertyData);
        }
    }
}
