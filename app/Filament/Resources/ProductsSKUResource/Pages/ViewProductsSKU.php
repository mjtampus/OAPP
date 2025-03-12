<?php

namespace App\Filament\Resources\ProductsSKUResource\Pages;

use App\Models\User;
use Filament\Actions;
use App\Models\ProductsSKU;
use Filament\Infolists\Infolist;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\FontWeight;
use Filament\Infolists\Components\Grid;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductsAttributesValues;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Components\FileUpload;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use App\Filament\Resources\ProductsSKUResource;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\TextEntry\TextEntrySize;

class ViewProductsSKU extends ViewRecord
{
    protected static string $resource = ProductsSKUResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Product Information')
                    ->schema([
                        TextEntry::make('product.name')
                            ->label('Product')
                            ->size(TextEntrySize::Large)
                            ->weight(FontWeight::Bold)
                            ->columnSpan(2),
                        TextEntry::make('product.description')
                            ->label('Description')
                            ->columnSpan(2)
                            ->visible(fn($record) => !empty($record->product->description)),
                    ])
                    ->columns(2)
                    ->collapsible(false),
                    
                Section::make('Product Variants')
                    ->description('Manage variant details including SKU, attributes, pricing, and stock')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('sku')
                                    ->label('SKU')
                                    ->badge()
                                    ->color('primary'),
                                TextEntry::make('attributes')
                                    ->label('Variation')
                                    ->getStateUsing(function ($record) {
                                        if (empty($record->attributes)) {
                                            return 'No Variations';
                                        }
                                        
                                        $attributes = is_string($record->attributes)
                                            ? json_decode($record->attributes, true)
                                            : $record->attributes;

                                        $ids = array_filter(array_map(fn($arr) => is_array($arr) ? ($arr[1] ?? null) : null, $attributes));
                                        
                                        if (empty($ids)) {
                                            return 'No Attributes Found';
                                        }
                                        
                                        $values = ProductsAttributesValues::whereIn('id', $ids)->pluck('value')->toArray();
                                        
                                        return implode(' | ', $values);
                                    }),
                            ]),
                        
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('price')
                                    ->label('Price')
                                    ->money('PHP')
                                    ->color('success')
                                    ->prefixAction(
                                        Action::make('editPrice')
                                            ->tooltip('Update Price')
                                            ->icon('heroicon-m-pencil-square')
                                            ->requiresConfirmation()
                                            ->modalHeading('Update Price')
                                            ->modalDescription('Enter the new price for this variant')
                                            ->form(function (Model $record) {
                                                return [
                                                    TextInput::make('price')
                                                        ->label('Current price')
                                                        ->numeric()
                                                        ->default($record->price)
                                                        ->required(),
                                                ];
                                            })
                                            ->action(fn(array $data, Model $record) => $record->update(['price' => $data['price']]))
                                    ),                                
                                    TextEntry::make('stock')
                                    ->label('Stock')
                                    ->badge()
                                    ->color(fn($state) => $state <= 0 ? 'danger' : ($state < 10 ? 'warning' : 'success'))
                                    ->prefixAction(
                                        Action::make('updateStock')
                                            ->icon('heroicon-m-pencil-square')
                                            ->tooltip('Update Stock')
                                            ->modalHeading('Update Stock')
                                            ->extraAttributes(['class' => 'justify-start'])
                                            ->form(function (Model $record) {
                                                return [
                                                    TextInput::make('stock')
                                                        ->label('Current Stock')
                                                        ->numeric()
                                                        ->default($record->stock)
                                                        ->required(),
                                                ];
                                            })
                                            ->action(fn(array $data, Model $record) => $record->update(['stock' => $data['stock']]))
                                    ),
                            ]),
                        
                            ImageEntry::make('sku_image_dir')
                            ->label('SKU Image')
                            ->size(250)
                            ->width(500)
                            ->extraImgAttributes(['class' => 'rounded-lg border border-gray-200'])
                            ->hintAction(
                                Action::make('updateImage')
                                    ->icon('heroicon-m-pencil-square')
                                    ->tooltip('Update SKU Image')
                                    ->modalHeading('Update SKU Image')
                                    ->form(fn(Model $record) => [
                                        FileUpload::make('sku_image_dir')
                                            ->label('Upload New Image')
                                            ->disk('public')
                                            ->directory('sku_images')
                                            ->image()
                                            ->previewable(true)
                                            ->required(),
                                    ])
                                    ->action(fn(array $data, Model $record) => $record->update(['sku_image_dir' => $data['sku_image_dir']]))
                            ),                        
                    ])
                    ->collapsible(),
                    
                Section::make('Inventory History')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Created')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime(),
                        TextEntry::make('last_stock_update')
                            ->label('Last Stock Movement')
                            ->dateTime()
                            ->visible(fn($record) => !empty($record->last_stock_update)),
                    ])
                    ->collapsed()
                    ->columns(2),
            ]);
    }
}
