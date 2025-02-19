<?php

namespace App\Filament\Resources\BrandResource\Pages;

use App\Filament\Resources\BrandResource;
use App\Models\Brand; // Import the Brand model
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateBrand extends CreateRecord
{
    protected static string $resource = BrandResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $brands = [];

        // Transform the repeater input into multiple brand records
        foreach ($data['brand_entries'] ?? [] as $categoryEntry) {
            foreach ($categoryEntry['brands'] ?? [] as $brand) {
                $brands[] = [
                    'category_id' => $categoryEntry['category_id'],
                    'name' => $brand['name'],
                ];
            }
        }

        return ['brands' => $brands]; // Ensure Filament processes the correct data structure
    }

    protected function handleRecordCreation(array $data): Model
    {
        $createdBrands = [];

        // Insert multiple brand records
        foreach ($data['brands'] as $brand) {
            $createdBrands[] = Brand::create([
                'category_id' => $brand['category_id'],
                'name' => $brand['name'],]);
        }

        return end($createdBrands) ?? new Brand(); // Return the last created brand to satisfy Filament
    }
}
