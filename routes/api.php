<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContestController;
use Illuminate\Support\Facades\Auth;



Route::middleware('auth:sanctum')->group(function () {
    Route::post('/contest/winner', [ContestController::class, 'chooseWinner']);
    Route::get('/contest/clients', [ContestController::class, 'getClients']);
    Route::get('/contest/count', [ContestController::class, 'count']);
    Route::get('/user', fn (Request $request) => $request->user());
});

Route::post('/contest/register', [ContestController::class, 'store']);

Route::get('/contest/winner', [ContestController::class, 'getWinner']);

Route::post('/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (!Auth::attempt($request->only('email', 'password'))) {
        return response()->json(['message' => 'Credenciales inválidas'], 401);
    }

    return response()->json([
        'token' => $request->user()->createToken('admin-token')->plainTextToken,
    ]);
});
