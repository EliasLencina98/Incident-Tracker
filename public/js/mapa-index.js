//Inicializamos el mapa centrado en Neuquen
const mapa = L.map("mapa").setView([-38.9516, -68.0591], 9);

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

//leyendas para marcadores en el mapaf
const leyenda = L.control({ position: "bottomleft" });
leyenda.onAdd = function () {
    const contenido = document.createElement("div");
    contenido.className = "leyenda-mapa";
    contenido.innerHTML = `
        <strong>Gravedad</strong>
        <div><span class="color-leve"></span> Leve</div>
        <div><span class="color-grave"></span> Grave</div>
        <div><span class="color-fatal"></span> Fatal</div>
        <div><span class="color-material"></span> Daño material</div>
    `;
    return contenido;
};
leyenda.addTo(mapa);

/****************************************************************************************************/

//Recupero formulario del filtro y tarjetas de incidentes
const formFiltros = document.getElementById("form-filtros");
const msjFiltro = document.querySelector(".msj-filtro");
const listadoIncidentes = document.getElementById("listado-incidentes");

//Creo grupo de capas y mapa de calor
const capasIncidentes = L.layerGroup().addTo(mapa);
const mapaCalor = L.heatLayer([], {
    radius: 60,
    blur: 30,
    maxZoom: 13,
    minOpacity: 0.4,
});

//Panel de contro para mostrar u ocultar capas (marcadores y mapa de calor)
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

async function buscarDatos() {
    const fechaInicio = document.getElementById("fecha_inicio").value;
    const fechaFin = document.getElementById("fecha_fin").value;
    const gravedad = document.getElementById("gravedad").value;
    const tipo = document.getElementById("tipo").value;
    const estado = document.getElementById("estado").value;

    //Validacion de las fechas (detiene el recorrido si estan mal)
    if (fechaInicio !== "" && fechaFin !== "" && fechaInicio > fechaFin) {
        msjFiltro.textContent =
            "IMPORTANTE: ¡La fecha Hasta debe ser posterior o igual a la fecha Desde!";
        return;
    }

    const parametros = new URLSearchParams();
    parametros.set("fecha_inicio", fechaInicio);
    parametros.set("fecha_fin", fechaFin);
    parametros.set("gravedad", gravedad);
    parametros.set("tipo", tipo);
    parametros.set("estado", estado);

    const url = formFiltros.action + "?" + parametros.toString();

    msjFiltro.textContent = "Buscando incidentes...";

    //enviamos consulta
    const respuesta = await fetch(url);

    //json a datos js
    const datos = await respuesta.json();
    console.log(datos);

    //borramos marcadores, inicializamos listado incidentes y puntos de calor
    capasIncidentes.clearLayers();
    listadoIncidentes.textContent = "";
    const puntosCalor = [];

    document.getElementById("metrica-total").textContent = datos.metricas.total;
    document.getElementById("metrica-leves").textContent = datos.metricas.leves;
    document.getElementById("metrica-graves").textContent = datos.metricas.graves;
    document.getElementById("metrica-fatales").textContent = datos.metricas.fatales;
    document.getElementById("metrica-danio-material").textContent = datos.metricas.danioMaterial;

    //marcador por cada incidente recibido
    datos.incidentes.forEach(function (incidente) {
        let gravedadHeatmap = (incidente.gravedad || "").toLowerCase().trim();
        let intensidad = 0.5; // Valor por defecto por si viene vació

        switch (gravedadHeatmap) {
            case "leve":
                intensidad = 0.5;
                break;
            case "daño material":
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
        puntosCalor.push([incidente.latitud, incidente.longitud, intensidad]);

        //tarjetas
        const tarjeta = document.createElement("article");
        tarjeta.className = "tarjeta-incidente";

        const titulo = document.createElement("h4");
        titulo.textContent = incidente.tipo;

        const fecha = document.createElement("p");
        fecha.textContent = "Fecha: " + incidente.fecha;

        const gravedad = document.createElement("p");
        gravedad.textContent = "Gravedad: " + incidente.gravedad;

        const estado = document.createElement("p");
        estado.textContent = "Estado: " + incidente.estado;

        //Agregamos al DOM (al final) y asociamos evento
        tarjeta.appendChild(titulo);
        tarjeta.appendChild(fecha);
        tarjeta.appendChild(gravedad);
        tarjeta.appendChild(estado);

        //Enlace ver detalles en tarjetas
        const enlaceDetalle = document.createElement("a");
        enlaceDetalle.textContent = "Ver detalle";
        enlaceDetalle.className = "enlace-detalle";
        enlaceDetalle.href = "/incidentes/" + incidente.id;
        enlaceDetalle.addEventListener("click", function (evento) {
            evento.stopPropagation(); //no se activa tambien la tarjeta
        });
        tarjeta.appendChild(enlaceDetalle);

        //Enlace editar en tarjetas
        const enlaceEditar = document.createElement("a");
        enlaceEditar.textContent = "Modificar";
        enlaceEditar.className = "enlace-editar";
        enlaceEditar.href = "/incidentes/" + incidente.id + "/edit";
        enlaceEditar.addEventListener("click", function (evento) {
            evento.stopPropagation();
        });
        tarjeta.appendChild(enlaceEditar);

        //asociamos tarjeta a evento
        tarjeta.addEventListener("click", function () {
            capasIncidentes.addTo(mapa);
            mapa.setView(marcador.getLatLng(), 16);
            marcador.openPopup();

            const contenedorMapa = document.getElementById("panel-mapa");
            contenedorMapa.scrollIntoView({
                behavior: "smooth",
                block: "start",
            });
        });
        listadoIncidentes.appendChild(tarjeta);

        //PopUp del incidente en el marcador
        const detalle = document.createElement("div");
        detalle.className = "tarjeta-popUp";

        const tituloPopUp = document.createElement("h4");
        tituloPopUp.textContent = incidente.tipo;

        const ubicacionTitulo = document.createElement("strong");
        ubicacionTitulo.textContent = "Ubicacion";
        const ubicacion = document.createElement("p");
        ubicacion.textContent = incidente.ubicacion;

        const descripcionTitulo = document.createElement("strong");
        descripcionTitulo.textContent = "Descripcion";
        const descripcion = document.createElement("p");
        descripcion.textContent = incidente.descripcion;

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
        detalle.appendChild(ubicacionTitulo);
        detalle.appendChild(ubicacion);
        detalle.appendChild(descripcionTitulo);
        detalle.appendChild(descripcion);
        detalle.appendChild(detallePopUp);

        //apariencia del marcador segun gravedad
        let claseIcono = "icono-material";

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

    //se agregan los puntos de calor al mapa de calor
    mapaCalor.setLatLngs(puntosCalor);

    if (datos.incidentes.length === 0) {
        listadoIncidentes.textContent = "No hay incidentes para mostrar.";
    }
    msjFiltro.textContent = "Incidentes filtrados: " + datos.incidentes.length;
}

formFiltros.addEventListener("submit", function (evento) {
    evento.preventDefault();
    buscarDatos();
});

formFiltros.addEventListener("reset", function (evento) {
    evento.preventDefault();

    document.getElementById("fecha_inicio").value = "";
    document.getElementById("fecha_fin").value = "";
    document.getElementById("gravedad").value = "";
    document.getElementById("tipo").value = "";
    document.getElementById("estado").value = "";

    buscarDatos();
});

buscarDatos();
