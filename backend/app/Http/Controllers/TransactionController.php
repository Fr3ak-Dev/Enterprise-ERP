<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use App\Http\Resources\TransactionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['account', 'transactionType', 'customer', 'user']);

        if ($request->has('account_id')) {
            $query->where('account_id', $request->account_id);
        }

        if ($request->has('transaction_type_id')) {
            $query->where('transaction_type_id', $request->transaction_type_id);
        }

        if ($request->has('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        $transactions = $query->orderBy('transaction_date', 'desc')
                             ->paginate($request->per_page ?? 15);

        return TransactionResource::collection($transactions);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_id' => 'required|exists:accounts,id',
            'transaction_type_id' => 'required|exists:transaction_types,id',
            'customer_id' => 'nullable|exists:customers,id',
            'amount' => 'required|numeric|min:0',
            'transaction_date' => 'required|date',
            'reference' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:completada,pendiente,cancelada',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $transaction = Transaction::create([
                'account_id' => $request->account_id,
                'transaction_type_id' => $request->transaction_type_id,
                'customer_id' => $request->customer_id,
                'user_id' => Auth::id(),
                'amount' => $request->amount,
                'transaction_date' => $request->transaction_date,
                'reference' => $request->reference,
                'description' => $request->description,
                'status' => $request->status,
            ]);

            // Si la transacción está completada, actualizar balance de cuenta
            if ($request->status === 'completada') {
                $account = Account::findOrFail($request->account_id);
                $transactionType = $transaction->transactionType;

                if ($transactionType->category === 'ingreso') {
                    $account->balance += $request->amount;
                } else {
                    $account->balance -= $request->amount;
                }

                $account->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transacción registrada exitosamente',
                'data' => new TransactionResource($transaction->load(['account', 'transactionType', 'customer', 'user']))
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar transacción',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(string $id)
    {
        $transaction = Transaction::with(['account', 'transactionType', 'customer', 'user'])->find($id);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transacción no encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new TransactionResource($transaction)
        ]);
    }

    public function update(Request $request, string $id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transacción no encontrada'
            ], 404);
        }

        // Solo se pueden editar transacciones pendientes
        if ($transaction->status !== 'pendiente') {
            return response()->json([
                'success' => false,
                'message' => 'Solo se pueden editar transacciones pendientes'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'account_id' => 'required|exists:accounts,id',
            'transaction_type_id' => 'required|exists:transaction_types,id',
            'customer_id' => 'nullable|exists:customers,id',
            'amount' => 'required|numeric|min:0',
            'transaction_date' => 'required|date',
            'reference' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:completada,pendiente,cancelada',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $oldStatus = $transaction->status;
            $transaction->update($request->all());

            // Si cambia de pendiente a completada, actualizar balance
            if ($oldStatus === 'pendiente' && $request->status === 'completada') {
                $account = Account::findOrFail($request->account_id);
                $transactionType = $transaction->transactionType;

                if ($transactionType->category === 'ingreso') {
                    $account->balance += $request->amount;
                } else {
                    $account->balance -= $request->amount;
                }

                $account->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transacción actualizada exitosamente',
                'data' => new TransactionResource($transaction->load(['account', 'transactionType', 'customer', 'user']))
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar transacción',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transacción no encontrada'
            ], 404);
        }

        // Solo se pueden eliminar transacciones pendientes o canceladas
        if ($transaction->status === 'completada') {
            return response()->json([
                'success' => false,
                'message' => 'No se pueden eliminar transacciones completadas'
            ], 400);
        }

        $transaction->delete();

        return response()->json([
            'success' => true,
            'message' => 'Transacción eliminada exitosamente'
        ]);
    }
}