<?php

function autenticarUsuario($conexion, $email, $password, $rolEsperado = null){
    $email = trim($email);

    if($email === '' || $password === ''){
        throw new InvalidArgumentException('Debe ingresar email y contraseña');
    }

    $sql = "SELECT 
                id_autor,
                nombre,
                email,
                telefono,
                password_hash,
                activo,
                id_rol
            FROM autor
            WHERE email = :email
            LIMIT 1";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([':email' => $email]);

    $usuario = $stmt->fetch();

    if(!$usuario || !password_verify($password, $usuario['password_hash'])){
        throw new RuntimeException('Email o contraseña incorrecto');
    }

    if(!$usuario['activo']){
        throw new RuntimeException('El usuario se encuentra inactivo');
    }

    if($rolEsperado !== null && (int)$usuario['id_rol'] !== $rolEsperado){
        throw new RuntimeException('No tiene permisos para realizar esta operacion');
    }

    unset($usuario['password_hash']);

    return $usuario;

}