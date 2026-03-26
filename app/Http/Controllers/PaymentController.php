<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Stripe\StripeClient;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
//========================================================== PAYMENTS ==================================================================//

<<<<<<< Updated upstream
    public function payment($id , string $gateway)
    {      
        $order = Order::findorFail($id);

        abort_if(
            ! in_array($gateway, ['stripe', 'paymongo']) || $order->is_paid,
            400,
            'Payment Gateway Not Supported or Order is paid'
        );
=======
public function payment($id, string $gateway)
{
    $order = Order::with('items.product', 'items.sku')->findOrFail($id); // Load order with related items
    $gateway = strtolower($gateway);

    abort_if(
        !in_array($gateway, ['stripe', 'paymongo', 'cod']) || $order->is_paid,
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

    return match ($gateway) {
        'stripe' => $this->payViaStripe($orderedItems, $gateway, $order->id),
        'paymongo' => $this->payViaPaymongo($orderedItems, $gateway, $order->id),
        'cod' => $this->payViaCod($order),
    };
}
>>>>>>> Stashed changes

        return $gateway === 'stripe' ? $this->payViaStripe($order , $gateway) : $this->payViaPaymongo($order, $gateway);
    }

    //========================================================== STRIPE ==================================================================//

private function payViaStripe($order , $gateway)
{
    $stripe = new StripeClient(env('STRIPE_SECRET'));
    $referenceNumber = Str::random(10);

    $lineItems = 

    [[
        'price_data' => [
            'currency' => 'php',
            'product_data' => [
                'name' => $order->order_name,
                'description' => $order->order_description,
            ],
            'unit_amount' => $order->amount * 100,
        ],
        'quantity' => $order->quantity,
    ]];

    $checkout_session = $stripe->checkout->sessions->create([
        'line_items' => $lineItems,
        'mode' => 'payment',
        'success_url' => route ('payment.sucess',['id' => $order->id, 'gateway' => $gateway]),
        'cancel_url' => route ('payment.cancel',['gateway' => $gateway]),
        'customer_email' =>'vU3e7@example.com',
        'metadata' => [ 
            'customer_name' => 'michaeltampus',
            'reference_number' => $referenceNumber,
        ],
    ]);

    session(['stripe_checkout_id' => $checkout_session->id]);

    // Redirect to the Stripe Checkout page
    return redirect($checkout_session->url);
}

//========================================================== PAYMONGO ==================================================================//
    private function payViaPaymongo($order , $gateway)
    {
        $lineItems = [
            [
                "currency" => "PHP",
                "amount" => $order->amount * 100, //Needed to be in cents paymongo supports only in cents
                "description" => $order->order_description,
                "name" => $order->order_name,
                "quantity" => $order->quantity
            ],
        ];
        $referenceNumber = Str::random(10);
        $data = [
            "data" => [
                "attributes" => [
                    "billing" => [
                        "name" => 'Michael Tampus',
                        "email" => 'vU3e7@example.com',
                        "phone" => '+639123456789'
                    ],
                    "send_email_receipt" => false,
                    "show_description" => true,
                    "show_line_items" => true,
                    "line_items" => $lineItems,
                    "payment_method_types" => ["card", "gcash", "paymaya", "qrph"],
                    "success_url" => route ('payment.sucess',['id' => $order->id, 'gateway' => $gateway]),
                    "cancel_url" => route ('payment.cancel',['gateway' => $gateway]),
                    "reference_number" => $referenceNumber,
                    "description" => "testing"
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

<<<<<<< Updated upstream
            $checkoutUrl = $response->json()['data']['attributes']['checkout_url'];
            $sessionId = $response->json()['data']['id'];
=======
    $apiKey = base64_encode(env('PAYMONGO_API_KEY'));
>>>>>>> Stashed changes

            session(['paymongo_sessionId' => $sessionId]);

            return redirect($checkoutUrl);

        } else {
    $errorMessage = $response->json()['errors'];

    return redirect()->back()->with('error', $errorMessage);

<<<<<<< Updated upstream
            }
    }
    
=======
private function payViaCod(Order $order)
{
    session(['cod_success' => true]);

    $order->update([
        'is_paid' => false,
        'order_status' => 'pending',
    ]);

    return redirect()->route('order.success');
}

>>>>>>> Stashed changes
//========================================================== SUCCESS PAYMENTS ==================================================================//

    public function paymentSuccess(Request $request)
    {
        $orderId = $request->query('id');
        $gateway = $request->query('gateway');
    
        $order = Order::findOrFail($orderId);
        $order->update([
            'is_paid' => true
    ]);

    if ($gateway === 'paymongo') {
        $sessionId = session('paymongo_sessionId');

        $apiKey = base64_encode( env('PAYMONGO_API_KEY'));

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Basic ' . $apiKey,
        ])->get("https://api.paymongo.com/v1/checkout_sessions/{$sessionId}");

        if ($response->successful()) {

            session()->forget('paymongo_sessionId');

            $billingDetails = $response->json()['data']['attributes']['billing'];
            $referenceNumber = $response->json()['data']['attributes']['reference_number'];
            $lineItems = $response->json()['data']['attributes']['line_items'];

            $payment = Payment::class::create([
                'order_id' => $orderId,
                'gateway' => $gateway,
                'amount' => $lineItems[0]['amount'] * $lineItems[0]['quantity'] / 100,                
                'name' => $billingDetails['name'],
                'email' => $billingDetails['email'],
                'phone' => $billingDetails['phone'],
                'reference_number' => $referenceNumber,
            ]);
        }

    }elseif($gateway === 'stripe') {

<<<<<<< Updated upstream
=======
            return redirect()->route('order.success');
        }
    }
    elseif ($gateway === 'stripe') {
>>>>>>> Stashed changes
        $sessionId = session('stripe_checkout_id');
        $stripe = new StripeClient(env('STRIPE_SECRET'));
    
        $response = $stripe->checkout->sessions->retrieve($sessionId);
<<<<<<< Updated upstream
=======
        $responseData = $response->toArray();

        // session()->forget('stripe_checkout_id');
>>>>>>> Stashed changes

        $responseData = $response->toArray(); 

<<<<<<< Updated upstream
        session()->forget('stripe_checkout_id');
=======
        return redirect()->route('order.success');
    }
>>>>>>> Stashed changes

        Payment::create([
            'order_id' => $orderId,
            'gateway' => $gateway,
            'amount' => $responseData['amount_total'] / 100,
            'name' => $responseData['customer_details']['name'],
            'email' => $responseData['customer_email'],
            'phone' => $responseData['customer_details']['phone'] ?? '',
            'reference_number' => $responseData['metadata']['reference_number'],
        ]);
    
    
    }else{
        return redirect()->route('home')->witherror('Payment Gateway Not Supported');
    }
    return redirect()->route('home');
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
    }

    else{
       return redirect()->route('home');
    }
    return redirect()->route('home');
}


}
