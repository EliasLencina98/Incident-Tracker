<aside class="panel-sidebar">

    <nav class="panel-navegacion">
        <p class="panel-menu-titulo"> Menu del sistema </p>

        <ul class="panel-menu">
            <li>
                <a href="{{ route("incidentes.dashboard") }}" class="panel-menu-enlace {{ request()->routeIs("incidentes.dashboard") ? "activo" : "" }}">
                    Dashboard
                </a>
            </li>

            <li>
                <a href="{{ route("incidentes.index") }}" class="panel-menu-enlace {{ request()->routeIs("incidentes.index", 
                "incidentes.create", "incidentes.show", "incidentes.edit", "choferes.*", "vehiculos.*") ? "activo" : "" }}"> 
                    Incidentes 
                </a>
            </li>

            <li>
                <a href="{{ route("incidentes.mapa") }}" class="panel-menu-enlace {{ request()->routeIs("incidentes.mapa") ? "activo" : "" }}">
                    Vista de mapa 
                </a>
            </li>
        </ul>
    </nav>

    <div class="panel-sidebar-pie">
        <a href="{{ route("incidentes.create") }}" class="panel-boton panel-boton-verde"> Registrar incidentes </a>
    </div>

</aside>
