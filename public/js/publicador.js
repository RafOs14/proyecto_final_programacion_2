
// Obtener elementos del formulario
const categoria = document.getElementById('categoria');
const modalidad = document.getElementById('modalidad');

// Secciones por categoría
const secciones = {
    1: document.getElementById('seccionLaboral'),
    2: document.getElementById('seccionProducto'),
    3: document.getElementById('seccionInmobiliario')
};

// Grupos de importes inmobiliarios
const grupoVenta = document.getElementById('grupoPrecioVenta');
const grupoAlquiler = document.getElementById('grupoPrecioAlquiler');
const grupoGastos = document.getElementById('grupoGastosComunes');

// Campos de importes
const precioVenta = document.getElementById('precio_venta');
const precioAlquiler = document.getElementById('precio_alquiler');
const gastosComunes = document.getElementById('gastos_comunes');

// Mostrar la sección correspondiente a la categoría
function cambiarCategoria() {

    // Ocultar y desactivar todas las secciones
    Object.values(secciones).forEach(seccion => {

        seccion.hidden = true;

        seccion.querySelectorAll('input, textarea, select').forEach(campo => {
            campo.disabled = true;
            campo.required = false;
        });

    });

    // Mostrar y habilitar la sección seleccionada
    const seleccionada = secciones[categoria.value];

    if (seleccionada) {

        seleccionada.hidden = false;

        seleccionada.querySelectorAll('input, textarea, select').forEach(campo => {
            campo.disabled = false;
        });

    }

    // Controlar importes inmobiliarios
    if (categoria.value === '3') {
        cambiarModalidad();
    } else {
        ocultarImportesInmobiliarios();
    }
}

// Mostrar los importes según la modalidad
function cambiarModalidad() {

    const venta = modalidad.value === 'Venta';
    const alquiler = modalidad.value === 'Alquiler';

    grupoVenta.hidden = !venta;
    grupoAlquiler.hidden = !alquiler;
    grupoGastos.hidden = !alquiler;

    // Habilitar únicamente los importes correspondientes
    precioVenta.disabled = !venta;
    precioAlquiler.disabled = !alquiler;
    gastosComunes.disabled = !alquiler;

    // Validaciones HTML
    precioVenta.required = venta;
    precioAlquiler.required = alquiler;
    gastosComunes.required = alquiler;
}

// Ocultar y desactivar todos los importes inmobiliarios
function ocultarImportesInmobiliarios() {

    grupoVenta.hidden = true;
    grupoAlquiler.hidden = true;
    grupoGastos.hidden = true;

    precioVenta.disabled = true;
    precioAlquiler.disabled = true;
    gastosComunes.disabled = true;

    precioVenta.required = false;
    precioAlquiler.required = false;
    gastosComunes.required = false;
}

// Eventos del formulario
categoria.addEventListener('change', cambiarCategoria);
modalidad.addEventListener('change', cambiarModalidad);

// Estado inicial
cambiarCategoria();
