<?php

require_once "Aviso.php";

class AvisoProducto extends Aviso
{
    private $descripcionProducto, $precio;

    public function __construct($titulo, Autor $autor, $categoria, $descripcionProducto, $precio, $id = null, $resaltado = false)
    {
        parent::__construct($titulo, $autor, $categoria, $id, $resaltado);
        $this->setDescripcionProducto($descripcionProducto);
        $this->setPrecio($precio);
    }

    public function setDescripcionProducto($descripcionProducto)
    {
        if (!is_string($descripcionProducto)) {
            throw new InvalidArgumentException("La descripcion del producto debe ser texto");
        }

        $descripcionProducto = trim($descripcionProducto);

        if ($descripcionProducto === "") {
            throw new InvalidArgumentException(
                "Debe indicar una descripción para el producto"
            );
        }

        $this->descripcionProducto = $descripcionProducto;
    }

    public function getDescripcionProducto()
    {
        return $this->descripcionProducto;
    }

    public function setPrecio($precio)
    {
        if (!is_numeric($precio) || $precio <= 0) {
            throw new InvalidArgumentException("El precio del producto debe ser numerico y mayor que cero");
        }
        $this->precio = (float) $precio;
    }

    public function getPrecio()
    {
        return $this->precio;
    }
}
