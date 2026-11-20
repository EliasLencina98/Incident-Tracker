<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncidenteController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\ChoferController;
use App\Http\Controllers\UsuarioController;

Route::view("/", "incidentes.dashboard")->name("incidentes.dashboard");
Route::view("/mapa", "incidentes.mapa")->name("incidentes.mapa");

// rutas incidentes
Route::get("/incidentes", [IncidenteController::class, "index"])->name("incidentes.index");
Route::get("/incidentes/crear", [IncidenteController::class, "create"])->name("incidentes.create");
Route::get("/incidentes/todas", [IncidenteController::class, "obtenerDatos"])->name("incidentes.obtenerDatos");
Route::post("/incidentes", [IncidenteController::class, "store"])->name("incidentes.store");
Route::get("/incidentes/{id}", [IncidenteController::class, "show"])->name(("incidentes.show"));
Route::get("/incidentes/{id}/edit", [IncidenteController::class, "edit"])->name("incidentes.edit");
Route::put("/incidentes/{id}", [IncidenteController::class, "update"])->name("incidentes.update");

// rutas vehiculo

Route::get('/vehiculos/crear', [VehiculoController::class, 'create'])->name('vehiculos.create');  //falta crear vista
Route::get("/vehiculos/datos", [VehiculoController::class, "obtenerDatos"])->name("vehiculos.obtenerDatos");
Route::post('/vehiculos', [VehiculoController::class, 'store'])->name('vehiculos.store');
Route::get('/vehiculos/{id}/editar', [VehiculoController::class, 'edit'])->name('vehiculos.edit');
Route::put('/vehiculos/{id}', [VehiculoController::class, 'update'])->name('vehiculos.update');
Route::get('/vehiculos/{id}', [VehiculoController::class, 'show'])->name('vehiculos.show');


// rutas choferes

Route::get('/choferes/crear', [ChoferController::class, 'create'])->name('choferes.create');  //falta crear vista
Route::get("choferes/datos", [ChoferController::class, "obtenerDatos"])->name("choferes.obtenerDatos");
Route::post('/choferes', [ChoferController::class, 'store'])->name('choferes.store');
Route::get('/choferes/{id}/editar', [ChoferController::class, 'edit'])->name('choferes.edit');
Route::put('/choferes/{id}', [ChoferController::class, 'update'])->name('choferes.update');
Route::get('/choferes/{id}', [ChoferController::class, 'show'])->name('choferes.show');


// rutas usuarios

Route::get('/usuarios/crear', [UsuarioController::class, 'create'])->name('usuarios.create');  //falta crear vista
Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
Route::get('/usuarios/{id}/editar', [UsuarioController::class, 'edit'])->name('usuarios.edit');
Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');