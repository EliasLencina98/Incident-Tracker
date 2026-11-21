@extends("layouts.panel")

@section("titulo", "Inicio")

@push("styles")
    <!-- CSS de Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin="" />
    <link rel="stylesheet" href="{{ asset('css/incidentes.css') }}">
@endpush

@push("scripts")
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin="" defer></script>
<script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js" defer></script>
<script src="{{ asset('js/dashboard.js') }}" defer></script>
@endpush

@section("contenido")
<section id="view-dashboard" class="dash-seccion">
    <div class="panel-vista-encabezado">
        <div>
            <h1 id="titulo-inicio">Resumen Ejecutivo y Métricas Generales</h1>
            <p class="panel-texto-secundario">
                Indicadores clave de rendimiento (KPIs) y análisis estadístico en tiempo real
            </p>
        </div>
        <div>
            <button type="button" onclick="" class="panel-boton panel-boton-verde">
                <span>+ Registrar Incidente</span>
            </button>
        </div>
    </div>

    <form method="GET" action="{{ route('incidentes.dashboardDatos') }}" class="panel-tarjeta dash-filtros" id="form-filtros-dashboard">
        <div class="dash-filtros-grid">
            <div class="dash-campo">
                <label for="filtro-desde" class="dash-label">Desde</label>
                <input
                    type="date"
                    id="filtro-desde"
                    name="fecha_inicio"
                    value="{{ $filtros['fecha_inicio'] ?? '' }}"
                    class="dash-input">
            </div>

            <div class="dash-campo">
                <label for="filtro-hasta" class="dash-label">Hasta</label>
                <input
                    type="date"
                    id="filtro-hasta"
                    name="fecha_fin"
                    value="{{ $filtros['fecha_fin'] ?? '' }}"
                    class="dash-input">
            </div>

            <div class="dash-campo">
                <label for="filtro-gravedad" class="dash-label">Gravedad</label>
                <select id="filtro-gravedad" name="gravedad" class="dash-input">
                    <option value="">Todos</option>
                    @foreach ($gravedadDisponibles as $gravedad)
                    <option value="{{ $gravedad }}" @selected(($filtros['gravedad'] ?? '' )===$gravedad)>
                        {{ $gravedad }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="dash-campo">
                <label for="filtro-tipo" class="dash-label">Tipo</label>
                <select id="filtro-tipo" name="tipo" class="dash-input">
                    <option value="">Todos</option>
                    @foreach ($tiposDisponibles as $tipo)
                    <option value="{{ $tipo }}" @selected(($filtros['tipo'] ?? '' )===$tipo)>
                        {{ $tipo }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="dash-campo">
                <label for="filtro-estado" class="dash-label">Estado</label>
                <select id="filtro-estado" name="estado" class="dash-input">
                    <option value="">Todos</option>
                    @foreach ($estadosDisponibles as $estado)
                    <option value="{{ $estado }}" @selected(($filtros['estado'] ?? '' )===$estado)>
                        {{ $estado }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="dash-filtros-acciones">
            <button type="submit" class="panel-boton panel-boton-verde">Aplicar filtros</button>
            <a href="{{ route('incidentes.dashboard') }}" class="dash-btn-limpiar">Limpiar</a>
        </div>
    </form>

    {{-- Tarjetas de KPIs --}}
    <div class="dash-kpi-grid">
        <div class="panel-tarjeta dash-kpi">
            <div class="dash-kpi-cabecera">
                <span class="dash-kpi-etiqueta">Total</span>
            </div>
            <div class="dash-kpi-valor-fila">
                <span id="kpi-total" class="dash-kpi-numero dash-texto-azul"></span>
                <span class="dash-kpi-sub">registros</span>
            </div>
            <div class="dash-barra">
                <div class="dash-barra-relleno dash-barra-azul" style="width: 100%"></div>
            </div>
        </div>

        <div class="panel-tarjeta dash-kpi">
            <div class="dash-kpi-cabecera">
                <span class="dash-kpi-etiqueta">Leves</span>
            </div>
            <div class="dash-kpi-valor-fila">
                <span id="kpi-leves" class="dash-kpi-numero dash-texto-verde"></span>
                <span id="kpi-leves-pct" class="dash-kpi-sub"></span>
            </div>
            <div class="dash-barra">
                <div id="bar-leves" class="dash-barra-relleno dash-barra-verde" style="width:1"></div>
            </div>
        </div>

        <div class="panel-tarjeta dash-kpi">
            <div class="dash-kpi-cabecera">
                <span class="dash-kpi-etiqueta">Moderados</span>
            </div>
            <div class="dash-kpi-valor-fila">
                <span id="kpi-moderados" class="dash-kpi-numero dash-texto-ambar"></span>
                <span id="kpi-moderados-pct" class="dash-kpi-sub">%</span>
            </div>
            <div class="dash-barra">
                <div id="bar-moderados" class="dash-barra-relleno dash-barra-ambar" style="width: 33%"></div>
            </div>
        </div>

        <div class="panel-tarjeta dash-kpi">
            <div class="dash-kpi-cabecera">
                <span class="dash-kpi-etiqueta">Graves</span>
            </div>
            <div class="dash-kpi-valor-fila">
                <span id="kpi-graves" class="dash-kpi-numero dash-texto-rojo"></span>
                <span id="kpi-graves-pct" class="dash-kpi-sub"></span>
            </div>
            <div class="dash-barra">
                <div id="bar-graves" class="dash-barra-relleno dash-barra-rojo" style="width:1"></div>
            </div>
        </div>

        <div class="panel-tarjeta dash-kpi dash-kpi-ancha">
            <div class="dash-kpi-cabecera">
                <span class="dash-kpi-etiqueta">Fatales</span>
            </div>
            <div class="dash-kpi-valor-fila">
                <span id="kpi-fatales" class="dash-kpi-numero dash-texto-purpura"></span>
                <span id="kpi-fatales-pct" class="dash-kpi-sub">%</span>
            </div>
            <div class="dash-barra">
                <div id="bar-fatales" class="dash-barra-relleno dash-barra-purpura" style="width:1"></div>
            </div>
        </div>
    </div>

    {{-- graficos  --}}
    <div class="dash-fila-principal">
        <div class="panel-tarjeta dash-col-2">
            <div class="dash-card-cabecera">
                <div>
                    <h2>Evolución Temporal de Incidentes</h2>
                    <p class="panel-texto-secundario dash-subtitulo-sm">Frecuencia de registros agrupados por período</p>
                </div>
            </div>
            <div id="grafico-tendencia" class="dash-chart-box"></div>
        </div>

        <div class="panel-tarjeta dash-col-1">
            <div class="dash-card-cabecera">
                <div>
                    <h2>Incidentes por Gravedad</h2>
                    <p class="panel-texto-secundario dash-subtitulo-sm">Clasificación según nivel de severidad</p>
                </div>
            </div>
            <div id="grafico-gravedad" class="dash-chart-box"></div>
        </div>
    </div>

    {{-- grafico de torta y Mapa --}}
    <div class="dash-fila-secundaria">
        <div class="panel-tarjeta dash-col-1">
            <div class="dash-card-cabecera">
                <div>
                    <h2>Distribución por Tipo</h2>
                    <p class="panel-texto-secundario dash-subtitulo-sm">Categorización operativa de eventos</p>
                </div>
            </div>
            <div id="grafico-tipo" class="dash-chart-box dash-chart-centrado"></div>
        </div>

        <div class="panel-tarjeta dash-col-2">
            <div class="dash-card-cabecera">
                <div>
                    <h2>Analisis riesgo geografico (Mapa de calor)</h2>
                    <p class="panel-texto-secundario dash-subtitulo-sm">Vista rápida de puntos críticos en Añelo, Neuquén, Cipolletti y alrededores</p>
                </div>
                <button type="button" onclick="switchTab('mapa')" class="dash-link-btn">
                    <span>Abrir mapa completo</span>
                </button>
            </div>
            <div id="dashboard-mini-map" class="dash-mapa"></div>
        </div>
    </div>
</section>

@endsection