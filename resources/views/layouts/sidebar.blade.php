<aside class="panel-sidebar">

    <nav class="panel-navegacion">
        <p class="panel-menu-titulo"> Menu del sistema </p>

        <ul class="panel-menu">
            <!-- Creacion de usuarios (administrador) / Dashboard / Incidentes / Vista de mapa -->
            @if (auth()->user()->rol === 'administrador')
                <li>
                    <a href="{{ route('usuarios.create') }}" class="panel-menu-enlace {{ request()->routeIs('usuarios.*') ? 'activo' : '' }}">
                        &#128100; Crear usuario
                    </a>
                </li>
            @endif

            <li>
                <a href="{{ route("incidentes.dashboard") }}" class="panel-menu-enlace {{ request()->routeIs("incidentes.dashboard") ? "activo" : "" }}">
                    &#128200; Dashboard
                </a>
            </li>

            <li>
                <a href="{{ route("incidentes.index") }}" class="panel-menu-enlace {{ request()->routeIs("incidentes.index", 
                "incidentes.create", "incidentes.show", "incidentes.edit", "choferes.*", "vehiculos.*") ? "activo" : "" }}"> 
                    &#128203; Incidentes 
                </a>
            </li>

            <li>
                <a href="{{ route("incidentes.mapa") }}" class="panel-menu-enlace {{ request()->routeIs("incidentes.mapa") ? "activo" : "" }}">
                    &#128205; Vista de mapa 
                </a>
            </li>
        </ul>
    </nav>

    <div class="panel-sidebar-pie">
        <a href="{{ route("incidentes.create") }}" class="panel-boton panel-boton-verde"> Registrar incidentes </a>
    </div>

</aside>
