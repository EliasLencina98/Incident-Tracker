<!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title> Editar accidente </title>

        <link rel="stylesheet" href="{{ asset('css/incidentes.css') }}">
    </head>

    <body>
        <header class="header">
            <h1>Panel de control de incidentes</h1>
            <h5>Sistema de visualización y análisis de incidentes de transporte</h5>
        </header>

        <main class="detalle-incidente formulario-contenedor">

            <form action="{{ route('incidentes.update', $incidente->id) }}" method="POST">
                @csrf
                @method('PUT')

                <label for="fecha">Fecha:</label>
                <input type="date" id="fecha" name="fecha" value="{{ old('fecha', $incidente->fecha) }}" required>

                @error("fecha")
                    <span>{{ $message }}</span>
                @enderror
                

                <label for="tipo">Tipo: </label>
                <select id="tipo" name="tipo" required>
                    <option value="">Seleccion una opcion</option>
                    <option value="Accidente vehicular" {{ old('tipo', $incidente->tipo) == 'Accidente vehicular' ? 'selected' : '' }}>Accidente vehicular</option>
                    <option value="Falla mecanica" {{ old('tipo', $incidente->tipo) == 'Falla mecanica' ? 'selected' : '' }}>Falla mecanica</option>
                    <option value="Incidente operativo" {{ old('tipo', $incidente->tipo) == 'Incidente operativo' ? 'selected' : '' }}>Incidente operativo</option>
                </select>

                @error("tipo")
                    <span>{{ $message }}</span>
                @enderror
                
                <label for="gravedad">Gravedad:</label>
                <select id="gravedad" name="gravedad" required>
                    <option value="">Seleccion una opcion</option>
                    <option value="Leve" {{ old('gravedad', $incidente->gravedad) == 'Leve' ? 'selected' : '' }}>Leve</option>
                    <option value="Grave" {{ old('gravedad', $incidente->gravedad) == 'Grave' ? 'selected' : '' }}>Gave</option>
                    <option value="Fatal" {{ old('gravedad', $incidente->gravedad) == 'Fatal' ? 'selected' : '' }}>Fatal</option>
                    <option value="Daño material" {{ old('gravedad', $incidente->gravedad) == 'Danio material' ? 'selected' : '' }}>Daño material</option>
                </select>

                @error("gravedad")
                    <span>{{ $message }}</span>
                @enderror

                <label for="ubicacion">Ubicación:</label>
                <input type="text" id="ubicacion" name="ubicacion" value="{{ old('ubicacion', $incidente->ubicacion) }}" required>

                @error("ubicacion")
                    <span>{{ $message }}</span>
                @enderror

                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" rows="4" required>{{ old('descripcion', $incidente->descripcion) }}</textarea>

                @error("descripcion")
                    <span>{{ $message }}</span>
                @enderror

                <label for="causa_raiz">Causa raíz:</label>
                <select id="causa_raiz" name="causa_raiz" required>
                    <option value="">Seleccion una opcion</option>
                    <option value="Factor humano" {{ old('causa_raiz', $incidente->causa_raiz) == 'Factor humano' ? 'selected' : '' }}>Factor humano</option>
                    <option value="Falla mecanica" {{ old('causa_raiz', $incidente->causa_raiz) == 'Falla mecanica' ? 'selected' : '' }}>Falla mecanica</option>
                    <option value="Condiciones climaticas" {{ old('causa_raiz', $incidente->causa_raiz) == 'Condiciones climaticas' ? 'selected' : '' }}>Condiciones climaticas</option>
                    <option value="Otro" {{ old('causa_raiz', $incidente->causa_raiz) == 'Otro' ? 'selected' : '' }}>Otro</option>
                </select>
                
                @error("causa_raiz")
                    <span>{{ $message }}</span>
                @enderror

                <label for="estado">Estado:</label>
                <select id="estado" name="estado" required>
                    <option value="">Seleccion una opcion</option>
                    <option value="pendiente" {{ old('estado', $incidente->estado) == 'pendiente' ? 'selected' : '' }}>pendiente</option>
                    <option value="en proceso" {{ old('estado', $incidente->estado) == 'en proceso' ? 'selected' : '' }}>en proceso</option>
                    <option value="resuelto" {{ old('estado', $incidente->estado) == 'resuelto' ? 'selected' : '' }}>resuelto</option>
                </select>

                @error("estado")
                    <span>{{ $message }}</span>
                @enderror

                <label for="accion_correlativa">Acción correlativa:</label>
                <textarea id="accion_correlativa" name="accion_correlativa" rows="4" required>{{ old('accion_correlativa', $incidente->accion_correlativa) }}</textarea>

                @error("accion_correlativa")
                    <span>{{ $message }}</span>
                @enderror

                <button type="submit" class="btn btn-agregar">
                    Guardar cambios
                </button>

                <a href="{{ route('incidentes.index') }}">Cancelar</a>
            </form>

        </main>
    </body>

</html>