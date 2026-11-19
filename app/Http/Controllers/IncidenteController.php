<?php

namespace App\Http\Controllers;

use App\Models\Incidente;
use App\Models\Vehiculo;
use App\Models\Chofer;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;

class IncidenteController extends Controller
{

    public function index(): View
    {

        $incidentes = Incidente::with(['vehiculo', 'chofer'])->orderBy("fecha", "desc")->orderBy("id", "desc")->get();

        return view("incidentes.index", compact("incidentes"));
    }


    public function create(): View
    {
        // Necesitamos mandar los vehículos y choferes a la vista para poblar los <select>
        $vehiculos = Vehiculo::all();
        $choferes = Chofer::all();

        return view("incidentes.create", compact("vehiculos", "choferes"));
    }

    public function show(Int $id): View
    {
        $incidente = Incidente::with(['vehiculo', 'chofer'])->findOrFail($id);

        return view("incidentes.show", compact("incidente"));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            "fecha" => ["date", "required", "date_format:Y-m-d"],
            "hora" => ["nullable", "date_format:H:i"],
            "tipo" => ["required", "in:colision/choque vehicular,impacto objeto fijo,vuelco,despiste,atropello,falla mecanica,principio de incendio/incendio,daño menor,casi incidente"],
            "fatalidad" => ["required", "integer"],
            "heridos" => ["required", "integer", "min:0"],
            "ubicacion" => ["string", "required"],
            "latitud" => ["numeric", "required"],
            "longitud" => ["numeric", "required"],
            "estado_calzada" => ["nullable", "string"],
            "clima" => ["nullable", "string"],
            "descripcion" => ["string", "required"],
            "causa_raiz" => ["nullable", "in:Factor humano,Falla mecanica,Condiciones climaticas,Otro"],
            "accion_correlativa" => ["nullable", "string"],
            "estado" => ["required", "in:pendiente,en proceso,resuelto"],
            "vehiculo_id" => ["required", "exists:vehiculos,id"],
            "chofer_id" => ["required", "exists:choferes,id"],
        ]);

        // calculamos automaticamente la gravedad usando la matriz de gravedad
        $gravedadCalculada = Incidente::calcularGravedad(
            $validated["tipo"],
            $validated["heridos"],
            $validated["fatalidad"]
        );

        Incidente::create([
            "fecha" => $validated["fecha"],
            "hora" => $validated["hora"] ?? null,
            "tipo" => $validated["tipo"],
            "fatalidad" => $validated["fatalidad"],
            "heridos" => $validated["heridos"],
            "gravedad" => $gravedadCalculada, // 
            "ubicacion" => $validated["ubicacion"],
            "latitud" => $validated["latitud"],
            "longitud" => $validated["longitud"],
            "estado_calzada" => $validated["estado_calzada"] ?? null,
            "clima" => $validated["clima"] ?? null,
            "descripcion" => $validated["descripcion"],
            "causa_raiz" => $validated["causa_raiz"] ?? null,
            "accion_correlativa" => $validated["accion_correlativa"] ?? null,
            "estado" => $validated["estado"],
            "vehiculo_id" => $validated["vehiculo_id"],
            "chofer_id" => $validated["chofer_id"],
        ]);

        return redirect()->route("incidentes.index")
            ->with("mensaje", "¡Incidente cargado correctamente!");
    }

    public function edit(Int $id): View
    {
        $incidente = Incidente::findOrFail($id);
        $vehiculos = Vehiculo::all();
        $choferes = Chofer::all();

        return view("incidentes.edit", compact("incidente", "vehiculos", "choferes"));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $incidente = Incidente::findOrFail($id);

        $datosValidados = $request->validate([
            "fecha" => ["date", "required", "date_format:Y-m-d"],
            "hora" => ["nullable", "date_format:H:i"],
            "tipo" => ["required", "in:colision/choque vehicular,impacto objeto fijo,vuelco,despiste,atropello,falla mecanica,principio de incendio/incendio,daño menor,casi incidente"],
            "fatalidad" => ["required", "integer"],
            "heridos" => ["required", "integer", "min:0"],
            "ubicacion" => ["string", "required"],
            "latitud" => ["numeric", "required"],
            "longitud" => ["numeric", "required"],
            "estado_calzada" => ["nullable", "string"],
            "clima" => ["nullable", "string"],
            "descripcion" => ["string", "required"],
            "causa_raiz" => ["nullable", "in:Factor humano,Falla mecanica,Condiciones climaticas,Otro"],
            "accion_correlativa" => ["nullable", "string"],
            "estado" => ["required", "in:pendiente,en proceso,resuelto"],
            "vehiculo_id" => ["required", "exists:vehiculos,id"],
            "chofer_id" => ["required", "exists:choferes,id"],
        ]);

        // en caso que se modifique el tipo, heridos o fatalidad, volvemos a recalcular
        $datosValidados['gravedad'] = Incidente::calcularGravedad(
            $datosValidados["tipo"],
            $datosValidados["heridos"],
            $datosValidados["fatalidad"]
        );

        $incidente->update($datosValidados);

        return redirect()->route("incidentes.index")->with("mensaje", "¡Incidente modificado correctamente!");
    }

    public function obtenerDatos(Request $request)
    {

        $datosFiltros = $request->validate([
            "fecha_inicio" => ["nullable", "date_format:Y-m-d"],
            "fecha_fin" => ["nullable", "date_format:Y-m-d", Rule::when($request->filled("fecha_inicio"), "after_or_equal:fecha_inicio")],
            "gravedad" => ["nullable", "in:leve,moderado,grave,fatal"],
            "tipo" => ["nullable", "string"],
            "estado" => ["nullable", "in:pendiente,en proceso,resuelto"],
        ]);

        $consulta = Incidente::with(['vehiculo', 'chofer']);

        if (!empty($datosFiltros["fecha_inicio"])) {
            $consulta->where("fecha", ">=", $datosFiltros["fecha_inicio"]);
        }

        if (!empty($datosFiltros["fecha_fin"])) {
            $consulta->where("fecha", "<=", $datosFiltros["fecha_fin"]);
        }

        if (!empty($datosFiltros["gravedad"])) {
            $consulta->where("gravedad", $datosFiltros["gravedad"]);
        }

        if (!empty($datosFiltros["tipo"])) {
            $consulta->where("tipo", $datosFiltros["tipo"]);
        }

        if (!empty($datosFiltros["estado"])) {
            $consulta->where("estado", $datosFiltros["estado"]);
        }

        $incidentes = (clone $consulta)->orderBy("fecha", "desc")->orderBy("id", "desc")->get();

        $total = (clone $consulta)->count();
        $leves = (clone $consulta)->where("gravedad", "leve")->count();
        $moderados = (clone $consulta)->where("gravedad", "moderado")->count();
        $graves = (clone $consulta)->where("gravedad", "grave")->count();
        $fatales = (clone $consulta)->where("gravedad", "fatal")->count();

        return response()->json([
            'incidentes' => $incidentes,
            'metricas' => [
                'total' => $total,
                'leves' => $leves,
                'moderados' => $moderados,
                'graves' => $graves,
                'fatales' => $fatales,
            ]
        ]);
    }
}
