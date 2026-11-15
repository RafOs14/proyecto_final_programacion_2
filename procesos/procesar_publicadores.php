
<?php

require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/autenticar_usuario.php';

// Verificar que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../public/admin.php');
    exit;
}

try {

    // Recibir los datos del administrador
    $emailAdmin = trim($_POST['email_admin'] ?? '');
    $passwordAdmin = $_POST['password_admin'] ?? '';
    $accion = $_POST['accion'] ?? '';

    // Verificar que sea administrador
    $admin = autenticarUsuario(
        $conexion,
        $emailAdmin,
        $passwordAdmin,
        1
    );

    // Crear publicador
    if ($accion === 'crear_publicador') {

        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $password = $_POST['password'] ?? '';

        if (
            $nombre === '' ||
            $email === '' ||
            $telefono === '' ||
            $password === ''
        ) {
            throw new InvalidArgumentException(
                'Debe completar todos los datos del formulario.'
            );
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException(
                'El email ingresado no es válido.'
            );
        }

        // Comprobar que el email no esté registrado
        $sql = "SELECT COUNT(*)
                FROM autor
                WHERE email = :email";

        $stmt = $conexion->prepare($sql);
        $stmt->execute([':email' => $email]);

        if ((int) $stmt->fetchColumn() > 0) {
            throw new InvalidArgumentException(
                'Ya existe un usuario registrado con ese email.'
            );
        }

        // Encriptar la contraseña
        $passwordHash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        // Insertar el publicador
        $sql = "INSERT INTO autor (
                    nombre,
                    email,
                    telefono,
                    password_hash,
                    activo,
                    id_rol
                ) VALUES (
                    :nombre,
                    :email,
                    :telefono,
                    :password_hash,
                    1,
                    2
                )";

        $stmt = $conexion->prepare($sql);

        $stmt->execute([
            ':nombre' => $nombre,
            ':email' => $email,
            ':telefono' => $telefono,
            ':password_hash' => $passwordHash
        ]);

        $mensaje = 'Publicador creado correctamente.';

    // Eliminar publicador
    } elseif ($accion === 'eliminar_publicador') {

        $idPublicador = filter_var(
            $_POST['id_publicador'] ?? '',
            FILTER_VALIDATE_INT
        );

        if ($idPublicador === false || $idPublicador <= 0) {
            throw new InvalidArgumentException(
                'El publicador seleccionado no es válido.'
            );
        }

        // Comprobar que exista y tenga rol de publicador
        $sql = "SELECT id_autor
                FROM autor
                WHERE id_autor = :id
                  AND id_rol = 2";

        $stmt = $conexion->prepare($sql);
        $stmt->execute([':id' => $idPublicador]);

        if (!$stmt->fetch()) {
            throw new InvalidArgumentException(
                'El publicador seleccionado no existe.'
            );
        }

        // Comprobar si tiene avisos publicados
        $sql = "SELECT COUNT(*)
                FROM aviso
                WHERE id_autor = :id";

        $stmt = $conexion->prepare($sql);
        $stmt->execute([':id' => $idPublicador]);

        $cantidadAvisos = (int) $stmt->fetchColumn();

        if ($cantidadAvisos > 0) {
            throw new InvalidArgumentException(
                'No se puede eliminar el publicador porque tiene avisos asociados.'
            );
        }

        // Eliminar el publicador
        $sql = "DELETE FROM autor
                WHERE id_autor = :id
                  AND id_rol = 2";

        $stmt = $conexion->prepare($sql);
        $stmt->execute([':id' => $idPublicador]);

        $mensaje = 'Publicador eliminado correctamente.';

    } else {

        throw new InvalidArgumentException(
            'La operación solicitada no es válida.'
        );
    }

    // Redirigir después de completar la operación
    header(
        'Location: /admin.php?mensaje=' . urlencode($mensaje) .
        '&tipo=success'
    );
    exit;

} catch (InvalidArgumentException $err) {

    header(
        'Location: /admin.php?mensaje=' .
        urlencode($err->getMessage()) .
        '&tipo=danger'
    );
    exit;

} catch (Throwable $err) {

    error_log($err->getMessage());

    header(
        'Location: /admin.php?mensaje=' .
        urlencode('Ocurrió un error al procesar la operación.') .
        '&tipo=danger'
    );
    exit;
}
