<?php

namespace App\Http\Controllers;

use App\Models\TransactionType;
use App\Http\Resources\TransactionTypeResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = TransactionType::withCount('transactions');

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('active')) {
            $query->where('active', $request->active);
        }

        $transactionTypes = $query->paginate($request->per_page ?? 15);

        return TransactionTypeResource::collection($transactionTypes);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|in:ingreso,egreso',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $transactionType = TransactionType::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Tipo de transacción creado exitosamente',
            'data' => new TransactionTypeResource($transactionType)
        ], 201);
    }

    public function show(string $id)
    {
        $transactionType = TransactionType::withCount('transactions')->find($id);

        if (!$transactionType) {
            return response()->json([
                'success' => false,
                'message' => 'Tipo de transacción no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new TransactionTypeResource($transactionType)
        ]);
    }

    public function update(Request $request, string $id)
    {
        $transactionType = TransactionType::find($id);

        if (!$transactionType) {
            return response()->json([
                'success' => false,
                'message' => 'Tipo de transacción no encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|in:ingreso,egreso',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $transactionType->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Tipo de transacción actualizado exitosamente',
            'data' => new TransactionTypeResource($transactionType)
        ]);
    }

    public function destroy(string $id)
    {
        $transactionType = TransactionType::find($id);

        if (!$transactionType) {
            return response()->json([
                'success' => false,
                'message' => 'Tipo de transacción no encontrado'
            ], 404);
        }

        // Verificar si tiene transacciones asociadas
        if ($transactionType->transactions()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar el tipo de transacción porque tiene transacciones asociadas'
            ], 400);
        }

        $transactionType->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tipo de transacción eliminado exitosamente'
        ]);
    }
}