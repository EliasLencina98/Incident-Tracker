<!DOCTYPE html>

<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="{{ asset('css/incidentes.css') }}">

    <title>Registrar incidente</title>

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
        <h1>Panel de control de incidentes</h1>
        <h5>Sistema de visualización y análisis de incidentes de transporte</h5>
    </header>


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
                    <option value="Accidente vehicular" {{ old('tipo', $incidente->tipo ?? '') == 'Accidente vehicular' ? 'selected' : '' }}>Accidente vehicular</option>
                    <option value="Falla mecanica" {{ old('tipo', $incidente->tipo ?? '') == 'Falla mecanica' ? 'selected' : '' }}>Falla mecanica</option>
                    <option value="Incidente operativo" {{ old('tipo', $incidente->tipo ?? '') == 'Incidente operativo' ? 'selected' : '' }}>Incidente operativo</option>
                </select>

                <label for="gravedad">Gravedad: </label>
                <select id="gravedad" name="gravedad" required>
                    <option value="">Seleccione gravedad</option>
                    <option value="Leve" {{ old('gravedad', $incidente->gravedad ?? '') == 'Leve' ? 'selected' : '' }}>Leve</option>
                    <option value="Grave" {{ old('gravedad', $incidente->gravedad ?? '') == 'Grave' ? 'selected' : '' }}>Grave</option>
                    <option value="Fatal" {{ old('gravedad', $incidente->gravedad ?? '') == 'Fatal' ? 'selected' : '' }}>Fatal</option>
                    <option value="Danio Material" {{ old('gravedad', $incidente->gravedad ?? '') == 'Danio Material' ? 'selected' : '' }}>Daño material</option>
                </select>

                <label for="ubicacion">Ubicacion: </label>
                <input type="text" id="ubicacion" name="ubicacion" placeholder="Ej: Ruta 17, Añelo" required value="{{ old('ubicacion') }}">

                <label for="descripcion">Descripcion: </label>
                <textarea id="descripcion" name="descripcion" rows="4" placeholder="Detalle el accidente" required>{{ old('descripcion') }}</textarea>

                <label for="causa_raiz">Causa raiz: </label>
                <select id="causa_raiz" name="causa_raiz">
                    <option value="">Seleccione causa</option>
                    <option value="Factor humano" {{ old('causa_raiz', $incidente->causa_raiz ?? '') == 'Factor humano' ? 'selected' : '' }}>Factor humano</option>
                    <option value="Falla mecanica" {{ old('causa_raiz', $incidente->causa_raiz ?? '') == 'Falla mecanica' ? 'selected' : '' }}>Falla mecanica</option>
                    <option value="Condiciones climaticas" {{ old('causa_raiz', $incidente->causa_raiz ?? '') == 'Condiciones climaticas' ? 'selected' : '' }}>Condiciones climaticas</option>
                    <option value="Otro" {{ old('causa_raiz', $incidente->causa_raiz ?? '') == 'Otro' ? 'selected' : '' }}>Otro</option>
                </select>

                <label for="estado">Estado: </label>
                <select name="estado" id="estado" required>
                    <option value="pendiente" {{ old('estado', $incidente->causa_raiz ?? '') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="en proceso" {{ old('estado', $incidente->causa_raiz ?? '') == 'en proceso' ? 'selected' : '' }}>En proceso</option>
                    <option value="resuelto" {{ old('estado', $incidente->causa_raiz ?? '') =='resuelto' ? 'selected' : '' }}>Resuelto</option>
                </select>

                <label for="accion_correlativa">Accionar correlativo: </label>
                <textarea id="accion_correlativa" name="accion_correlativa" rows="4" placeholder="Detalle el accionar a realizar segun el estado del accidente">{{ old('accion_correlativa') }}</textarea>

                <!-- latitud y longitud - Leaflet -->

                <h3>Coordenadas</h3>
                <label for="latitud">Latitud:</label>
                <input type="number" id="latitud" name="latitud" required readonly>


                <label for="longitud">Longitud:</label>
                <input type="number" id="longitud" name="longitud" required readonly>



                <button type="submit" class="btn btn-agregar">Guardar Incidente</button>

                <a href="{{ route('incidentes.index') }}">Cancelar</a>
            </form>
        </section>

        <!-- Mapa Leaflet -->
        <section class="registro-mapa">
            <h2>Ubicacion del incidente</h2>

            <div id="mapa-seleccion">
            </div>

            <span id="seleccione-coordenadas">
                Selecciona en el mapa para ver las coordenadas
            </span>

        </section>
    </main>

</body>

</html>