<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Http\Resources\AccountResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $query = Account::withCount('transactions');

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('currency')) {
            $query->where('currency', $request->currency);
        }

        if ($request->has('active')) {
            $query->where('active', $request->active);
        }

        $accounts = $query->paginate($request->per_page ?? 15);

        return AccountResource::collection($accounts);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'account_number' => 'required|string|unique:accounts,account_number|max:255',
            'type' => 'required|in:caja,banco,otro',
            'balance' => 'required|numeric',
            'currency' => 'required|string|max:10',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $account = Account::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Cuenta creada exitosamente',
            'data' => new AccountResource($account)
        ], 201);
    }

    public function show(string $id)
    {
        $account = Account::withCount('transactions')->find($id);

        if (!$account) {
            return response()->json([
                'success' => false,
                'message' => 'Cuenta no encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new AccountResource($account)
        ]);
    }

    public function update(Request $request, string $id)
    {
        $account = Account::find($id);

        if (!$account) {
            return response()->json([
                'success' => false,
                'message' => 'Cuenta no encontrada'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255|unique:accounts,account_number,' . $id,
            'type' => 'required|in:caja,banco,otro',
            'balance' => 'required|numeric',
            'currency' => 'required|string|max:10',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $account->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Cuenta actualizada exitosamente',
            'data' => new AccountResource($account)
        ]);
    }

    public function destroy(string $id)
    {
        $account = Account::find($id);

        if (!$account) {
            return response()->json([
                'success' => false,
                'message' => 'Cuenta no encontrada'
            ], 404);
        }

        // Verificar si tiene transacciones asociadas
        if ($account->transactions()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar la cuenta porque tiene transacciones asociadas'
            ], 400);
        }

        $account->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cuenta eliminada exitosamente'
        ]);
    }
}