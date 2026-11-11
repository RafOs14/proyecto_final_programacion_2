<?php

class Aviso
{
    private $id, $titulo, $fechaHoraCreacion, $resaltado, $autor, $categoria;

    public function __construct($titulo, Autor $autor, $categoria, $id = null, $resaltado = false)
    {
        $this->id = $id;
        $this->fechaHoraCreacion = date('Y-m-d H:i:s');

        $this->setTitulo($titulo);
        $this->setAutor($autor);
        $this->setCategoria($categoria);
        $this->setResaltado($resaltado);
    }

    public function getTitulo()
    {
        return $this->titulo;
    }
    public function setTitulo($titulo)
    {
        $titulo = trim($titulo);

        if ($titulo === "") {
            throw new InvalidArgumentException("Debe indicar el titulo para el aviso");
        }
        $this->titulo = $titulo;
    }

    public function getFechaHoraCreacion()
    {
        return $this->fechaHoraCreacion;
    }

    public function getAutor()
    {
        return $this->autor;
    }

    public function setAutor(Autor $autor)
    {
        $this->autor = $autor;
    }

    public function getCategoria()
    {
        return $this->categoria;
    }

    protected function setCategoria($categoria)
    {
        if (!filter_var($categoria, FILTER_VALIDATE_INT) || $categoria <= 0) {
            throw new InvalidArgumentException("La categoría debe ser un identificador valido");
        }
        $this->categoria = $categoria;
    }

    public function getResaltado()
    {
        return $this->resaltado;
    }

    public function setResaltado($resaltado)
    {
        if (!is_bool($resaltado)) {
            throw new InvalidArgumentException("El resaltado solo admite verdadero o falso");
        }
        $this->resaltado = $resaltado;
    }
}
