const formularioListado = document.getElementById("filtros-listado");
const listadoIncidentes = document.getElementById("listado-incidentes");
const mensajeListado = document.getElementById("mensaje-listado");
const cantidadIncidentes = document.getElementById("cantidad-incidentes");

let ultimaConsulta = 0;

function crearTarjeta(incidente)