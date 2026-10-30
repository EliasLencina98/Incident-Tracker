<!DOCTYPE html>

<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="{{ asset('css/incidentes.css') }}">

    <title>Registrar incidente - Incident Tracker</title>

    <!-- CSS de Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin="" />

    <!-- JavaScripts de Leaflet -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin="" defer></script>
    <script src="{{ asset('js/mapa-registro.js') }}" defer></script>

</head>

<body>
    <header class="header">
        <h1>Incident Tracker</h1>
        <h5>Sistema de visualización y análisis de incidentes de transporte</h5>
    </header>

    <a href="{{route('incidentes.index')}}"><- Volver atras</a>

    <main class="registro-contenedor">
        

        <section class="formulario-contenedor">
            <h2>Registro de incidentes</h2>

            <form action="{{ route('incidentes.store') }}" method="POST">
                @csrf

                <label for="fecha">Fecha de incidente: </label>
                <input type="date" id="fecha" name="fecha" required>

                <label for="tipo">Tipo de incidente: </label>
                <select id="tipo" name="tipo" required>
                    <option value="">Seleccione tipo</option>
                    <option value="Accidente vehicular">Accidente vehicular</option>
                    <option value="Falla mecanica">Falla mecanica</option>
                    <option value="Incidente operativo">Incidente operativo</option>
                </select>

                <label for="gravedad">Gravedad: </label>
                <select id="gravedad" name="gravedad">
                    <option value="">Seleccione gravedad</option>
                    <option value="Leve">Leve</option>
                    <option value="Grave">Grave</option>
                    <option value="Fatal">Fatal</option>
                    <option value="Danio Material">Daño material</option>
                </select>

                <label for="ubicacion">Ubicacion: </label>
                <input type="text" id="ubicacion" name="ubicacion" placeholder="Ej: Ruta 17, Añelo" required>

                <label for="descripcion">Descripcion: </label>
                <textarea id="descripcion" name="descripcion" rows="4" required></textarea>

                <label for="causa_raiz">Causa raiz: </label>
                <select id="causa_raiz" name="causa" required>
                    <option value="">Seleccione causa</option>
                    <option value="Factor humano">Factor humano</option>
                    <option value="Falla mecanica">Falla mecanica</option>
                    <option value="Condiciones climaticas">Condiciones climaticas</option>
                    <option value="Otro">Otro</option>
                </select>

                <label for="accion_correlativa">Accionar correlativo: </label>
                <textarea id="accion_correlativa" name="accion" rows="4" required></textarea>

                <!-- latitud y longitud - Leaflet -->
                <div>
                    <h3>Seleccionar en el mapa</h3>
                    <div>
                        <label for="">Latitud:</label>
                        <input type="number" id="latitud" name="latitud" required readonly>
                    </div>
                    <div>
                        <label for="">Longitud:</label>
                        <input type="number" id="longitud" name="longitud" required readonly>
                    </div>
                </div>

                <button type="submit" class="btn btn-agregar">Guardar Incidente</button>

                <a href="{{ route('incidentes.index') }}">Cancelar</a>
            </form>
        </section>

        <!-- Mapa Leaflet -->
        <section class="registro-mapa">
            <h2>Ubicacion del incidente</h2>

            <div id="mapa-seleccion">
            </div>

            <p id="coordenadas-seleccionadas">
                Selecciona en el mapa para ver las coordenadas
            </p>

        </section>
    </main>



</body>

</html>