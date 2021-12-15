<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\tooltypeController;
use App\Http\Controllers\entrancesController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\utldticketsController;


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
//Route::get('catalogo/eliminar/{id}', [CatalogoController::class, 'eliminar'])->name('catalogo.eliminar');
Route::post('catalogo/eliminar', [CatalogoController::class, 'eliminar'])->name('catalogo.eliminar'); 
Route::get('catalogo/editar/{id}', [CatalogoController::class, 'editar'])->name('catalogo.editar');
Route::post('catalogo/actualizar', [CatalogoController::class, 'actualizar'])->name('catalogo.actualizar');
Route::get('catalogo/fetchCategorias', [CatalogoController::class, 'fetchCategorias'])->name('catalogo.fetchCategorias');
Route::get('catalogo/export/{id}', [CatalogoController::class, 'exportKardex'])->name('catalogo.export');

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
Route::get('inventario/fetchTools', [InventarioController::class, 'fetchTools'])->name('inventario.fetchTools');
Route::get('inventario/getTool/{codigo}', [InventarioController::class, 'getTool'])->name('inventario.getTool');
Route::post('inventario/hacerPrestamo', [InventarioController::class, 'hacerPrestamo'])->name('inventario.hacerPrestamo');
Route::get('inventario/getTicket/{id}', [InventarioController::class, 'getTicket'])->name('inventario.getTicket');
Route::get('inventario/export', [InventarioController::class, 'exportInventario'])->name('inventario.export');
Route::get('inventario/getPrestamos', [InventarioController::class, 'getPrestamos'])->name('inventario.getPrestamos');
Route::get('inventario/getPrestamoDetalle/{id}', [InventarioController::class, 'getPrestamoDetalle'])->name('inventario.getPrestamoDetalle');
Route::post('inventario/regresarPrestamo', [InventarioController::class, 'regresarPrestamo'])->name('inventario.regresarPrestamo');
Route::get('inventario/getMovimiento/{ticket}', [InventarioController::class, 'getMovimiento'])->name('inventario.getMovimiento');
Route::post('inventario/insertFaltantes', [InventarioController::class, 'insertFaltantes'])->name('inventario.insertFaltantes');
Route::get('inventario/fetchFaltantes', [InventarioController::class, 'fetchFaltantes'])->name('inventario.fetchFaltantes');
Route::get('inventario/exportFaltantes/{accion}', [InventarioController::class, 'exportFaltantes'])->name('inventario.exportFaltantes');
Route::post('inventario/confirmarPendiente', [InventarioController::class, 'confirmarPendiente'])->name('inventario.confirmarPendiente');


Route::get('ticket', [InventarioController::class, 'indexTicket'])->name('inventario.indexTicket');
Route::get('regreso', [InventarioController::class, 'indexRegreso'])->name('inventario.indexRegresos');

Route::get('/peticiones', [utldticketsController::class, 'index'])->name('peticiones');