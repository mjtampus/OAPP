<?php

namespace App\Filament\Resources;

use Log;
use App\Models\Products;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ProductsSKU;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use App\Models\ProductsAttributesValues;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resources\ProductsSKUResource\Pages;

class ProductsSKUResource extends Resource
{
    protected static ?string $model = ProductsSKU::class;

    protected static ?string $navigationGroup = 'Products';

    protected static ?string $navigationLabel = 'SKU-Variations';

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    public static function getNavigationBadge(): ?string
    {

    return (string) static::$model::all()->count();

    }

    
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                TextColumn::make('product.name')
                    ->label('Product')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('attributes')
                    ->label('Variations')
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        // Fix format if stored as a string
                        $state = '[' . $state . ']';
                        $attributes = json_decode($state, true);
    
                        if (!is_array($attributes)) {
                            return 'Invalid Data';
                        }
                        $ids = array_map(fn($arr) => $arr[1] ?? null, $attributes);
                        $ids = array_filter($ids);
                        // Fetch the corresponding values from the database
                        $values = ProductsAttributesValues::whereIn('id', $ids)->pluck('value')->toArray();
                        // Return as a comma-separated string
                        return implode(' | ', $values);
                    }),
                ImageColumn::make('sku_image_dir')
                    ->label('Image'),
                TextColumn::make('stock')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('price')
                    ->money('PHP')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('product_id')
                    ->label('Filter by Product')
                    ->options(Products::pluck('name', 'id')->toArray())
                    ->searchable()
                    ->preload()
                    ->query(fn ($query, $data) => $data['value'] ? $query->where('products_id', $data['value']) : $query),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductsSKUS::route('/'),
            'edit' => Pages\EditProductsSKU::route('/{record}/edit'),
            'view' => Pages\ViewProductsSKU::route('/{record}/view'),
        ];
    }
    public static function canCreate(): bool
    {
        return false;
    }
    public static function getModelLabel(): string
    {
        return 'Product Variation';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Product Variations';
    }
}