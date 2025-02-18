<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Products;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ProductsSKU;
use Pages\ProductVariations;
use Filament\Resources\Resource;
use App\Models\ProductsAttributes;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Navigation\NavigationItem;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Navigation\NavigationGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Pages\SubNavigationPosition;
use Filament\Forms\Components\ColorPicker;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\ProductsResource\Pages;

class ProductsResource extends Resource
{
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static ?string $model = Products::class;

    protected static ?string $navigationGroup = 'Products';

    protected static ?int $sortNavigationItems = 1;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function getNavigationBadge(): ?string
    {

    return (string) static::$model::all()->count();

    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                ->schema([
                    // Left side - Main product details
                    Section::make('Product Details')
                        ->schema([
                            TextInput::make('name')
                                ->label('Product Name')
                                ->required()
                                ->maxLength(255)
                                ->columnSpanFull(),
                            RichEditor::make('description')
                                ->label('Description')
                                ->required()
                                ->maxLength(500)
                                ->columnSpanFull(),
                            FileUpload::make('product_image_dir')
                                ->label('Product Image')
                                ->required()
                                ->columnSpanFull(),
                        ])
                        ->columnSpan(['lg' => 2]),
            
                    // Right side - Categories and Brand
                    Section::make('Categories & Brand')
                        ->schema([
                            TextInput::make('price')
                                ->label('Price')
                                ->required()
                                ->numeric()
                                ->prefix('PHP'),
                            TextInput::make('stock')
                                ->label('stock')
                                ->required()
                                ->numeric(),
                            Select::make('brand_id')
                                ->label('Brand')
                                ->relationship('brand', 'name')
                                ->required(),
                            Select::make('category_id')
                                ->label('Category')
                                ->relationship('category', 'name')
                                ->required(),
                        Section::make('Product Status')
                                ->schema([
                                    Toggle::make('is_new_arrival')
                                    ->label('New Arrival')
                                    ->required(),
                                    Toggle::make('is_featured')
                                    ->label('Featured')
                                    ->required(),
                                ])
                        ])
                        ->columnSpan(['lg' => 1]),
                ])
                ->columns(['lg' => 3]),
                Section::make('Add Product Variants')
                ->description('Choose if you want to add product variants')
                ->schema([
                    Toggle::make('has_variants')
                        ->label('Enable Variants')
                        ->default(false)
                        ->live(),
            
                    Repeater::make('attributes')
                        ->relationship()
                        ->addActionLabel('Add more attributes')
                        ->schema([
                            Select::make('type')
                                ->label('Attribute Type')
                                ->options([
                                    'color' => 'Color',
                                    'origin' => 'Origin',
                                    'sizes' => 'Sizes',
                                ])
                                ->live()
                                ->disableOptionWhen(fn (string $value, callable $get): bool => 
                                    in_array($value, array_column($get('../../attributes') ?? [], 'type'))
                                ),
                            Repeater::make('product_attribute_values')
                                ->relationship('product_attribute_values') // Target values in ProductAttribute model
                                ->addActionLabel('Add more values')
                                ->schema([
                                    TextInput::make('value')
                                        ->label('Value'),
                                    ColorPicker::make('code')
                                        ->label('Color')
                                        ->nullable()
                                        ->hidden(fn (callable $get) => $get('../../type') !== 'color'),   
                                ])
                                ->collapsible(),
                        ])
                        ->minItems(1)
                        ->collapsible()
                        ->hidden(fn ($get) => !$get('has_variants')) // Hide if toggle is off
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('product_image_dir')
                    ->label('Image')
                    ->circular(),
                TextColumn::make('name')
                    ->label('Product Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('brand.name')
                    ->label('Brand')
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable(),
                IconColumn::make('is_new_arrival')
                    ->label('New Arrival')
                    ->boolean(),
                IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),
                TextColumn::make('price')
                    ->label('Price')
                    ->money()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProducts::route('/create'),
            'edit' => Pages\EditProducts::route('/{record}/edit'),
            'variations' => Pages\ProductVariations::route('/{record}/variations'),
        ];
    }


    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([

                Pages\EditProducts::class,            
                Pages\ProductVariations::class

        ]);
    }
}






