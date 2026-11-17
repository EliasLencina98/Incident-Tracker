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
        return view('choferes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'dni' => 'required|string|unique:choferes,dni|max:15',
            'nombre' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date|before:-18 years',
            'fecha_ingreso' => 'required|date|before_or_equal:today',
            'puesto' => 'required|string|max:100',
            'licencia' => 'required|string|max:50',
        ]);

        Chofer::create([
            'dni' => $request->dni,
            'nombre' => strtolower($request->nombre),
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'fecha_ingreso' => $request->fecha_ingreso,
            'puesto' => $request->puesto,
            'licencia' => strtoupper($request->licencia),
        ]);

        return redirect()->route('choferes.create')->with('success', '¡Chofer registrado correctamente!');
    }

    public function edit(int $id)
    {
        $chofer = Chofer::findOrFail($id);
        return view('choferes.edit', compact('chofer'));
    }

    public function update(Request $request, int $id)
    {
        $chofer = Chofer::findOrFail($id);

        $request->validate([
            'dni' => 'required|string|max:15|unique:choferes,dni,' . $chofer->id,
            'nombre' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'fecha_ingreso' => 'required|date',
            'puesto' => 'required|string|max:100',
            'licencia' => 'required|string|max:50',
        ]);

        $chofer->update([
            'dni' => $request->dni,
            'nombre' => strtolower($request->nombre),
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'fecha_ingreso' => $request->fecha_ingreso,
            'puesto' => $request->puesto,
            'licencia' => strtoupper($request->licencia),
        ]);

        return redirect()->route('choferes.index')->with('success', '¡Chofer actualizado correctamente!');
    }
}
