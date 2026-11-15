<?php

require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../procesos/autenticar_usuario.php';

// Escapar datos para mostrarlos en HTML
function e($valor)
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

$mensaje = $_GET['mensaje'] ?? '';
$tipoMensaje = $_GET['tipo'] ?? '';

$publicador = null;
$avisos = [];

// Consultar los avisos propios
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    ($_POST['accion'] ?? '') === 'consultar_avisos'
) {

    try {

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Autenticar al publicador
        $publicador = autenticarUsuario(
            $conexion,
            $email,
            $password,
            2
        );

        // Obtener sus avisos y los datos específicos de cada categoría
        $sql = "SELECT
                    a.id_aviso,
                    a.titulo,
                    a.fecha_hora_creacion,
                    a.id_categoria,
                    c.nombre AS categoria,

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

                JOIN categoria c
                    ON c.id_categoria = a.id_categoria

                LEFT JOIN aviso_laboral al
                    ON al.id_aviso = a.id_aviso

                LEFT JOIN aviso_producto ap
                    ON ap.id_aviso = a.id_aviso

                LEFT JOIN aviso_inmobiliario ai
                    ON ai.id_aviso = a.id_aviso

                LEFT JOIN tipo_inmueble ti
                    ON ti.id_tipo_inmueble = ai.id_tipo_inmueble

                WHERE a.id_autor = :id_autor

                ORDER BY a.fecha_hora_creacion DESC";

        $stmt = $conexion->prepare($sql);

        $stmt->execute([
            ':id_autor' => $publicador['id_autor']
        ]);

        $avisos = $stmt->fetchAll();

    } catch (Throwable $err) {

        $mensaje = $err instanceof InvalidArgumentException
            ? $err->getMessage()
            : 'No fue posible consultar los avisos.';

        if (!($err instanceof InvalidArgumentException)) {
            error_log($err->getMessage());
        }

        $tipoMensaje = 'danger';
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Publicar aviso | Clasificados</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- CSS del proyecto -->
    <link rel="stylesheet" href="css/estilos.css">

</head>

<body>

    <!-- NAVEGACIÓN -->

    <nav class="navbar navbar-expand-lg navbar-dark navbar-principal">

        <div class="container">

            <a class="navbar-brand fw-bold" href="clasificados.php">
                <i class="bi bi-grid-1x2-fill me-2"></i>
                Clasificados<span class="marca-punto">.</span>
            </a>

            <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#menuPrincipal">

                <span class="navbar-toggler-icon"></span>

            </button>

            <div class="collapse navbar-collapse" id="menuPrincipal">

                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3">

                    <li class="nav-item">
                        <a class="nav-link" href="clasificados.php">
                            Inicio
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="clasificados.php#avisos">
                            Explorar Avisos
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="btn btn-acento px-4 active" href="#formulario">
                            Publicar aviso
                            <i class="bi bi-plus-lg ms-1"></i>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#misAvisos">
                            Mis avisos
                        </a>
                    </li>

                </ul>

            </div>

        </div>

    </nav>


    <!-- PORTADA -->

    <header class="portada">

        <div class="container">

            <div class="row">

                <div class="col-lg-8 mx-auto text-center">

                    <span class="etiqueta-portada">
                        PUBLICÁ TU OPORTUNIDAD
                    </span>

                    <h1 class="titulo-portada">
                        Tu aviso empieza acá.
                        <span>Publicalo hoy.</span>
                    </h1>

                    <p class="texto-portada">
                        Completá los datos y conectá con personas
                        interesadas en lo que tenés para ofrecer.
                    </p>

                </div>

            </div>

        </div>

    </header>


    <main class="container seccion-avisos">

        <!-- MENSAJES -->

        <?php if ($mensaje !== ''): ?>

            <div class="alert alert-<?php echo e($tipoMensaje); ?>">
                <?php echo e($mensaje); ?>
            </div>

        <?php endif; ?>


        <!-- FORMULARIO DE PUBLICACIÓN -->

        <section id="formulario">

            <div class="row justify-content-center">

                <div class="col-lg-9">

                    <div class="aviso-card">

                        <div class="aviso-contenido">

                            <span class="subtitulo-seccion">
                                NUEVA PUBLICACIÓN
                            </span>

                            <h2 class="titulo-seccion mb-4">
                                Datos del aviso
                            </h2>

                            <form method="POST" action="controladores/procesar_aviso.php">

                                <input type="hidden" name="accion" value="crear_aviso">
                                <input type="hidden" name="origen" value="publicador">

                                <!-- DATOS GENERALES -->

                                <h5 class="mb-3">Información general</h5>

                                <div class="row g-3">

                                    <div class="col-12">

                                        <label for="titulo" class="form-label">
                                            Título del aviso
                                        </label>

                                        <input type="text" class="form-control"
                                            id="titulo" name="titulo" required>

                                    </div>

                                    <div class="col-md-6">

                                        <label for="email" class="form-label">
                                            Email de usuario
                                        </label>

                                        <input type="email" class="form-control"
                                            id="email" name="email" required>

                                                                                </div>

                                    <div class="col-md-6">

                                        <label for="password" class="form-label">
                                            Contraseña
                                        </label>

                                        <input type="password" class="form-control"
                                            id="password" name="password" required>

                                    </div>

                                    <div class="col-md-6">

                                        <label for="nombre" class="form-label">
                                            Nombre del autor
                                        </label>

                                        <input type="text" class="form-control"
                                            id="nombre" name="nombre" required>

                                    </div>

                                    <div class="col-md-6">

                                        <label for="telefono" class="form-label">
                                            Teléfono de contacto
                                        </label>

                                        <input type="tel" class="form-control"
                                            id="telefono" name="telefono" required>

                                    </div>

                                    <div class="col-12">

                                        <label for="categoria" class="form-label">
                                            Categoría del aviso
                                        </label>

                                        <select class="form-select"
                                            id="categoria" name="categoria" required>

                                            <option value="">Seleccione una categoría</option>
                                            <option value="1">Laboral</option>
                                            <option value="2">Producto</option>
                                            <option value="3">Inmobiliario</option>

                                        </select>

                                    </div>

                                </div>


                                <hr class="my-4">


                                <!-- AVISO LABORAL -->

                                <section id="seccionLaboral" hidden>

                                    <h5 class="mb-3">
                                        <i class="bi bi-briefcase me-2"></i>
                                        Información laboral
                                    </h5>

                                    <div class="row g-3">

                                        <div class="col-12">

                                            <label for="empresa" class="form-label">
                                                Empresa contratante
                                            </label>

                                            <input type="text" class="form-control"
                                                id="empresa" name="empresa">

                                        </div>

                                        <div class="col-12">

                                            <label for="descripcion_cargo" class="form-label">
                                                Descripción del cargo
                                            </label>

                                            <textarea class="form-control"
                                                id="descripcion_cargo"
                                                name="descripcion_cargo"
                                                rows="4"></textarea>

                                        </div>

                                        <div class="col-md-6">

                                            <label for="sueldo" class="form-label">
                                                Sueldo mensual
                                            </label>

                                            <input type="number" class="form-control"
                                                id="sueldo" name="sueldo"
                                                min="0.01" step="0.01">

                                        </div>

                                    </div>

                                </section>


                                <hr class="my-4">


                                <!-- AVISO PRODUCTO -->

                                <section id="seccionProducto" hidden>

                                    <h5 class="mb-3">
                                        <i class="bi bi-bag me-2"></i>
                                        Información del producto
                                    </h5>

                                    <div class="row g-3">

                                        <div class="col-12">

                                            <label for="descripcion_producto" class="form-label">
                                                Descripción del producto
                                            </label>

                                            <textarea class="form-control"
                                                id="descripcion_producto"
                                                name="descripcion_producto"
                                                rows="4"></textarea>

                                        </div>

                                        <div class="col-md-6">

                                            <label for="precio_producto" class="form-label">
                                                Precio
                                            </label>

                                            <input type="number" class="form-control"
                                                id="precio_producto" name="precio_producto"
                                                min="0.01" step="0.01">

                                        </div>

                                    </div>

                                </section>


                                <hr class="my-4">


                                <!-- AVISO INMOBILIARIO -->

                                <section id="seccionInmobiliario" hidden>

                                    <h5 class="mb-3">
                                        <i class="bi bi-house me-2"></i>
                                        Información del inmueble
                                    </h5>

                                    <div class="row g-3">

                                        <div class="col-12">

                                            <label for="direccion" class="form-label">
                                                Dirección del inmueble
                                            </label>

                                            <input type="text" class="form-control"
                                                id="direccion" name="direccion">

                                        </div>

                                        <div class="col-md-6">

                                            <label for="tipo_inmueble" class="form-label">
                                                Tipo de inmueble
                                            </label>

                                            <select class="form-select"
                                                id="tipo_inmueble" name="tipo_inmueble">

                                                <option value="">Seleccione</option>
                                                <option value="Casa">Casa</option>
                                                <option value="Apartamento">Apartamento</option>

                                            </select>

                                        </div>

                                        <div class="col-md-6">

                                            <label for="inmobiliaria" class="form-label">
                                                Nombre de la inmobiliaria
                                            </label>

                                            <input type="text" class="form-control"
                                                id="inmobiliaria" name="inmobiliaria">

                                        </div>

                                        <div class="col-md-6">

                                            <label for="modalidad" class="form-label">
                                                Modalidad
                                            </label>

                                            <select class="form-select"
                                                id="modalidad" name="modalidad">

                                                <option value="">Seleccione</option>
                                                <option value="Venta">Venta</option>
                                                <option value="Alquiler">Alquiler</option>

                                            </select>

                                        </div>

                                        <div class="col-md-6" id="grupoPrecioVenta">

                                            <label for="precio_venta" class="form-label">
                                                Precio de venta
                                            </label>

                                            <input type="number" class="form-control"
                                                id="precio_venta" name="precio_venta"
                                                min="0.01" step="0.01">

                                        </div>

                                        <div class="col-md-6" id="grupoPrecioAlquiler">

                                            <label for="precio_alquiler" class="form-label">
                                                Precio mensual de alquiler
                                            </label>

                                            <input type="number" class="form-control"
                                                id="precio_alquiler" name="precio_alquiler"
                                                min="0.01" step="0.01">

                                        </div>

                                        <div class="col-md-6" id="grupoGastosComunes">

                                            <label for="gastos_comunes" class="form-label">
                                                Gastos comunes
                                            </label>

                                            <input type="number" class="form-control"
                                                id="gastos_comunes" name="gastos_comunes"
                                                min="0" step="0.01">

                                        </div>

                                    </div>

                                </section>


                                <hr class="my-4">

                                <!-- OPCIÓN DE AVISO RESALTADO -->

                                <div class="form-check border rounded p-3 ps-5 mb-4">

                                    <input class="form-check-input"
                                        type="checkbox"
                                        id="resaltado"
                                        name="resaltado"
                                        value="1">

                                    <label class="form-check-label" for="resaltado">

                                                                            <strong>Publicar aviso resaltado</strong>
                                        <span class="d-block text-muted small">
                                            El aviso se mostrará destacado, con mayor énfasis visual.
                                            Esta opción representa el costo doble indicado en la consigna;
                                            el sistema no procesa pagos reales.
                                        </span>
                                    </label>

                                </div>

                                <!-- BOTONES -->

                                <div class="d-flex flex-wrap justify-content-between gap-3">

                                    <a href="clasificados.php" class="btn btn-outline-secondary">
                                        Cancelar
                                    </a>

                                    <button type="submit" class="btn btn-acento px-4">

                                        <i class="bi bi-check-circle me-2"></i>
                                        Publicar aviso

                                    </button>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </section>


        <!-- MIS AVISOS -->

        <section id="misAvisos" class="mt-5">

            <div class="row justify-content-center">

                <div class="col-lg-10">

                    <div class="aviso-card">

                        <div class="aviso-contenido">

                            <span class="subtitulo-seccion">
                                GESTIÓN DE PUBLICACIONES
                            </span>

                            <h2 class="titulo-seccion mb-3">
                                Mis avisos
                            </h2>

                            <p class="text-muted">
                                Identificate para consultar y administrar tus publicaciones.
                            </p>


                            <!-- FORMULARIO DE AUTENTICACIÓN -->

                            <form method="POST" action="publicador.php" class="mb-4">

                                <input type="hidden"
                                    name="accion"
                                    value="consultar_avisos">

                                <div class="row g-3">

                                    <div class="col-md-5">

                                        <label for="email_consulta" class="form-label">
                                            Email
                                        </label>

                                        <input type="email"
                                            class="form-control"
                                            id="email_consulta"
                                            name="email"
                                            required>

                                    </div>

                                    <div class="col-md-5">

                                        <label for="password_consulta" class="form-label">
                                            Contraseña
                                        </label>

                                        <input type="password"
                                            class="form-control"
                                            id="password_consulta"
                                            name="password"
                                            required>

                                    </div>

                                    <div class="col-md-2 d-flex align-items-end">

                                        <button type="submit" class="btn btn-acento w-100">
                                            Consultar
                                        </button>

                                    </div>

                                </div>

                            </form>


                            <?php if ($publicador !== null): ?>

                                <hr class="my-4">

                                <h5 class="mb-3">
                                    Publicaciones de <?php echo e($publicador['nombre']); ?>
                                </h5>

                                <?php if (count($avisos) > 0): ?>

                                    <?php foreach ($avisos as $aviso): ?>

                                        <article class="border rounded p-3 mb-4">

                                            <div class="d-flex flex-wrap justify-content-between gap-2">

                                                <div>

                                                    <span class="badge bg-primary">
                                                        <?php echo e($aviso['categoria']); ?>
                                                    </span>

                                                    <h5 class="mt-2">
                                                        <?php echo e($aviso['titulo']); ?>
                                                    </h5>

                                                    <small class="text-muted">
                                                        Aviso N.º <?php echo e($aviso['id_aviso']); ?>
                                                        |
                                                        <?php echo e($aviso['fecha_hora_creacion']); ?>
                                                    </small>

                                                </div>

                                            </div>


                                            <!-- FORMULARIO DE MODIFICACIÓN -->

                                            <details class="mt-3">

                                                <summary class="btn btn-outline-primary btn-sm">
                                                    Modificar aviso
                                                </summary>

                                                <form method="POST"
                                                    action="controladores/procesar_aviso.php"
                                                    class="mt-3">

                                                    <input type="hidden"
                                                        name="accion"
                                                        value="modificar_aviso">

                                                    <input type="hidden"
                                                        name="origen"
                                                        value="publicador">

                                                    <input type="hidden"
                                                        name="id_aviso"
                                                        value="<?php echo e($aviso['id_aviso']); ?>">

                                                    <div class="mb-3">

                                                        <label class="form-label">
                                                            Título
                                                        </label>

                                                        <input type="text"
                                                            class="form-control"
                                                            name="titulo"
                                                            value="<?php echo e($aviso['titulo']); ?>"
                                                            required>

                                                    </div>


                                                    <?php if ((int) $aviso['id_categoria'] === 1): ?>

                                                        <div class="mb-3">

                                                            <label class="form-label">
                                                                Empresa contratante
                                                            </label>

                                                            <input type="text"
                                                                class="form-control"
                                                                name="empresa"
                                                                value="<?php echo e($aviso['empresa_contratante']); ?>"
                                                                required>

                                                        </div>

                                                        <div class="mb-3">

                                                            <label class="form-label">
                                                                Descripción del cargo
                                                            </label>

                                                            <textarea class="form-control"
                                                                name="descripcion_cargo"
                                                                rows="3"
                                                                required><?php echo e($aviso['descripcion_cargo']); ?></textarea>

                                                        </div>

                                                        <div class="mb-3">

                                                            <label class="form-label">
                                                                Sueldo mensual
                                                            </label>

                                                            <input type="number"
                                                                class="form-control"
                                                                name="sueldo"
                                                                min="0.01"
                                                                step="0.01"
                                                                value="<?php echo e($aviso['sueldo_mensual']); ?>">

                                                        </div>


                                                    <?php elseif ((int) $aviso['id_categoria'] === 2): ?>

                                                        <div class="mb-3">

                                                            <label class="form-label">
                                                                Descripción del producto
                                                            </label>

                                                            <textarea class="form-control"
                                                                name="descripcion_producto"
                                                                rows="3"
                                                                required><?php echo e($aviso['descripcion_producto']); ?></textarea>

                                                        </div>

                                                        <div class="mb-3">

                                                            <label class="form-label">
                                                                Precio
                                                            </label>

                                                            <input type="number"
                                                                class="form-control"
                                                                name="precio_producto"
                                                                min="0.01"
                                                                step="0.01"
                                                                value="<?php echo e($aviso['precio']); ?>"
                                                                required>

                                                        </div>


                                                    <?php elseif ((int) $aviso['id_categoria'] === 3): ?>

                                                        <div class="mb-3">

                                                            <label class="form-label">
                                                                Dirección
                                                            </label>

                                                            <input type="text"
                                                                class="form-control"
                                                                name="direccion"

                                                                                                                                value="<?php echo e($aviso['direccion']); ?>"
                                                                required>

                                                        </div>

                                                        <div class="mb-3">

                                                            <label class="form-label">
                                                                Tipo de inmueble
                                                            </label>

                                                            <select class="form-select"
                                                                name="tipo_inmueble"
                                                                required>

                                                                <option value="Casa"
                                                                    <?php echo $aviso['tipo_inmueble'] === 'Casa' ? 'selected' : ''; ?>>
                                                                    Casa
                                                                </option>

                                                                <option value="Apartamento"
                                                                    <?php echo $aviso['tipo_inmueble'] === 'Apartamento' ? 'selected' : ''; ?>>
                                                                    Apartamento
                                                                </option>

                                                            </select>

                                                        </div>

                                                        <div class="mb-3">

                                                            <label class="form-label">
                                                                Inmobiliaria
                                                            </label>

                                                            <input type="text"
                                                                class="form-control"
                                                                name="inmobiliaria"
                                                                value="<?php echo e($aviso['nombre_inmobiliaria']); ?>"
                                                                required>

                                                        </div>

                                                        <div class="mb-3">

                                                            <label class="form-label">
                                                                Modalidad
                                                            </label>

                                                            <select class="form-select"
                                                                name="modalidad"
                                                                required>

                                                                <option value="Venta"
                                                                    <?php echo $aviso['modalidad'] === 'Venta' ? 'selected' : ''; ?>>
                                                                    Venta
                                                                </option>

                                                                <option value="Alquiler"
                                                                    <?php echo $aviso['modalidad'] === 'Alquiler' ? 'selected' : ''; ?>>
                                                                    Alquiler
                                                                </option>

                                                            </select>

                                                        </div>

                                                        <div class="mb-3">

                                                            <label class="form-label">
                                                                Precio de venta
                                                            </label>

                                                            <input type="number"
                                                                class="form-control"
                                                                name="precio_venta"
                                                                min="0.01"
                                                                step="0.01"
                                                                value="<?php echo e($aviso['precio_venta']); ?>">

                                                        </div>

                                                        <div class="mb-3">

                                                            <label class="form-label">
                                                                Precio de alquiler
                                                            </label>

                                                            <input type="number"
                                                                class="form-control"
                                                                name="precio_alquiler"
                                                                min="0.01"
                                                                step="0.01"
                                                                value="<?php echo e($aviso['precio_alquiler']); ?>">

                                                        </div>

                                                        <div class="mb-3">

                                                            <label class="form-label">
                                                                Gastos comunes
                                                            </label>

                                                            <input type="number"
                                                                class="form-control"
                                                                name="gastos_comunes"
                                                                min="0"
                                                                step="0.01"
                                                                value="<?php echo e($aviso['gastos_comunes']); ?>">

                                                        </div>

                                                    <?php endif; ?>


                                                    <hr>

                                                    <h6>Confirmar identidad</h6>

                                                    <input type="hidden"
                                                        name="email"
                                                        value="<?php echo e($publicador['email']); ?>">

                                                    <div class="mb-3">

                                                        <label class="form-label">
                                                            Contraseña
                                                        </label>

                                                        <input type="password"
                                                            class="form-control"
                                                            name="password"
                                                            required>

                                                    </div>

                                                    <button type="submit"
                                                        class="btn btn-primary">

                                                        Guardar modificaciones

                                                    </button>

                                                </form>

                                            </details>


                                            <!-- FORMULARIO DE ELIMINACIÓN -->

                                            <form method="POST"
                                                action="controladores/procesar_aviso.php"
                                                class="mt-3"
                                                onsubmit="return confirm('¿Está seguro de eliminar este aviso?');">

                                                <input type="hidden"
                                                    name="accion"
                                                    value="eliminar_aviso">

                                                <input type="hidden"
                                                    name="origen"
                                                    value="publicador">

                                                <input type="hidden"
                                                    name="id_aviso"
                                                    value="<?php echo e($aviso['id_aviso']); ?>">

                                                <input type="hidden"
                                                    name="email"
                                                    value="<?php echo e($publicador['email']); ?>">

                                                <div class="mb-2">

                                                    <label class="form-label">
                                                        Contraseña para eliminar
                                                    </label>

                                                    <input type="password"
                                                        class="form-control"
                                                        name="password"
                                                        required>

                                                </div>

                                                <button type="submit"
                                                    class="btn btn-danger btn-sm">

                                                    <i class="bi bi-trash me-1"></i>
                                                    Eliminar aviso

                                                </button>

                                            </form>

                                        </article>

                                    <?php endforeach; ?>

                                <?php else: ?>

                                    <div class="alert alert-info">
                                        Todavía no tenés avisos publicados.
                                    </div>

                                <?php endif; ?>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </main>


    <!-- PIE DE PÁGINA -->

    <footer class="footer-principal">

        <div class="container">

            <div class="d-flex flex-wrap justify-content-between gap-3">

                <div>

                    <h5>
                        Clasificados<span class="marca-punto">.</span>
                    </h5>

                    <p>
                        Tu espacio para encontrar y ofrecer oportunidades.
                    </p>

                </div>

                <div class="footer-copy">
                    Proyecto Integrador 2026 | Todos los derechos reservados.
                </div>

            </div>

        </div>

    </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="js/publicador.js"></script>

</body>

</html>
