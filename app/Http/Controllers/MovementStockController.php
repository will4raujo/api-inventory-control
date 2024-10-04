<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockMovement;

class StockMovementController extends Controller
{
    public function index()
    {
        $query = StockMovement::query();
    
        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        if ($request->has('date_from') && $request->has('date_to')) {
            $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
        }
    
        $query->orderBy('created_at', 'desc');
    
        $movements = $query->paginate(10);
    
        return view('movements.index', compact('movements'));
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
