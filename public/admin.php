
<?php

require_once __DIR__ . '/../config/conexion.php';

// Función para convertir caracteres especiales
function convertirCaracter($valor)
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

// Recibir mensajes enviados desde los procesadores
$mensaje = $_GET['mensaje'] ?? '';
$tipoMensaje = $_GET['tipo'] ?? '';

$clasesPermitidas = ['success', 'danger'];

if (!in_array($tipoMensaje, $clasesPermitidas, true)) {
    $tipoMensaje = 'info';
}

// Obtener todos los publicadores
$sql = "SELECT
            id_autor,
            nombre,
            email,
            telefono,
            activo
        FROM autor
        WHERE id_rol = 2
        ORDER BY nombre";

$stmt = $conexion->query($sql);
$publicadores = $stmt->fetchAll();

// Obtener todos los avisos publicados
$sql = "SELECT
            a.id_aviso,
            a.titulo,
            a.fecha_hora_creacion,
            a.id_categoria,
            c.nombre AS categoria,
            au.nombre AS autor,
            au.email AS email_autor,
            al.empresa_contratante,
            al.descripcion_cargo,
            al.sueldo_mensual,
            ap.descripcion_producto,
            ap.precio,
            ai.direccion,
            ai.nombre_inmobiliaria,
            ai.modalidad,
            ai.precio_venta,
            ai.precio_alquiler,
            ai.gastos_comunes,
            ti.nombre AS tipo_inmueble
        FROM aviso a
        JOIN categoria c ON c.id_categoria = a.id_categoria
        JOIN autor au ON au.id_autor = a.id_autor
        LEFT JOIN aviso_laboral al ON al.id_aviso = a.id_aviso
        LEFT JOIN aviso_producto ap ON ap.id_aviso = a.id_aviso
        LEFT JOIN aviso_inmobiliario ai ON ai.id_aviso = a.id_aviso
        LEFT JOIN tipo_inmueble ti ON ti.id_tipo_inmueble = ai.id_tipo_inmueble
        ORDER BY a.fecha_hora_creacion DESC";

