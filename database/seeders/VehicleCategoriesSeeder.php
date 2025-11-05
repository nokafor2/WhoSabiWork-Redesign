<?php

namespace Database\Seeders;

use App\Models\SparePart;
use App\Models\TechnicalService;
use App\Models\VehicleCategory;
use Illuminate\Database\Seeder;

class VehicleCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // The number of vehicleCategories to be created should not be more than the number of technicians in the technical_service table        
        // Get all the technical services and create vehicle categories for them
        $technicalServices = TechnicalService::all();
        $technicalServices->each(function ($item, $key) {
            // Generate random values for vehicle types (car, bus, truck)
            $vehicleCategoryData = [
                'user_id' => $item->user_id,
                'business_category' => 'technical_service',
                'car' => rand(0, 1),
                'bus' => rand(0, 1),
                'truck' => rand(0, 1),
            ];
            
            // Create or update the vehicle category
            VehicleCategory::updateOrCreate(
                [
                    'user_id' => $item->user_id,
                    'business_category' => 'technical_service'
                ],
                $vehicleCategoryData
            );
        });

        // Get all the spare parts and create vehicle categories for them
        $spareParts = SparePart::all();
        $spareParts->each(function ($item, $key) {
            // Generate random values for vehicle types (car, bus, truck)
            $vehicleCategoryData = [
                'user_id' => $item->user_id,
                'business_category' => 'spare_part',
                'car' => rand(0, 1),
                'bus' => rand(0, 1),
                'truck' => rand(0, 1),
            ];
            
            // Create or update the vehicle category
            VehicleCategory::updateOrCreate(
                [
                    'user_id' => $item->user_id,
                    'business_category' => 'spare_part'
                ],
                $vehicleCategoryData
            );
        });
    }
}
