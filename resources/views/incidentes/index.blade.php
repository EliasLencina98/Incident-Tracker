@extends("layouts.panel")

@section("titulo", "Incidentes")

@push("styles")
    <link rel="stylesheet" href="{{ asset("css/gestion.css") }}">
@endpush

@push("scripts")
    <script src="{{ asset("js/incidentes-listado.js") }}" defer></script>
@endpush

@section("contenido")
    <section class="gestion">
        <div class="panel-tarjeta gestion-encabezado">
            <div>
                <h1>Gestión de incidentes y flota</h1>

                <p class="panel-texto-secundario">
                    Consulta y registro de incidentes, choferes y vehículos.
                </p>
            </div>

            <div class="gestion-acciones">
                <a href="{{ route("incidentes.create") }}" class="panel-boton panel-boton-verde">
                    Registrar incidente
                </a>

                <a href="{{ route("choferes.create") }}" class="panel-boton gestion-boton-azul">
                    Nuevo chofer
                </a>

                <a href="{{ route("vehiculos.create") }}" class="panel-boton gestion-boton-naranja">
                    Agregar vehículo
                </a>
            </div>
        </div>

        <section class="panel-tarjeta gestion-listado">
            <div class="gestion-listado-encabezado">
                <h2>Incidentes registrados</h2>

                <span class="gestion-etiqueta">
                    Resultados: <span id="cantidad-incidentes">—</span>
                </span>
            </div>

            <form id="filtros-listado" class="gestion-filtros" action="{{ route('incidentes.obtenerDatos') }}"method="GET">

                <div>
                    <label for="listado-gravedad">Gravedad</label>
                    <select id="listado-gravedad" name="gravedad">
                        <option value="">Todas</option>
                        <option value="leve">Leve</option>
                        <option value="moderado">Moderado</option>
                        <option value="grave">Grave</option>
                        <option value="fatal">Fatal</option>
                    </select>
                </div>

                <div>
                    <label for="listado-estado">Estado</label>
                    <select id="listado-estado" name="estado">
                        <option value="">Todos</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="en proceso">En proceso</option>
                        <option value="resuelto">Resuelto</option>
                    </select>
                </div>

                <button type="submit" class="panel-boton gestion-boton-azul">Buscar</button>

                <button type="reset" class="panel-boton gestion-boton-secundario">Restablecer</button>
            </form>

            <p id="mensaje-listado" class="panel-texto-secundario" role="status"></p>

            <div id="listado-incidentes" class="gestion-registros" data-url="{{ route("incidentes.obtenerDatos") }}"></div>
        </section>
    </section>
@endsection