<?php

namespace App\Http\Controllers;

use App\Models\Carts;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Products;
use Stripe\StripeClient;
use App\Models\ProductsSKU;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
//========================================================== PAYMENTS ==================================================================//

public function payment($id, string $gateway)
{      
    $order = Order::with('items.product', 'items.sku')->findOrFail($id); // Load order with related items

    abort_if(
        !in_array($gateway, ['stripe', 'paymongo']) || $order->is_paid,
        400,
        'Payment Gateway Not Supported or Order is already paid'
    );

    $orderedItems = [];

    foreach ($order->items as $item) { // Loop through each order's items
        $orderedItems[] = [
            'user_id' => auth()->user()->email,
            'id' => $order->id,
            'name' => $item->product->name,
            'description' => strip_tags($item->product->description),
            'price' => $item->sku->price,
            'quantity' => $item->quantity,
            'image' => $item->sku->sku_image_dir ? Storage::url($item->sku->sku_image_dir) : null,
            'amount' => $item->sku->price * $item->quantity, // Calculate total per item
            'payment_method' => $order->payment_method,
        ];
    }

    return $gateway === 'stripe' 
        ? $this->payViaStripe($orderedItems, $gateway, $order->id) 
        : $this->payViaPaymongo($orderedItems, $gateway, $order->id);
}

//========================================================== STRIPE ==================================================================//

private function payViaStripe($orderedItems, $gateway, $orderId)
{
    $stripe = new StripeClient(env('STRIPE_SECRET'));
    $referenceNumber = Str::random(10);

    $lineItems = [];

    foreach ($orderedItems as $item) {
        $lineItems[] = [
            'price_data' => [
                'currency' => 'php',
                'product_data' => [
                    'name' => $item['name'],
                    'description' => strip_tags($item['description']),
                    'images' => $item['image'] ? [url($item['image'])] : [], // Handle missing images
                ],
                'unit_amount' => $item['price'] * 100, // Stripe requires the amount in cents
            ],
            'quantity' => $item['quantity'],
        ];
    }

    $checkout_session = $stripe->checkout->sessions->create([
        'line_items' => $lineItems,
        'mode' => 'payment',
        'success_url' => route('payment.success', ['id' => $orderId, 'gateway' => $gateway]),
        'cancel_url' => route('payment.cancel', ['gateway' => $gateway]),
        'customer_email' => auth()->user()->email, // Ensure the user is authenticated
        'metadata' => [
            'customer_name' => auth()->user()->name ?? 'Guest',
            'reference_number' => $referenceNumber,
        ],
    ]);

    session(['stripe_checkout_id' => $checkout_session->id]);

    return redirect($checkout_session->url);
}

//========================================================== PAYMONGO ==================================================================//
private function payViaPaymongo($orderedItems, $gateway, $orderId)
{
    $lineItems = [];

    foreach ($orderedItems as $item) {
        $lineItems[] = [
            "currency" => "PHP",
            "amount" => $item['price'] * 100, // Convert to cents
            "description" => strip_tags($item['description']), // Remove any HTML tags
            "name" => $item['name'],
            "quantity" => $item['quantity']
        ];
    }

    $referenceNumber = Str::random(10);

    $data = [
        "data" => [
            "attributes" => [
                "billing" => [
                    "name" => auth()->user()->name,
                    "email" => auth()->user()->email,
                    "phone" => '+639123456789' // Consider making this dynamic
                ],
                "send_email_receipt" => false,
                "show_description" => true,
                "show_line_items" => true,
                "line_items" => $lineItems,
                "payment_method_types" => [$item['payment_method'], "qrph"], // Adjust payment method dynamically if needed
                "success_url" => route('payment.success', ['id' => $orderId, 'gateway' => $gateway]),
                "cancel_url" => route('payment.cancel', ['gateway' => $gateway]),
                "reference_number" => $referenceNumber,
                "description" => "Order Payment"
            ]
        ]
    ];

    $apiKey = base64_encode(env('PAYMONGO_API_KEY'));  

    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
        'Authorization' => 'Basic ' . $apiKey,
    ])->post('https://api.paymongo.com/v1/checkout_sessions', $data);

    if ($response->successful()) {
        $checkoutUrl = $response->json()['data']['attributes']['checkout_url'];
        $sessionId = $response->json()['data']['id'];

        session(['paymongo_sessionId' => $sessionId]);

        return redirect($checkoutUrl);
    } else {
        $errorMessage = $response->json()['errors'][0]['detail'] ?? 'Payment error. Please try again.';
        return redirect()->back()->with('error', $errorMessage);
    }
}

