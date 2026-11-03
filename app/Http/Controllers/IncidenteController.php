<?php

namespace App\Http\Controllers;
use App\Models\Incidente;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class IncidenteController extends Controller
{
    
    public function index (): View 
    {
        return view ("incidentes.index");
    }

    public function create (): View
    {
        return view("incidentes.create");
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "fecha" => ["date", "required"],
            "tipo" => ["required", "in:Accidente vehicular,Falla mecanica,Incidente operativo"],
            "gravedad" => ["required", "in:Leve,Grave,Fatal,Danio Material"],
            "ubicacion" => ["string", "required"],
            "descripcion" => ["string", "required"],
            "causa" => ["nullable", "in:Factor humano,Falla mecanica,Condiciones climaticas,Otro"],
            "accion_correlativa" => ["nullable","string"],
            "estado" => ["required", "in:pendiente,en proceso,resuelto"],
            "latitud" => ["numeric", "required"],
            "longitud" => ["numeric", "required"]
        ]);

        Incidente::create([
            "fecha" => $validated["fecha"],
            "tipo" => $validated["tipo"],
            "gravedad" => $validated["gravedad"],
            "ubicacion" => $validated["ubicacion"],
            "latitud" => $validated["latitud"],
            "longitud" => $validated["longitud"],
            "descripcion" => $validated["descripcion"],
            "causa_raiz" => $validated["causa"],
            "accion_correlativa" => $validated["accion_correlativa"],
            "estado" => $validated["estado"]
        ]);

        return redirect()->route("incidentes.index")
                ->with("mensaje", "¡incidente cargado correctamente!");
    }

    public function obtenerDatos(Request $request)
    {
        //validacion
        $datosFiltros = $request->validate([
            "fecha_inicio" => ["nullable", "date_format:Y-m-d"],
            "fecha_fin" => ["nullable", "date_format:Y-m-d", Rule::when($request->filled("fecha_inicio"), "after_or_equal:fecha_inicio")],
            "gravedad" => ["nullable", "in:Leve,Grave,Fatal,Danio Material"],
            "tipo" => ["nullable", "in:Accidente vehicular,Falla mecanica,Incidente operativo"],
        ]);

        //inicio consulta
        $consulta = Incidente::query();

        if(!empty($datosFiltros["fecha_inicio"])) {
            $consulta->where("fecha", ">=", $datosFiltros["fecha_inicio"]);
        }

        if(!empty($datosFiltros["fecha_fin"])) {
            $consulta->where("fecha", "<=", $datosFiltros["fecha_fin"]);
        }

        if (!empty($datosFiltros["gravedad"])) {
            $consulta->where("gravedad", $datosFiltros["gravedad"]);
        }

        if (!empty($datosFiltros["tipo"])) {
            $consulta->where("tipo", $datosFiltros["tipo"]);
        }

        $incidentes = $consulta->orderBy("fecha", "desc")->orderBy("id", "desc")->get();

        //respondo con .json
        return response()->json($incidentes);
    }

}
