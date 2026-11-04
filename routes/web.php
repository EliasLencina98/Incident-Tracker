<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncidenteController;

Route::get("/", [IncidenteController::class, "index"])->name("incidentes.index");
Route::get("/incidentes/crear", [IncidenteController::class, "create"])->name("incidentes.create");
Route::get("/incidentes/todas", [IncidenteController::class, "obtenerDatos"])->name("incidentes.obtenerDatos");
Route::post("/incidentes", [IncidenteController::class, "store"])->name("incidentes.store");
Route::get("/incidentes/{id}", [IncidenteController::class, "show"])->name(("incidentes.show"));
Route::get("/incidentes/{id}/edit", [IncidenteController::class, "edit"])->name("incidentes.edit");
Route::put("/incidentes/{id}", [IncidenteController::class, "update"])->name("incidentes.update");