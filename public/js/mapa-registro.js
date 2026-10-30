//Inicializamos el mapa centrado en Neuquen
const mapa = L.map("mapa-seleccion").setView([-38.9516, -68.0591], 10);

//Agregamos capa de mosaico de OpenStreetMap
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(mapa);

// //marcador
// let marcador = null;

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

// //Se guarda coordenadas en el formulario 
// document.getElementById("latitud").value = latitud;
// document.getElementById("longitud").value = longitud;

// //Mostramos las coordenadas al usuario 
// document.getElementById('coordenadas-seleccionadas').textContent = `Latitud: ${ latitud.toPrecision(7) } | Longitud: ${ longitud.toPrecision(7) }`;

// });

let marcadorActual = null;

mapa.on('click', async function(e) {
    const lat = e.latlng.lat;
    const lon = e.latlng.lng;

    const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&zoom=18&addressdetails=1`;
    
    try {
        const response = await fetch(url, {
            headers: {
                'User-Agent': 'IncidentTrackerApp/1.0' 
            }
        });
        const data = await response.json();

        if (data && data.lat && data.lon) {
            const latAjustada = parseFloat(data.lat);
            const lonAjustada = parseFloat(data.lon);
            
            // data.display_name te da la dirección completa formateada por OpenStreetMap
            // Ej: "Avenida Las Flores, Neuquén, Confluencia, Provincia de Neuquén, Argentina"
            const direccionCompleta = data.display_name;

            document.getElementById("latitud").value = latAjustada;
            document.getElementById("longitud").value = lonAjustada;
            document.getElementById("ubicacion").value = direccionCompleta;

            if (marcadorActual) {
                mapa.removeLayer(marcadorActual);
            }

            marcadorActual = L.marker([latAjustada, lonAjustada])
                .addTo(mapa)
                .bindPopup(`<b>Ubicación:</b><br>${direccionCompleta}`)
                .openPopup();


            console.log("Dirección encontrada:", direccionCompleta);

        } else {
            alert("No se encontró una dirección válida en este punto.");
        }
    } catch (error) {
        console.error("Error al obtener la dirección:", error);
        alert("Ocurrió un error al conectar con el servicio de mapas.");
    }
});