<!DOCTYPE html>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar incidente - Incident Tracker</title>    
</head>

<body>
    <header class="header">
        <h1>Incident Tracker</h1>
        <h5>Sistema de visualización y análisis de incidentes de transporte</h5>
    </header>

    <main class="formulario-contenedor">
        <form action="#" method="POST">
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

            <label for="causa">Causa raiz: </label>
            <select id="causa" name="causa" required>
                <option value="">Seleccione causa</option>
                <option value="Factor humano">Factor humano</option>
                <option value="Falla mecanica">Falla mecanica</option>
                <option value="Condiciones climaticas">Condiciones climaticas</option>
                <option value="Otro">Otro</option>
            </select>

            <label for="accion-correlativa">Accionar correlativo: </label>
            <textarea id="accion-correlativa" name="accion-correlativa" rows="4" required></textarea>

            <!-- latitud y longitud - Leaflet -->
            <input type="hidden" id="latitud" name="latitud" required>
            <input type="hidden" id="longitud" name="longitud" required>

            <button type="submit" class="btn-filtrar">Guardar Incidente</button>

            <a href="{{ route('incidentes.index') }}">Cancelar</a>
        </form>
    </main>

</body>
</html>