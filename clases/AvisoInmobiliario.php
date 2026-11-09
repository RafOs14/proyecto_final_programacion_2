<?php

class AvisoInmobiliario extends Aviso
{
    private $direccionInmueble, $tipoInmueble, $nombreInmobiliaria, $modalidad, $precioVenta, $precioAlquiler, $gastosComunes;

    public function __construct(
        $titulo,
        Autor $autor,
        $categoria,
        $direccionInmueble,
        $tipoInmueble,
        $nombreInmobiliaria,
        $modalidad,
        $precioVenta = null,
        $precioAlquiler = null,
        $gastosComunes = null,
        $id = null,
        $resaltado = false
    ) {
        parent::__construct($titulo, $autor, $categoria, $id, $resaltado);
        $this->setModalidad($modalidad);
        $this->setDireccionInmueble($direccionInmueble);
        $this->setTipoInmueble($tipoInmueble);
        $this->setNombreInmobiliaria($nombreInmobiliaria);
    }

    public function setModalidad($modalidad)
    {
        if ($modalidad !== "Venta" && $modalidad !== "Alquiler") {
            throw new InvalidArgumentException("La modalidad debe ser Venta o Alquiler");
        }
        $this->modalidad = $modalidad;
    }

    public function getModalidad()
    {
        return $this->modalidad;
    }
}
