<!DOCTYPE html> 
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Incident Tracker</title>

        <link rel="stylesheet" href="{{ asset('css/incidentes.css') }}">

        <!-- CSS de Leaflet -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""/>

        <!-- JavaScripts de Leaflet -->
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin="" defer></script>
        <script src="{{ asset('js/mapa-index.js') }}" defer></script>
    </head>

    <body>
        <header class="header">
            <h1>Incident Tracker</h1>
            <h5>Sistema de visualización y análisis de incidentes de transporte</h5>
        </header>
        
        <main class="contenedor">
            <!-- Panel izquiedo -->
            <aside class="panel-izquierdo">

                <!-- Seccion de filtros y agregar incidente -->
                <h2>Incidentes</h2>

                <form id="form-filtros">

                    <label for="fecha">Fecha: </label>
                    <input type="date" id="fecha" name="fecha">

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
                        <option value="Falla vehicular">Falla vehicular</option>
                        <option value="Falla mecanica">Falla mecanica</option> 
                        <option value="Incidente operativo">Incidente operativo</option>   
                    </select>

                    <button type="submit" class="btn-filtrar">Filtrar</button>
                </form>

                <a href="{{ route('incidentes.create') }}" class="btn-agregar">Agregar incidente</a>

                <!-- Seccion de lista de incidentes -->
                <section class="lista-incidentes">
                    <h3>Incidentes recientes</h3>
                    
                    @forelse ($incidentes as $incidente)
                        <article class="tarjeta-incidente">
                            <h4>{{ $incidente->tipo }}</h4>

                            <p>
                                Fecha: {{ $incidente->fecha }}
                            </p>

                            <p>
                                Gravedad: {{ $incidente->gravedad }}
                            </p>
                        </article>
                    @empty
                        <p>Todavia no hay incidentes registrados.</p>
                    @endforelse
                </section>
            </aside>

             <!-- Mapa Leaflet -->
             <section class="panel-mapa">
                <h2>Mapa de incidentes</h2>
                <div id="mapa">
                    <p>Proximamente un mapa de Leaflet</p>
                </div>
             </section>
        </main>

    </body>
</html>