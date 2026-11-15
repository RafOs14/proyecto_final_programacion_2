<?php

require_once __DIR__ . '/../config/conexion.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try{
        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($nombre === '' || $email === '' || $telefono === '' || $password === ''){
            throw new InvalidArgumentException('Debe completar todos los campos');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            throw new InvalidArgumentException('El email no tiene un formato valido');
        }

        $sql = "SELECT COUNT(*) FROM autor WHERE id_rol = 1";
        $stmt = $conexion->query($sql);

        if($stmt->fetchColumn() > 0){
            throw new RuntimeException('Ya existe un administrador registrado');
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO autor (nombre,email,telefono,password_hash,activo,id_rol)
                VALUES (:nombre,:email,:telefono,:password_hash,1,1)";
        
        $stmt = $conexion->prepare($sql);

        $stmt->execute([
            ':nombre' => $nombre,
            ':email' => $email,
            ':telefono' => $telefono,
            ':password_hash' => $passwordHash
        ]);

        $mensaje = 'Administrador creado correctamente';
        

    } catch (PDOException $err){
        $mensaje = 'Error al registrar administrador: ' . $err->getMessage();
    } catch (Exception $err){
        $mensaje = $err->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear administrador</title>
</head>
<body>

    <h1>Crear administrador inicial</h1>

    <?php if ($mensaje !== ''): ?>
        <p><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <form method="POST">

        <label>Nombre:</label>
        <input type="text" name="nombre" required>
        <br><br>

        <label>Email:</label>
        <input type="email" name="email" required>
        <br><br>

        <label>Teléfono:</label>
        <input type="text" name="telefono" required>
        <br><br>

        <label>Contraseña:</label>
        <input type="password" name="password" required>
        <br><br>

        <button type="submit">Crear administrador</button>

    </form>

</body>
</html>