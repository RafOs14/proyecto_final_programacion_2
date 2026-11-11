
<?php

require_once "../clases/Autor.php";
require_once "../clases/AvisoInmobiliario.php";

try {

    // Creamos un autor
    $autor = new Autor(
        "Juan Perez",
        "juan@gmail.com",
        "099123456",
        2
    );

    // PRUEBA 1: Inmueble en venta
    $venta = new AvisoInmobiliario(
        "Casa en venta",
        $autor,
        3,
        "Av. Giannattasio 123",
        "Casa",
        "Inmobiliaria X",
        "Venta",
        150000
    );

    echo "<h2>Propiedad en venta</h2>";
    echo "Modalidad: " . $venta->getModalidad() . "<br>";
    echo "Dirección: " . $venta->getDireccionInmueble() . "<br>";
    echo "Tipo: " . $venta->getTipoInmueble() . "<br>";
    echo "Inmobiliaria: " . $venta->getNombreInmobiliaria() . "<br>";
    echo "Precio venta: " . $venta->getPrecioVenta() . "<br>";
    echo "Precio alquiler: " . var_export($venta->getPrecioAlquiler(), true) . "<br>";
    echo "Gastos comunes: " . var_export($venta->getGastosComunes(), true) . "<br>";

    // PRUEBA 2: Inmueble en alquiler
    $alquiler = new AvisoInmobiliario(
        "Apartamento en alquiler",
        $autor,
        3,
        "Av. Brasil 456",
        "Apartamento",
        "Inmobiliaria Y",
        "Alquiler",
        null,
        25000,
        4000
    );

    echo "<h2>Propiedad en alquiler</h2>";
    echo "Modalidad: " . $alquiler->getModalidad() . "<br>";
    echo "Dirección: " . $alquiler->getDireccionInmueble() . "<br>";
    echo "Tipo: " . $alquiler->getTipoInmueble() . "<br>";
    echo "Inmobiliaria: " . $alquiler->getNombreInmobiliaria() . "<br>";
    echo "Precio venta: " . var_export($alquiler->getPrecioVenta(), true) . "<br>";
    echo "Precio alquiler: " . $alquiler->getPrecioAlquiler() . "<br>";
    echo "Gastos comunes: " . $alquiler->getGastosComunes() . "<br>";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
