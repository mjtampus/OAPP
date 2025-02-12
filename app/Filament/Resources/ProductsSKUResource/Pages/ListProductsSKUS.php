<?php

namespace App\Filament\Resources\ProductsSKUResource\Pages;

use App\Filament\Resources\ProductsSKUResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductsSKUS extends ListRecords
{
    protected static string $resource = ProductsSKUResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
