@extends('layouts.panel')

@section('titulo', 'Registrar incidente')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/gestion.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" defer></script>
    <script src="{{ asset('js/mapa-registro.js') }}" defer></script>
@endpush

@section('contenido')
    <section class="gestion">
        <div class="panel-tarjeta gestion-encabezado">
            <div>
                <h1>Registrar incidente</h1>

                <p class="panel-texto-secundario">La gravedad se calcula al guardar según el tipo, los heridos y las fatalidades.</p>
            </div>

            <a href="{{ route('incidentes.index') }}" class="panel-boton gestion-boton-secundario">
                Volver al listado
            </a>
        </div>

        @if ($errors->any())
            <ul class="gestion-errores">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form action="{{ route('incidentes.store') }}" method="POST">
            @csrf

            <div class="incidente-columnas">

                <div class="panel-tarjeta">
                    <h2>Datos del incidente</h2>

                    <div class="gestion-campos">
                        <div>
                            <label for="fecha">Fecha</label>
                            <input type="date" id="fecha" name="fecha" value="{{ old('fecha') }}" required>
                        </div>

                        <div>
                            <label for="hora">Hora (Opcional)</label>
                            <input type="time" id="hora" name="hora" value="{{ old('hora') }}">
                        </div>

                        <div class="campo-completo">
                            <label for="tipo">Tipo de incidente</label>
                            <select id="tipo" name="tipo" required>
                                <option value="colision/choque vehicular">Colision/Choque vehicular</option>
                                <option value="impacto objeto fijo">Impacto objeto fijo</option>
                                <option value="vuelco">Vuelco</option>
                                <option value="despiste">Despiste</option>
                                <option value="atropello">Atropello</option>
                                <option value="falla mecanica">Falla mecanica</option>
                                <option value="principio de incendio/incendio">Principio de incendio/incendio</option>
                                <option value="daño menor">Daño menor</option>
                                <option value="casi incidente">Casi incidente</option>
                            </select>
                        </div>

                        <div>
                            <label for="heridos">Cantidad de heridos</label>
                            <input type="number" id="heridos" name="heridos" min="0" value="{{ old('heridos', 0) }}" required>
                        </div>

                        <div>
                            <label for="fatalidad">Cantidad de fallecidos</label>
                            <input type="number" id="fatalidad" name="fatalidad" min="0" value="{{ old('fatalidad', 0) }}"required>
                        </div>

                        <div>
                            <label for="chofer_id">Chofer</label>
                            <select id="chofer_id" name="chofer_id" required>
                                <option value="">Seleccionar chofer</option>
                                <!-- Insercion de choferes en las opciones (se usa id pero se identifica por nombre/apellido)-->
                                @foreach ($choferes as $chofer)
                                    <option value="{{ $chofer->id }}" @selected(old('chofer_id') == $chofer->id)>
                                        {{ $chofer->nombre }} — {{ $chofer->dni }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="vehiculo_id">Vehículo</label>
                            <select id="vehiculo_id" name="vehiculo_id" required>
                                <option value="">Seleccionar vehículo</option>
                                <!-- Insercion de vehivulos en las opciones (se usa id pero se identifica por patente/modelo)-->
                                @foreach ($vehiculos as $vehiculo)
                                    <option value="{{ $vehiculo->id }}" @selected(old('vehiculo_id') == $vehiculo->id)>
                                        {{ $vehiculo->patente }} — {{ $vehiculo->modelo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="clima">Clima (Opcional)</label>
                            <select id="clima" name="clima">
                                <option value="">Sin informar</option>
                                <option value="despejado">despejado</option>
                                <option value="lluvia">lluvia</option>
                                <option value="nieve/hielo">nieve/hielo</option>
                                <option value="neblina">neblina</option>
                                <option value="viento">viento</option>
                            </select>
                        </div>

                        <div>
                            <label for="estado_calzada">Calzada (Opcional)</label>
                            <select id="estado_calzada" name="estado_calzada">
                                <option value="">Sin informar</option>
                                <option value="seca">Seca</option>
                                <option value="humeda">Humeda</option>
                                <option value="barro/lodo">Barro/lodo</option>
                                <option value="hielo/escarcha">Hielo/escarcha</option>
                                <option value="dañada/baches">Dañada/baches</option>
                                <option value="ripio">Ripio</option>
                            </select>
                        </div>

                        <div class="campo-completo">
                            <label for="descripcion">Descripción</label>
                            <textarea id="descripcion" name="descripcion" rows="4" required>{{ old('descripcion') }}</textarea>
                        </div>

                        <div>
                            <label for="estado">Estado</label>
                            <select id="estado" name="estado" required>
                                <option value="">Seleccione estado</option>
                                <option value="pendiente">Pendiente</option>
                                <option value="en proceso">En proceso</option>
                                <option value="resuelto">Resuelto</option>
                            </select>
                        </div>

                        <div>
                            <label for="causa_raiz">Causa raíz (Opcional)</label>
                            <select id="causa_raiz" name="causa_raiz">
                                <option value="">Pendiente de investigación</option>
                                <option value="Factor humano">Factor humano</option>
                                <option value="Falla mecanica">Falla mecanica</option>
                                <option value="Condiciones climaticas">Condiciones climaticas</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>

                        <div class="campo-completo">
                            <label for="accion_correlativa">Acción correlativa (Opcional)</label>
                            <textarea id="accion_correlativa" name="accion_correlativa" rows="3">{{ old('accion_correlativa') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="panel-tarjeta">
                    <h2>Ubicación del incidente</h2>

                    <div class="gestion-campos">
                        <div class="campo-completo">
                            <label for="ubicacion">Ubicación / referencia</label>
                            <input type="text" id="ubicacion" name="ubicacion" value="{{ old('ubicacion') }}" placeholder="Ej.: Ruta 7, kilómetro 42" required>
                        </div>

                        <div>
                            <label for="latitud">Latitud</label>
                            <input type="number" id="latitud" name="latitud" step="any" min="-90" max="90" value="{{ old('latitud') }}" readonly>
                        </div>

                        <div>
                            <label for="longitud">Longitud</label>
                            <input type="number" id="longitud" name="longitud" step="any" min="-180" max="180" value="{{ old('longitud') }}" readonly>
                        </div>
                    </div>

                    <p id="mensaje-ubicacion" class="panel-texto-secundario"> Seleccioná un punto en el mapa.</p>

                    <!--Mapa Registro-->
                    <div id="mapa-seleccion"></div>
                </div>
            </div>

            <div class="gestion-formulario-acciones">
                <a href="{{ route('incidentes.index') }}" class="panel-boton gestion-boton-secundario">
                    Cancelar
                </a>

                <button type="submit" class="panel-boton panel-boton-verde">
                    Guardar incidente
                </button>
            </div>
        </form>
    </section>
@endsection