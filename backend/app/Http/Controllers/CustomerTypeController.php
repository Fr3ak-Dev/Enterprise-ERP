<?php

namespace App\Http\Controllers;

use App\Models\CustomerType;
use App\Http\Resources\CustomerTypeResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = CustomerType::withCount('customers');

        if ($request->has('active')) {
            $query->where('active', $request->active);
        }

        $customerTypes = $query->paginate($request->per_page ?? 15);

        return CustomerTypeResource::collection($customerTypes);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $customerType = CustomerType::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Tipo de cliente creado exitosamente',
            'data' => new CustomerTypeResource($customerType)
        ], 201);
    }

    public function show(string $id)
    {
        $customerType = CustomerType::withCount('customers')->find($id);

        if (!$customerType) {
            return response()->json([
                'success' => false,
                'message' => 'Tipo de cliente no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new CustomerTypeResource($customerType)
        ]);
    }

    public function update(Request $request, string $id)
    {
        $customerType = CustomerType::find($id);

        if (!$customerType) {
            return response()->json([
                'success' => false,
                'message' => 'Tipo de cliente no encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $customerType->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Tipo de cliente actualizado exitosamente',
            'data' => new CustomerTypeResource($customerType)
        ]);
    }

    public function destroy(string $id)
    {
        $customerType = CustomerType::find($id);

        if (!$customerType) {
            return response()->json([
                'success' => false,
                'message' => 'Tipo de cliente no encontrado'
            ], 404);
        }

        // Verificar si tiene clientes asociados
        if ($customerType->customers()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar el tipo de cliente porque tiene clientes asociados'
            ], 400);
        }

        $customerType->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tipo de cliente eliminado exitosamente'
        ]);
    }
}