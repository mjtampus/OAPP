<?php

namespace App\Livewire\Components;

use App\Models\Carts;
use App\Models\Order;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ShopHeaderNavigation extends Component
{
    public $cartCount = 0;
    public $orderNotifications = [];
    public $notificationBadge = 0;

    protected ?int $authId = null;

    protected $listeners = [
        'cart-updated' => 'updateCartCount',
        'logout',
        'order-status-updated' => 'loadNotifications',
    ];

    public function mount()
    {
        $this->authId = Auth::id();
        $this->updateCartCount();
        $this->loadNotifications();
    }

    public function getListeners()
    {
        $listeners = $this->listeners;

        if ($this->authId) {
            $listeners["echo-private:App.Models.User.{$this->authId},OrderStatusUpdated"] = 'handleOrderStatusUpdated';
        }

        return $listeners;
    }

    public function hydrate()
    {
        $this->authId = Auth::id();
        $this->loadNotifications();
    }

    public function updateCartCount()
    {
        if (!Auth::check()) {
<<<<<<< Updated upstream
            $cart = Session::get('cart', []); // Default to an empty array if null
            $this->cartCount = is_array($cart) ? count($cart) : 0; // Ensure count() is used on an array 
        }else{
=======
            $cart = Session::get('cart', []);
            $this->cartCount = is_array($cart) ? count($cart) : 0;
        } else {
>>>>>>> Stashed changes
            $this->cartCount = Carts::where('user_id', Auth::id())->count();
        }
    }

    public function loadNotifications()
    {
        if (!Auth::check()) {
            $this->orderNotifications = [];
            $this->notificationBadge = 0;

            return;
        }

        $orders = Order::query()
            ->where('user_id', Auth::id())
            ->latest('updated_at')
            ->take(5)
            ->get(['id', 'order_number', 'order_status', 'updated_at']);

        $this->orderNotifications = $orders->map(function ($order) {
            return [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->order_status,
                'updated_at' => $order->updated_at,
                'is_pending' => in_array($order->order_status, ['pending', 'processing', 'shipped', 'in_transit']),
            ];
        })->toArray();

        $this->notificationBadge = collect($this->orderNotifications)
            ->where('is_pending', true)
            ->count();
    }

    public function handleOrderStatusUpdated(array $payload): void
    {
        $this->loadNotifications();
        $this->dispatch('order-status-updated');

        $statusLabel = ucfirst(str_replace('_', ' ', $payload['status'] ?? ''));

        $this->dispatch('notify', [
            'message' => "Order {$payload['order_number']} status updated to {$statusLabel}.",
            'type' => 'info',
            'duration' => 4000,
        ]);
    }

    public function logout($comfirmed = false)
    {
        if (!$comfirmed) {
            $this->dispatch('openModal', 'Are you sure you want to logout?', 'logout', true);
            return;
        }
        Auth::logout();

        return redirect(route('login'));
    }
    public function render()
    {
        return view('livewire.components.shop-header-navigation');
    }
}
