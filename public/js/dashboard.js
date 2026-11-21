(() => {
    const form = document.querySelector("#form-filtros-dashboard");
    const btnLimpiar = document.querySelector("#btn-limpiar-filtros");

    console.log("adentro");

    let capasIncidentes = null;
    let mapaCalor = null;

    if (!form) return;

    const baseTheme = {
        theme: { mode: "dark" },
        chart: {
            background: "transparent",
            toolbar: { show: false },
            fontFamily: "Arial, Helvetica, sans-serif",
        },
        grid: {
            borderColor: "#334155",
            strokeDashArray: 4,
        },
    };

    let graficoTendencia = null;
    let graficoGravedad = null;
    let graficoTipo = null;

    function IniciarGraficos() {
        const elTendencia = document.querySelector("#grafico-tendencia");
        if (elTendencia && typeof ApexCharts !== "undefined") {
            graficoTendencia = new ApexCharts(elTendencia, {
                ...baseTheme,
                chart: { ...baseTheme.chart, type: "area", height: 280 },
                series: [{ name: "Incidentes", data: [] }],
                xaxis: {
                    categories: [],
                    labels: { style: { colors: "#a8b5c9", fontSize: "12px" } },
                },
                yaxis: {
                    min: 0,
                    forceNiceScale: true,
                    labels: { style: { colors: "#a8b5c9", fontSize: "12px" } },
                },
                colors: ["#3b82f6"],
                stroke: { curve: "smooth", width: 2.5 },
                fill: {
                    type: "gradient",
                    gradient: { opacityFrom: 0.35, opacityTo: 0.02 },
                },
                dataLabels: { enabled: false },
                noData: {
                    text: "Sin registros para los filtros seleccionados",
                    style: { color: "#a8b5c9", fontSize: "13px" },
                },
            });
            graficoTendencia.render();
        }

        const elGravedad = document.querySelector("#grafico-gravedad");
        if (elGravedad && typeof ApexCharts !== "undefined") {
            graficoGravedad = new ApexCharts(elGravedad, {
                ...baseTheme,
                chart: { ...baseTheme.chart, type: "bar", height: 280 },
                series: [{ name: "Incidentes", data: [0, 0, 0, 0] }],
                plotOptions: {
                    bar: {
                        distributed: true,
                        borderRadius: 6,
                        columnWidth: "52%",
                    },
                },
                colors: ["#10b981", "#f59e0b", "#f43f5e", "#A855F7"],
                xaxis: {
                    categories: ["Leves", "Moderados", "Graves", "Fatales"],
                    labels: { style: { colors: "#a8b5c9", fontSize: "12px" } },
                },
                yaxis: {
                    min: 0,
                    forceNiceScale: true,
                    labels: { style: { colors: "#a8b5c9", fontSize: "12px" } },
                },
                legend: { show: false },
                dataLabels: { enabled: false },
            });
            graficoGravedad.render();
        }

        const elTipo = document.querySelector("#grafico-tipo");
        if (elTipo && typeof ApexCharts !== "undefined") {
            graficoTipo = new ApexCharts(elTipo, {
                ...baseTheme,
                chart: { ...baseTheme.chart, type: "donut", height: 350 },
                series: [],
                labels: [],
                stroke: { colors: ["#1e293b"] },
                legend: {
                    position: "bottom",
                    labels: { colors: "#a8b5c9" },
                },
                dataLabels: {
                    enabled: true,
                    formatter: (val) => `${Math.round(val)}%`,
                },
                noData: {
                    text: "Sin datos por tipo",
                    style: { color: "#a8b5c9", fontSize: "13px" },
                },
            });
            graficoTipo.render();
        }
    }

    function actualizarGraficos(data) {
        if (graficoTendencia) {
            const meses = (data.tendencia || []).map((item) => item.mes);
            const totales = (data.tendencia || []).map((item) =>
                Number(item.total),
            );

            graficoTendencia.updateOptions({
                xaxis: { categories: meses },
            });
            graficoTendencia.updateSeries([{ name: "Incidentes", data: totales }]);
        }

        if (graficoGravedad) {
            const g = data.por_gravedad || {};
            graficoGravedad.updateSeries([
                {
                    name: "Incidentes",
                    data: [
                        Number(g.leve || 0),
                        Number(g.moderado || 0),
                        Number(g.grave || 0),
                        Number(g.fatal || 0),
                    ],
                },
            ]);
        }

        if (graficoTipo) {
            const items = data.por_tipo || [];
            const labels = items.map((item) => item.tipo || "Sin especificar");
            const series = items.map((item) => Number(item.total));

            graficoTipo.updateOptions({ labels });
            graficoTipo.updateSeries(series);
        }
    }

    function actualizarKpis(kpis) {

        // funcion flecha seteo de valores kpis
        const setearTexto = (id, val) => {
            const el = document.querySelector(id);
            if (el) el.textContent = val;
        };
        // funcion flecha seteo de estilo width % de kpis
        const setearWidth = (id, pct) => {
            const el = document.querySelector(id);
            if (el) el.style.width = `${pct}%`;
        };

        setearTexto("#kpi-total", kpis.total);
        setearTexto("#kpi-leves", kpis.leves);
        setearTexto("#kpi-leves-pct", `${kpis.leves_pct}%`);
        setearWidth("#bar-leves", kpis.leves_pct);

        setearTexto("#kpi-graves", kpis.graves);
        setearTexto("#kpi-graves-pct", `${kpis.graves_pct}%`);
        setearWidth("#bar-graves", kpis.graves_pct);

        setearTexto("#kpi-fatales", kpis.fatales);
        setearTexto("#kpi-fatales-pct", `${kpis.fatales_pct}%`);
        setearWidth("#bar-fatales", kpis.fatales_pct);

        setearTexto("#kpi-moderados", kpis.moderados);
        setearTexto("#kpi-moderados-pct", `${kpis.moderados_pct}%`);
        setearWidth("#bar-moderados", kpis.moderados_pct);
    }

    function iniciarMapa() {
        const elMapa = document.querySelector("#dashboard-mini-map");
        const mapa = L.map(elMapa).setView([-38.9516, -68.0591], 9);

        //Agregamos capa de mosaico de OpenStreetMap
        const mapaClaro = L.tileLayer(
            "https://tile.openstreetmap.org/{z}/{x}/{y}.png",
            {
                maxZoom: 19,
                attribution:
                    '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            },
        ).addTo(mapa);

        const mapaOscuro = L.tileLayer(
            "https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}.png?key=cb1_51qx_1_24e563624e6d7319006285fd",
            {
                subdomains: "abcd",
                maxZoom: 19,
                attribution:
                    '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> ' +
                    '&copy; <a href="https://carto.com/attributions">CARTO</a>',
            },
        );

        const leyenda = L.control({ position: "bottomleft" });
        leyenda.onAdd = function () {
            const contenido = document.createElement("div");
            contenido.className = "leyenda-mapa";
            contenido.innerHTML = `
        <strong>Gravedad</strong>
        <div><span class="color-leve"></span> Leve</div>
        <div><span class="color-moderado"></span> Moderado</div>
        <div><span class="color-grave"></span> Grave</div>
        <div><span class="color-fatal"></span> Fatal</div>
    `;
            return contenido;
        };
        leyenda.addTo(mapa);

        capasIncidentes = L.layerGroup().addTo(mapa);
        mapaCalor = L.heatLayer([], {
            radius: 60,
            blur: 30,
            maxZoom: 13,
            minOpacity: 0.4,
        });

        L.control
            .layers(
                { Claro: mapaClaro, Oscuro: mapaOscuro },
                { Marcadores: capasIncidentes, "Mapa de calor": mapaCalor },
            )
            .addTo(mapa);

        //Al seleccionar mapa de calor se muestra mapa oscuro
        mapa.on("overlayadd", function (evento) {
            if (evento.layer === mapaCalor) {
                mapa.removeLayer(mapaClaro);
                mapaOscuro.addTo(mapa);
            }
        });

        //Al quitar mapa de calor se muestra mapa claro
        mapa.on("overlayremove", function (evento) {
            if (evento.layer === mapaCalor) {
                mapa.removeLayer(mapaOscuro);
                mapaClaro.addTo(mapa);
            }
        });
    }

    function actualizarMapa(incidentes) {

        capasIncidentes.clearLayers();
        const puntosCalor = [];
        incidentes.forEach(function (incidente) {
            let gravedadHeatmap = (incidente.gravedad || "").toLowerCase().trim();
            let intensidad = 0.5; // Valor por defecto por si viene vació

        switch (gravedadHeatmap) {
            case "leve":
                intensidad = 0.5;
                break;
            case "moderado":
                intensidad = 0.25;
                break;
            case "grave":
                intensidad = 0.8;
                break;
            case "fatal":
                intensidad = 1.0;
                break;
        }
            
            //Se agrega punto de calor por incidente
            puntosCalor.push([
                incidente.latitud,
                incidente.longitud,
                intensidad,
            ]);

            //PopUp del incidente en el marcador
            const detalle = document.createElement("div");
            detalle.className = "tarjeta-popUp";

            const tituloPopUp = document.createElement("h4");
            tituloPopUp.textContent = incidente.tipo;


            //enlace a la vista blade del incidente
            const detallePopUp = document.createElement("a");
            detallePopUp.textContent = "Ver detalle completo";
            detallePopUp.href = "/incidentes/" + incidente.id;
            detallePopUp.addEventListener("click", function (evento) {
                evento.stopPropagation();
            });

            const datosPopUp = document.createElement("p");
            datosPopUp.className = "datos-popUp";
            datosPopUp.textContent =
                "Fecha: " +
                incidente.fecha +
                "\nGravedad: " +
                incidente.gravedad +
                "\nEstado: " +
                incidente.estado;

            detalle.appendChild(tituloPopUp);
            detalle.appendChild(datosPopUp);
            detalle.appendChild(detallePopUp);

            //apariencia del marcador segun gravedad
            let claseIcono = "icono-moderado";

            if (incidente.gravedad === "Leve") {
                claseIcono = "icono-leve";
            } else if (incidente.gravedad === "Grave") {
                claseIcono = "icono-grave";
            } else if (incidente.gravedad === "Fatal") {
                claseIcono = "icono-fatal";
            }
            
            //Asignamos la clase al marcador original de Leaflet
            const icono = new L.Icon.Default({ className: claseIcono });
            const marcador = L.marker([incidente.latitud, incidente.longitud], {
                icon: icono,
            }).addTo(capasIncidentes);
            marcador.bindPopup(detalle);
            
        });
        mapaCalor.setLatLngs(puntosCalor);
    }

    async function cargarDashboard() {
        const parametros = new URLSearchParams(new FormData(form));
        const url = new URL(form.action);
        url.search = parametros.toString();

        try {
            const response = await fetch(url, {
                headers: {
                    Accept: "application/json",
                },
            });

            if (!response.ok) {
                throw new Error(`Error HTTP ${response.status}`);
            }

            const data = await response.json();
            actualizarKpis(data.kpis || {});
            actualizarGraficos(data);
            console.log(data.incidentes);
            actualizarMapa(data.incidentes)
        } catch (error) {}
    }

    form.addEventListener("submit", (event) => {
        event.preventDefault();
        cargarDashboard();
    });

    if (btnLimpiar) {
        btnLimpiar.addEventListener("click", (event) => {
            form.reset();
            event.preventDefault();
            cargarDashboard();
        });
    }

    document.addEventListener("DOMContentLoaded", () => {
        iniciarMapa();
        IniciarGraficos();
        cargarDashboard();
    });
})();
