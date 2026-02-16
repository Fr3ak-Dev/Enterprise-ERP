<?php

namespace App\Http\Controllers;

use App\Models\InventoryMovement;
use App\Models\Product;
use App\Http\Resources\InventoryMovementResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class InventoryMovementController extends Controller
{
    public function index(Request $request)
    {
        $query = InventoryMovement::with(['product', 'user']);

        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $movements = $query->orderBy('created_at', 'desc')
                          ->paginate($request->per_page ?? 15);

        return InventoryMovementResource::collection($movements);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:entrada,salida',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'nullable|numeric|min:0',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($request->product_id);

            // Calcular nuevo stock
            if ($request->type === 'entrada') {
                $newStock = $product->stock + $request->quantity;
            } else {
                // Verificar que hay suficiente stock
                if ($product->stock < $request->quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stock insuficiente. Stock actual: ' . $product->stock
                    ], 400);
                }
                $newStock = $product->stock - $request->quantity;
            }

            // Crear movimiento
            $movement = InventoryMovement::create([
                'product_id' => $request->product_id,
                'user_id' => Auth::id(),
                'type' => $request->type,
                'quantity' => $request->quantity,
                'unit_price' => $request->unit_price,
                'reference' => $request->reference,
                'notes' => $request->notes,
                'stock_after' => $newStock,
            ]);

            // Actualizar stock del producto
            $product->update(['stock' => $newStock]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Movimiento de inventario registrado exitosamente',
                'data' => new InventoryMovementResource($movement->load(['product', 'user']))
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar movimiento',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(string $id)
    {
        $movement = InventoryMovement::with(['product', 'user'])->find($id);

        if (!$movement) {
            return response()->json([
                'success' => false,
                'message' => 'Movimiento no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new InventoryMovementResource($movement)
        ]);
    }

    public function update(Request $request, string $id)
    {
        return response()->json([
            'success' => false,
            'message' => 'Los movimientos de inventario no pueden ser editados una vez creados'
        ], 403);
    }

    public function destroy(string $id)
    {
        return response()->json([
            'success' => false,
            'message' => 'Los movimientos de inventario no pueden ser eliminados'
        ], 403);
    }
}