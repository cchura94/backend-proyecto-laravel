<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\Http\Controllers\CursoController;

Route::get('/', function () {
    return view('welcome');
});

Route::get("/saludos", [CursoController::class, "prueba"]);

// Route::resource("/curso", CursoController::class);
// GET, POST, PUT, DELETE ||||  index, create, show, store, update, edit, destroy