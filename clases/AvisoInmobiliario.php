<?php

require_once "Aviso.php";

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
        $this->setPrecioVenta($precioVenta);
        $this->setPrecioAlquiler($precioAlquiler);
        $this->setGastosComunes($gastosComunes);
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

    public function setPrecioVenta($precioVenta){
        if($this->modalidad === "Alquiler"){
            $this->precioVenta = null;
            return;
        }
        if(!is_numeric($precioVenta) || $precioVenta <= 0){
            throw new InvalidArgumentException("El precio debe ser numerico y mayor que cero");
        }
        $this->precioVenta = (float) $precioVenta;
    }

    public function getPrecioVenta () {
        return $this->precioVenta;
    }

    public function setPrecioAlquiler($precioAlquiler){
        if($this->modalidad === "Venta"){
            $this->precioAlquiler = null;
            return;
        }
        if(!is_numeric($precioAlquiler) || $precioAlquiler <= 0){
            throw new InvalidArgumentException ("El precio de alquiler debe ser numerico y mayor a cero");
        }
        $this->precioAlquiler = (float) $precioAlquiler;
    }

    public function getPrecioAlquiler(){
        return $this->precioAlquiler;
    }
    
    public function setGastosComunes($gastosComunes){
        if($this->modalidad === "Venta"){
            $this->gastosComunes = null;
            return;
        }
        if(!is_numeric($gastosComunes) || $gastosComunes < 0){
            throw new InvalidArgumentException ("Los gastos comunes deben ser un numero y ser 0 o mayor a 0");
        }
        $this->gastosComunes = (float) $gastosComunes;
    }

    public function getGastosComunes(){
        return $this->gastosComunes;
    }

    public function setDireccionInmueble ($direccionInmueble){
        if(!is_string($direccionInmueble)){
            throw new InvalidArgumentException("La dirección debe ser una cadena de texto");
        }

        $direccionInmueble = trim($direccionInmueble);

        if($direccionInmueble === ""){
            throw new InvalidArgumentException("Debe indicar la dirección del inmueble");
        }
        $this->direccionInmueble = $direccionInmueble;
    }

    public function getDireccionInmueble () {
        return $this->direccionInmueble;
    }
    
    public function setNombreInmobiliaria ($nombreInmobiliaria){
        if(!is_string($nombreInmobiliaria)){
            throw new InvalidArgumentException("El nombre de la inmobiliaria debe ser texto");
        }

        $nombreInmobiliaria = trim($nombreInmobiliaria);

        if($nombreInmobiliaria === ""){
            throw new InvalidArgumentException("Debe indicar el nombre de la inmobiliaria");
        }
        $this->nombreInmobiliaria = $nombreInmobiliaria;
    }

    public function getNombreInmobiliaria () {
        return $this->nombreInmobiliaria;
    }
    
    public function setTipoInmueble($tipoInmueble)
    {
        if ($tipoInmueble !== "Casa" && $tipoInmueble !== "Apartamento") {
            throw new InvalidArgumentException("El tipo de inmueble debe ser casa o apartamento");
        }
        $this->tipoInmueble = $tipoInmueble;
    }

    public function getTipoInmueble()
    {
        return $this->tipoInmueble;
    }
}
