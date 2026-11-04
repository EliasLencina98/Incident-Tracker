<!DOCTYPE html>
    <html>

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Detalle del incidente</title>

            <link rel="stylesheet" href="{{ asset('css/incidentes.css') }}">
        </head>

        <body>

            <header class="header">
                <h1>Panel de control de incidentes</h1>
                <h5>Sistema de visualización y análisis de incidentes de transporte</h5>
            </header>

            <main class="detalle-incidente">
                <a href="{{ route('incidentes.index') }}">Volver al panel</a>

                <h2>{{ $incidente->tipo }}</h2>

                <h3>Fecha</h3>
                <p>{{ $incidente->fecha }}</p>

                <h3>Gravedad</h3>
                <p>{{ $incidente->gravedad }}</p>

                <h3>Estado</h3>
                <p>{{ $incidente->estado }}</p>

                <h3>Ubicacion</h3>
                <p>{{ $incidente->ubicacion }}</p>

                <h3>Descripcion</h3>
                <p>{{ $incidente->descripcion }}</p>

                <h3>Causa Raiz</h3>
                <p>{{ $incidente->causa_raiz }}</p>

                <h3>Accion correlativa</h3>
                <p>{{ $incidente->accion_correlativa }}</p>

                <h3>Coordenadas</h3>
                <p><strong>Latitud:</strong>{{ $incidente->latitud }}</p>
                <p><strong>Longitud:</strong>{{ $incidente->longitud }}</p>

            </main>

        </body>
    </html>