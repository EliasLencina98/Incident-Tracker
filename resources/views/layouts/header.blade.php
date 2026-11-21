<header class="panel-header">
    <div class="panel-marca">
        <span class="panel-marca-icono"> PCA </span>

        <div>
            <p class="panel-nombre"> Panel de control de incidentes </p>
            <p class="panel-descripcion"> Visualización y análisis de incidentes viales </p> 
        </div>
    </div>

    <div class="panel-sesion">
        <span> {{ auth()->user()->name }} · {{ ucfirst(auth()->user()->rol) }} </span>

        <form action="{{ route('logout') }}" method="POST">
            @csrf

            <button type="submit" class="panel-boton panel-salir">
                Cerrar sesión
            </button>
        </form>
    </div>
</header>