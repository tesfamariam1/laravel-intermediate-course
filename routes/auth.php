<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', function (Request $request) {
        $token = $request->user()->currentAccessToken();

        return [
            'user' => $request->user(),
            'abilities' => $token->abilities,
            'expires_at' => $token->expires_at,
        ];
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
});
