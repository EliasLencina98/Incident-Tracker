<!DOCTYPE html>
<html>
    <head>
        <title> @yield("titulo", "Inicio") | Panel de control de accidentes </title>
        <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
        @stack("styles") <!-- Para agregar estilos en las otras views -->
        @stack("scripts") <!-- Para agregar js -->
    </head>

    <body>
        <div class="panel">
            @include("layouts.header")
            @include("layouts.sidebar")

            <main id="contenido-principal" class="panel-contenido">
                @if (session("mensaje"))
                    <p class="panel-aviso" role="status">
                        {{ session("mensaje") }}
                    </p>
                @endif

                @yield("contenido")
            </main>
        </div>
    </body>
</html>