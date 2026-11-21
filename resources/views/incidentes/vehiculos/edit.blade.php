@extends('layouts.panel')

@section('titulo', 'Editar vehículo')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/gestion.css') }}">
@endpush

@section('contenido')
    <section class="gestion">
        <div class="panel-tarjeta gestion-encabezado">
            <h1>Editar vehículo</h1>

            <a href="{{ route('incidentes.index') }}" class="panel-boton gestion-boton-secundario">
                Volver al listado
            </a>
        </div>

        <div class="panel-tarjeta gestion-formulario">
            @if ($errors->any())
                <ul class="gestion-errores">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form action="{{ route('vehiculos.update', $vehiculo->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="gestion-campos">
                    <div>
                        <label for="patente">Patente</label>
                        <input type="text" id="patente" name="patente" value="{{ old('patente', $vehiculo->patente) }}" maxlength="20" required>
                    </div>

                    <div>
                        <label for="modelo">Marca y modelo</label>
                        <input type="text" id="modelo" name="modelo" value="{{ old('modelo', $vehiculo->modelo) }}" maxlength="255" required>
                    </div>

                    <div>
                        <label for="anio">Año</label>
                        <input type="number" id="anio" name="anio" value="{{ old('anio', $vehiculo->anio) }}"min="1950" max="{{ date('Y') + 1 }}" required>
                    </div>

                    <div>
                        <label for="tipo_vehiculo">Tipo de vehículo</label>
                        <select id="tipo_vehiculo" name="tipo_vehiculo" required>
                            <option value="">Seleccionar tipo</option>
                            <option value="transporte de personal" @selected(old('tipo_vehiculo', $vehiculo->tipo_vehiculo) === 'transporte de personal')>Transporte de personal</option>
                            <option value="pesado" @selected(old('tipo_vehiculo', $vehiculo->tipo_vehiculo) === 'pesado')>Pesado</option>
                            <option value="liviano" @selected(old('tipo_vehiculo', $vehiculo->tipo_vehiculo) === 'liviano')>Liviano</option>
                        </select>
                    </div>
                </div>

                <div class="gestion-formulario-acciones">
                    <a href="{{ route('incidentes.index') }}" class="panel-boton gestion-boton-secundario">
                        Cancelar
                    </a>

                    <button type="submit" class="panel-boton gestion-boton-naranja">
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection