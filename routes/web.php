<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncidenteController;

Route::get('/', function () { return view('welcome');});
Route::get("/", [IncidenteController::class, "index"])->name("incidentes.index");
Route::get("/incidentes/crear", [IncidenteController::class, "create"])->name("incidentes.create");
