<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Importa os Controllers
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SubscriptionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| O Laravel vai adicionar o prefixo /api automaticamente.
|
*/

// Rota: GET /api/planos
Route::get('/planos', [PlanController::class, 'index']);

// Rota: GET /api/usuario
Route::get('/usuario', [UserController::class, 'show']);

// ROTA DE TESTE: GET /api/teste
Route::get('/teste', function () {
    return response()->json(['mensagem' => 'AGORA VAI!']);
});

Route::post('/contratar', [SubscriptionController::class, 'store']);

Route::get('/contrato-ativo', [SubscriptionController::class, 'showActive']);

Route::put('/trocar-plano', [SubscriptionController::class, 'update']);