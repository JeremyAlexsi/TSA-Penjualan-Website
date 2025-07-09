<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('created_at', 'DESC')->paginate();

        $inCartIds = [];

        if (Auth::check())  {
            $inCartIds = CartItem::where('user_id', auth()->id())
                ->pluck('product_id')
                ->toArray();
        }

        return view('shop', compact('products', 'inCartIds'));
    }

    public function product_details($product_slug)
    {
        $product = Product::where('slug', $product_slug)->first();
        $rproducts = Product::where('slug', '<>', $product_slug)->get()->take(8);
        return view('details', compact('product', 'rproducts'));
    }
}