//========================================================== SUCCESS PAYMENTS ==================================================================//

public function paymentSuccess(Request $request)
{
    $orderId = $request->query('id'); // Single order ID
    $gateway = $request->query('gateway');

    if ($gateway === 'paymongo') {
        $sessionId = session('paymongo_sessionId');
        $apiKey = base64_encode(env('PAYMONGO_API_KEY'));

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Basic ' . $apiKey,
        ])->get("https://api.paymongo.com/v1/checkout_sessions/{$sessionId}");

        if ($response->successful()) {
            // session()->forget('paymongo_sessionId');

            $billingDetails = $response->json()['data']['attributes']['billing'];
            $referenceNumber = $response->json()['data']['attributes']['reference_number'];

            $order = Order::find($orderId);

            if ($order) {
                $order->update(['is_paid' => true]);

                Payment::create([
                    'order_id' => $orderId,
                    'gateway' => $gateway,
                    'amount' => $order->amount,
                    'name' => $billingDetails['name'] ?? 'Unknown',
                    'email' => $billingDetails['email'] ?? 'No email',
                    'phone' => $billingDetails['phone'] ?? 'No phone',
                    'reference_number' => $referenceNumber,
                ]);
            }

            return redirect()->route('order.success');
        }
    } 
    elseif ($gateway === 'stripe') {
        $sessionId = session('stripe_checkout_id');
        $stripe = new StripeClient(env('STRIPE_SECRET'));

        $response = $stripe->checkout->sessions->retrieve($sessionId);
        $responseData = $response->toArray(); 

        // session()->forget('stripe_checkout_id');

        $order = Order::find($orderId);

        if ($order) {
            $order->update(['is_paid' => true]);

            Payment::create([
                'order_id' => $orderId,
                'gateway' => $gateway,
                'amount' => $responseData['amount_total'] / 100,
                'name' => $responseData['customer_details']['name'] ?? 'Unknown',
                'email' => $responseData['customer_email'] ?? 'No email',
                'phone' => $responseData['customer_details']['phone'] ?? 'No phone',
                'reference_number' => $responseData['metadata']['reference_number'] ?? Str::random(10),
            ]);
        }

        return redirect()->route('order.success');
    } 

    return redirect()->route('home')->with('error', 'Payment Gateway Not Supported');
}



public function paymentCancel(Request $request)
{
    $gateway = $request->query('gateway');

    if ($gateway === "paymongo") {
        $sessionId = session('paymongo_sessionId');

        $apiKey = base64_encode( env('PAYMONGO_API_KEY'));

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Basic ' . $apiKey,
        ])->post("https://api.paymongo.com/v1/checkout_sessions/{$sessionId}/expire");

        if ($response->successful()) {

            session()->forget('paymongo_sessionId');

        }

    }elseif($gateway === 'stripe'){

        $sessionId = session('stripe_checkout_id');
        $stripe = new StripeClient(env('STRIPE_SECRET'));
    
        $response = $stripe->checkout->sessions->expire($sessionId);

        $responseData = $response->toArray(); 
        session()->forget('paymongo_sessionId');
    }

    else{
       return redirect()->route('cart');
    }
    return redirect()->route('cart');
    }
}
