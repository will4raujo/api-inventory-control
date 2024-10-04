<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockMovement;

class StockMovementController extends Controller
{
    public function index()
    {
        $movements = StockMovement::with('product')->orderBy('created_at', 'desc')->get();
        return response()->json($movements);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:input,output,return,loss',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        switch ($request->type) {
            case 'input':
                $product->stock += $request->quantity;
                break;

            case 'output':
                if ($product->stock < $request->quantity) {
                    return response()->json(['error' => 'Estoque insuficiente'], 400);
                }
                $product->stock -= $request->quantity;
                break;

            case 'return':
                $product->stock += $request->quantity;
                break;

            case 'loss':
                if ($product->stock < $request->quantity) {
                    return response()->json(['error' => 'Estoque insuficiente para registrar a perda'], 400);
                }
                $product->stock -= $request->quantity;
                break;

            default:
                return response()->json(['error' => 'Tipo de movimentação inválido'], 400);
        }

        $product->save();

        StockMovement::create([
            'product_id' => $product->id,
            'type' => $request->type,
            'quantity' => $request->quantity,
            'user_id' => auth()->id(), 
        ]);

        return response()->json(['message' => 'Movimentação registrada com sucesso']);
    }
}
