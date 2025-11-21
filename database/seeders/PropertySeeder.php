<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $landlords = User::role('landlord')->get();

        $properties = [
            [
                'landlord_id' => $landlords->first()->id,
                'title' => 'Luxury Apartment Complex',
                'description' => 'A modern apartment complex with premium amenities.',
                'address' => '123 Main Street, Nairobi',
                'neighborhood' => 'Westlands',
                'geo_lat' => -1.286389,
                'geo_lng' => 36.817223,
                'amenities' => json_encode(['gym', 'pool', 'parking', 'security']),
            ],
            [
                'landlord_id' => $landlords->first()->id,
                'title' => 'Cozy Family Home',
                'description' => 'Spacious family home in a quiet neighborhood.',
                'address' => '456 Oak Avenue, Nairobi',
                'neighborhood' => 'Karen',
                'geo_lat' => -1.316667,
                'geo_lng' => 36.783333,
                'amenities' => json_encode(['garden', 'parking', 'security']),
            ],
            [
                'landlord_id' => $landlords->skip(1)->first()->id ?? $landlords->first()->id,
                'title' => 'Downtown Office Space',
                'description' => 'Prime office location in the city center.',
                'address' => '789 Business District, Nairobi',
                'neighborhood' => 'CBD',
                'geo_lat' => -1.283333,
                'geo_lng' => 36.816667,
                'amenities' => json_encode(['elevator', 'conference room', 'parking']),
            ],
        ];

        foreach ($properties as $propertyData) {
            Property::create($propertyData);
        }
    }
}