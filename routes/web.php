<?php

use App\Models\Order;
use App\Models\Payment;
use App\Livewire\Pages\Cart;
use App\Livewire\Pages\Shop;
use App\Livewire\Pages\Login;
use App\Livewire\Pages\Checkout;
use App\Livewire\Pages\HomePage;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Livewire\Components\ProductDetails;
<<<<<<< Updated upstream

=======
use App\Livewire\Auth\Payments\PaymentSuccess;
use App\Livewire\Pages\Orders as OrdersPage;
>>>>>>> Stashed changes

// Route::get('/', function () {

//     $orders = Order::with('payment')->paginate(5, ['*'], 'orders_page');
<<<<<<< Updated upstream
//     $payments = Payment::with('order')->paginate(5, ['*'], 'payments_page'); 

=======
//     $payments = Payment::with('order')->paginate(5, ['*'], 'payments_page');
>>>>>>> Stashed changes
//     return view('welcome', compact('orders', 'payments'));

    
// })->name('home');

Route::get('/',HomePage::class)->lazy()->name('home');
Route::get('/shop',Shop::class)->lazy()->name('shop');
Route::get('/cart',Cart::class)->lazy();
Route::get('/login',Login::class)->lazy()->name('login');
<<<<<<< Updated upstream
Route::get('/checkout' ,Checkout::class)->name('checkout')->middleware('auth'); 
Route::get('/product/{productId}', ProductDetails::class)->name('product.details');
=======
Route::get('/checkout' ,Checkout::class)->name('checkout')->middleware('auth');
Route::get('/orders', OrdersPage::class)->name('orders.index')->middleware('auth');
Route::get('/product/{product:slug}', ProductDetails::class)->name('product.details');
>>>>>>> Stashed changes


<<<<<<< Updated upstream
Route::get('payment/{id}/{gateway}',[PaymentController::class,'payment'])->name('payment');
Route::get('payment-sucess',[PaymentController::class,'paymentSuccess'])->name('payment.sucess');
Route::get('payment-cancel',[PaymentController::class,'paymentCancel'])->name('payment.cancel');
=======
Route::get('/test', function () {
    return view('test');
});
>>>>>>> Stashed changes
