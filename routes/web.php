<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncidenteController;

Route::get("/", [IncidenteController::class, "index"])->name("incidentes.index");
Route::get("/incidentes/crear", [IncidenteController::class, "create"])->name("incidentes.create");
Route::get('api/incidentes/todas', [IncidenteController::class, 'obtenerDatos'])->name('incidentes.obtenerDatos');
Route::post('incidentes/', [IncidenteController::class, 'store'])->name('incidentes.store');