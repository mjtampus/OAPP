<?php

namespace App\Filament\Resources\ProductsSKUResource\Pages;

use Filament\Actions;
use App\Models\Products;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\FileUpload;
use App\Filament\Resources\ProductsSKUResource;

class EditProductsSKU extends EditRecord
{
    protected static string $resource = ProductsSKUResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('products_id')
                    ->label('Select Product')
                    ->options(Products::pluck('name', 'id'))
                    ->required()
                    ->disabled(),
                Section::make('Product Variants')
                    ->schema([
                                TextInput::make('sku')  // Changed from empty string to 'sku'
                                    ->label('SKU')
                                    ->disabled(),
                                TextInput::make('attributes')
                                    ->label('Variation')
                                    ->disabled(),
                                TextInput::make('price')
                                    ->label('Price')
                                    ->numeric()
                                    ->required(),
                                TextInput::make('stock')
                                    ->label('Stock')
                                    ->numeric()
                                    ->required(),
                                FileUpload::make('sku_image_dir')
                                    ->label('SKU Image')
                                    ->image()
                                    ->directory('products/skus')
                                    ->visibility('public')
                    ]),
            ]);
    }

}
