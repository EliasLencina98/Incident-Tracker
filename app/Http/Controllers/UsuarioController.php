<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{

    public function index()
    {
        $usuarios = Usuario::all();
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'dni' => 'required|string|unique:usuarios,dni|max:15',
            'nombre' => 'required|string|max:255',
            'rol' => 'required|string|max:100', // Ejemplo: Administrador, HSE, Supervisor
        ]);

        Usuario::create([
            'dni' => $request->dni,
            'nombre' => strtolower($request->nombre),
            'rol' => $request->rol,
        ]);

        return redirect()->route('usuarios.create')->with('success', '¡Usuario registrado correctamente!');
    }

    public function edit(int $id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, int $id)
    {
        $usuario = Usuario::findOrFail($id);

        $request->validate([
            'dni' => 'required|string|max:15|unique:usuarios,dni,' . $usuario->id,
            'nombre' => 'required|string|max:255',
            'rol' => 'required|string|max:100',
        ]);

        $usuario->update([
            'dni' => $request->dni,
            'nombre' => strtolower($request->nombre),
            'rol' => $request->rol,
        ]);

        return redirect()->route('usuarios.index')->with('success', '¡Usuario actualizado correctamente!');
    }
}
