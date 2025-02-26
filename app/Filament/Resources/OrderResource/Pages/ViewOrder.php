<?php

namespace App\Filament\Resources\OrderResource\Pages;

use Filament\Actions;
use Filament\Forms\Form;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\OrderResource;
use Filament\Forms\Components\Placeholder;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\EditAction::make(),
            // Actions\DeleteAction::make(),
        ];
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Grid::make([
                'default' => 1,
                'md' => 3,
            ])->schema([
                // Column 1: Order Information
                Section::make('Order Details')
                    ->columnSpan(['default' => 1, 'md' => 1])
                    ->schema([

                        Select::make('order_status')
                        ->label('Order Status')
                        ->options([
                            'pending' => 'Pending'
                        ]),

                        Placeholder::make('order_number')
                            ->label('Order Number')
                            ->content(fn ($record) => $record->order_number),

                        Placeholder::make('created_at')
                            ->label('Order Date')
                            ->content(fn ($record) => $record->created_at->format('F j, Y - g:i A')),
                    ]),
                
                // Column 2: Status & Payment Information
                Section::make('Status & Payment')
                    ->columnSpan(['default' => 1, 'md' => 1])
                    ->schema([

                        Placeholder::make('payment_method')
                            ->label('Payment Method')
                            ->content(fn ($record) => ucfirst($record->payment_method)),

                        Placeholder::make('is_paid')
                            ->label('Payment Status')
                            ->content(fn ($record) => $record->is_paid ? 'Paid' : 'Unpaid'),

                        Placeholder::make('amount')
                            ->label('Total Amount')
                            ->content(fn ($record) => '₱' . number_format($record->amount, 2)),
                    ]),

                // Column 3: Customer Information
                Section::make('Customer Information')
                    ->columnSpan(['default' => 1, 'md' => 1])
                    ->schema([
                        Placeholder::make('customer_name')
                            ->label('Customer Name')
                            ->content(fn ($record) => $record->user?->name),

                        Placeholder::make('customer_email')
                            ->label('Email Address')
                            ->content(fn ($record) => $record->user?->email),
                            
                        Placeholder::make('customer_phone')
                            ->label('Phone Number')
                            ->content(fn ($record) => $record->user?->phone ?? 'Not provided'),
                    ]),
            ]),

            Section::make('Notes')
            ->collapsed()
            ->schema([
                Placeholder::make('notes')
                    ->content(fn ($record) => $record->notes ?? 'No notes for this order'),
            ]),
            
            // Order Items Section (Full Width)
            Section::make('Order Items')
                ->icon('heroicon-o-rectangle-stack')
                ->schema([
                    Repeater::make('items')
                        ->label('Order Items')
                        ->relationship('items') // Ensure this is correctly set up in your Model
                        ->schema([
                            Placeholder::make('product_name')
                            ->label('Product Name')
                            ->content(fn ($record) => $record->product->name)
                            ->disabled(),

                            Placeholder::make('sku')
                                ->label('sku')
                                ->content(fn ($record) => $record->sku->sku)
                                ->disabled(),
            
                            Placeholder::make('quantity')
                                ->content(fn ($record) => $record->quantity)
                                ->label('Quantity')
                                ->disabled(),
            
                            Placeholder::make('price')
                                ->label('Price')
                                ->content(fn ($state) => '₱' . number_format($state, 2))
                                ->disabled(),
            
                            Placeholder::make('subtotal')
                                ->label('Subtotal')
                                ->content(fn ($record) => '₱' . number_format($record->price * $record->quantity, 2))
                                ->disabled(),
                        ])
                        ->columns(5)
                        ->disabled(), // Make it readonly in View mode
                ]),
        
            // Shipping Information (Full Width)
            // Section::make('Shipping Information')
            //     ->schema([
            //         Grid::make(2)
            //             ->schema([
            //                 Group::make([
            //                     Placeholder::make('shipping_address')
            //                         ->label('Shipping Address')
            //                         ->content(fn ($record) => $record->shipping_address ?? 'No shipping address provided'),
                                    
            //                     Placeholder::make('shipping_method')
            //                         ->label('Shipping Method')
            //                         ->content(fn ($record) => $record->shipping_method ?? 'Standard Shipping'),
            //                 ]),
                            
            //                 Group::make([
            //                     Placeholder::make('tracking_number')
            //                         ->label('Tracking Number')
            //                         ->content(fn ($record) => $record->tracking_number ?? 'Not yet assigned'),
                                    
            //                     Placeholder::make('estimated_delivery')
            //                         ->label('Estimated Delivery')
            //                         ->content(fn ($record) => $record->estimated_delivery ? $record->estimated_delivery->format('F j, Y') : 'Not available'),
            //                 ]),
            //             ]),
            //     ]),
                
            // Order Notes (Full Width)
        ]);
    }

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('product_name')
                ->label('Product Name'),

            
            TextColumn::make('quantity')
                ->label('Quantity'),

            TextColumn::make('price')
                ->label('Price'),

            
            TextColumn::make('subtotal')
                ->label('Subtotal'),
        ]);
}
}