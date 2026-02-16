<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Resources\CustomerResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::with('customerType');

        if ($request->has('customer_type_id')) {
            $query->where('customer_type_id', $request->customer_type_id);
        }

        if ($request->has('active')) {
            $query->where('active', $request->active);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query->paginate($request->per_page ?? 15);

        return CustomerResource::collection($customers);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|unique:customers,code|max:255',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'customer_type_id' => 'required|exists:customer_types,id',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'tax_id' => 'nullable|string|max:50',
            'credit_limit' => 'required|numeric|min:0',
            'current_balance' => 'numeric',
            'notes' => 'nullable|string',
            'active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $customer = Customer::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Cliente creado exitosamente',
            'data' => new CustomerResource($customer->load('customerType'))
        ], 201);
    }

    public function show(string $id)
    {
        $customer = Customer::with('customerType')->find($id);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Cliente no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new CustomerResource($customer)
        ]);
    }

    public function update(Request $request, string $id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Cliente no encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:255|unique:customers,code,' . $id,
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'customer_type_id' => 'required|exists:customer_types,id',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'tax_id' => 'nullable|string|max:50',
            'credit_limit' => 'required|numeric|min:0',
            'current_balance' => 'numeric',
            'notes' => 'nullable|string',
            'active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $customer->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Cliente actualizado exitosamente',
            'data' => new CustomerResource($customer->load('customerType'))
        ]);
    }

    public function destroy(string $id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Cliente no encontrado'
            ], 404);
        }

        $customer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cliente eliminado exitosamente'
        ]);
    }
}