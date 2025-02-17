<?php

use App\Models\Order;
use App\Models\Payment;
use App\Livewire\Pages\Cart;
use App\Livewire\Pages\Shop;
use App\Livewire\Pages\Login;
use App\Livewire\Pages\HomePage;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Livewire\Components\ProductDetails;


// Route::get('/', function () {

//     $orders = Order::with('payment')->paginate(5, ['*'], 'orders_page');
//     $payments = Payment::with('order')->paginate(5, ['*'], 'payments_page'); 

//     return view('welcome', compact('orders', 'payments'));

    
// })->name('home');

Route::get('/',HomePage::class)->lazy();
Route::get('/shop',Shop::class)->lazy()->name('shop');
Route::get('/cart',Cart::class)->lazy();
Route::get('/login',Login::class)->lazy()->name('login');
Route::get('/product/{productId}', ProductDetails::class)->name('product.details');


Route::get('payment/{id}/{gateway}',[PaymentController::class,'payment'])->name('payment');
Route::get('payment-sucess',[PaymentController::class,'paymentSuccess'])->name('payment.sucess');
Route::get('payment-cancel',[PaymentController::class,'paymentCancel'])->name('payment.cancel');