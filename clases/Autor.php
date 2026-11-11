<?php

class Autor
{
    private $id, $nombre, $email, $telefono, $passwordHash, $activo, $idRol;

    public function __construct($nombre, $email, $telefono, $idRol, $id = null, $activo = true)
    {
        $this->id = $id;
        $this->setNombre($nombre);
        if ($email !== null && $email !== '') {
            $this->setEmail($email);
        } else {
            $this->email = null;
        }
        $this->setTelefono($telefono);
        $this->setIdRol($idRol);
        $this->setActivo($activo);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getTelefono()
    {
        return $this->telefono;
    }

    public function getActivo()
    {
        return $this->activo;
    }

    public function getIdRol()
    {
        return $this->idRol;
    }

    public function setNombre($nombre)
    {
        if (trim($nombre) === "") {
            throw new InvalidArgumentException("Debe indicar el nombre del usuario");
        }
        $this->nombre = trim($nombre);
    }

    public function setTelefono($telefono)
    {
        if (trim($telefono) === "") {
            throw new InvalidArgumentException("Debe indicar el telefono del usuario");
        }
        $this->telefono = trim($telefono);
    }

    public function setEmail($email)
    {
        $email = trim($email);

        if ($email === "") {
            throw new InvalidArgumentException("Debe indicar el email del usuario");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("El email ingresado no es valido");
        }
        $this->email = $email;
    }

    public function setActivo($activo)
    {
        if (!is_bool($activo)) {
            throw new InvalidArgumentException("El estado del usuario debe ser verdadero o falso");
        }
        $this->activo = $activo;
    }

    public function setIdRol($idRol)
    {
        if (!filter_var($idRol, FILTER_VALIDATE_INT) || $idRol <= 0) {
            throw new InvalidArgumentException("El identificador del rol no es valido");
        }
        $this->idRol = $idRol;
    }

    public function setPassword($password)
    {
        $password = trim($password);

        if ($password === "") {
            throw new InvalidArgumentException("Debe indicar la contraseña");
        }

        $this->passwordHash = password_hash($password, PASSWORD_DEFAULT);
    }

    public function verificarPassword($password)
    {
        return password_verify($password, $this->passwordHash);
    }
}
