@extends('layouts.app')

@section('content')
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="shop-checkout container">
            <h2 class="page-title">Shipping and Checkout</h2>

            <div class="checkout-steps">
                <a href="{{ route('cart.index') }}" class="checkout-steps__item active">
                    <span class="checkout-steps__item-number">01</span>
                    <span class="checkout-steps__item-title">
                        <span>Shopping Bag</span>
                        <em>Manage Your Items List</em>
                    </span>
                </a>
                <a href="javascript:void(0);" class="checkout-steps__item active">
                    <span class="checkout-steps__item-number">02</span>
                    <span class="checkout-steps__item-title">
                        <span>Shipping and Checkout</span>
                        <em>Checkout Your Items List</em>
                    </span>
                </a>
                <a href="javascript:void(0);" class="checkout-steps__item">
                    <span class="checkout-steps__item-number">03</span>
                    <span class="checkout-steps__item-title">
                        <span>Confirmation</span>
                        <em>Review And Submit Your Order</em>
                    </span>
                </a>
            </div>

            <div class="row justify-content-center mt-5">
                <div class="col-md-8 text-center">
                    <h4 class="mb-3">Total Pembayaran:
                        <strong>Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</strong>
                    </h4>
                    <p>Silakan lanjutkan pembayaran melalui metode yang tersedia.</p>
                    <button id="pay-button" class="btn btn-primary mt-3">Bayar Sekarang</button>
                </div>
            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <!-- Midtrans Snap JS -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <script>
        document.getElementById('pay-button').addEventListener('click', function() {
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    window.location.href = "/checkout/success/" + result.order_id;
                },
                onPending: function(result) {
                    window.location.href = "/checkout/success/" + result.order_id;
                },
                onError: function(result) {
                    window.location.href = "{{ route('checkout.failed') }}";
                },
                onClose: function() {
                    alert('Transaksi dibatalkan oleh pengguna.');
                }
            });
        });
    </script>
@endpush
