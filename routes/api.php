<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\UsuarioController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class, "login"]);
    Route::post('logout', [AuthController::class, "logout"]);
    Route::post('refresh', [AuthController::class, "refresh"]);
    Route::get('me', [AuthController::class, "me"]);

});



Route::group(['middleware' => 'auth:api'], function ($router) {

    // Api resource
    Route::post("/producto/{id}/asignar_sucursal", [ProductoController::class, "asignar_sucursal"]);

    Route::apiResource("/usuario", UsuarioController::class);
    Route::apiResource("/categoria", CategoriaController::class);
    Route::apiResource("/sucursal", SucursalController::class);
    Route::apiResource("/producto", ProductoController::class);
    Route::apiResource("/personal", PersonaController::class);
});