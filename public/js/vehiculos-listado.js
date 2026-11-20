const formularioVehiculos = document.getElementById("form-vehiculos");
const listadoVehiculos = document.getElementById("listado-vehiculos");
const mensajeVehiculos = document.getElementById("mensaje-vehiculos");

async function buscarVehiculos(){
    //datos del formulario vehiculos
    const filtros = new URLSearchParams(new FormData(formularioVehiculos));

    mensajeVehiculos.textContent = "Buscando vehículos...";

    try {
        //peticion
        const respuesta = await fetch(formularioVehiculos.action + "?" + filtros,{
            headers: {Accept: "application/json"},
        });

        if(!respuesta.ok){
            throw new Error ("No se pudo consultar el listado");
        }

        const datos = await respuesta.json();
        listadoVehiculos.textContent = "";

        datos.vehiculos.forEach( function(vehiculo){

            //Creacion de tarjetas de vehiculo
            const tarjeta = document.createElement("article");
            tarjeta.className = "gestion-registro";

            const contenido = document.createElement("div");
            contenido.className = "gestion-registro-contenido";

            agregarTexto(contenido, "h3", vehiculo.patente);
            agregarTexto(contenido, "p", "Modelo: " + vehiculo.modelo);
            agregarTexto(contenido, "p", "Año: " + vehiculo.anio);
            agregarTexto(contenido, "p", "Tipo: " + vehiculo.tipo_vehiculo);
            tarjeta.append(contenido);

            const acciones = document.createElement("div");
            acciones.className = "gestion-acciones";

            const detalle = document.createElement("a");
            detalle.textContent = "Ver detalle";
            detalle.className = "panel-boton gestion-boton-secundario";
            detalle.href = "/vehiculos/" + vehiculo.id;

            const editar = document.createElement("a");
            editar.textContent = "Modificar";
            editar.className = "panel-boton gestion-boton-naranja";
            editar.href = "/vehiculos/" + vehiculo.id + "/editar";

            acciones.append(detalle, editar);
            tarjeta.append(acciones);

            listadoVehiculos.append(tarjeta);
        });
            mensajeVehiculos.textContent = "Vehiculos encontrados: " + datos.vehiculos.length;

    } catch (error) {
            mensajeVehiculos.textContent = "No se pudieron cargar los vehiculos";
            console.log(error);
    }
}

formularioVehiculos.addEventListener("submit", function(evento){
    evento.preventDefault();
    buscarVehiculos();
});

formularioVehiculos.addEventListener("reset", function(evento){
    evento.preventDefault();
    //Vacia los campos (inputs, selects) del formulario
    formularioVehiculos.querySelectorAll("input, select").forEach(function (campo) {
        campo.value = "";
    });
    buscarVehiculos();
});

buscarVehiculos();