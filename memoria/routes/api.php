<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => [], 'prefix' => 'public'], function () {
    Route::get('/getProductos', [ProductoController::class, 'index']);
    Route::post('/postProducto', [ProductoController::class, 'store']);
    Route::get('/getProducto/{id}', [ProductoController::class, 'show']);
    Route::get('/getSearch/p={producto}/c={comuna}/pag={paginacion}', [ProductoController::class, 'search']);
});