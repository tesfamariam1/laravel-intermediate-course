<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::apiResource('posts', \App\Http\Controllers\PostController::class);

Route::get('posts', [PostController::class, 'index']);
Route::get('posts/{post}', [PostController::class, 'show']);
Route::post('posts', [PostController::class, 'store']);
Route::put('posts/{post}', [PostController::class, 'update']);

Route::get('users/{user}/posts', [PostController::class, 'userPosts']);


// Tags CRUD
Route::get('tags', [TagController::class, 'index']);
Route::post('tags', [TagController::class, 'store']);

// Detach Tags from Post
Route::post('post/{post}', [PostController::class, 'detachTag']);
