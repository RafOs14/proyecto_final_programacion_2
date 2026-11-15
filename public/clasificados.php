
<?php

require_once __DIR__ . '/../config/conexion.php';

function e($valor)
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

// Filtros recibidos por GET
$categoria = $_GET['categoria'] ?? '';
$busqueda = trim($_GET['busqueda'] ?? '');

// Categorías disponibles
$categorias = [
    'Laboral' => 1,
    'Producto' => 2,
    'Inmobiliario' => 3
];

// Validar categoría
if ($categoria !== '' && !isset($categorias[$categoria])) {
    $categoria = '';
}

// Consultar avisos desde la base de datos
$sql = "SELECT
            a.id_aviso,
            a.titulo,
            a.fecha_hora_creacion,
            a.resaltado,
            a.id_categoria,
            c.nombre AS categoria,
            au.nombre AS autor,
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
        WHERE 1 = 1";

$parametros = [];

// Aplicar filtro por categoría
if ($categoria !== '') {
    $sql .= " AND a.id_categoria = :categoria";
    $parametros[':categoria'] = $categorias[$categoria];
}

// Aplicar búsqueda
if ($busqueda !== '') {
    $sql .= " AND (
        a.titulo LIKE :busqueda
        OR au.nombre LIKE :busqueda
        OR al.empresa_contratante LIKE :busqueda
        OR al.descripcion_cargo LIKE :busqueda
        OR ap.descripcion_producto LIKE :busqueda
        OR ai.direccion LIKE :busqueda
        OR ai.nombre_inmobiliaria LIKE :busqueda
    )";

    $parametros[':busqueda'] = '%' . $busqueda . '%';
}

$sql .= " ORDER BY c.nombre ASC, a.fecha_hora_creacion DESC";

$stmt = $conexion->prepare($sql);
$stmt->execute($parametros);

$resultados = $stmt->fetchAll();

// Adaptar los resultados al formato utilizado por las tarjetas
$avisos = [];

foreach ($resultados as $fila) {

    $descripcion = '';
    $ubicacion = 'No especificada';
    $precio = 'A convenir';

    switch ((int) $fila['id_categoria']) {

        case 1:

            $descripcion = $fila['descripcion_cargo'];
            $ubicacion = $fila['empresa_contratante'];

            if ($fila['sueldo_mensual'] !== null) {
                $precio = '$ ' . number_format(
                    (float) $fila['sueldo_mensual'],
                    2,
                    ',',
                    '.'
                ) . ' / mes';
            }

            $imagen = 'img/categoria-laboral.svg'; //'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=800';

            break;

        case 2:

            $descripcion = $fila['descripcion_producto'];

            $precio = '$ ' . number_format(
                (float) $fila['precio'],
                2,
                ',',
                '.'
            );

            $imagen = 'img/categoria-producto.svg'; //'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=800';

            break;

        case 3:

            $descripcion = $fila['tipo_inmueble'] .
                ' - ' . $fila['modalidad'] .
                ' - ' . $fila['nombre_inmobiliaria'];

            $ubicacion = $fila['direccion'];

            if ($fila['modalidad'] === 'Venta') {

                $precio = '$ ' . number_format(
                    (float) $fila['precio_venta'],
                    2,
                    ',',
                    '.'
                );

            } else {

                $precio = '$ ' . number_format(
                    (float) $fila['precio_alquiler'],
                    2,
                    ',',
                    '.'
                ) . ' / mes';

            }

            $imagen = 'img/categoria-inmobiliario.svg'; //'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?w=800';

            break;
    }

    $avisos[] = [
        'id' => $fila['id_aviso'],
        'titulo' => $fila['titulo'],
        'categoria' => $fila['categoria'],
        'fecha' => $fila['fecha_hora_creacion'],
        'resaltado' => (bool) $fila['resaltado'],
        'descripcion' => $descripcion,
        'ubicacion' => $ubicacion,
        'autor' => $fila['autor'],
        'precio' => $precio,
        'imagen' => $imagen
    ];
}

// Los resultados ya vienen filtrados desde MySQL
$avisosFiltrados = $avisos;

