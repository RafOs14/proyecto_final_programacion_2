<DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, inicial-scale=1.0">

    <title>Publicar aviso | Clasificados</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- CSS del proyecto -->
    <link rel="stylesheet" href="../css/estilos.css">
</head>

<body>
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
                        <a class="nav-link" href="clasificados.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="clasificados.php#avisos">Explorar Avisos</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-acento px-4 active" href="publicador.php">
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


    <!-- FORMULARIO -->

    <main class="container seccion-avisos">

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


                        <form method="POST" action="../procesos/procesar_aviso.php">

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

                                    <select class="form-select" id="categoria"
                                        name="categoria" required>

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
                                    </div>                                </div>
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
    <script src="../js/publicador.js"></script>
</body>
</html>
