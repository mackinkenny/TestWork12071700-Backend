<?php

use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('getAllProducts', [ProductController::class, 'getAllProducts']);
Route::get('getProduct/{id}', [ProductController::class, 'getProduct']);
Route::delete('deleteProduct/{id}', [ProductController::class, 'delete']);

Route::post('storeProduct', [ProductController::class, 'store']);
Route::post('updateProduct', [ProductController::class, 'update']);
