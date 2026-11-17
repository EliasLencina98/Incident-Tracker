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
        return view('vehiculos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'patente' => 'required|string|unique:vehiculos,patente|max:20',
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

        return redirect()->route('vehiculos.create')->with('success', '¡Vehículo registrado con éxito!');
    }

    public function edit(int $id)
    {
        $vehiculo = Vehiculo::findOrFail($id);
        return view('vehiculos.edit', compact('vehiculo'));
    }

    public function update(Request $request, int $id)
    {
        $vehiculo = Vehiculo::findOrFail($id);

        $request->validate([
            'patente' => 'required|string|max:20|unique:vehiculos,patente,' . $vehiculo->id,
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

        return redirect()->route('vehiculos.index')->with('success', '¡Vehículo actualizado con éxito!');
    }
}
