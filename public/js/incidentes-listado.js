const formulario = document.getElementById("form-listado");
const listado = document.getElementById("listado-incidentes");
const mensaje = document.getElementById("mensaje-listado");


async function buscarIncidentes(){
    //datos del formulario
    const filtros = new URLSearchParams(new FormData(formulario));

    mensaje.textContent = "Buscando incidentes...";

    try {
        //peticion
        const respuesta = await fetch(formulario.action + "?" + filtros,{
            headers: {Accept: "application/json"},
        });

        if(!respuesta.ok){
            throw new Error ("No se pudo consultar el listado");
        }

        const datos = await respuesta.json();
        listado.textContent = "";

        datos.incidentes.forEach(function(incidente){
            //Creacion de tarjetas de incidentes
            const tarjeta = document.createElement("article");
            tarjeta.className = "gestion-registro";

            const contenido = document.createElement("div");
            contenido.className = "gestion-registro-contenido";

            //Incidentes
            agregarTexto(contenido, "h3", incidente.tipo);
            agregarTexto(contenido, "p", incidente.descripcion ?? "");
            agregarTexto(contenido, "p", "Fecha: " + incidente.fecha);
            agregarTexto(contenido, "p", "Gravedad: " + incidente.gravedad);
            agregarTexto(contenido, "p", "Estado: " + incidente.estado);
            agregarTexto(contenido, "p", "Ubicacion: " + incidente.ubicacion);

            //Choferes
            agregarTexto(contenido, "p", "Chofer: " + (incidente.chofer?.nombre ?? "Sin asignar"));

            //Vehiculos
            agregarTexto(contenido, "p", "Vehiculo: " + (incidente.vehiculo?.patente ?? "Sin asignar"));

            //Acciones 
            const acciones = document.createElement("div");
            acciones.className = "gestion-acciones";

            const detalle = document.createElement("a");
            detalle.className = "panel-boton gestion-boton-azul";
            detalle.textContent = "Ver detalle";
            detalle.href = "/incidentes/" + incidente.id;

            const editar = document.createElement("a");
            editar.className = "panel-boton panel-boton-verde";
            editar.textContent = "Modificar";
            editar.href = "/incidentes/" + incidente.id + "/edit";

            acciones.append(detalle, editar);
            agregarBotonEliminar(acciones, "/incidentes/" + incidente.id, buscarIncidentes);
            tarjeta.append(contenido, acciones);
            listado.append(tarjeta);
        });

        mensaje.textContent = "Incidentes encontrados: " + datos.incidentes.length;
    } catch (error){
        mensaje.textContent = "No se pudieron cargar los incidentes.";
        console.log(error);
    }

}

formulario.addEventListener("submit", function(evento){
    evento.preventDefault();
    buscarIncidentes();
});

formulario.addEventListener("reset", function(evento){
    evento.preventDefault();
    //Vacia los campos (inputs, selects) del formulario
    formulario.querySelectorAll("input, select").forEach(function (campo) {
        campo.value = "";
    });
    buscarIncidentes();
});

buscarIncidentes();