$stmt = $conexion->query($sql);
$avisos = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Administración - Clasificados</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body>

    <main class="container py-5">

        <h1 class="mb-4">Administración</h1>

        <p class="text-muted">
            Gestión de avisos y usuarios publicadores del sistema.
        </p>

        <!-- MENSAJES DEL SISTEMA -->

        <?php if ($mensaje !== ''): ?>

            <div class="alert alert-<?php echo convertirCaracter($tipoMensaje); ?>">
                <?php echo convertirCaracter($mensaje); ?>
            </div>

        <?php endif; ?>


        <!-- LISTADO DE AVISOS -->

        <section class="card mb-5">

            <div class="card-header">
                <h2 class="h5 mb-0">Todos los avisos publicados</h2>
            </div>

            <div class="card-body">

                <?php if (count($avisos) > 0): ?>

                    <div class="table-responsive">

                        <table class="table table-striped align-middle">

                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Título</th>
                                    <th>Categoría</th>
                                    <th>Publicador</th>
                                    <th>Detalle</th>
                                    <th>Fecha</th>
                                    <th>Eliminar</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php foreach ($avisos as $aviso): ?>

                                    <tr>

                                        <td>
                                            <?php echo convertirCaracter($aviso['id_aviso']); ?>
                                        </td>

                                        <td>
                                            <?php echo convertirCaracter($aviso['titulo']); ?>
                                        </td>

                                        <td>
                                            <?php echo convertirCaracter($aviso['categoria']); ?>
                                        </td>

                                        <td>
                                            <?php echo convertirCaracter($aviso['autor']); ?>
                                            <br>
                                            <small class="text-muted">
                                                <?php echo convertirCaracter($aviso['email_autor']); ?>
                                            </small>
                                        </td>

                                        <td>

                                            <?php if ((int) $aviso['id_categoria'] === 1): ?>

                                                <strong>Empresa:</strong>
                                                <?php echo convertirCaracter($aviso['empresa_contratante']); ?>
                                                <br>

                                                <strong>Descripción:</strong>
                                                <?php echo convertirCaracter($aviso['descripcion_cargo']); ?>
                                                <br>

                                                <strong>Sueldo:</strong>
                                                <?php echo $aviso['sueldo_mensual'] !== null
                                                    ? convertirCaracter($aviso['sueldo_mensual'])
                                                    : 'No especificado'; ?>

                                            <?php elseif ((int) $aviso['id_categoria'] === 2): ?>

                                                <strong>Descripción:</strong>
                                                <?php echo convertirCaracter($aviso['descripcion_producto']); ?>
                                                <br>

                                                <strong>Precio:</strong>
                                                <?php echo convertirCaracter($aviso['precio']); ?>

                                            <?php elseif ((int) $aviso['id_categoria'] === 3): ?>

                                                <strong>Dirección:</strong>
                                                <?php echo convertirCaracter($aviso['direccion']); ?>
                                                <br>

                                                <strong>Tipo:</strong>
                                                <?php echo convertirCaracter($aviso['tipo_inmueble']); ?>
                                                <br>

                                                <strong>Inmobiliaria:</strong>
                                                <?php echo convertirCaracter($aviso['nombre_inmobiliaria']); ?>
                                                <br>

                                                <strong>Modalidad:</strong>
                                                <?php echo convertirCaracter($aviso['modalidad']); ?>
                                                <br>

                                                <?php if ($aviso['modalidad'] === 'Venta'): ?>

                                                    <strong>Precio:</strong>
                                                    <?php echo convertirCaracter($aviso['precio_venta']); ?>

                                                <?php else: ?>

                                                    <strong>Alquiler:</strong>
                                                    <?php echo convertirCaracter($aviso['precio_alquiler']); ?>
                                                    <br>

                                                    <strong>Gastos comunes:</strong>
                                                    <?php echo convertirCaracter($aviso['gastos_comunes']); ?>

                                                <?php endif; ?>

                                            <?php endif; ?>

                                        </td>

                                        <td>
                                            <?php echo convertirCaracter($aviso['fecha_hora_creacion']); ?>
                                        </td>

                                        <td>

                                            <form
                                                method="POST"
                                                action="controladores/procesar_aviso.php"
                                                onsubmit="return confirm('¿Está seguro de eliminar este aviso?');"
                                            >

                                                <input
                                                    type="hidden"
                                                    name="accion"
                                                    value="eliminar_aviso"
                                                >

                                                <input
                                                    type="hidden"
                                                    name="origen"
                                                    value="admin"
                                                >

                                                <input
                                                    type="hidden"
                                                    name="id_aviso"
                                                    value="<?php echo convertirCaracter($aviso['id_aviso']); ?>"
                                                >

                                                <input
                                                    type="email"
                                                    name="email"
                                                    class="form-control form-control-sm mb-2"
                                                    placeholder="Email administrador"
                                                    required
                                                >

                                                <input
                                                    type="password"
                                                    name="password"
                                                    class="form-control form-control-sm mb-2"
                                                    placeholder="Contraseña administrador"
                                                    required
                                                >

                                                <button
                                                    type="submit"
                                                    class="btn btn-danger btn-sm"
                                                >
                                                    Eliminar aviso
                                                </button>

                                            </form>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            </tbody>

                        </table>

                    </div>

                <?php else: ?>

                    <p class="text-muted mb-0">
                        Todavía no hay avisos publicados.
                    </p>

                <?php endif; ?>

            </div>

        </section>


        <!-- CREAR PUBLICADOR -->

        <section class="card mb-5">

            <div class="card-header">
                <h2 class="h5 mb-0">Crear publicador</h2>
            </div>

            <div class="card-body">

                <form
                    method="POST"
                    action="controladores/procesar_publicadores.php"
                >

                    <input
                        type="hidden"
                        name="accion"
                        value="crear_publicador"
                    >

                    <h3 class="h6 mb-3">Datos del publicador</h3>

                    <div class="mb-3">
                        <label for="nombre" class="form-label">
                            Nombre
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="nombre"
                            name="nombre"
                            maxlength="100"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">
                            Email
                        </label>

                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            maxlength="150"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label for="telefono" class="form-label">
                            Teléfono
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="telefono"
                            name="telefono"
                            maxlength="20"
                            required
                        >
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">
                            Contraseña
                        </label>

                        <input
                            type="password"
                            class="form-control"
                            id="password"
                            name="password"
                            required
                        >
                    </div>

                    <hr>

                    <h3 class="h6 mb-3">
                        Confirmar identidad del administrador
                    </h3>

                    <div class="mb-3">
                        <label for="email_admin" class="form-label">
                            Email del administrador
                        </label>

                        <input
                            type="email"
                            class="form-control"
                            id="email_admin"
                            name="email_admin"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label for="password_admin" class="form-label">
                            Contraseña del administrador
                        </label>

                        <input
                            type="password"
                            class="form-control"
                            id="password_admin"
                            name="password_admin"
                            required
                        >
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Crear publicador
                    </button>

                </form>

            </div>

        </section>


        <!-- LISTADO DE PUBLICADORES -->

        <section class="card">

            <div class="card-header">
                <h2 class="h5 mb-0">Publicadores registrados</h2>
            </div>

            <div class="card-body">

                <?php if (count($publicadores) > 0): ?>

                    <div class="table-responsive">

                        <table class="table table-striped align-middle">

                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Teléfono</th>
                                    <th>Estado</th>
                                    <th>Eliminar</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php foreach ($publicadores as $publicador): ?>

                                    <tr>

                                        <td>
                                            <?php echo convertirCaracter($publicador['id_autor']); ?>
                                        </td>

                                        <td>
                                            <?php echo convertirCaracter($publicador['nombre']); ?>
                                        </td>

                                        <td>
                                            <?php echo convertirCaracter($publicador['email']); ?>
                                        </td>

                                        <td>
                                            <?php echo convertirCaracter($publicador['telefono']); ?>
                                        </td>

                                        <td>

                                            <?php if ($publicador['activo']): ?>

                                                <span class="badge bg-success">
                                                    Activo
                                                </span>

                                            <?php else: ?>

                                                <span class="badge bg-secondary">
                                                    Inactivo
                                                </span>

                                            <?php endif; ?>

                                        </td>

                                        <td>

                                            <form
                                                method="POST"
                                                action="controladores/procesar_publicadores.php"
                                                onsubmit="return confirm('¿Está seguro de eliminar este publicador?');"
                                            >

                                                <input
                                                    type="hidden"
                                                    name="accion"
                                                    value="eliminar_publicador"
                                                >

                                                <input
                                                    type="hidden"
                                                    name="id_publicador"
                                                    value="<?php echo convertirCaracter($publicador['id_autor']); ?>"
                                                >

                                                <input
                                                    type="email"
                                                    name="email_admin"
                                                    class="form-control form-control-sm mb-2"
                                                    placeholder="Email administrador"
                                                    required
                                                >

                                                <input
                                                    type="password"
                                                    name="password_admin"
                                                    class="form-control form-control-sm mb-2"
                                                    placeholder="Contraseña administrador"
                                                    required
                                                >

                                                <button
                                                    type="submit"
                                                    class="btn btn-danger btn-sm"
                                                >
                                                    Eliminar
                                                </button>

                                            </form>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            </tbody>

                        </table>

                    </div>

                <?php else: ?>

                    <p class="text-muted mb-0">
                        Todavía no hay publicadores registrados.
                    </p>

                <?php endif; ?>

            </div>

        </section>


        <div class="mt-4">

            <a href="clasificados.php" class="btn btn-outline-secondary">
                Volver a clasificados
            </a>

        </div>

    </main>

</body>
</html>
