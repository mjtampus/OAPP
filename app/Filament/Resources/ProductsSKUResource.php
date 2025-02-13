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
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use App\Filament\Resources\ProductsSKUResource\Pages;

class ProductsSKUResource extends Resource
{
    protected static ?string $model = ProductsSKU::class;

    protected static ?string $navigationGroup = 'Products';

    protected static ?string $navigationLabel = 'SKU';

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    public static function getNavigationBadge(): ?string
    {

    return (string) static::$model::all()->count();

    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('products_id')
                    ->label('Select Product')
                    ->options(Products::pluck('name', 'id'))
                    ->required()
                    ->live(),
                Section::make('Generate Product Variants')
                    ->description('This will generate all possible combinations based on product attributes.')
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
                    ->label('Attributes')
                    ->sortable(),
                ImageColumn::make('sku_image_dir')
                    ->label('Image'),
                TextColumn::make('stock')
                    ->numeric()
                    ->sortable(),
               TextColumn::make('price')
                    ->money('PHP')
                    ->sortable(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductsSKUS::route('/'),
            'edit' => Pages\EditProductsSKU::route('/{record}/edit'),
        ];
    }
    public static function canCreate(): bool
    {
        return false;
    }
}