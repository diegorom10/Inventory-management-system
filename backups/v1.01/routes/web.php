<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\tooltypeController;
use App\Http\Controllers\entrancesController;
use App\Http\Controllers\InventarioController;


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

Route::get('/', function () {
    return view('home');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('catalogo', [CatalogoController::class, 'index'])->name('catalogo.index');
Route::post('catalogo/registrar', [CatalogoController::class, 'registrar'])->name('catalogo.registrar');
Route::get('catalogo/eliminar/{id}', [CatalogoController::class, 'eliminar'])->name('catalogo.eliminar');
Route::get('catalogo/editar/{id}', [CatalogoController::class, 'editar'])->name('catalogo.editar');
Route::post('catalogo/actualizar', [CatalogoController::class, 'actualizar'])->name('catalogo.actualizar');

// Rutas  de tipo_herramienta
Route::get('tipo', [tooltypeController::class, 'index'])->name('tipo.index');
Route::post('tipo/registrar', [tooltypeController::class, 'registrar'])->name('tipo.registrar');
Route::get('tipo/eliminar/{id}', [tooltypeController::class, 'eliminar'])->name('tipo.eliminar');
Route::get('tipo/editartipo/{id}', [tooltypeController::class, 'editartipo'])->name('tipo.editartipo');
Route::get('tipo/datos/{id}', [tooltypeController::class, 'datos'])->name('tipo.datos');

// Rutas de movimientos
Route::get('entradas', [entrancesController::class, 'index'])->name('entradas.index');
Route::post('entradas/registrar', [entrancesController::class, 'registrar'])->name('entradas.registrar');
Route::get('entradas/eliminar/{id}', [entrancesController::class, 'eliminar'])->name('entradas.eliminar');


Route::get('inventario', [InventarioController::class, 'index'])->name('inventario.index');
Route::post('inventario/registrar', [InventarioController::class, 'registrar'])->name('inventario.registrar');
Route::get('inventario/eliminar/{id}', [InventarioController::class, 'eliminar'])->name('inventario.eliminar');
Route::get('inventario/editar/{id}', [InventarioController::class, 'editar'])->name('inventario.editar');
Route::post('inventario/actualizar', [InventarioController::class, 'actualizar'])->name('inventario.actualizar');