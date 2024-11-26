<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class SaleController extends Controller
{
    /**
     * Registra a venda de um produto para um cliente.
     *
     * Este método registra a venda de um produto associada a um cliente. O valor da venda
     * e a data são fornecidos na requisição, e a venda é salva no banco de dados.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Client $client
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Client $client): JsonResponse
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'amount' => 'required|numeric|min:0',
                'sale_date' => 'required|date',
            ], [
                'amount.required' => 'O valor da venda é obrigatório.',
                'amount.numeric' => 'O valor da venda deve ser um número.',
                'sale_date.required' => 'A data da venda é obrigatória.',
                'sale_date.date' => 'A data da venda deve ser válida.',
            ]);

            $sale = new Sale([
                'client_id' => $client->id,
                'amount' => $validated['amount'],
                'sale_date' => $validated['sale_date'],
            ]);

            $sale->save();

            DB::commit();

            return response()->json([
                'message' => 'Venda registrada com sucesso!',
                'sale' => $sale
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao registrar a venda: ' . $e->getMessage()
            ], 400);
        }
    }
}
