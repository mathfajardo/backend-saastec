<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    // leads
    Route::get('/leads', [LeadController::class, 'index']);
    Route::get('/leads/{lead}', [LeadController::class, 'show']);
    Route::post('/leads', [LeadController::class, 'store']);
    Route::put('/leads/{lead}', [LeadController::class, 'update']);
    Route::delete('/leads/{lead}', [LeadController::class, 'destroy']);
    Route::get('/leadsMes', [LeadController::class, 'leadsMes']);

    //clientes
    Route::get('/clientes', [ClienteController::class, 'index']);
    Route::get('/clientes/{cliente}', [ClienteController::class, 'show']);
    Route::post('/clientes', [ClienteController::class, 'store']);
    Route::put('/clientes/{cliente}', [ClienteController::class, 'update']);
    Route::get('/clientesMes', [ClienteController::class, 'clientesMes']);
    Route::get('/clientesTotal', [ClienteController::class, 'clientesTotal']);


    // user
    Route::post('/user', [UserController::class, 'store']);


    // verifica token
    Route::get('login/verifica', [AuthController::class, 'verifica_token']);
});


// rota para autenticação
Route::post('/login', [AuthController::class, 'login']);


Route::post('/user', [UserController::class, 'store']);

Route::get('/leadsMes', [LeadController::class, 'leadsMes']);
Route::get('/clientesMes', [ClienteController::class, 'clientesMes']);
Route::get('/clientesTotal', [ClienteController::class, 'clientesTotal']);
