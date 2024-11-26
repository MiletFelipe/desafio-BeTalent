<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Exception;

class UserController extends Controller
{
    /**
     * Exibe todos os usuários cadastrados ordenados pelo ID.
     *
     * Este método retorna uma lista de todos os usuários cadastrados no banco de dados,
     * com os campos `id`, `name` e `email`, ordenados pelo ID.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index() :JsonResponse {
        try {
            $users = User::select('id','name', 'email')->get();

            $users = $users->sortBy('id');

            return response()->json([
                'users' => $users
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao buscar os usuários: ' . $e->getMessage()
            ], 500);
        }
        return response()->json($users);
    }


}
