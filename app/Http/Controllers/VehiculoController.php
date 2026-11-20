<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;
use Illuminate\Http\Request;

class VehiculoController extends Controller
{

    public function index()
    {
        $vehiculos = Vehiculo::all(); 
        return view('vehiculos.index', compact('vehiculos'));
    }

    public function create()
    {
        return view('incidentes.vehiculos.create');
    }

    public function show(int $id)
    {
        $vehiculo = Vehiculo::findOrFail($id);
        return view('incidentes.vehiculos.show', compact('vehiculo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patente' => 'required|string|unique:vehiculo,patente|max:20',
            'modelo' => 'required|string|max:255',
            'anio' => 'required|digits:4|integer|min:1950|max:' . (date('Y') + 1),
            'tipo_vehiculo' => 'required|in:transporte de personal,pesado,liviano',
        ]);

        Vehiculo::create([
            'patente' => strtoupper($request->patente),
            'modelo' => $request->modelo,
            'anio' => $request->anio,
            'tipo_vehiculo' => $request->tipo_vehiculo,
        ]);

        return redirect()->route('incidentes.index')->with('mensaje', '¡Vehículo registrado con éxito!');
    }

    public function edit(int $id)
    {
        $vehiculo = Vehiculo::findOrFail($id);
        return view('incidentes.vehiculos.edit', compact('vehiculo'));
    }

    public function update(Request $request, int $id)
    {
        $vehiculo = Vehiculo::findOrFail($id);

        $request->validate([
            'patente' => 'required|string|max:20|unique:vehiculo,patente,' . $vehiculo->id,
            'modelo' => 'required|string|max:255',
            'anio' => 'required|digits:4|integer|min:1950|max:' . (date('Y') + 1),
            'tipo_vehiculo' => 'required|in:transporte de personal,pesado,liviano',
        ]);

        $vehiculo->update([
            'patente' => strtoupper($request->patente),
            'modelo' => $request->modelo,
            'anio' => $request->anio,
            'tipo_vehiculo' => $request->tipo_vehiculo,
        ]);

        return redirect()->route('incidentes.index')->with('mensaje', '¡Vehículo actualizado con éxito!');
    }

    public function obtenerDatos(Request $request){
        $datos = $request->validate([
            "buscar" => ["nullable", "string", "max:100"]
        ]);

        $consulta = Vehiculo::query();

        if(!empty($datos["buscar"])){
            $buscar = "%" . $datos["buscar"] . "%";

            $consulta->where(function ($query) use ($buscar) {
                $query->where("patente", "like", $buscar)
                ->orWhere("modelo", "like", $buscar)
                ->orWhere("tipo_vehiculo", "like", $buscar);
            });
        }

        $resultado = ["vehiculos" => $consulta->orderBy("patente")->get()];
        return response()->json($resultado);
    }
}
