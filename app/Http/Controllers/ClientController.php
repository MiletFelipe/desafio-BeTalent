<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Http\Requests\ClientRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Exibe todos os clientes cadastrados.
     *
     * Este método retorna uma lista de todos os clientes cadastrados no banco de dados.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $clients = Client::select('id','name', 'email', 'address')->get();

            return response()->json([
                'clients' => $clients
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar os clientes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exibe os detalhes de um cliente específico.
     *
     * Este método retorna os detalhes de um cliente específico com base no ID fornecido.
     *
     * @param \App\Models\Client $client
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Client $client): JsonResponse
    {
        try {

            $validated = $request->validate([
                'mes' => 'nullable|integer|between:1,12',
                'ano' => 'nullable|integer|digits:4',
            ], [
                'mes.between' => 'O mês deve ser entre 1 e 12.',
                'ano.digits' => 'O ano deve ter 4 dígitos.',
            ]);

            // Recupera os parâmetros de consulta
            $month = $validated['mes'] ?? null;
            $year = $validated['ano'] ?? null;

            $query = $client->sales();

            if ($month && $year) {
                $query->whereYear('sale_date', $year)
                    ->whereMonth('sale_date', $month);
            }

            $sales = $query->get();

            return response()->json([
                'client' => $client,
                'sales' => $sales
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar o cliente ou as vendas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cria um novo cliente.
     *
     * Este método cria um novo cliente com os dados fornecidos na requisição.
     *
     * @param \App\Http\Requests\ClientRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ClientRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $client = Client::create([
                'name' => $request->name,
                'email' => $request->email,
                'address' => $request->address,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Cliente cadastrado com sucesso!',
                'client' => $client
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao cadastrar o cliente: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Atualiza os dados de um cliente.
     *
     * Valida os dados de entrada (name, email, address), realiza a atualização no banco de dados e retorna uma mensagem de sucesso.
     *
     * @param \Illuminate\Http\Request $request Requisição contendo os dados para atualização (name, email, address).
     * @param \App\Models\Client $client O cliente a ser atualizado.
     *
     * @return \Illuminate\Http\JsonResponse Retorna mensagem de sucesso com os dados do cliente ou erro caso ocorra falha.
     */
    public function update(Request $request, Client $client): JsonResponse
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'name' => 'nullable|string|max:128',
                'email' => 'nullable|email|unique:clients,email,' . $client->id,
                'address' => 'nullable|string',
            ], [
                'name.string' => 'O nome deve ser uma string válida.',
                'name.max' => 'O nome não pode ter mais de 128 caracteres.',
                'email.email' => 'O e-mail deve ser um endereço de e-mail válido.',
                'email.unique' => 'Já existe um cliente com este e-mail.',
                'address.string' => 'O endereço deve ser uma string válida.',
            ]);

            $updateData = [];

            if (isset($validated['name'])) {
                $updateData['name'] = $validated['name'];
            }

            if (isset($validated['email'])) {
                $updateData['email'] = $validated['email'];
            }

            if (isset($validated['address'])) {
                $updateData['address'] = $validated['address'];
            }

            $client->update($updateData);

            DB::commit();

            return response()->json([
                'message' => 'Cliente atualizado com sucesso!',
                'client' => $client
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao atualizar o cliente: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Deleta um cliente e suas vendas associadas.
     *
     * Este método deleta o cliente e todas as suas vendas associadas do banco de dados. Caso ocorra algum erro durante o processo,
     * a transação será revertida.
     *
     * @param \App\Models\Client $client O cliente a ser deletado, incluindo suas vendas associadas.
     *
     * @return \Illuminate\Http\JsonResponse Retorna mensagem de sucesso ou erro caso ocorra falha durante a operação.
     */
    public function destroy(Client $client): JsonResponse
    {
        DB::beginTransaction();

        try {
            $client->sales()->delete();

            $client->delete();

            DB::commit();

            return response()->json([
                'message' => 'Cliente e suas vendas deletados com sucesso!'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao deletar o cliente e suas vendas: ' . $e->getMessage()
            ], 400);
        }
    }
}
