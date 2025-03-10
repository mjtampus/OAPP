<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Models\Order;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\OrderResource\Pages\ViewOrder;
use Filament\Forms\Components\Tabs;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function getTabs(): array
    {
        return [
            'All' => Tab::make(),
            'Pending' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('order_status', 'pending'))
            ->icon('heroicon-m-exclamation-circle')
            ->badge(fn () => Order::where('order_status', 'pending')->count())
            ->badgeColor('warning'), 
            'Processing' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('order_status', 'processing'))
            ->icon('heroicon-m-clock')
            ->badge(fn () => Order::where('order_status', 'processing')->count())
            ->badgeColor('warning'), 
            'Shipped' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('order_status', 'shipped'))
            ->icon('heroicon-m-truck')
            ->badge(fn () => Order::where('order_status', 'shipped')->count())
            ->badgeColor('info'), 
            'Delivered' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('order_status', 'delivered'))
            ->icon('heroicon-m-check-circle')
            ->badge(fn () => Order::where('order_status', 'delivered')->count())
            ->badgeColor('success'), 
            'Cancelled' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('order_status', 'cancelled'))
            ->icon('heroicon-m-x-circle')
            ->badge(fn () => Order::where('order_status', 'cancelled')->count())
            ->badgeColor('danger'), 
        ];
    }

}
