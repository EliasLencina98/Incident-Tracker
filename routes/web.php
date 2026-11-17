<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncidenteController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\ChoferController;
use App\Http\Controllers\UsuarioController;

Route::get("/", [IncidenteController::class, "index"])->name("incidentes.index");
Route::get("/incidentes/crear", [IncidenteController::class, "create"])->name("incidentes.create");
Route::get("/incidentes/todas", [IncidenteController::class, "obtenerDatos"])->name("incidentes.obtenerDatos");
Route::post("/incidentes", [IncidenteController::class, "store"])->name("incidentes.store");
Route::get("/incidentes/{id}", [IncidenteController::class, "show"])->name(("incidentes.show"));
Route::get("/incidentes/{id}/edit", [IncidenteController::class, "edit"])->name("incidentes.edit");
Route::put("/incidentes/{id}", [IncidenteController::class, "update"])->name("incidentes.update");

// rutas vehiculo

Route::get('/vehiculos/crear', [VehiculoController::class, 'create'])->name('vehiculos.create');  //falta crear vista
Route::post('/vehiculos', [VehiculoController::class, 'store'])->name('vehiculos.store');
Route::get('/vehiculos/{id}/editar', [VehiculoController::class, 'edit'])->name('vehiculos.edit');
Route::put('/vehiculos/{id}', [VehiculoController::class, 'update'])->name('vehiculos.update');

// rutas choferes

Route::get('/choferes/crear', [ChoferController::class, 'create'])->name('choferes.create');  //falta crear vista
Route::post('/choferes', [ChoferController::class, 'store'])->name('choferes.store');
Route::get('/choferes/{id}/editar', [ChoferController::class, 'edit'])->name('choferes.edit');
Route::put('/choferes/{id}', [ChoferController::class, 'update'])->name('choferes.update');

// rutas usuarios

Route::get('/usuarios/crear', [UsuarioController::class, 'create'])->name('usuarios.create');  //falta crear vista
Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
Route::get('/usuarios/{id}/editar', [UsuarioController::class, 'edit'])->name('usuarios.edit');
Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');