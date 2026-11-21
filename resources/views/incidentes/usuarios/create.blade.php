<!-- Solo administrador -->
@extends('layouts.panel')

@section('titulo', 'Nuevo usuario')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/gestion.css') }}">
@endpush

@section('contenido')
    <section class="gestion">
        <div class="panel-tarjeta gestion-encabezado">
            <h1>Crear usuario</h1>
        </div>

        <div class="panel-tarjeta gestion-formulario">
            @if ($errors->any())
                <ul class="gestion-errores">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form action="{{ route('usuarios.store') }}" method="POST">
                @csrf

                <div class="gestion-campos">
                    <div>
                        <label for="name">Nombre y apellido</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" maxlength="255" required>
                    </div>

                    <div>
                        <label for="dni">DNI</label>
                        <input type="text" id="dni" name="dni" value="{{ old('dni') }}" maxlength="15" required>
                    </div>

                    <div>
                        <label for="email">Correo electrónico</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                    </div>

                    <div>
                        <label for="rol">Rol</label>
                        <select id="rol" name="rol" required>
                            <option value="operador" @selected(old('rol', 'operador') === 'operador')>Operador</option>
                            <option value="administrador" @selected(old('rol') === 'administrador')>Administrador</option>
                        </select>
                    </div>

                    <div>
                        <label for="password">Contraseña (Mínimo 8 caracteres)</label>
                        <input type="password" id="password" name="password" minlength="8" required>
                    </div>

                    <div>
                        <label for="password_confirmation">Repetir contraseña</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" minlength="8" required>
                    </div>
                </div>

                <div class="gestion-formulario-acciones">
                    <a href="{{ route('incidentes.dashboard') }}" class="panel-boton gestion-boton-secundario">
                        Volver
                    </a>

                    <button type="submit" class="panel-boton gestion-boton-azul">
                        Crear usuario
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection