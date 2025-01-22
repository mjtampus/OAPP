<?php

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;


Route::get('/', function () {

    $orders = Order::with('payment')->paginate(5, ['*'], 'orders_page');
    $payments = Payment::with('order')->paginate(5, ['*'], 'payments_page'); 

    return view('welcome', compact('orders', 'payments'));
})->name('home');

Route::get('payment/{id}/{gateway}',[PaymentController::class,'payment'])->name('payment');
Route::get('payment-sucess',[PaymentController::class,'paymentSuccess'])->name('payment.sucess');
Route::get('payment-cancel',[PaymentController::class,'paymentCancel'])->name('payment.cancel');