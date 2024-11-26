<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
     /**
     * Realiza a autenticação do usuário e retorna um token JWT.
     *
     * @param \Illuminate\Http\Request $request Requisição contendo o e-mail e a senha do usuário.
     *
     * @return \Illuminate\Http\JsonResponse Retorna o token JWT ou erro de credenciais inválidas.
     */
     public function login(Request $request)
     {
         $credentials = $request->only('email', 'password');

         try {

             if ($token = JWTAuth::attempt($credentials)) {
                 return response()->json(['token' => $token]);
             } else {
                 return response()->json(['error' => 'Credenciais inválidas'], 401);
             }
         } catch (Exception $e) {
             return response()->json(['error' => 'Erro ao tentar fazer login: ' . $e->getMessage()], 500);
         }
     }

    /**
     * Registra um novo usuário e retorna um token JWT.
     *
     * Valida os dados de entrada, cria um novo usuário no banco e gera um token JWT para autenticação.
     *
     * @param \Illuminate\Http\Request $request Requisição contendo os dados do usuário (name, email, password).
     *
     * @return \Illuminate\Http\JsonResponse Retorna mensagem de sucesso com o token ou erro caso ocorra falha.
     */
    public function register(Request $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ],
            [
                'name.required' => 'O nome é obrigatório.',
                'name.string' => 'O nome deve ser uma string válida.',
                'name.max' => 'O nome não pode ter mais de 255 caracteres.',
                'email.required' => 'O e-mail é obrigatório.',
                'email.string' => 'O e-mail deve ser uma string válida.',
                'email.email' => 'O e-mail deve ser um endereço de e-mail válido.',
                'email.unique' => 'Já existe um usuário com este e-mail.',
                'password.required' => 'A senha é obrigatória.',
                'password.string' => 'A senha deve ser uma string válida.',
                'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            ]);

            $newUser = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
            ]);

            DB::commit();

            $token = JWTAuth::fromUser($newUser);

            return response()->json([
                'message' => 'Usuário cadastrado com sucesso!',
                'JWT-token' => $token
            ], 201);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao cadastrar o usuário: ' . $e->getMessage()
            ], 400);
        }
    }

}
