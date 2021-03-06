<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PrecioProductoController;
use App\Http\Controllers\ComunaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ValoracionProductoController;
use App\Http\Controllers\MiListaUserController;
use App\Http\Controllers\NotificacionUserController;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\MiCartUserController;
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
    Route::get('/getRatingUser/{usuario_id}', [ValoracionProductoController::class, 'ratingUser']);
    Route::get('/getIsRating/{usuario_id}/{producto_id}', [ValoracionProductoController::class, 'isRating']);
    Route::delete('/deleteRating/{id}/{usuario_id}', [ValoracionProductoController::class, 'destroy']);
    Route::put('/updateRating/{id}', [ValoracionProductoController::class, 'update']);
    Route::post('/postRating', [ValoracionProductoController::class, 'store']);
    Route::post('/postProductMiList', [MiListaUserController::class, 'store']);
    Route::delete('/deleteProductMiList/{id}/{usuario_id}', [MiListaUserController::class, 'destroy']);
    Route::delete('/deleteAuxProductMiList/{id}/{usuario_id}', [MiListaUserController::class, 'destroyAux']);
    Route::get('/getOnUserList/{usuario_id}/{producto_id}', [MiListaUserController::class, 'onUserList']);
    Route::get('/getUserList/{usuario_id}', [MiListaUserController::class, 'userList']);
    //Route::get('/getNotificationUser', [ProductoController::class, 'getNotificacionUser']);
    //Route::any('/markReadNotificacion', [ProductoController::class, 'markReadNotificacion']);
    //Route::any('/sendNotificacion/{user_id}', [ProductoController::class, 'sendNotificacion']);
    Route::get('/getNotificationUser/{usuario_id}', [NotificacionUserController::class, 'show']);
    Route::put('/markReadNotificacion/{usuario_id}', [NotificacionUserController::class, 'readNotify']);
    Route::delete('/deleteNotificacion/{id}/{usuario_id}', [NotificacionUserController::class, 'destroy']);

    
    Route::get('/getRegion',[RegionController::class, 'index']);
    Route::post('/postRegion',[RegionController::class, 'store']);
    Route::put('/updateRegion/{id}', [RegionController::class, 'update']);
    Route::delete('/deleteRegion/{id}', [RegionController::class, 'destroy']);

    Route::get('/getComunas', [ComunaController::class, 'index']);
    Route::post('/postComuna', [ComunaController::class, 'store']);
    Route::put('/updateComuna/{id}', [ComunaController::class, 'update']);
    Route::delete('/deleteComuna/{id}', [ComunaController::class, 'destroy']);
    Route::get('/enviarNotificacion/{id_producto}/{descuento}', [ProductoController::class, 'nuevoDescuento']);

    Route::post('/postProductoCart',[MiCartUserController::class, 'store']);
    Route::delete('/deleteProductoCart/{id}/{usuario_id}',[MiCartUserController::class, 'destroy']);
    Route::get('/getCartUsuario/{usuario_id}',[MiCartUserController::class, 'userCartList']);
    Route::delete('/deleteProductoCartRaiz/{id}/{usuario_id}',[MiCartUserController::class, 'destroyRaiz']);
});


Route::group(['middleware' => [], 'prefix' => 'public'], function () {
    Route::get('/getComunasPublic', [ComunaController::class, 'index']);
    Route::post('/registerUser', [UserController::class, 'store']);
    Route::get('/getProductos', [ProductoController::class, 'index']);
    Route::get('/getHomeProductos', [ProductoController::class, 'homeProducts']);
    Route::post('/postProducto', [ProductoController::class, 'store']);
    Route::get('/getProducto/{id}', [ProductoController::class, 'mostrar']);
    Route::get('/productoVisitado/{id}', [ProductoController::class, 'productoVisitado']);
    Route::get('/getHistorial/{id}', [PrecioProductoController::class, 'show']);
    Route::get('/getDetailsRating/{producto_id}', [ValoracionProductoController::class, 'detailsRating']);
    Route::get('/getSearch/p={producto}/c={comuna}/ori={orientacion}/mp={marletplace}/rgp={rangoprecio}/val={valoracion}/pag={paginacion}', [ProductoController::class, 'search']);
});