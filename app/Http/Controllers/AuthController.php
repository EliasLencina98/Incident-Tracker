<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function create(){
        return view("incidentes.autenticaciones.login");
    }

    public function store(Request $request){
        //Normalizacion de email
        $request->merge(["email" => strtolower(trim($request->input("email", "")))]);

        $credenciales = $request->validate([
            "email" => ["required", "email"],
            "password" => ["required", "string"]
        ]);

        //Si las credenciales coinciden se lo envia al inicio
        if(Auth::attempt($credenciales)) {
            //Nuevo Id de sesión
            $request->session()->regenerate();
            return redirect()->route("incidentes.dashboard");
        }

        //Se reenvia al usuario al formulario con msj de error y el email ingresado
        return back()->withErrors(["email" => "El correo o la contraseña son incorrectos"])->onlyInput("email");
    }

    public function destroy(Request $request){
        //Elimina informacion de auteticacion y cierra session
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route("login");
    }




}
