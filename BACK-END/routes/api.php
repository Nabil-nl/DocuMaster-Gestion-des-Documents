<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MemberController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Member creation route without token requirement
Route::post('/members', [MemberController::class, 'store']); // No middleware here

Route::middleware('auth:sanctum')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);
    });


Route::middleware('auth:sanctum')->middleware('role:member|admin')->group(function () {
        Route::middleware('permission:view documents')->get('/documents', [DocumentController::class, 'index']);
        Route::middleware('permission:create documents')->post('/documents', [DocumentController::class, 'store']);
        Route::middleware('permission:edit documents')->put('/documents/{document}', [DocumentController::class, 'update']);
        Route::middleware('permission:delete documents')->delete('/documents/{document}', [DocumentController::class, 'destroy']);

        Route::middleware('permission:view categories')->get('/categories', [CategoryController::class, 'index']);
        Route::middleware('permission:create categories')->post('/categories', [CategoryController::class, 'store']);
        Route::middleware('permission:edit categories')->put('/categories/{category}', [CategoryController::class, 'update']);
        Route::middleware('permission:delete categories')->delete('/categories/{category}', [CategoryController::class, 'destroy']);
        Route::put('/members/{id}', [MemberController::class, 'update'])->middleware('permission:edit members');

        Route::get('/members', [MemberController::class, 'index'])->middleware('permission:view members');



});
Route::middleware('auth:sanctum')->middleware('role:admin')->group(function () {
    Route::delete('/members/{id}', [MemberController::class, 'destroy'])->middleware('permission:delete members'); 

    

});