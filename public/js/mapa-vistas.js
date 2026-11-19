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
        <div><span class="color-moderado"></span> Moderado</div>
        <div><span class="color-grave"></span> Grave</div>
        <div><span class="color-fatal"></span> Fatal</div>
    `;
    return contenido;
};
leyenda.addTo(mapa);

/****************************************************************************************************/

//Recupero formulario del filtro y tarjetas de incidentes
const formFiltros = document.getElementById("form-filtros");
const msjFiltro = document.querySelector(".msj-filtro");


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

async function buscarDatos(){
    //URL con datos del filtro del formulario
    const parametros = new URLSearchParams(new FormData(formFiltros));

    //validacion fechas
    const fechaInicio = parametros.get("fecha_inicio");
    const fechaFin = parametros.get("fecha_fin");
    if(fechaInicio && fechaFin && fechaInicio > fechaFin) {
        msjFiltro.textContent = "La fecha Hasta debe ser posterior o igual a la fecha Desde.";
        return;
    }

    //URL del formulario de filtros
    const url = new URL(formFiltros.action);
    url.search = parametros.toString();

    msjFiltro.textContent = "Buscando incidentes...";

    try {

        const respuesta = await fetch(url, {
            headers: {
                Accept: "application/json",
            },
        });

        //datos del controlador
        const datos = await respuesta.json();

        //Mensaje de errores
        if(!respuesta.ok){
            const mensaje = Object.values(datos.errors ?? {}).flat().join(" "); //se preformatea el msj de error a una simple cadena de texto
            throw new Error(mensaje || "La consulta fallo: HTTP " + respuesta.status);
        }

        if(!Array.isArray(datos.incidentes)){
            throw new Error ("La respuesta no contiene una lista de incidentes");
        }

        //Intensidades de Heatmap
        const intensidades = {
            leve: 0.5,
            moderado: 0.65,
            grave: 0.8,
            fatal: 1,
        };

        //Iconos de marcadores
        const clasesIconos = {
            leve: "icono-leve",
            moderado: "icono-moderado",
            grave: "icono-grave",
            fatal: "icono-fatal",
        }

        const marcadores = [];
        const puntosCalor = [];

        datos.incidentes.forEach( function(incidente) {
            const latitud =  Number(incidente.latitud);
            const longitud =  Number(incidente.longitud);

            if(incidente.latitud === null || incidente.longitud === null || incidente.latitud === "" || incidente.longitud === "" ||
                !Number.isFinite(latitud) || !Number.isFinite(longitud) ) {
                    return;
            }

            const gravedad = (incidente.gravedad || "").toLowerCase().trim();
            puntosCalor.push([latitud, longitud, intensidades[gravedad] ?? 0.5]);

            //contenido del PopUp
            const detalle = document.createElement("div");
            detalle.className = "tarjeta-popUp";

            const titulo = document.createElement("h4");
            titulo.textContent = incidente.tipo;

            const resumen = document.createElement("p");
            resumen.className = "datos-popUp";
            resumen.textContent =
                "Fecha: " + incidente.fecha + "\n" +
                "Gravedad: " + incidente.gravedad + "\n" +
                "Estado: " + incidente.estado;

            const ubicacion = document.createElement("p");
            ubicacion.textContent = "Ubicación: " + incidente.ubicacion;

            const descripcion = document.createElement("p");
            descripcion.textContent = incidente.descripcion ?? "";

            const enlaceDetalle = document.createElement("a");
            enlaceDetalle.textContent = "Ver detalle";
            enlaceDetalle.href = "/incidentes/" + encodeURIComponent(incidente.id);

            //Inserto al DOM
            detalle.append(
                titulo,
                resumen,
                ubicacion,
                descripcion,
                enlaceDetalle
            );

            //Marcadores asociados a su gravedad
            const icono = new L.Icon.Default({
                className: clasesIconos[gravedad] ?? "",
            });
            const marcador = L.marker([latitud, longitud],{icon: icono,});

            marcador.bindPopup(detalle);
            marcadores.push(marcador);
        });

        //limpiamos marcadores y agregamos los recibidos al grupo de capas
        capasIncidentes.clearLayers();

        marcadores.forEach(function(marcador){
            capasIncidentes.addLayer(marcador);
        });

        //Insertamos puntos de calor al mapa de calor
        mapaCalor.setLatLngs(puntosCalor);

        msjFiltro.textContent = `Incidentes encontrados: ${datos.incidentes.length}`;



    } catch (error) {
        console.log(error);

        msjFiltro.textContent = "No se pudieron actualizar los incidentes. " + error.message;
    }


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
