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



