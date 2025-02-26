<?php

namespace App\Filament\Resources;

use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\OrderResource\Pages;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

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
                Forms\Components\TextInput::make('order_number')
                    ->label('Order Number')
                    ->disabled(), // Assuming it's auto-generated
                Forms\Components\TextInput::make('amount')
                    ->label('Total Amount')
                    ->required()
                    ->numeric(),
                Forms\Components\Toggle::make('is_paid')
                    ->label('Payment Status')
                    ->required(),
                Forms\Components\Select::make('payment_method')
                    ->label('Payment Method')
                    ->options([
                        'credit_card' => 'Credit Card',
                        'Maya' => 'Maya',
                        'Gcash' => 'Gcash',
                        'COD' => 'Cash on Delivery',
                    ])
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('order_status')
                    ->label('Order Status')
                    ->default('Pending')
                    ->required(),
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
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer Name')
                    ->description(fn ($record) => $record->user?->email) // Show email as description
                    ->sortable(),

                Tables\Columns\TextColumn::make('order_status')
                    ->label('Status')
                    ->badge()
                    ->sortable(),

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
}

