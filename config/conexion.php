<?php

$host = "localhost";
$basedatos = "avisos_clasificados";
$usuario = "root";
$password = "";

try {
    $conexion = new PDO(
        "mysql:host=$host;dbname=$basedatos;charset=utf8mb4",
        $usuario,
        $password
    );

    $conexion->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    $conexion->setAttribute(
        PDO::ATTR_DEFAULT_FETCH_MODE,
        PDO::FETCH_ASSOC
    ),
}catch (PDOException $err){
    die("Error en la conexión a la base de datos: " . $err->getMessage());
}