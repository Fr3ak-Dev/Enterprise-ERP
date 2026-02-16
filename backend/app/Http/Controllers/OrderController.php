<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'user', 'items.product']);

        if ($request->has('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from')) {
            $query->whereDate('order_date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('order_date', '<=', $request->date_to);
        }

        $orders = $query->orderBy('order_date', 'desc')
                       ->paginate($request->per_page ?? 15);

        return OrderResource::collection($orders);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'order_number' => 'required|string|unique:orders,order_number|max:255',
            'order_date' => 'required|date',
            'type' => 'required|in:venta,compra',
            'status' => 'required|in:pendiente,procesando,completada,cancelada',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Crear la orden
            $order = Order::create([
                'customer_id' => $request->customer_id,
                'user_id' => Auth::id(),
                'order_number' => $request->order_number,
                'order_date' => $request->order_date,
                'type' => $request->type,
                'status' => $request->status,
                'notes' => $request->notes,
                'subtotal' => 0,
                'discount' => 0,
                'tax' => 0,
                'total' => 0,
            ]);

            $subtotal = 0;
            $totalDiscount = 0;

            // Crear los items de la orden
            foreach ($request->items as $itemData) {
                $discount = $itemData['discount'] ?? 0;
                $itemSubtotal = ($itemData['quantity'] * $itemData['unit_price']) - $discount;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $itemData['product_id'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'discount' => $discount,
                    'subtotal' => $itemSubtotal,
                ]);

                $subtotal += $itemSubtotal;
                $totalDiscount += $discount;

                // Si es venta y está completada, descontar del stock
                if ($request->type === 'venta' && $request->status === 'completada') {
                    $product = Product::findOrFail($itemData['product_id']);
                    
                    if ($product->stock < $itemData['quantity']) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => "Stock insuficiente para el producto: {$product->name}"
                        ], 400);
                    }
                    
                    $product->stock -= $itemData['quantity'];
                    $product->save();
                }
            }

            // Calcular impuesto
            $tax = $subtotal * 0.13;
            $total = $subtotal + $tax;

            // Actualizar totales de la orden
            $order->update([
                'subtotal' => $subtotal,
                'discount' => $totalDiscount,
                'tax' => $tax,
                'total' => $total,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Orden creada exitosamente',
                'data' => new OrderResource($order->load(['customer', 'user', 'items.product']))
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear orden',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(string $id)
    {
        $order = Order::with(['customer', 'user', 'items.product'])->find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Orden no encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new OrderResource($order)
        ]);
    }

    public function update(Request $request, string $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Orden no encontrada'
            ], 404);
        }

        // Solo se pueden editar órdenes pendientes
        if ($order->status !== 'pendiente') {
            return response()->json([
                'success' => false,
                'message' => 'Solo se pueden editar órdenes pendientes'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pendiente,procesando,completada,cancelada',
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

            $oldStatus = $order->status;
            $order->update($request->only(['status', 'notes']));

            // Si cambia a completada, descontar stock
            if ($oldStatus !== 'completada' && $request->status === 'completada' && $order->type === 'venta') {
                foreach ($order->items as $item) {
                    $product = Product::findOrFail($item->product_id);
                    
                    if ($product->stock < $item->quantity) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => "Stock insuficiente para el producto: {$product->name}"
                        ], 400);
                    }
                    
                    $product->stock -= $item->quantity;
                    $product->save();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Orden actualizada exitosamente',
                'data' => new OrderResource($order->load(['customer', 'user', 'items.product']))
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar orden',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Orden no encontrada'
            ], 404);
        }

        // Solo se pueden eliminar órdenes pendientes o canceladas
        if ($order->status === 'completada') {
            return response()->json([
                'success' => false,
                'message' => 'No se pueden eliminar órdenes completadas'
            ], 400);
        }

        $order->delete();

        return response()->json([
            'success' => true,
            'message' => 'Orden eliminada exitosamente'
        ]);
    }
}