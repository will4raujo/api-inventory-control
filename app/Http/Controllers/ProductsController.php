<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Carbon\Carbon;

class ProductsController extends Controller
{
    public function index(Request $request)
    {   
        $products = Product::all();
        return response()->json($products);
    }

    public function lists(Request $request)
    {
        $lowStockProducts = Product::query()         
            ->whereColumn('stock', '<', 'min_stock')
            ->orderBy('stock', 'asc')
            ->get();

        $soonToExpireProducts = Product::query()
            ->where('expiration_date', '<=', Carbon::now()->addDays(30))
            ->orderBy('expiration_date', 'asc')
            ->get();

        return response()->json([
            'low_stock_products' => $lowStockProducts,
            'soon_to_expire_products' => $soonToExpireProducts,
        ]);
    }

    public function balance(Request $request)
    {
        {
            $stockByCategory = Product::query()
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->selectRaw('categories.name as category_name, category_id, SUM(stock) as total_stock')
                ->groupBy('category_id', 'categories.name')
                ->get();

            $stockBySupplier = Product::query()
                ->join('suppliers', 'products.supplier_id', '=', 'suppliers.id')
                ->selectRaw('suppliers.name as supplier_name, supplier_id, SUM(stock) as total_stock')
                ->groupBy('supplier_id', 'suppliers.name')
                ->get();
                
            return response()->json([
                'stock_by_category' => $stockByCategory,
                'stock_by_supplier' => $stockBySupplier,
            ]);
        }
    }

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

        $expirationDate = Carbon::createFromFormat('Y-m-d', $request->expiration_date)->format('Y-m-d');

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

    public function update(Request $request, int $id)
    {
        if (!is_numeric($id) || $id <= 0) {
            return response()->json(['message' => 'Invalid supplier id'], 400);
        }

        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:250',
            'code' => 'required|integer',
            'description' => 'nullable|string|max:250',
            'category_id' => 'required|integer|exists:categories,id',
            'supplier_id' => 'required|integer|exists:suppliers,id',
            'cost_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'nullable|integer|min:0',
            'max_stock' => 'nullable|integer|min:0',
            'expiration_date' => 'nullable|date',
        ]);

        $product->update([
            'name' => $request->name ?? $product->name,
            'code' => $request->code ?? $product->code,
            'description' => $request->description ?? $product->description,
            'category_id' => $request->category_id ?? $product->category_id,
            'supplier_id' => $request->supplier_id ?? $product->supplier_id,
            'cost_price' => $request->cost_price ?? $product->cost_price,
            'sale_price' => $request->sale_price ?? $product->sale_price,
            'stock' => $request->stock ?? $product->stock,
            'min_stock' => $request->min_stock ?? $product->min_stock,
            'max_stock' => $request->max_stock ?? $product->max_stock,
            'expiration_date' => $request->expiration_date ? Carbon::createFromFormat('Y-m-d', $request->expiration_date)->format('Y-m-d') : $product->expiration_date,
        ]);

        return response()->json(['message' => 'Product updated successfully', 'product' => $product]);
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
