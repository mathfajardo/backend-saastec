<?php

use App\Http\Controllers\AuthController;
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

    // user
    Route::post('/user', [UserController::class, 'store']);
});


// rota para autenticação
Route::post('/login', [AuthController::class, 'login']);

Route::get('/leads', [LeadController::class, 'index']);
Route::get('/leads/{lead}', [LeadController::class, 'show']);
Route::post('/leads', [LeadController::class, 'store']);
Route::put('/leads/{lead}', [LeadController::class, 'update']);
Route::delete('/leads/{lead}', [LeadController::class, 'destroy']);

