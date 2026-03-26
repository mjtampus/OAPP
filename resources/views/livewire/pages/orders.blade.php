@php
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\Storage;
@endphp

<div class="max-w-5xl mx-auto px-4 py-12">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Order History</h1>
        <p class="mt-2 text-gray-600">Track the status of your recent purchases.</p>
    </div>

    @if ($orders->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-10 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-50 mb-4">
                <svg class="h-8 w-8 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M16 11V7a4 4 0 10-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-900">No orders yet</h2>
            <p class="mt-2 text-gray-600">When you place an order, you'll be able to track it here.</p>
            <a href="{{ route('shop') }}"
               class="mt-6 inline-flex items-center px-5 py-2.5 bg-blue-600 text-white rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Browse Products
            </a>
        </div>
    @else
        <div class="space-y-6">
            @foreach ($orders as $order)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Order {{ $order->order_number }}</h2>
                            <p class="text-sm text-gray-500">Placed on {{ Carbon::parse($order->created_at)->format('F d, Y') }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1 text-sm font-medium rounded-full
                                @class([
                                    'bg-green-100 text-green-800' => $order->order_status === 'delivered',
                                    'bg-blue-100 text-blue-800' => $order->order_status === 'processing' || $order->order_status === 'shipped',
                                    'bg-yellow-100 text-yellow-800' => $order->order_status === 'pending',
                                    'bg-red-100 text-red-800' => $order->order_status === 'cancelled',
                                    'bg-gray-100 text-gray-800' => !in_array($order->order_status, ['delivered', 'processing', 'shipped', 'pending', 'cancelled']),
                                ])">
                                {{ ucfirst($order->order_status) }}
                            </span>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Total Amount</p>
                                <p class="text-lg font-semibold text-gray-900">₱{{ number_format($order->amount, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 border-t border-gray-100 pt-4 space-y-4">
                        @foreach ($order->items as $item)
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="h-14 w-14 rounded-lg bg-gray-100 overflow-hidden">
                                        @php
                                            $imagePath = $item->sku->sku_image_dir ?? null;
                                            $hasImage = $imagePath && Storage::disk('public')->exists($imagePath);
                                        @endphp

                                        @if ($hasImage)
                                            <img src="{{ Storage::url($imagePath) }}"
                                                 alt="{{ $item->product->name }}"
                                                 class="h-full w-full object-cover">
                                        @else
                                            <div class="h-full w-full flex items-center justify-center text-gray-400">
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h18v18H3z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $item->product->name }}</p>
                                        <p class="text-xs text-gray-500">Qty: {{ $item->quantity }}</p>
                                        <p class="text-xs text-gray-500">₱{{ number_format($item->price, 2) }} each</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-gray-900">₱{{ number_format($item->price * $item->quantity, 2) }}</p>
                                    <p class="text-xs text-gray-500">{{ ucfirst($order->payment_method) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    @endif
</div>
