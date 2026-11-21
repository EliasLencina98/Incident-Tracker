@extends("layouts.panel")

@section("titulo", "Vista de mapa")

@push("styles")
    <!-- CSS de Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin="" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

    <link rel="stylesheet" href="{{ asset('css/mapa.css') }}">
@endpush

@push("scripts")
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin="" defer></script>
    <script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js" defer></script> <!-- heat map -->
    <script src="{{ asset('js/mapa-vistas.js') }}" defer></script>
@endpush

@section("contenido")
    <section class="vista-mapa">
        <div class="panel-vista-encabezado">
            <div>
                <h1 id="titulo-vista-mapa">Vista de mapa</h1>
                <p class="panel-texto-secundario">Ubicacion de incidentes y analisis de calor</p>
            </div>
        </div>

        <div class="mapa-distribucion">
            <section class="panel-tarjeta mapa-filtros">
                <h2>Filtros</h2>

                <form action="{{ route('incidentes.obtenerDatos') }}" method="GET" id="form-filtros">

                    <label for="fecha_inicio">Desde:</label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio">

                    <label for="fecha_fin">Hasta:</label>
                    <input type="date" id="fecha_fin" name="fecha_fin">

                    <label for="gravedad">Gravedad: </label>
                    <select id="gravedad" name="gravedad">
                        <option value="">Todas</option>
                        <option value="leve">Leve</option>
                        <option value="moderado">Moderado</option>
                        <option value="grave">Grave</option>
                        <option value="fatal">Fatal</option>
                    </select>

                    <label for="tipo">Tipo de incidente</label>
                    <select id="tipo" name="tipo">
                        <option value="">Todos</option>
                        <option value="colision/choque vehicular">Colisión / choque vehicular</option>
                        <option value="impacto objeto fijo">Impacto con objeto fijo</option>
                        <option value="vuelco">Vuelco</option>
                        <option value="despiste">Despiste</option>
                        <option value="atropello">Atropello</option>
                        <option value="falla mecanica">Falla mecánica</option>
                        <option value="principio de incendio/incendio">Principio de incendio / incendio</option>
                        <option value="daño menor">Daño menor</option>
                        <option value="casi incidente">Casi incidente</option>
                    </select>

                    <label for="estado">Estado: </label>
                    <select id="estado" name="estado">
                        <option value="">Todas</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="en proceso">En proceso</option>
                        <option value="resuelto">Resuelto</option>
                    </select>

                    <button type="submit" class="panel-boton mapa-boton-filtrar">Filtrar mapa</button>
                    <button type="reset" class="panel-boton mapa-boton-restablecer">Restablecer</button>
            
                </form>
                <p class="msj-filtro panel-texto-secundario" role="status"></p>
            </section>

            <section class="panel-tarjeta mapa-superficie" id="panel-mapa">
                <h2>Mapa de incidentes</h2>
                <div id="mapa">
                </div>
                <p class="panel-texto-secundario">
                    Usa el control de capa para activar los marcadores o el mapa de calor
                </p>
            </section>

        </div>

    </section>
@endsection