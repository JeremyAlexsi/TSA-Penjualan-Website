@extends('layouts.penjual')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <h3 class="mb-4">Edit Harga Produk</h3>

        <form action="{{ route('penjual.products.updatePrice', $product->id) }}" method="POST">
            @csrf

            <div class="wg-box">
                {{-- Nama Produk --}}
                <div class="mb-4">
                    <label class="form-label">Nama Produk</label>
                    <input type="text" class="form-control" value="{{ $product->name }}" readonly>
                </div>

                {{-- Slug --}}
                <div class="mb-4">
                    <label class="form-label">Slug</label>
                    <input type="text" class="form-control" value="{{ $product->slug }}" readonly>
                </div>

                {{-- Kategori & Brand --}}
                <div class="cols gap20 mb-4">
                    <div class="w-full">
                        <label class="form-label">Kategori</label>
                        <input type="text" class="form-control" value="{{ $product->category->name }}" readonly>
                    </div>
                    <div class="w-full">
                        <label class="form-label">Brand</label>
                        <input type="text" class="form-control" value="{{ $product->brand->name }}" readonly>
                    </div>
                </div>

                {{-- Short Description --}}
                <div class="mb-4">
                    <label class="form-label">Short Description</label>
                    <textarea class="form-control" rows="3" readonly>{{ $product->short_description }}</textarea>
                </div>

                {{-- Description --}}
                <div class="mb-4">
                    <label class="form-label">Deskripsi</label>
                    <textarea class="form-control" rows="4" readonly>{{ $product->description }}</textarea>
                </div>

                {{-- SKU & Quantity --}}
                <div class="cols gap20 mb-4">
                    <div class="w-full">
                        <label class="form-label">SKU</label>
                        <input type="text" class="form-control" value="{{ $product->SKU }}" readonly>
                    </div>
                    <div class="w-full">
                        <label class="form-label">Quantity</label>
                        <input type="text" class="form-control" value="{{ $product->quantity }}" readonly>
                    </div>
                </div>

                {{-- Stock & Featured --}}
                <div class="cols gap20 mb-4">
                    <div class="w-full">
                        <label class="form-label">Stock</label>
                        <input type="text" class="form-control" value="{{ $product->stock_status }}" readonly>
                    </div>
                    <div class="w-full">
                        <label class="form-label">Featured</label>
                        <input type="text" class="form-control" value="{{ $product->feature == 1 ? 'Yes' : 'No' }}" readonly>
                    </div>
                </div>

                {{-- Harga (bisa diubah) --}}
                <div class="cols gap20 mb-4">
                    <div class="w-full">
                        <label class="form-label">Regular Price</label>
                        <input type="number" step="0.01" name="regular_price" class="form-control"
                            value="{{ $product->regular_price }}" required>
                    </div>
                    <div class="w-full">
                        <label class="form-label">Sale Price</label>
                        <input type="number" step="0.01" name="sale_price" class="form-control"
                            value="{{ $product->sale_price }}" required>
                    </div>
                </div>

                {{-- Tombol Update --}}
                <button type="submit" class="tf-button style-1 mt-2">Update Harga</button>
            </div>
        </form>
    </div>
</div>
@endsection

