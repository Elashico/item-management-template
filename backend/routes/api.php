<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BoxController;
use App\Http\Controllers\ItemController;

Route::apiResource('box', BoxController::class)->parameters(['box' => 'box']);
Route::patch('box/{id}/restore', [BoxController::class, 'restore']);

Route::apiResource('item', ItemController::class);
Route::patch('item/{id}/restore', [ItemController::class, 'restore']);

Route::apiResource('box', BoxController::class);
Route::patch('box/{id}/restore', [BoxController::class, 'restore']);

Route::apiResource('item', ItemController::class);
Route::patch('item/{id}/restore', [ItemController::class, 'restore']);

Route::delete('box/{id}/force', [BoxController::class, 'forceDelete']);
Route::delete('item/{id}/force', [ItemController::class, 'forceDelete']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
