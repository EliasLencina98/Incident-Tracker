@extends('layouts.panel')

@section('titulo', 'Detalle del vehículo')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/gestion.css') }}">
@endpush

@section('contenido')
    <section class="gestion">
        <div class="panel-tarjeta gestion-encabezado">
            <div>
                <h1>{{ $vehiculo->patente }}</h1>
                <p class="panel-texto-secundario">Detalle del vehículo</p>
            </div>

            <div class="gestion-acciones">
                <a href="{{ route('incidentes.index') }}" class="panel-boton gestion-boton-secundario">
                    Volver al listado
                </a>

                <a href="{{ route('vehiculos.edit', $vehiculo->id) }}" class="panel-boton gestion-boton-naranja">
                    Modificar
                </a>
            </div>
        </div>

        <div class="panel-tarjeta gestion-detalle">
            <h2>Datos del vehículo</h2>

            <dl class="detalle-datos">
                @foreach ([
                    'Patente' => $vehiculo->patente,
                    'Marca y modelo' => $vehiculo->modelo,
                    'Año' => $vehiculo->anio,
                    'Tipo de vehículo' => ucfirst($vehiculo->tipo_vehiculo)
                ] as $etiqueta => $valor)
                    <div>
                        <dt>{{ $etiqueta }}</dt>
                        <dd>{{ $valor }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </section>
@endsection