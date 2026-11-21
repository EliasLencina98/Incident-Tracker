//Funcion para reutlizar
function agregarTexto(contenedor, etiqueta, texto) {
    const elemento = document.createElement(etiqueta);
    elemento.textContent = texto;
    contenedor.appendChild(elemento);
}

//Cambio de secciones/pestaña
const pestanas = document.querySelectorAll("[data-listado]");
const secciones = document.querySelectorAll(".gestion-seccion");

pestanas.forEach(function (boton) {
    boton.addEventListener("click", function () {
        //Oculta la seccion que no coincida con la pestaña
        secciones.forEach(function (seccion) {
            seccion.hidden = seccion.id !== boton.dataset.listado;
        });

        //Remueve *-activo de su clase  
        pestanas.forEach(function (pestana) {
            pestana.classList.remove("activo");
        });

        //Agrega *-activo a su clase
        boton.classList.add("activo");
    });
});

//Agregar boton eliminar (administrador/operario)
function agregarBotonEliminar (contenedor, url, actualizarListado){

    const permiso = document.getElementById("gestion-panel").dataset.eliminar; //data de la etiqueta html

    //Operario
    if(permiso !== "si"){
        return;
    }

    const boton = document.createElement("button");
    boton.type = "button";
    boton.textContent = "Eliminar";
    boton.className = "panel-boton gestion-boton-rojo";

    boton.addEventListener("click", async function(evento) {
        if( !confirm("¿Queres eliminar este registro?") ) {
            return;
        }

        boton.disabled = true;

        try {
            const respuesta = await fetch(url, {
                method: "DELETE",
                headers: {
                    Accept: "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                }
            });

            const datos = await respuesta.json();

            if(!respuesta.ok){
                throw new Error(datos.mensaje || datos.message || "No se pudo elinar el registro");
            }

            alert(datos.mensaje);
            actualizarListado();

        } catch (error){
            alert(error.message);
            
        } finally {
            boton.disabled = false;
        }
    });

    contenedor.appendChild(boton);
}

