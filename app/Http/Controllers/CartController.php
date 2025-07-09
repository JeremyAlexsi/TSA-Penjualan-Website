<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::where('user_id', Auth::id())->with('items.product')->first();

        $items = $cart ? $cart->items : collect(); // untuk menghindari error jika cart belum ada

        return view('keranjang', compact('items'));
    }

    public function add($productId)
    {
        $product = Product::findOrFail($productId);
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if ($item) {
            $item->increment('quantity');
        } else {
            $cart->items()->create([
                'product_id' => $productId,
                'quantity' => 1,
                'user_id' => Auth::id() // ← penting
            ]);
        }

        return redirect()->back()->with('success', 'Produk ditambahkan ke keranjang!');
    }

    public function update(Request $request, $itemId)
    {
        $item = CartItem::findOrFail($itemId);
        $item->quantity = $request->quantity;
        $item->save();

        $price = $item->product->sale_price ?? $item->product->regular_price;
        $tax = ($price * $item->product->tax_percent / 100) * $item->quantity;
        $subtotal = ($price * $item->quantity) + $tax;

        // Hitung ulang total semua item
        $cartItems = CartItem::where('cart_id', $item->cart_id)->get();
        $grandSubtotal = 0;
        $grandTax = 0;
        foreach ($cartItems as $ci) {
            $p = $ci->product->sale_price ?? $ci->product->regular_price;
            $t = ($p * $ci->product->tax_percent / 100) * $ci->quantity;
            $grandSubtotal += $p * $ci->quantity;
            $grandTax += $t;
        }

        return response()->json([
            'success' => true,
            'item_id' => $item->id,
            'new_quantity' => $item->quantity,
            'subtotal' => number_format($subtotal, 0, ',', '.'),
            'grandSubtotal' => number_format($grandSubtotal, 0, ',', '.'),
            'grandTax' => number_format($grandTax, 0, ',', '.'),
            'grandTotal' => number_format($grandSubtotal + $grandTax, 0, ',', '.')
        ]);
    }


    public function remove($itemId)
    {
        CartItem::findOrFail($itemId)->delete();
        return redirect()->back()->with('success', 'Produk dihapus dari keranjang!');
    }
}
