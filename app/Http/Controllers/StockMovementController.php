<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockMovement;
use App\Models\Product;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $movements = StockMovement::query()
            ->select('stock_movements.*', 'products.name as product_name')
            ->join('products', 'stock_movements.product_id', '=', 'products.id')
            ->when($request->name, function ($query) use ($request) {
                return $query->where('products.name', 'like', '%' . $request->name . '%');
            })
            ->get();
    
        return response()->json($movements);
    }

    public function movements(Request $request)
    {   
            $user = auth()->user();
        $movements = StockMovement::query()
            ->select('stock_movements.type', 'stock_movements.quantity', 'stock_movements.created_at', 'products.name as product_name')
            ->join('products', 'stock_movements.product_id', '=', 'products.id')
            ->when($request->type && $request->type !== 'all', function ($query) use ($request, $user) {
                if ($request->type === 'my-movements') {
                    return $query->where('stock_movements.user_id', $user->id);
                }
                return $query->where('stock_movements.type', $request->type);
            })
            ->orderBy('stock_movements.created_at', 'desc')
            ->get();

        return response()->json($movements);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:input,output,return,loss,adjustment',
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
