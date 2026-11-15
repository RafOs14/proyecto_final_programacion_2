<?php

require_once "Aviso.php";

class AvisoLaboral extends Aviso
{
    private $empresaContratante, $descripcionCargo, $sueldoMensual;

    public function __construct($titulo, Autor $autor, $categoria, $empresaContratante, $descripcionCargo, $sueldoMensual = null, $id = null, $resaltado = false)
    {
        parent::__construct($titulo, $autor, $categoria, $id, $resaltado);
        $this->setEmpresaContratante($empresaContratante);
        $this->setDescripcionCargo($descripcionCargo);
        $this->setSueldoMensual($sueldoMensual);
    }

    public function setEmpresaContratante($empresaContratante)
    {

        if (!is_string($empresaContratante)) {
            throw new InvalidArgumentException("La empresa debe ser texto");
        }

        $empresaContratante = trim($empresaContratante);

        if ($empresaContratante === "") {
            throw new InvalidArgumentException("Debe indicar la empresa que contrata");
        }
        $this->empresaContratante = $empresaContratante;
    }

    public function getEmpresaContratante()
    {
        return $this->empresaContratante;
    }

    public function setDescripcionCargo($descripcionCargo)
    {
        if (!is_string($descripcionCargo)) {
            throw new InvalidArgumentException("La descripcion del cargo debe ser texto");
        }

        $descripcionCargo = trim($descripcionCargo);

        if ($descripcionCargo === "") {
            throw new InvalidArgumentException("Debe indicar el cargo del empleo");
        }
        $this->descripcionCargo = $descripcionCargo;
    }

    public function getDescripcionCargo()
    {
        return $this->descripcionCargo;
    }

    public function setSueldoMensual($sueldoMensual)
    {
        if ($sueldoMensual === null) {
            $this->sueldoMensual = null;
            return;
        }


        if (!is_numeric($sueldoMensual) || $sueldoMensual <= 0) {
            throw new InvalidArgumentException("Debe indicar un valor mayor a cero");
        }
        $this->sueldoMensual = $sueldoMensual;
    }

    public function getSueldoMensual()
    {
        return $this->sueldoMensual;
    }
}
