<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function __construct()
    {
        Session::put('page_title', 'Product');
        Session::put('menu', 'Product');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        if (request()->ajax()) {
            return $products;
        }

        return view('pages.product.index', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'unique:products'],
            'desc' => ['required'],
            'price' => ['required', 'integer'],
        ]);

        Product::create($validated);
        return Response()->json([
            'content' => 'product ' . $validated['name'] . ' added!',
            'type' => 'success' // or 'error'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return Response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique('products', 'name')->ignore($product->id)
            ],
            'desc' => ['required'],
            'price' => ['required', 'integer'],
        ]);

        $product->update($validated);

        return Response()->json([
            'content' => 'product updated!',
            'type' => 'success' // or 'error'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return Response()->json([
            'content' => 'product ' . $product['name'] . ' deleted!',
            'type' => 'success' // or 'error'
        ]);
    }
}
