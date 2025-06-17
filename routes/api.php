<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\AuthorController;
use Tymon\JWTAuth\Http\Middleware\Authenticate as JWTAuth;

// تسجيل الدخول والتسجيل
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/books', [BookController::class, 'index']);
Route::get('/books/search', [BookController::class, 'search']);
Route::get('/authors/search', [AuthorController::class, 'search']);
Route::get('/publishers/search', [PublisherController::class, 'search']);




// المسارات المحمية بالتوكن


Route::middleware([JWTAuth::class])->group(function () {
    Route::post('/books', [BookController::class, 'store']);
    Route::post('/publisher', [PublisherController::class, 'store']);
    Route::post('/author', [AuthorController::class, 'store']);
});


// اختبار الاتصال
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});
