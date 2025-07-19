@extends('layouts.app')
@section('content')
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="shop-checkout container">
            <h2 class="page-title">Order Received</h2>
            <div class="checkout-steps">
                <a href="#" class="checkout-steps__item active">
                    <span class="checkout-steps__item-number">01</span>
                    <span class="checkout-steps__item-title">
                        <span>Shopping Bag</span>
                        <em>Manage Your Items List</em>
                    </span>
                </a>
                <a href="#" class="checkout-steps__item active">
                    <span class="checkout-steps__item-number">02</span>
                    <span class="checkout-steps__item-title">
                        <span>Shipping and Checkout</span>
                        <em>Checkout Your Items List</em>
                    </span>
                </a>
                <a href="#" class="checkout-steps__item active">
                    <span class="checkout-steps__item-number">03</span>
                    <span class="checkout-steps__item-title">
                        <span>Confirmation</span>
                        <em>Review And Submit Your Order</em>
                    </span>
                </a>
            </div>

            <div class="order-complete">
                <div class="order-complete__message">
                    <svg width="80" height="80" viewBox="0 0 80 80" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <circle cx="40" cy="40" r="40" fill="#B9A16B" />
                        <path d="M52.9743 35.7612C52.9743..." fill="white" />
                    </svg>
                    <h3>Your order is completed!</h3>
                    <p>Thank you. Your order has been received.</p>
                </div>

                <div class="order-info">
                    <div class="order-info__item">
                        <label>Order Number</label>
                        <span>{{ $transaction->order_id }}</span>
                    </div>
                    <div class="order-info__item">
                        <label>Date</label>
                        <span>{{ $transaction->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="order-info__item">
                        <label>Total</label>
                        <span>Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="order-info__item">
                        <label>Payment Method</label>
                        <span>Midtrans ({{ $transaction->payment_type ?? 'N/A' }})</span>
                    </div>
                </div>

                <div class="checkout__totals-wrapper">
                    <div class="checkout__totals">
                        <h3>Order Details</h3>
                        <table class="checkout-cart-items" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th style="text-align: left;">PRODUCT</th>
                                    <th style="text-align: right; width: 150px;">SUBTOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaction->items as $item)
                                    <tr>
                                        <td>
                                            {{ $item->product_name }} x {{ $item->quantity }}
                                        </td>
                                        <td style="text-align: right;">
                                            Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>


                        <table class="checkout-totals">
                            <tbody>
                                <tr>
                                    <th>TAX</th>
                                    <td>
                                        Rp{{ number_format($transaction->items->sum('tax_amount'), 0, ',', '.') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>TOTAL</th>
                                    <td>Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
