<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class PenjualController extends Controller
{
    public function index()
    {
        return view("penjual.dashboard");
    }


    public function brands()
    {
        $brands = Brand::orderBy('id', 'DESC')->paginate(10);
        return view("penjual.brands", compact('brands'));
    }

    public function add_brand()
    {
        return view('penjual.brand-add');
    }

    public function brand_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug',
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);
        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        $image = $request->file('image');
        $file_extention = $request->file('image')->extension();
        $file_name = Carbon::now()->timestamp . '.' . $file_extention;
        $this->GenerateBrandThumbailImage($image, $file_name);
        $brand->image = $file_name;
        $brand->save();
        return redirect()->route('penjual.brands')->with('status', 'Record has been added successfully !');
    }

    public function GenerateBrandThumbailImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/brands');
        $img = Image::read($image->path());
        $img->cover(124, 124, "top");
        $img->resize(124, 124, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $imageName);
    }

    public function categories()
    {
        $categories = Category::orderBy('id', 'DESC')->paginate(10);
        return view('penjual.categories', compact('categories'));
    }

    public function products()
    {
        $products = Product::orderBy('created_at', 'ASC')->paginate(10);
        return view('penjual.products', compact('products'));
    }

    public function updateGlobalTax(Request $request)
    {
        $request->validate([
            'global_tax' => 'required|numeric|min:0|max:100',
        ]);

        $newTax = $request->global_tax;

        // Update semua produk
        Product::query()->update(['tax_percent' => $newTax]);

        return redirect()->back()->with('success', 'Pajak berhasil diperbarui ke ' . $newTax . '%');
    }

    public function editProductPrice($id)
    {
        $product = Product::findOrFail($id);
        return view('penjual.product-edit', compact('product'));
    }

    public function updateProductPrice(Request $request, $id)
    {
        $request->validate([
            'regular_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
        ]);

        $product = Product::findOrFail($id);
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->save();

        return redirect()->route('penjual.products')->with('success', 'Harga produk berhasil diperbarui.');
    }


    //

}
