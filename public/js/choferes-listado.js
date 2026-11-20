const formularioChoferes = document.getElementById("form-choferes");
const listadoChoferes = document.getElementById("listado-choferes");
const mensajeChoferes = document.getElementById("mensaje-choferes");

async function buscarChoferes(){
    //datos del formulario choferes
    const filtros = new URLSearchParams(new FormData(formularioChoferes));

    mensajeChoferes.textContent = "Buscando choferes...";

    try {
        //peticion
        const respuesta = await fetch(formularioChoferes.action + "?" + filtros,{
            headers: {Accept: "application/json"},
        });

        if(!respuesta.ok){
            throw new Error ("No se pudo consultar el listado");
        }

        const datos = await respuesta.json();
        listadoChoferes.textContent = "";

        datos.choferes.forEach( function(chofer){
            //Creacion de tarjetas de choferes
            const tarjeta = document.createElement("article");
            tarjeta.className = "gestion-registro";

            const contenido = document.createElement("div");
            contenido.className = "gestion-registro-contenido";

            agregarTexto(contenido, "h3", chofer.nombre);
            agregarTexto(contenido, "p", "DNI: " + chofer.dni);
            agregarTexto(contenido, "p", "Licencia: " + chofer.licencia);
            agregarTexto(contenido, "p", "Puesto: " + chofer.puesto);
            agregarTexto(contenido, "p", "Fecha de ingreso: " + chofer.fecha_ingreso);
            tarjeta.append(contenido);

            const acciones = document.createElement("div");
            acciones.className = "gestion-acciones";

            const detalle = document.createElement("a");
            detalle.textContent = "Ver detalle";
            detalle.className = "panel-boton gestion-boton-secundario";
            detalle.href = "/choferes/" + chofer.id;

            const editar = document.createElement("a");
            editar.textContent = "Modificar";
            editar.className = "panel-boton gestion-boton-azul";
            editar.href = "/choferes/" + chofer.id + "/editar";

            acciones.append(detalle, editar);
            tarjeta.append(acciones);

            listadoChoferes.append(tarjeta);
        });
            mensajeChoferes.textContent = "Choferes encontrados: " + datos.choferes.length;

    } catch (error) {
            mensajeChoferes.textContent = "No se pudieron cargar los choferes";
            console.log(error);

    }
}

formularioChoferes.addEventListener("submit", function (evento){
    evento.preventDefault();
    buscarChoferes();
});

formularioChoferes.addEventListener("reset", function(evento){
    evento.preventDefault();
    //Vacia los campos (inputs, selects) del formulario
    formularioChoferes.querySelectorAll("input, select").forEach(function (campo) {
        campo.value = "";
    });
    buscarChoferes();
});

buscarChoferes();