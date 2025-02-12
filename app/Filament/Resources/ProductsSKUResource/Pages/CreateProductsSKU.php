<?php

namespace App\Filament\Resources\ProductsSKUResource\Pages;

use Filament\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ProductsSKUResource;

class CreateProductsSKU extends CreateRecord
{
    protected static string $resource = ProductsSKUResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $variants = $data['variants'] ?? [];
        unset($data['variants']);
        
        $records = [];
        foreach ($variants as $variant) {
            $records[] = $this->getModel()::create([
                'products_id' => $data['products_id'],
                'sku' => $variant['sku'],
                'attributes' => $variant['attributes'],
                'price' => $variant['price'],
                'stock' => $variant['stock'],
                'sku_image_dir' => $variant['sku_image_dir']
            ]);
        }
        
        return $records[0] ?? $this->getModel()::create($data);
    }
}

