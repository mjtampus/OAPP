<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use App\Models\Products;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ProductsSKU;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Repeater;
use App\Models\ProductsAttributesValues;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\OrderResource\Pages;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    public $total = 0;

    protected static ?string $navigationGroup = 'Orders';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationBadge(): ?string
    {

    return (string) static::$model::all()->count();

    }
    /** 
     * Define the Form Schema for Creating & Editing Orders 
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Customer')
                        ->schema([
                            Select::make('user_id')
                                ->relationship('user', 'name')
                                ->required()
                                ->preload()
                                ->live()
                                ->searchable()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    $user = \App\Models\User::find($state);
                                    $set('user_name', $user?->name);
                                    $set('user_email', $user?->email);
                                }),
    
                            TextInput::make('user_name')
                                ->label('User Name')
                                ->disabled(),
    
                            TextInput::make('user_email')
                                ->label('User Email')
                                ->disabled()
                                ->dehydrated(false), // Prevents it from being saved
                        ]),
    
                    Wizard\Step::make('Status & Payment')
                        ->schema([
                            TextInput::make('order_number')
                                ->required()
                                ->default(fn () => 'ORD-' . uniqid(6))
                                ->dehydrated(true)
                                ->unique()
                                ->readonly(),
    
                            Select::make('order_status')
                                ->options([
                                    'pending' => 'Pending',
                                    'processing' => 'Processing',
                                    'shipped' => 'Shipped',
                                    'delivered' => 'Delivered',
                                    'cancelled' => 'Cancelled',
                                ])
                                ->required(),
    
                            Select::make('payment_method')
                                ->options([
                                    'cod' => 'Cash On Delivery',
                                    'credit_card' => 'Credit Card',
                                    'maya' => 'Maya',
                                    'gcash' => 'GCash',
                                ])
                                ->required(),
    
                            Select::make('is_paid')
                                ->options([
                                    true => 'Paid',
                                    false => 'Unpaid',
                                ])
                                ->required(),
                        ]),
    
                    Wizard\Step::make('Order Items')
                        ->schema([
                            Repeater::make('items')
                                ->relationship('items') // This references the OrderItems relationship
                                ->schema([
                                    Select::make('product_id')
                                        ->label('Product')
                                        ->relationship('product', 'name') // Ensure 'name' is the correct column in Product
                                        ->required()
                                        ->live(),
    
                                    Select::make('sku_id')
                                        ->label('SKU')
                                        ->options(function (callable $get) {
                                            $productId = $get('product_id'); // Get selected product_id
                                            if (!$productId) {
                                                return []; // If no product is selected, return empty options
                                            }
    
                                            return \App\Models\ProductsSKU::where('products_id', $productId)
                                                ->get()
                                                ->mapWithKeys(function ($sku) {
                                                    $attributes = $sku->attributes;
                                                    if (!is_array($attributes)) {
                                                        return [$sku->id => $sku->sku]; // Return only SKU if attributes are invalid
                                                    }
    
                                                    $extractedIds = array_filter(array_map(fn($attr) => $attr[1] ?? null, $attributes));
    
                                                    $attributeValues = ProductsAttributesValues::whereIn('id', $extractedIds)
                                                        ->pluck('value')
                                                        ->toArray();
                                                        $skuLabel = $sku->sku . ' - ' . implode(', ', $attributeValues);
    
                                                    return [$sku->id => $skuLabel];
                                                })
                                                ->toArray();
                                        })
                                        ->required()
                                        ->disabled(fn (callable $get) => !$get('product_id'))
                                        ->searchable()
                                        ->preload()
                                        ->live()
                                        ->afterStateUpdated(function ($state, callable $set) {
                                            $sku = \App\Models\ProductsSKU::find($state);
                                            $set('price', $sku?->price);
                                        }),
    
                                    TextInput::make('price')
                                        ->disabled()
                                        ->dehydrated(true),
    
                                    TextInput::make('quantity')
                                        ->required()
                                        ->numeric()
                                        ->live()
                                        ->default(1)
                                        ->afterStateUpdated(function (callable $get, callable $set) {
                                            $items = $get('../../items') ?? [];
                                            $total = collect($items)->sum(fn ($item) => 
                                                ((float) ($item['price'] ?? 0)) * ((int) ($item['quantity'] ?? 0))
                                            );                                        
                                            foreach ($items as $index => $item) {
                                                $set("../../items.{$index}.total_price", ((float) ($item['price'] ?? 0)) * ((int) ($item['quantity'] ?? 0)));
                                            }
                                            $set('../../amount', $total);
                                        }),
                                                                        
    
                                    TextInput::make('total_price')
                                        ->disabled()
                                        ->dehydrated(true),
                                ])
                                ->collapsible(),
                        ])
                ]),
                TextInput::make('amount')
                    ->label('Total Amount')
                    ->live()
                    ->disabled()
                    ->dehydrated(true),
            ]);
    }
    

    /** 
     * Define the Table Columns for Listing Orders 
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Order #')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer Name')
                    ->description(fn ($record) => $record->user?->email) // Show email as description
                    ->sortable(),

                Tables\Columns\TextColumn::make('order_status')
                    ->label('Status')
                    ->badge()
                    ->sortable()
                    ->color(function (string $state): string {
                        return match ($state) {
                            'pending' => 'warning',
                            'processing' => 'warning',
                            'shipped' => 'info',
                            'delivered' => 'success',
                            'cancelled' => 'danger',
                            default => 'secondary',
                        };
                    }),

                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Payment Method')
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Total Amount')
                    ->numeric()
                    ->sortable()
                    ->money('USD'), // Display currency format

                Tables\Columns\IconColumn::make('is_paid')
                    ->label('Paid')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Order Date')
                    ->dateTime('F j, Y - g:i A') // Example: February 26, 2025 - 2:30 PM
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('F j, Y - g:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Add filters if needed
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /** 
     * Define Any Relations If Needed 
     */
    public static function getRelations(): array
    {
        return [
            // Add relation managers here if needed
        ];
    }


    /** 
     * Define Resource Pages 
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
            'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }

    function calculateTotal(callable $get)
    {
    $items = $get('../../items') ?? [];

    return collect($items)->sum(fn ($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 0));
    }
}

