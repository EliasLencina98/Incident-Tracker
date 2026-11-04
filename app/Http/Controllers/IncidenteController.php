<?php

namespace App\Http\Controllers;
use App\Models\Incidente;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;

class IncidenteController extends Controller
{
    
    public function index (): View 
    {
        $total = Incidente::count();
        $leves = Incidente::where("gravedad", "Leve")->count();
        $graves = Incidente::where("gravedad", "Grave")->count();
        $fatales = Incidente::where("gravedad", "Fatal")->count();
        $danioMaterial = Incidente::where("gravedad", "Danio Material")->count();

        return view ("incidentes.index", compact("total", "leves", "graves", "fatales", "danioMaterial"));
    }

    public function create (): View
    {
        return view("incidentes.create");
    }

    public function show (Int $id): View
    {
        $incidente = Incidente::findOrFail($id);
        
        return view("incidentes.show", compact("incidente"));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "fecha" => ["date", "required", "date_format:Y-m-d"],
            "tipo" => ["required", "in:Accidente vehicular,Falla mecanica,Incidente operativo"],
            "gravedad" => ["required", "in:Leve,Grave,Fatal,Danio Material"],
            "ubicacion" => ["string", "required"],
            "descripcion" => ["string", "required"],
            "causa_raiz" => ["required", "in:Factor humano,Falla mecanica,Condiciones climaticas,Otro"],
            "accion_correlativa" => ["string", "required"],
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
            "causa_raiz" => $validated["causa_raiz"],
            "accion_correlativa" => $validated["accion_correlativa"],
            "estado" => $validated["estado"]
        ]);

        return redirect()->route("incidentes.index")
                ->with("mensaje", "¡Incidente cargado correctamente!");
    }

    public function edit(Int $id): View
    {
        $incidente = Incidente::findOrFail($id);
        return view("incidentes.edit", compact("incidente"));
    }

    public function update (Request $request, int $id): RedirectResponse
    {
        $incidente = Incidente::findOrFail($id);

        $datosValidados = $request->validate([
            "fecha" => ["date", "required", "date_format:Y-m-d"],
            "tipo" => ["required", "in:Accidente vehicular,Falla mecanica,Incidente operativo"],
            "gravedad" => ["required", "in:Leve,Grave,Fatal,Danio Material"],
            "ubicacion" => ["string", "required"],
            "descripcion" => ["string", "required"],
            "causa_raiz" => ["required", "in:Factor humano,Falla mecanica,Condiciones climaticas,Otro"],
            "accion_correlativa" => ["string", "required"],
            "estado" => ["required", "in:pendiente,en proceso,resuelto"],
        ]);

        $incidente->update($datosValidados);
        return redirect()->route("incidentes.index")->with("mensaje", "¡Incidente modificado correctamente!");
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
