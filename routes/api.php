<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

require 'auth.php';
// Route::apiResource('posts', \App\Http\Controllers\PostController::class);

Route::get('posts', [PostController::class, 'index']);
Route::get('posts/{post}', [PostController::class, 'show']);
Route::post('posts', [PostController::class, 'store']);
Route::put('posts/{post}', [PostController::class, 'update']);

Route::delete('uploads/{id}', [FileUploadController::class, 'deleteFile']);
Route::post('upload', [FileUploadController::class, 'uploadSingle']);
Route::get('uploads', [FileUploadController::class, 'listFiles']);
Route::get('uploads/{id}', [FileUploadController::class, 'downloadFile']);
// Route::get('users/{user}/posts', [PostController::class, 'userPosts']);

Route::apiResource('users', UserController::class);
// Tags CRUD
// Route::get('tags', [TagController::class, 'index']);
// Route::post('tags', [TagController::class, 'store']);

// // Detach Tags from Post
// Route::post('post/{post}', [PostController::class, 'detachTag']);
