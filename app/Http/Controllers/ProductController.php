<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query();

        if ($request->filled('name')) {
            $search = $request->name;

            $products->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $products = $products
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString(); // supaya pagination tetap bawa filter

        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sku' => 'required|string|unique:products,sku',
            'name' => 'required|string',
            'category' => 'required|string',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'unit' => 'required|string',
            'location' => 'nullable|string',
        ]);

        $data['last_updated'] = Carbon::now();

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Product created.');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'sku' => 'required|string|unique:products,sku,'.$product->id,
            'name' => 'required|string',
            'category' => 'required|string',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'unit' => 'required|string',
            'location' => 'nullable|string',
        ]);

        $data['last_updated'] = Carbon::now();

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        if (!$product) {
            return redirect()->route('products.index')
                ->with('error', 'Product not found.');
        }
    
        $product->delete();
    
        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
