//Inicializamos el mapa centrado en Neuquen
const mapa = L.map("mapa").setView([-38.9516, -68.0591], 9);

//Agregamos capa de mosaico de OpenStreetMap
const capaClara = L.tileLayer(
    "https://tile.openstreetmap.org/{z}/{x}/{y}.png",
    {
        maxZoom: 19,
        attribution:
            '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    },
).addTo(mapa);

const capaOscura = L.tileLayer("https://tiles.stadiamaps.com/tiles/stamen_toner_dark/{z}/{x}/{y}{r}.png", {
    maxZoom: 20,
    attribution: '© <a href="https://stadiamaps.com/">Stadia Maps</a>, © <a href="https://stamen.com/">Stamen Design</a>, © <a href="https://openmaptiles.org/">OpenMapTiles</a> © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors',
});

capaClara.addTo(mapa);

//leyendas para marcadores en el mapa
const leyenda = L.control({ position: "bottomleft" });
leyenda.onAdd = function () {
    const contenido = document.createElement("div");
    contenido.className = "leyenda-mapa";
    contenido.innerHTML = `<strong>Gravedad</strong>
        <div><span class="color-leve"></span>Leve</div>
        <div><span class="color-grave"></span> Grave</div>
        <div><span class="color-fatal"></span> Fatal</div>
        <div><span class="color-material"></span> Daño material</div>`;
    return contenido;
};
leyenda.addTo(mapa);

/****************************************************************************************************/

//Recupero formulario del filtro y tarjetas de incidentes
const formFiltros = document.getElementById("form-filtros");
const msjFiltro = document.querySelector(".msj-filtro");
const listadoIncidentes = document.getElementById("listado-incidentes");

//Creo grupo de capas
const capasIncidentes = L.layerGroup().addTo(mapa);

let capaDeCalor = null;
let iscapaDeCalorActivo = false;

//Botón flotante dentro del mapa para alternar el mapa de calor
const mapaDeCalorControles = L.Control.extend({
    options: { position: "topright" },
    onAdd: function (map) {
        var btn = L.DomUtil.create("button", "leaflet-bar leaflet-control");
        btn.textContent = "Mapa de calor";
        btn.style.backgroundColor = "white";
        btn.style.border = "2px solid rgba(0,0,0,0.2)";
        btn.style.padding = "6px 12px";
        btn.style.cursor = "pointer";
        btn.style.borderRadius = "4px";
        btn.style.fontWeight = "bold";
        btn.style.boxShadow = "0 1px 5px rgba(0,0,0,0.4)";

        L.DomEvent.disableClickPropagation(btn);

        btn.onclick = function () {
            iscapaDeCalorActivo = !iscapaDeCalorActivo;
            if (iscapaDeCalorActivo) {
                map.removeLayer(capasIncidentes);
                map.removeLayer(capaClara);
                capaOscura.addTo(map);
                if (capaDeCalor) capaDeCalor.addTo(map);
                btn.textContent = "📍 Ver Puntos";
                btn.style.backgroundColor = "#28a745";
                btn.style.color = "white";
            } else {
                if (capaDeCalor) map.removeLayer(capaDeCalor);
                map.removeLayer(capaOscura);
                capaClara.addTo(map);
                capasIncidentes.addTo(map);

                capasIncidentes.eachLayer(function(marcador) {
                    // Verificamos que el elemento HTML ya exista en el DOM del mapa
                    let el = marcador.getElement();
                    if (el) {
                        if (marcador.claseAsociada) {
                            el.classList.add(marcador.claseAsociada);
                        }
                    }
                });

                btn.textContent = "Mapa de calor";
                btn.style.backgroundColor = "white";
                btn.style.color = "black";
            }
        };
        return btn;
    },
});
mapa.addControl(new mapaDeCalorControles());

async function buscarDatos() {
    const fechaInicio = document.getElementById("fecha_inicio").value;
    const fechaFin = document.getElementById("fecha_fin").value;
    const gravedad = document.getElementById("gravedad").value;
    const tipo = document.getElementById("tipo").value;
    const estado = document.getElementById("estado").value;

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
    const incidentes = await respuesta.json();

    //borramos marcadores anteriores
    capasIncidentes.clearLayers();
    listadoIncidentes.textContent = "";
    let datosCalor = [];

    //marcador por cada incidente recibido
    incidentes.forEach(function (incidente) {
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
                intensidad = 0.80;
                break;
            case "fatal":
                intensidad = 1.0;
                break;
        }
        // lo pusheamos a la variable datos calor que va a contener como arreglo asociativo, para mostrarlos en el mapa
        datosCalor.push([incidente.latitud, incidente.longitud, intensidad]);

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
        //asociamos tarjeta a evento
        tarjeta.addEventListener("click", function () {
            mapa.setView(marcador.getLatLng(), 16);
            marcador.openPopup();
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

        //apariencia del marcador segun gravedad
        let claseIcono = "icono-material";

        if (incidente.gravedad === "Leve") {
            claseIcono = "icono-leve";
        } else if (incidente.gravedad === "Grave") {
            claseIcono = "icono-grave";
        } else if (incidente.gravedad === "Fatal") {
            claseIcono = "icono-fatal";
        }

        const marcador = L.marker([
            incidente.latitud,
            incidente.longitud,
        ]).addTo(capasIncidentes);

        marcador.claseAsociada = claseIcono;
        marcador.getElement().classList.add(claseIcono);
        marcador.bindPopup(detalle);
    });

    if (incidentes.length === 0) {
        listadoIncidentes.textContent = "No hay incidentes para mostrar.";
    }

    // Creamos o actualizamos la capa de calor
    if (capaDeCalor) {
        mapa.removeLayer(capaDeCalor);
    }

    console.log(datosCalor);

    // atributos del mapa de calor
    capaDeCalor = L.heatLayer(datosCalor, {
        radius: 80,
        blur: 50,
        maxZoom: 20,
        minOpacity: 0.3,
    });

    // Si el botón de mapa de calor está activo lo mostramos
    if (iscapaDeCalorActivo) {
        mapa.removeLayer(capasIncidentes);
        capaClara.remove();
        capaOscura.addTo(mapa);
        capaDeCalor.addTo(mapa);
    }

    msjFiltro.textContent = "Incidentes encontrados: " + incidentes.length;
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

    buscarDatos();
});

buscarDatos();
