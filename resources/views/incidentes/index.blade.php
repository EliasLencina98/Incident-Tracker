@extends("layouts.panel")

@section("titulo", "Incidentes")

@push("styles")
    <link rel="stylesheet" href="{{ asset("css/gestion.css") }}">
@endpush

@push("scripts")
    <script src="{{ asset('js/gestion.js') }}" defer></script>
    <script src="{{ asset('js/incidentes-listado.js') }}" defer></script>
    <script src="{{ asset('js/choferes-listado.js') }}" defer></script>
    <script src="{{ asset('js/vehiculos-listado.js') }}" defer></script>
@endpush

@section("contenido")
    <section class="gestion">

        <div class="panel-tarjeta gestion-encabezado">
            <h1>Gestión de incidentes y flota</h1>

            <div class="gestion-acciones">
                <a href="{{ route("incidentes.create") }}" class="panel-boton panel-boton-verde">
                    Registrar incidente
                </a>

                <a href="{{ route("choferes.create") }}" class="panel-boton gestion-boton-azul">
                    Nuevo chofer
                </a>

                <a href="{{ route("vehiculos.create") }}" class="panel-boton gestion-boton-naranja">
                    Agregar vehículo
                </a>
            </div>

        </div>

        <section class="panel-tarjeta">

            <!-- Pestañas -->
            <div class="gestion-pestanas">
                <button type="button" class="panel-boton gestion-pestana activo" data-listado="seccion-incidentes">
                    Incidentes
                </button>

                <button type="button" class="panel-boton gestion-pestana" data-listado="seccion-choferes">
                    Choferes
                </button>

                <button type="button" class="panel-boton gestion-pestana" data-listado="seccion-vehiculos">
                    Vehículos
                </button>
            </div>

            <!-- Seccion Incidentes -->
            <div id="seccion-incidentes" class="gestion-seccion">  <!-- Activa -->
                <h2>Incidentes registrados</h2>

                <form id="form-listado" class="gestion-filtros" action="{{ route('incidentes.obtenerDatos') }}" method="GET">
                    <div class="gestion-busqueda">
                        <label for="buscar-incidente">Buscar</label>
                        <input type="search" id="buscar-incidente" name="buscar" placeholder="Tipo de incidente, chofer o patente">
                    </div>

                    <div class="gestion-filtro-corto">
                        <label for="gravedad">Gravedad</label>
                        <select id="gravedad" name="gravedad">
                            <option value="">Todas</option>
                            <option value="leve">Leve</option>
                            <option value="moderado">Moderado</option>
                            <option value="grave">Grave</option>
                            <option value="fatal">Fatal</option>
                        </select>
                    </div>

                    <div class="gestion-filtro-corto">
                        <label for="estado">Estado</label>
                        <select id="estado" name="estado">
                            <option value="">Todos</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="en proceso">En proceso</option>
                            <option value="resuelto">Resuelto</option>
                        </select>
                    </div>

                    <button type="submit" class="panel-boton gestion-boton-azul">
                        Buscar
                    </button>

                    <button type="reset" class="panel-boton gestion-boton-secundario">
                        Restablecer
                    </button>
                </form>

                <p id="mensaje-listado" class="panel-texto-secundario"></p>
                <div id="listado-incidentes" class="gestion-registros"></div>
            </div>

            <!-- Seccion Choferes -->
            <div id="seccion-choferes" class="gestion-seccion" hidden>  <!-- Oculta hasta activarla -->
                <h2>Choferes registrados</h2>

                <form id="form-choferes" class="gestion-filtros" action="{{ route('choferes.obtenerDatos') }}" method="GET">

                    <div class="gestion-busqueda">
                        <label for="buscar-chofer">Buscar</label>
                        <input type="search" id="buscar-chofer" name="buscar" placeholder="Nombre, DNI o licencia">
                    </div>

                    <button type="submit" class="panel-boton gestion-boton-azul">
                        Buscar
                    </button>

                    <button type="reset" class="panel-boton gestion-boton-secundario">
                        Restablecer
                    </button>
                </form>

                <p id="mensaje-choferes" class="panel-texto-secundario"></p>
                <div id="listado-choferes" class="gestion-registros"></div>
            </div>

            <!-- Seccion Vehiculos -->
            <div id="seccion-vehiculos" class="gestion-seccion" hidden>  <!-- Oculta hasta activarla -->
                <h2>Vehículos registrados</h2>

                <form id="form-vehiculos" class="gestion-filtros" action="{{ route('vehiculos.obtenerDatos') }}" method="GET">
                    <div class="gestion-busqueda">
                        <label for="buscar-vehiculo">Buscar</label>
                        <input type="search" id="buscar-vehiculo" name="buscar" placeholder="Patente, modelo o tipo de vehículo">
                    </div>

                    <button type="submit" class="panel-boton gestion-boton-azul">
                        Buscar
                    </button>

                    <button type="reset" class="panel-boton gestion-boton-secundario">
                        Restablecer
                    </button>
                </form>

                <p id="mensaje-vehiculos" class="panel-texto-secundario"></p>
                <div id="listado-vehiculos" class="gestion-registros"></div>
            </div>

        </section>

    </section>
    
@endsection