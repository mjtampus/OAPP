<?php

namespace App\Filament\Resources\OrderResource\Pages;

use Filament\Actions;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Notifications\Notification;
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

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Grid::make([
                'default' => 1,
                'md' => 3,
            ])->schema([
                // Column 1: Order Information
                \Filament\Infolists\Components\Section::make('Order Details')
                ->icon('heroicon-m-document-text')
                ->columnSpan(['default' => 1, 'md' => 1])
                ->schema([
                    \Filament\Infolists\Components\Grid::make(1)
                        ->schema([
                            \Filament\Infolists\Components\TextEntry::make('order_status')
                                ->label('Order Status')
                                ->badge()
                                ->formatStateUsing(fn ($state) => ucfirst($state))
                                ->color(fn ($state) => match($state) {
                                    'pending' => 'warning',
                                    'processing' => 'info',
                                    'shipped' => 'info',
                                    'delivered' => 'success',
                                    'cancelled' => 'danger',
                                    default => 'secondary',
                                })
                                ->suffixAction(
                                    \Filament\Infolists\Components\Actions\Action::make('updateStatus')
                                        ->label('Update Status')
                                        ->icon('heroicon-m-pencil-square')
                                        ->color('primary')
                                        ->form([
                                            \Filament\Forms\Components\Select::make('order_status')
                                                ->label('New Status')
                                                ->options([
                                                    'pending' => 'Pending',
                                                    'processing' => 'Processing',
                                                    'shipped' => 'Shipped',
                                                    'delivered' => 'Delivered',
                                                    'cancelled' => 'Cancelled',
                                                ])
                                                ->default(fn () => $this->record->order_status)
                                                ->required(),
                                        ])
                                        ->action(function (array $data) {
                                            $this->record->update([
                                                'order_status' => $data['order_status'],
                                            ]);
                                            
                                            Notification::make()
                                                ->title('Order status updated successfully')
                                                ->success()
                                                ->send();
                                        })
                                ),
                        ]),
                        
                
                        \Filament\Infolists\Components\TextEntry::make('order_number')
                            ->label('Order Number'),
    
                        \Filament\Infolists\Components\TextEntry::make('created_at')
                            ->label('Order Date')
                            ->dateTime('F j, Y - g:i A'),
                    ]),
                
                // Column 2: Status & Payment Information
                \Filament\Infolists\Components\Section::make('Status & Payment')
                ->icon('heroicon-m-currency-dollar')
                    ->columnSpan(['default' => 1, 'md' => 1])
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('payment_method')
                            ->label('Payment Method')
                            ->formatStateUsing(fn ($state) => ucfirst($state)),
    
                        \Filament\Infolists\Components\TextEntry::make('is_paid')
                            ->label('Payment Status')
                            ->formatStateUsing(fn ($state) => $state ? 'Paid' : 'Unpaid')
                            ->badge()
                            ->color(fn ($state) => $state ? 'success' : 'danger')
                            ->suffixAction(
                                \Filament\Infolists\Components\Actions\Action::make('updatePaymentStatus')
                                    ->label('Update Payment Status')
                                    ->icon('heroicon-m-pencil-square')
                                    ->color('primary')
                                    ->form([
                                        \Filament\Forms\Components\Select::make('is_paid')
                                            ->label('Payment Status')
                                            ->options([
                                                true => 'Paid',
                                                false => 'Unpaid',
                                            ])
                                            ->default(fn () => $this->record->is_paid)
                                        ])
                                        ->action(function (array $data) {
                                            $this->record->update([
                                                'is_paid' => $data['is_paid'],
                                            ]);
                                            Notification::make()
                                            ->title('Order status updated successfully')
                                            ->success()
                                            ->send();
                                        }),
                                    ),
    
                        \Filament\Infolists\Components\TextEntry::make('amount')
                            ->label('Total Amount')
                            ->money('PHP'),
                    ]),
    
                // Column 3: Customer Information
                \Filament\Infolists\Components\Section::make('Customer Information')
                    ->columnSpan(['default' => 1, 'md' => 1])
                    ->icon('heroicon-m-user')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('user.name')
                            ->label('Customer Name'),
    
                        \Filament\Infolists\Components\TextEntry::make('user.email')
                            ->label('Email Address'),
                            
                        \Filament\Infolists\Components\TextEntry::make('user.phone')
                            ->label('Phone Number')
                            ->default('Not provided'),
                    ]),
            ]),
    
            \Filament\Infolists\Components\Section::make('Notes')
                ->collapsed()
                ->schema([
                    \Filament\Infolists\Components\TextEntry::make('notes')
                        ->default('No notes for this order'),
                ]),
            
            // Order Items Section (Full Width)
            \Filament\Infolists\Components\Section::make('Order Items')
                ->icon('heroicon-m-shopping-bag')
                ->schema([
                    \Filament\Infolists\Components\RepeatableEntry::make('items')
                        ->label('Order Items')
                        ->schema([
                            \Filament\Infolists\Components\TextEntry::make('product.name')
                                ->label('Product Name'),
    
                            \Filament\Infolists\Components\TextEntry::make('sku.sku')
                                ->label('SKU'),
            
                            \Filament\Infolists\Components\TextEntry::make('quantity')
                                ->label('Quantity'),
            
                            \Filament\Infolists\Components\TextEntry::make('price')
                                ->label('Price')
                                ->money('PHP'),
            
                            \Filament\Infolists\Components\TextEntry::make('subtotal')
                                ->label('Subtotal')
                                ->state(fn ($record) => $record->price * $record->quantity)
                                ->money('PHP'),
                        ])
                        ->columns(5),
                ]),
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