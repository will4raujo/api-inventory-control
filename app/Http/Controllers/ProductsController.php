<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Carbon\Carbon;

class ProductsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|integer',
            'description' => 'required|string:max:250',
            'category_id' => 'required|integer',
            'supplier_id' => 'required|integer',
            'cost_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'stock' => 'required|integer',
            'min_stock' => 'required|integer',
            'max_stock' => 'required|integer',
            'expiration_date' => 'required|date'
        ]);

        $expirationDate = Carbon::createFromFormat('d/m/Y', $request->expiration_date)->format('Y-m-d');

        $product = Product::create([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'supplier_id' => $request->supplier_id,
            'cost_price' => $request->cost_price,
            'sale_price' => $request->sale_price,
            'stock' => $request->stock,
            'min_stock' => $request->min_stock,
            'max_stock' => $request->max_stock,
            'expiration_date' => $expirationDate
        ]);

        return response()->json(['message' => 'Product created successfully'], 201);
    }

    public function index()
    {
        $query = Product::query();
    
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }
        if ($request->has('supplier')) {
            $query->where('supplier', $request->supplier);
        }
        if ($request->has('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }
    
        if ($request->has('sort') && $request->sort === 'low_stock') {
            $query->orderBy('stock', 'asc'); 
        }
    
        if ($request->has('sort') && $request->sort === 'expiration') {
            $query->orderBy('expiration_date', 'asc');
        }
    
        $products = $query->paginate(10);
    
        return view('products.index', compact('products'));
    }

    public function findById(Request $request, int $id)
    {
        if (!is_numeric($id) || $id <= 0) {
            return response()->json(['message' => 'Invalid product id'], 400);
        }

        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json($product);
    }

    public function destroy(int $id)
    {
        if (!is_numeric($id) || intval($id) <= 0) {
            return response()->json(['message' => 'Invalid product id'], 400);
        }

        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();
    }
}
