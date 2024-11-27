<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Client;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class SaleController extends Controller
{
    /**
     * Registra a venda de um produto para um cliente.
     *
     * Este método registra a venda de um produto associada a um cliente e a um produto. O valor da venda
     * e a data são fornecidos na requisição, e a venda é salva no banco de dados.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Client $client
     * @param \App\Models\Product $product
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Client $client, Product $product): JsonResponse
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'quantity' => 'required|numeric|min:1',
                'sale_date' => 'required|date',
            ], [
                'price.required' => 'O valor da venda é obrigatório.',
                'price.numeric' => 'O valor da venda deve ser um número.',
                'sale_date.required' => 'A data da venda é obrigatória.',
                'sale_date.date' => 'A data da venda deve ser válida.',
            ]);

            if (!$client || !$product) {
                return response()->json([
                    'message' => 'Cliente ou produto não encontrados.'
                ], 404);
            }

            $sale = new Sale([
                'client_id' => $client->id,
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
                'price' => $product->price * $validated['quantity'],
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
