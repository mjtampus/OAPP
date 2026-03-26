<?php

namespace App\Livewire\Pages;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Orders extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public function render()
    {
        $orders = Order::with(['items.product', 'items.sku'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('livewire.pages.orders', [
            'orders' => $orders,
        ])->layout('components.layouts.app');
    }
}
