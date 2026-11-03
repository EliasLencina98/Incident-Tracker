<!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Incident Tracker</title>

        <link rel="stylesheet" href="{{ asset('css/incidentes.css') }}">

        <!-- CSS de Leaflet -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
            crossorigin="" />

        <!-- JavaScripts de Leaflet -->
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin="" defer></script>

        <script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js" defer></script>

        <script src="{{ asset('js/mapa-index.js') }}" defer></script>

    </head>

    <body>
        <header class="header">
            <h1>Panel de control de incidentes</h1>
            <h5>Sistema de visualización y análisis de incidentes viales</h5>
        </header>

        @if (session('mensaje'))
            <p>{{ session('mensaje') }}</p>
        @endif
        
        <main class="contenedor">

            <!-- Panel izquiedo -->
            <section class="panel-izquierdo">

                <!-- Seccion de filtros y agregar incidente -->

                <h2 id="titulo-filtros-principal">Filtros Avanzados</h2>

                <form id="form-filtros" action="{{ route('incidentes.obtenerDatos')}}" method="GET">

                    <p class="titulo-fechas">Rango de Fecha</p>

                    <label for="fecha_inicio">Desde:</label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio">

                    <label for="fecha_fin">Hasta:</label>
                    <input type="date" id="fecha_fin" name="fecha_fin">

                    <label for="gravedad">Gravedad: </label>
                    <select id="gravedad" name="gravedad">
                        <option value="">Todas</option>
                        <option value="Leve">Leve</option>
                        <option value="Grave">Grave</option>
                        <option value="Fatal">Fatal</option>
                        <option value="Danio Material">Daño Material</option>
                    </select>

                    <label for="tipo">Tipo de incidente: </label>
                    <select id="tipo" name="tipo">
                        <option value="">Todos</option>
                        <option value="Accidente vehicular">Accidente vehicular</option>
                        <option value="Falla mecanica">Falla mecanica</option>
                        <option value="Incidente operativo">Incidente operativo</option>
                    </select>

                    <button type="submit" class=" btn btn-filtrar">Filtrar</button>
                    <button type="reset" class="btn btn-reset">Restablecer</button>
                </form>

                <p class="msj-filtro" role="status"></p>

                <!-- Seccion de lista de incidentes -->
                <h3>Incidentes recientes</h3>
                <section class="lista-incidentes">

                    <div id="listado-incidentes"></div>
                </section>

                <a href="{{ route('incidentes.create') }}" class="btn btn-agregar">+ Agregar incidente</a>
            </section>

            <!-- Mapa Leaflet HTML -->
            <section class="panel-mapa">
                <h3 id="titulo-mapa">Mapa de Incidentes</h3>
                <div id="mapa"></div> <!-- Asegúrate de darle altura al div del mapa -->
            </section>

        </main>

    </body>

</html>