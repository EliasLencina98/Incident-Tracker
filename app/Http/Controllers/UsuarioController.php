<?php

namespace App\Http\Controllers;

use App\Models\Chofer;
use App\Models\User;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{

    public function create()
    {
        return view('incidentes.usuarios.create');
    }

    public function store(Request $request)
    {
        //Normalizacion de email
        $request->merge(['email' => strtolower(trim($request->input('email', ''))),]);

        $datos = $request->validate([
            "password" => "required|string|min:8|confirmed", //exige una confirmacion de la contraseña en el formulario 
            "email" => "required|email|max:225|unique:users,email", //Verifica que no hay otro usuario con el mismo email
            'dni' => 'required|string|unique:users,dni|max:15',     //Verifica que no hay otro usuario con el mismo dni
            'name' => 'required|string|max:255',
            'rol' => 'required|in:administrador,operador',  //Administrador y operador
        ]);

        User::create($datos);

        return redirect()->route('usuarios.create')->with('mensaje', '¡Usuario registrado correctamente!');
    }

   
}
