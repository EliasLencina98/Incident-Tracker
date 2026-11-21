@extends('layouts.panel')

@section('titulo', 'Detalle del chofer')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/gestion.css') }}">
@endpush

@section('contenido')
    <section class="gestion">
        <div class="panel-tarjeta gestion-encabezado">
            <div>
                <h1>{{ $chofer->nombre }}</h1>
                <p class="panel-texto-secundario">Detalle del chofer</p>
            </div>

            <div class="gestion-acciones">
                <a href="{{ route('incidentes.index') }}" class="panel-boton gestion-boton-secundario">
                    Volver al listado
                </a>

                <a href="{{ route('choferes.edit', $chofer->id) }}" class="panel-boton gestion-boton-azul">
                    Modificar
                </a>
            </div>
        </div>

        <div class="panel-tarjeta gestion-detalle">
            <h2>Datos del chofer</h2>

            <dl class="detalle-datos">
                @foreach ([
                    'DNI' => $chofer->dni,
                    'Licencia' => $chofer->licencia,
                    'Fecha de nacimiento' => $chofer->fecha_nacimiento,
                    'Fecha de ingreso' => $chofer->fecha_ingreso,
                    'Puesto' => $chofer->puesto
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