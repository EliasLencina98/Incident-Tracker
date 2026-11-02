
//Inicializamos el mapa centrado en Neuquen
const mapa = L.map("mapa").setView([-38.9516, -68.0591], 9);

//Agregamos capa de mosaico de OpenStreetMap
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(mapa);

/****************************************************************************************************/

//Recupero formulario del filtro y tarjetas de incidentes
const formFiltros = document.getElementById("form-filtros");
const msjFiltro = document.querySelector(".msj-filtro");
const listadoIncidentes = document.getElementById("listado-incidentes");

//Creo grupo de capas
const capasIncidentes = L.layerGroup().addTo(mapa);


async function buscarDatos() {

    const fechaInicio = document.getElementById("fecha_inicio").value;
    const fechaFin = document.getElementById("fecha_fin").value;
    const gravedad = document.getElementById("gravedad").value;
    const tipo = document.getElementById("tipo").value;

    const parametros = new URLSearchParams();
    parametros.set("fecha_inicio", fechaInicio);
    parametros.set("fecha_fin", fechaFin);
    parametros.set("gravedad", gravedad);
    parametros.set("tipo", tipo);

    const url = formFiltros.action + "?" + parametros.toString();

    msjFiltro.textContent = "Buscando incidentes...";

    //enviamos consulta
    const respuesta = await fetch(url);

    //json a datos js
    const incidentes = await respuesta.json();

    //borramos marcadores anteriores
    capasIncidentes.clearLayers();
    listadoIncidentes.textContent = "";

    //marcador por cada incidente recibido
    incidentes.forEach(function(incidente){

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
        tarjeta.addEventListener("click", function(){
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
        datosPopUp.textContent = "Fecha: " + incidente.fecha + "\nGravedad: " + incidente.gravedad + "\nEstado: " + incidente.estado;

        detalle.appendChild(tituloPopUp);
        detalle.appendChild(datosPopUp);
        detalle.appendChild(ubicacionTitulo);
        detalle.appendChild(ubicacion);
        detalle.appendChild(descripcionTitulo);
        detalle.appendChild(descripcion);
        
        const marcador = L.marker([incidente.latitud, incidente.longitud]).addTo(capasIncidentes);
        marcador.bindPopup(detalle);
    });

    if(incidentes.length === 0){
        listadoIncidentes.textContent = "No hay incidentes para mostrar.";
    }
    msjFiltro.textContent = "Incidentes encontrados: " + incidentes.length;
}


formFiltros.addEventListener("submit", function(evento){
    evento.preventDefault();
    buscarDatos();
    });

formFiltros.addEventListener("reset", function(evento){
    evento.preventDefault();

    document.getElementById("fecha_inicio").value = "";
    document.getElementById("fecha_fin").value = "";
    document.getElementById("gravedad").value = "";
    document.getElementById("tipo").value = "";

    buscarDatos();
})

buscarDatos();
