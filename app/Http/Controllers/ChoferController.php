<?php

namespace App\Http\Controllers;

use App\Models\Chofer;
use Illuminate\Http\Request;

class ChoferController extends Controller
{

    public function index()
    {
        $choferes = Chofer::all();
        return view('choferes.index', compact('choferes'));
    }

    public function create()
    {
        return view("incidentes.choferes.create");
    }

    public function show(int $id)
    {
        $chofer = Chofer::findOrFail($id);
        return view('incidentes.choferes.show', compact('chofer'));
        }

    public function store(Request $request)
    {
        $request->validate([
            'dni' => 'required|string|unique:chofer,dni|max:15',
            'nombre' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date|before:-18 years',
            'fecha_ingreso' => 'required|date|before_or_equal:today',
            'puesto' => 'required|string|max:100',
            'licencia' => 'required|string|max:50',
        ]);

        Chofer::create([
            'dni' => $request->dni,
            'nombre' => $request->nombre,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'fecha_ingreso' => $request->fecha_ingreso,
            'puesto' => $request->puesto,
            'licencia' => strtoupper($request->licencia),
        ]);

        return redirect()->route('incidentes.index')->with('mensaje', '¡Chofer registrado correctamente!');
    }

    public function edit(int $id)
    {
        $chofer = Chofer::findOrFail($id);
        return view('incidentes.choferes.edit', compact('chofer'));
    }

    public function update(Request $request, int $id)
    {
        $chofer = Chofer::findOrFail($id);

        $request->validate([
            'dni' => 'required|string|max:15|unique:chofer,dni,' . $chofer->id,
            'nombre' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date|before:-18 years',
            'fecha_ingreso' => 'required|date|before_or_equal:today',
            'puesto' => 'required|string|max:100',
            'licencia' => 'required|string|max:50',
        ]);

        $chofer->update([
            'dni' => $request->dni,
            'nombre' => $request->nombre,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'fecha_ingreso' => $request->fecha_ingreso,
            'puesto' => $request->puesto,
            'licencia' => strtoupper($request->licencia),
        ]);

        return redirect()->route('incidentes.index')->with('mensaje', '¡Chofer actualizado correctamente!');
    }

    public function obtenerDatos(Request $request){
        $datos =$request->validate([
            "buscar" => ["nullable", "string", "max:100"],
        ]);

        $consulta = Chofer::query();

        if(!empty($datos["buscar"])) {
            $buscar = "%" . trim($datos["buscar"]) . "%";

            $consulta->where(function($query) use ($buscar){
                $query->where("nombre", "like", $buscar)
                ->orWhere("dni", "like", $buscar)
                ->orWhere("licencia", "like", $buscar);
            });
        }

        $resultado = ["choferes" => $consulta->orderBy("nombre")->get()];
        return response()->json($resultado);
    }

    public function destroy(int $id)
    {
        $chofer = Chofer::findOrFail($id);

        //Solo si el chofer no esta involucrado en ningun accidente
        if($chofer->incidentes()->exists()){
            return response()->json(["mensaje" => "No se puede eliminar, el chofer tiene incidentes asociados."], 400);
        }

        $chofer->delete();

        return response()->json([
            'mensaje' => 'Chofer eliminado correctamente.',
        ]);
    }
}
