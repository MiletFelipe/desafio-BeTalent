<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

     /**
     * Exibe todos os produtos cadastrados em ordem alfabética.
     *
     * Este método retorna uma lista de todos os produtos cadastrados no banco de dados,
     * ordenados alfabeticamente pelo nome.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index() : JsonResponse {
        try {
            $products = Product::select('id','name', 'quantity', 'price', 'brand', 'active')->get();

            $products = $products->sortBy('name');

            return response()->json([
                'products' => $products
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao buscar os produtos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exibe os detalhes de um produto.
     *
     * Este método retorna os detalhes de um produto específico.
     *
     * @param \App\Models\Product $product O objeto do produto a ser exibido.
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Product $product) :JsonResponse {

        return response()->json([
            'product' => $product
        ],200);
    }

    /**
     * Cria um novo produto com os dados fornecidos na requisição.
     *
     * Este método cria um novo produto no banco de dados utilizando os dados fornecidos
     * na requisição. Em caso de sucesso, retorna o produto criado e uma mensagem de sucesso.
     * Em caso de erro, realiza o rollback da transação e retorna uma mensagem de erro.
     *
     * @param \App\Http\Requests\ProductRequest $request O objeto de requisição contendo os dados do produto a ser criado.
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProductRequest $request) : JsonResponse {

        DB::beginTransaction();

        try {
            $newProduct =  Product::create([
                'name' => $request->name,
                'brand' => $request->brand,
                'quantity' => $request->quantity,
                'price' => $request->price,
            ]);

            DB::commit();

            return response()->json([
                'product' => $newProduct,
                'message' => 'Produto cadastrado com sucesso!'
            ], 201);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao cadastrar o produto: ' . $e->getMessage()
            ], 400);
        }
    }

     /**
     * Atualiza um produto existente com os dados fornecidos na requisição.
     *
     * Este método atualiza um produto no banco de dados utilizando os dados fornecidos
     * na requisição. Em caso de sucesso, retorna o produto atualizado e uma mensagem de sucesso.
     * Em caso de erro, realiza o rollback da transação e retorna uma mensagem de erro.
     *
     * @param \App\Http\Requests\ProductRequest $request O objeto de requisição contendo os dados do produto a ser atualizado.
     * @param \App\Models\Product $product O objeto do produto a ser atualizado.
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProductRequest $request, Product $product) : JsonResponse {
        DB::beginTransaction();
        try {
            $product->update([
                'name' => $request->name,
                'brand' => $request->brand,
                'quantity' => $request->quantity,
                'price' => $request->price,
            ]);
            DB::commit();
            return response()->json([
                'product' => $product,
                'message' => 'Produto atualizado com sucesso!'
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao atualizar o produto: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Deleta um produto existente utilizando soft delete.
     *
     * Este método realiza a exclusão lógica de um produto no banco de dados. Em caso de sucesso,
     * retorna uma mensagem de sucesso. Em caso de erro, retorna uma mensagem de erro.
     *
     * @param \App\Models\Product $product O objeto do produto a ser deletado.
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Product $product) : JsonResponse {
        DB::beginTransaction();
        try {
            $product->delete();
            DB::commit();
            return response()->json([
                'message' => 'Produto deletado com sucesso!'
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao deletar o produto: ' . $e->getMessage()
            ], 400);
        }
    }


}
