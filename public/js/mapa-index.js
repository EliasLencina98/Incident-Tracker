//Inicializamos el mapa centrado en Neuquen
const mapa = L.map("mapa").setView([-38.9516, -68.0591], 9);

//Agregamos capa de mosaico de OpenStreetMap
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(mapa);

//marcador
let marcador = null;


async function buscarDatos() {
    const url = 'api/incidentes/todas';
    const respuesta = await fetch(url);
    if (!respuesta.ok) throw new Error('Error HTTP');
    const resultado = await respuesta.json();
    console.log(resultado);

    resultado.forEach(incidente => {
        L.marker([incidente['latitud'], incidente['longitud']]).addTo(mapa);
    });

}

buscarDatos();

// //se recupera el evento de hacer click dado por usuario sobre el mapa 
// mapa.on ("click", function(evento){
// const latitud = evento.latlng.lat;
// const longitud = evento.latlng.lng;

// //Si existe un marcador anterior, se borra
// if(marcador !== null){
//     mapa.removeLayer(marcador);
// }

// //Se crea un marcador en la ubicacion seleccionada
// marcador = L.marker([latitud, longitud]).addTo(mapa);

// //Mostramos las coordenadas al usuario 
// document.getElementById('coordenadas-seleccionadas').textContent = `Latitud: ${ latitud.toPrecision(7) } | Longitud: ${ longitud.toPrecision(7) }`;

// });