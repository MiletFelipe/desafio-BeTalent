<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SaleController;

//Rotas para registar, fazer login, buscar todos usuário e deletar usuário
Route::post('/register', [AuthController::class, 'register']); // POST - http://127.0.0.1:8000/api/register
Route::post('login', [AuthController::class, 'login']); // POST - http://127.0.0.1:8000/api/login - Para gerar o token JWT
Route::get('/users', [UserController::class, 'index']); // GET - http://127.0.0.1:8000/api/users
Route::delete('/user/{user}', [UserController::class, 'destroy']); // DELETE - http://127.0.0.1:8000/api/user/1


// Rota para registar venda protegida com JWT
Route::middleware(['jwt.auth'])->group(function () {
    Route::post('client/{client}/sales', [SaleController::class, 'store']); // - POST - http://127.0.0.1:8000/api/client/1/sales
});

// Rotas de Clientes protegidas com JWT
Route::middleware(['jwt.auth'])->group(function () {
    Route::get('/clients', [ClientController::class, 'index']); // - GET - http://127.0.0.1:8000/api/clients
    Route::get('/client/{client}', [ClientController::class, 'show']); // - GET - http://127.0.0.1:8000/api/client/1 aceita parametros mes e ano
    Route::post('/client', [ClientController::class, 'store']); // - POST - http://127.0.0.1:8000/api/client
    Route::put('/client/{client}', [ClientController::class, 'update']); // - PUT -http://127.0.0.1:8000/api/client/1
    Route::delete('/client/{client}', [ClientController::class, 'destroy']); // - DELETE  - http://127.0.0.1:8000/api/client/1

});

// Rotas de Produtos protegidas com JWT
Route::middleware(['jwt.auth'])->group(function () {
    Route::get('/products', [ProductController::class, 'index']); // GET - http://127.0.0.1:8000/api/products
    Route::get('/product/{product}', [ProductController::class, 'show']); // GET - http://127.0.0.1:8000/api/product/1
    Route::post('/product', [ProductController::class, 'store']); // POST - http://127.0.0.1:8000/api/products
    Route::put('/product/{product}', [ProductController::class, 'update']); // PUT - http://127.0.0.1:8000/api/products/1
    Route::delete('/product/{product}', [ProductController::class, 'destroy']); // DELETE - http://127.0.0.1:8000/api/product/1
});



