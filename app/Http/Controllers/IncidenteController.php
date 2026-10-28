<?php

namespace App\Http\Controllers;

use App\Models\Incidente;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IncidenteController extends Controller
{
    
public function index (): View 
{
    $incidentes = Incidente::orderBy("fecha", "desc")->orderBy("id", "desc")->get();

    return view ("incidentes.index", compact("incidentes"));
}

public function create (): View
{
    return view("incidentes.create");
}






}
