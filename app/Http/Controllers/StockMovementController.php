<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockMovement;
use App\Models\Product;

class StockMovementController extends Controller
{
    public function index()
    {
        $movements = StockMovement::all();

        return response()->json($movements);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:input,output,return,loss',
            'quantity' => 'required|integer|min:1',
            'user_id' => 'required|exists:users,id'
        ]);

        $product = Product::find($request->product_id);

        if (!$product) {
            return response()->json(['error' => 'Produto não encontrado'], 404);
        }

        $currentStock = $product->stock;

        switch ($request->type) {
            case 'input':
            case 'return':
                $currentStock += $request->quantity;
                break;
            case 'output':
            case 'loss':
                if ($currentStock < $request->quantity) {
                    return response()->json(['error' => 'Estoque insuficiente'], 400);
                }
                $currentStock -= $request->quantity;
                break;
            case 'adjustment':
                $currentStock = $request->quantity;
                break;
            default:
                return response()->json(['error' => 'Tipo de movimentação inválido'], 400);
        }

        $product->stock = $currentStock;
        $product->save();

        StockMovement::create([
            'product_id' => $request->product_id,
            'type' => $request->type,
            'quantity' => $request->quantity,
            'user_id' => auth()->id(), 
        ]);

        return response()->json(['message' => 'Movimentação registrada com sucesso']);
    }
}