?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Avisos | Clasificados</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

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

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#menuPrincipal">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="menuPrincipal">

                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3">

                    <li class="nav-item">
                        <a class="nav-link active" href="clasificados.php">Inicio</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#avisos">Explorar avisos</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="publicador.php">Publicadores</a>
                    </li>

                    <li class="nav-item">
                        <a class="btn btn-acento px-4" href="publicador.php">
                            Publicar aviso
                            <i class="bi bi-plus-lg ms-1"></i>
                        </a>
                    </li>

                </ul>

            </div>
        </div>
    </nav>


    <!-- PORTADA -->
    <header class="portada">

        <div class="container">

            <div class="row align-items-center">

                <div class="col-lg-8 mx-auto text-center">

                    <span class="etiqueta-portada">
                        TU PRÓXIMA OPORTUNIDAD ESTÁ ACÁ
                    </span>

                    <h1 class="titulo-portada">
                        Encontrá lo que buscás.<br>
                        <span>Publicá lo que ofrecés.</span>
                    </h1>

                    <p class="texto-portada">
                        Explorá oportunidades laborales, productos y propiedades
                        en un solo lugar.
                    </p>

                    <!-- BUSCADOR -->
                    <form action="clasificados.php" method="GET" class="buscador">

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>

                            <input
                                type="text"
                                name="busqueda"
                                class="form-control"
                                placeholder="¿Qué estás buscando?"
                                value="<?= e($busqueda) ?>">

                            <?php if ($categoria !== ''): ?>
                                <input type="hidden" name="categoria" value="<?= e($categoria) ?>">
                            <?php endif; ?>

                            <button class="btn btn-acento" type="submit">
                                Buscar
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </header>


    <!-- CATEGORÍAS -->
    <section class="container categorias-section">

        <div class="row g-3">

            <div class="col-md-4">

                <a href="clasificados.php?categoria=Laboral" class="categoria-card categoria-laboral">

                    <div class="categoria-icono">
                        <i class="bi bi-briefcase"></i>
                    </div>

                    <div>
                        <h5>Empleos</h5>
                        <p>Encontrá tu próximo trabajo</p>
                    </div>

                    <i class="bi bi-arrow-up-right ms-auto"></i>

                </a>

            </div>

            <div class="col-md-4">

                <a href="clasificados.php?categoria=Producto" class="categoria-card categoria-producto">

                    <div class="categoria-icono">
                        <i class="bi bi-bag"></i>
                    </div>

                    <div>
                        <h5>Productos</h5>
                        <p>Comprá y vendé artículos</p>
                    </div>

                    <i class="bi bi-arrow-up-right ms-auto"></i>

                </a>

            </div>

            <div class="col-md-4">

                <a href="clasificados.php?categoria=Inmobiliario" class="categoria-card categoria-inmobiliario">

                    <div class="categoria-icono">
                        <i class="bi bi-house"></i>
                    </div>

                    <div>
                        <h5>Inmuebles</h5>
                        <p>Casas, apartamentos y más</p>
                    </div>

                    <i class="bi bi-arrow-up-right ms-auto"></i>

                </a>

            </div>

        </div>

    </section>


    <!-- LISTADO DE AVISOS -->
    <main class="container seccion-avisos" id="avisos">

        <div class="d-flex flex-wrap justify-content-between align-items-end mb-4">

            <div>
                <span class="subtitulo-seccion">DESCUBRÍ OPORTUNIDADES</span>

                <h2 class="titulo-seccion">
                    <?php if ($categoria !== ''): ?>
                        Avisos de <?= e($categoria) ?>
                    <?php else: ?>
                        Últimos avisos publicados
                    <?php endif; ?>
                </h2>
            </div>

            <a href="clasificados.php" class="link-ver-todos">
                Ver todos <i class="bi bi-arrow-right"></i>
            </a>

        </div>


        <div class="row g-4">

            <?php if (count($avisosFiltrados) > 0): ?>

                <?php foreach ($avisosFiltrados as $aviso): ?>

                    <div class="col-md-6 col-lg-4">

                        <article class="aviso-card">

                            <div class="aviso-imagen">

                                <img
                                    src="<?= e($aviso['imagen']) ?>"
                                    alt="<?= e($aviso['titulo']) ?>">

                                <span class="badge-categoria">
                                    <?= e($aviso['categoria']) ?>
                                </span>

                                <?php if ($aviso['resaltado']): ?>
                                    <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-3">
                                        <i class="bi bi-star-fill me-1"></i>Destacado
                                    </span>
                                <?php endif; ?>

                            </div>

                            <div class="aviso-contenido">

                                <h5 class="<?= $aviso['resaltado'] ? 'fw-bold fs-4' : '' ?>">
                                    <?= e($aviso['titulo']) ?>
                                </h5>

                                <p class="text-muted small mb-2">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    Publicado el <?= e(date('d/m/Y H:i', strtotime($aviso['fecha']))) ?>
                                </p>

                                <p class="aviso-descripcion">
                                    <?= e($aviso['descripcion']) ?>
                                </p>

                                <div class="aviso-ubicacion">
                                    <i class="bi bi-geo-alt"></i>
                                    <?= e($aviso['ubicacion']) ?>
                                </div>

                                <div class="aviso-footer">

                                    <div>
                                        <span class="aviso-autor">Publicado por</span>
                                        <p><?= e($aviso['autor']) ?></p>
                                    </div>

                                    <strong class="aviso-precio">
                                        <?= e($aviso['precio']) ?>
                                    </strong>

                                </div>

                            </div>

                        </article>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <div class="col-12">

                    <div class="sin-resultados">
                        <i class="bi bi-search"></i>
                        <h4>No encontramos avisos</h4>
                        <p>Probá con otra búsqueda o seleccioná una categoría diferente.</p>
                        <a href="clasificados.php" class="btn btn-acento">
                            Ver todos los avisos
                        </a>
                    </div>

                </div>

            <?php endif; ?>

        </div>

    </main>


    <!-- LLAMADO A LA ACCIÓN -->
    <section class="publicar-section">

        <div class="container">

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <h2>¿Tenés algo para ofrecer?</h2>

                    <p>
                        Publicá tu aviso y conectá con personas que están buscando
                        lo que ofrecés.
                    </p>

                </div>

                <div class="col-lg-4 text-lg-end">

                    <a href="publicador.php" class="btn btn-light btn-lg">
                        Publicar mi aviso
                        <i class="bi bi-arrow-right ms-2"></i>
                    </a>

                </div>

            </div>

        </div>

    </section>


    <!-- PIE DE PÁGINA -->
    <footer class="footer-principal">

        <div class="container">

            <div class="d-flex flex-wrap justify-content-between gap-3">

                <div>
                    <h5>Clasificados<span class="marca-punto">.</span></h5>
                    <p>Tu espacio para encontrar y ofrecer oportunidades.</p>
                </div>

                <div class="footer-copy">
                    Proyecto Integrador 2026 | Todos los derechos reservados.
                </div>

            </div>

        </div>

    </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>