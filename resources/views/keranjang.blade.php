@extends('layouts.app')
@section('content')
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="shop-checkout container">
            <h2 class="page-title">Cart</h2>
            <div class="checkout-steps">
                <a href="javascript:void(0)" class="checkout-steps__item active">
                    <span class="checkout-steps__item-number">01</span>
                    <span class="checkout-steps__item-title">
                        <span>Shopping Bag</span>
                        <em>Manage Your Items List</em>
                    </span>
                </a>
                <a href="javascript:void(0)" class="checkout-steps__item">
                    <span class="checkout-steps__item-number">02</span>
                    <span class="checkout-steps__item-title">
                        <span>Shipping and Checkout</span>
                        <em>Checkout Your Items List</em>
                    </span>
                </a>
                <a href="javascript:void(0)" class="checkout-steps__item">
                    <span class="checkout-steps__item-number">03</span>
                    <span class="checkout-steps__item-title">
                        <span>Confirmation</span>
                        <em>Review And Submit Your Order</em>
                    </span>
                </a>
            </div>
            <div class="shopping-cart">
                @if ($items->count() > 0)
                    <div class="cart-table__wrapper">
                        <table class="cart-table">
                            <thead>
                                <tr>
                                    <th style="width: 40px;"></th>
                                    <th>Product</th>
                                    <th></th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $grandSubtotal = 0;
                                    $grandTax = 0;
                                @endphp
                                @foreach ($items as $item)
                                    @php
                                        $price = $item->product->sale_price ?? $item->product->regular_price;
                                        $tax = (($price * $item->product->tax_percent) / 100) * $item->quantity;
                                        $subtotal = $price * $item->quantity + $tax;
                                        $grandSubtotal += $price * $item->quantity;
                                        $grandTax += $tax;
                                    @endphp
                                    <tr>
                                        <td class="text-center" style="width: 40px;">
                                            <input type="checkbox" class="item-check" data-id="{{ $item->id }}"
                                                data-price="{{ $price }}"
                                                data-tax-percent="{{ $item->product->tax_percent }}"
                                                data-qty="{{ $item->quantity }}" checked>
                                        </td>
                                        <td>
                                            <div class="shopping-cart__product-item">
                                                <img loading="lazy"
                                                    src="{{ asset('uploads/products/thumbnails') }}/{{ $item->product->image }}"
                                                    width="120" height="120" alt="{{ $item->product->name }}" />
                                            </div>
                                        </td>
                                        <td>
                                            <div class="shopping-cart__product-item__detail">
                                                <h4>{{ $item->product->name }}</h4>
                                                <ul class="shopping-cart__product-item__options">
                                                    <li>Kode: {{ $item->product->SKU }}</li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td>
                                            <span
                                                class="shopping-cart__product-price">Rp{{ number_format($price, 0, ',', '.') }}</span>

                                        </td>
                                        <td>
                                            <div class="qty-control position-relative" data-id="{{ $item->id }}">
                                                <div
                                                    class="qty-control__reduce btn-change-qty {{ $item->quantity == 1 ? 'disabled' : '' }}">
                                                    -</div>
                                                <input type="number" class="cart-qty-input qty-control__number text-center"
                                                    value="{{ $item->quantity }}" readonly>
                                                <div class="qty-control__increase btn-change-qty">+</div>
                                            </div>
                                        </td>

                                        <td>
                                            <span class="shopping-cart__subtotal" id="subtotal-{{ $item->id }}">
                                                Rp{{ number_format($subtotal, 0, ',', '.') }}
                                            </span>
                                        </td>

                                        <td>
                                            <form method="POST" action="{{ route('cart.remove', $item->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="remove-cart"
                                                    style="background: none; border: none;">
                                                    <svg width="10" height="10" viewBox="0 0 10 10" fill="#767676"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M0.259435 8.85506L9.11449 0L10 0.885506L1.14494 9.74056L0.259435 8.85506Z" />
                                                        <path
                                                            d="M0.885506 0.0889838L9.74057 8.94404L8.85506 9.82955L0 0.97449L0.885506 0.0889838Z" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="shopping-cart__totals-wrapper mt-4">
                        <div class="sticky-content">
                            <div class="shopping-cart__totals">
                                <h3>Total Keranjang</h3>
                                <table class="cart-totals">
                                    <tbody>
                                        <tr>
                                            <th>Subtotal</th>
                                            <td id="cart-subtotal">Rp{{ number_format($grandSubtotal, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Pajak</th>
                                            <td id="cart-tax">Rp{{ number_format($grandTax, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Total</th>
                                            <td id="cart-total">
                                                Rp{{ number_format($grandSubtotal + $grandTax, 0, ',', '.') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <a href="#" class="btn btn-primary btn-checkout mt-3">PROCEED TO CHECKOUT</a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-md-12 text-center pt-5 bp-5">
                            <p>No item found in your cart</p>
                            <a href="{{ route('shop.index') }}" class="btn btn-info">Shop Now</a>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </main>
@endsection
@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                $(document).off('click.qtyUpdate').on('click.qtyUpdate', '.btn-change-qty', function(e) {
                    e.preventDefault();

                    const wrapper = $(this).closest('.qty-control');
                    const input = wrapper.find('.cart-qty-input');
                    const itemId = wrapper.data('id');

                    let currentQty = parseInt(input.attr('value')); // Gunakan attr bukan val()

                    if ($(this).hasClass('qty-control__increase')) {
                        currentQty += 1;
                    } else if ($(this).hasClass('qty-control__reduce') && currentQty > 1) {
                        currentQty -= 1;
                    }

                    console.log('Click: item', itemId, 'qty:', currentQty);

                    // Update tampilan input
                    input.val(currentQty);
                    input.attr('value', currentQty); // pastikan value attr juga ikut berubah

                    // Kirim update ke server
                    $.ajax({
                        url: '/cart/update/' + itemId,
                        method: 'POST',
                        data: {
                            quantity: currentQty,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(res) {
                            $('#subtotal-' + itemId).text('Rp' + res.subtotal);
                            $('#cart-subtotal').text('Rp' + res.grandSubtotal);
                            $('#cart-tax').text('Rp' + res.grandTax);
                            $('#cart-total').text('Rp' + res.grandTotal);
                        },
                        error: function() {
                            alert('Gagal memperbarui jumlah barang.');
                        }
                    });
                });
            });

            $(document).on('change', '.item-check', function() {
                updateTotals();
            });

            function updateTotals() {
                let subtotal = 0;
                let tax = 0;

                $('.item-check:checked').each(function() {
                    const price = parseFloat($(this).data('price'));
                    const qty = parseInt($(this).closest('tr').find('.cart-qty-input').val());
                    const taxPercent = parseFloat($(this).data('tax-percent'));

                    subtotal += price * qty;
                    tax += (price * taxPercent / 100) * qty;
                });

                $('#cart-subtotal').text('Rp' + subtotal.toLocaleString('id-ID'));
                $('#cart-tax').text('Rp' + tax.toLocaleString('id-ID'));
                $('#cart-total').text('Rp' + (subtotal + tax).toLocaleString('id-ID'));
            }

            updateTotals();
        </script>
    @endpush
@endonce
