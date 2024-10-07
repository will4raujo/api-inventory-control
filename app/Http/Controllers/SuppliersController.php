<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SuppliersController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'cnpj' => 'required|string|max:18',
            'contact' => 'required|string|max:255'
        ]);

        $supplier = Supplier::create([
            'name' => $request->name,
            'cnpj' => $request->cnpj,
            'contact' => $request->contact
        ]);

        return response()->json(['message' => 'Supplier created successfully'], 201);
    }

    public function index()
    {
        $suppliers = Supplier::all();

        return response()->json($suppliers);
    }

    public function findById(Request $request, int $id)
    {
        if (!is_numeric($id) || $id <= 0) {
            return response()->json(['message' => 'Invalid supplier id'], 400);
        }

        $supplier = Supplier::find($id);

        if (!$supplier) {
            return response()->json(['message' => 'Supplier not found'], 404);
        }

        return response()->json($supplier);
    }

    public function update(Request $request, int $id)
    {
        if (!is_numeric($id) || $id <= 0) {
            return response()->json(['message' => 'Invalid supplier id'], 400);
        }

        $supplier = Supplier::find($id);

        if (!$supplier) {
            return response()->json(['message' => 'Supplier not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'cnpj' => 'required|string|max:18',
            'contact' => 'required|string|max:255'
        ]);

        $supplier->update([
            'name' => $request->name,
            'cnpj' => $request->cnpj,
            'contact' => $request->contact
        ]);

        return response()->json(['message' => 'Supplier updated successfully']);
    }

    public function destroy(int $id)
    {
        if (!is_numeric($id) || intval($id) <= 0) {
            return response()->json(['message' => 'Invalid supplier id'], 400);
        }

        $supplier = Supplier::find($id);
        if (!$supplier) {
            return response()->json(['message' => 'Supplier not found'], 404);
        }

        // $products = Product::where('supplier_id', $id)->exists();
        // if ($products) {
        //     return response()->json(['message' => 'Supplier has products and cannot be deleted'], 400);
        // }

        $supplier->delete();

        return response()->json(['message' => 'Supplier deleted successfully']);
    }
}
