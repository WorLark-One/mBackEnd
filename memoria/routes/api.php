<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ComunaController;
use App\Http\Controllers\UserController;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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
    $user = $request->user();
    $roles = $user->getRoleNames();
    $permisos = $user->getAllPermissions();
    return response()->json(['code' => '200','user' => $user, 'roles' => $roles, 'permisos' => $permisos], 200);
});


Route::group(['middleware' => ['auth:sanctum'], 'prefix' => 'private'], function () {
    Route::get('/getComunas', [ComunaController::class, 'index']);
    Route::post('/postComuna', [ComunaController::class, 'store']);
    Route::put('/updateComuna/{id}', [ComunaController::class, 'update']);
    Route::delete('/deleteComuna/{id}', [ComunaController::class, 'destroy']);
});


Route::group(['middleware' => [], 'prefix' => 'public'], function () {
    Route::post('/registerUser', [UserController::class, 'store']);
    Route::get('/getProductos', [ProductoController::class, 'index']);
    Route::post('/postProducto', [ProductoController::class, 'store']);
    Route::get('/getProducto/{id}', [ProductoController::class, 'mostrar']);
    Route::get('/getSearch/p={producto}/c={comuna}/ori={orientacion}/mp={marletplace}/rgp={rangoprecio}/pag={paginacion}', [ProductoController::class, 'search']);
});