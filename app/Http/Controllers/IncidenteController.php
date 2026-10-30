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

public function store(Request $request){
    $validated = $request->validate([
        'fecha' => ['date', 'required'],
        'tipo' => ['string', 'required'],
        'gravedad' => ['string', 'required'],
        'ubicacion' => ['string', 'required'],
        'descripcion' => ['string', 'required'],
        'causa' => ['string', 'required'],
        'accion' => ['string', 'required'],
        'latitud' => ['numeric', 'required'],
        'longitud' => ['numeric', 'required']
    ]);

    Incidente::create([
        "fecha" => $validated['fecha'],
        "tipo" => $validated['tipo'],
        "gravedad" => $validated['gravedad'],
        "ubicacion" => $validated['ubicacion'],
        "latitud" => $validated['latitud'],
        "longitud" => $validated['longitud'],
        "descripcion" => $validated['descripcion'],
        "causa_raiz" => $validated['causa'],
        "accion_correlativa" => $validated['accion']
    ]);

    return redirect()->route('incidentes.index')
            ->with('mensaje', '¡incidente cargado correctamente!');
}

public function obtenerDatos(){
    $incidentes = Incidente::all();
    return response()->json($incidentes, 200);
}


}
