@extends('layouts.penjual')

@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Order Details - #{{ $order->id }}</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('penjual.dashboard') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <a href="{{ route('penjual.orders') }}">
                            <div class="text-tiny">Orders</div>
                        </a>
                    </li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Order #{{ $order->id }}</div>
                    </li>
                </ul>
            </div>

            <!-- Ordered Items -->
            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <h5>Ordered Items</h5>
                    </div>
                    <a class="tf-button style-1 w208" href="{{ route('penjual.orders') }}">Back</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th class="text-center">Price</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">SKU</th>
                                <th class="text-center">Category</th>
                                <th class="text-center">Brand</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td class="pname">
                                        <div class="image">
                                            <img src="{{ asset('uploads/products/' . $item->product->image) }}"
                                                alt="{{ $item->product->name }}" class="image">
                                        </div>
                                        <div class="name">
                                            <span class="body-title-2">{{ $item->product->name }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-center">{{ $item->product->sku ?? '-' }}</td>
                                    <td class="text-center">{{ $item->product->category->name ?? '-' }}</td>
                                    <td class="text-center">{{ $item->product->brand->name ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="wg-box mt-5">
                <h5>Shipping Address</h5>
                <div class="my-account__address-item col-md-6">
                    <div class="my-account__address-item__detail">
                        <p>{{ $order->user->name }}</p>
                        <p>{{ $order->user->address ?? '-' }}</p>
                        <p>Mobile : {{ $order->user->phone ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Transactions + Update Status -->
            <div class="wg-box mt-5">
                <h5>Transactions</h5>
                <form action="{{ route('penjual.orders.status', $order->id) }}" method="POST">
                    @csrf
                    <table class="table table-striped table-bordered table-transaction">
                        <tbody>
                            <tr>
                                <th>Subtotal</th>
                                <td>Rp{{ number_format($order->items->sum(fn($i) => $i->price * $i->quantity), 0, ',', '.') }}</td>
                                <th>Tax</th>
                                <td>Rp{{ number_format($order->tax ?? 0, 0, ',', '.') }}</td>
                                <th>Discount</th>
                                <td>Rp{{ number_format($order->discount ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Total</th>
                                <td>Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <th>Payment Mode</th>
                                <td>{{ $order->payment_mode ?? 'COD' }}</td>
                                <th>Status</th>
                                <td>
                                    <select name="status" class="form-select">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="canceled" {{ $order->status == 'canceled' ? 'selected' : '' }}>Canceled</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Order Date</th>
                                <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                <th>Delivered Date</th>
                                <td>{{ $order->delivered_at ?? '-' }}</td>
                                <th>Canceled Date</th>
                                <td>{{ $order->canceled_at ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="mt-3">
                        <button type="submit" class="tf-button style-1">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
