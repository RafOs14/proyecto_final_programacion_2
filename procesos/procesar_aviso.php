
<?php

require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../clases/Autor.php';
require_once __DIR__ . '/../clases/Aviso.php';
require_once __DIR__ . '/../clases/AvisoLaboral.php';
require_once __DIR__ . '/../clases/AvisoProducto.php';
require_once __DIR__ . '/../clases/AvisoInmobiliario.php';
require_once __DIR__ . '/autenticar_usuario.php';

// Verificar que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /clasificados.php');
    exit;
}

// Obtener campos de texto
function obtenerCampo($nombre)
{
    if (!isset($_POST[$nombre]) || !is_string($_POST[$nombre])) {
        return '';
    }

    return trim($_POST[$nombre]);
}

// Obtener importes opcionales
function obtenerImporteOpcional($nombre)
{
    $valor = obtenerCampo($nombre);

    return $valor === '' ? null : $valor;
}

// Redirigir mostrando un mensaje
function redirigirAvisos($origen, $mensaje, $tipo = 'success')
{
    $pagina = $origen === 'admin'
        ? '/admin.php'
        : '/publicador.php';

    header(
        'Location: ' . $pagina .
        '?mensaje=' . urlencode($mensaje) .
        '&tipo=' . urlencode($tipo)
    );

    exit;
}

// Obtener el objeto Autor autenticado
function obtenerAutor($usuario)
{
    return new Autor(
        $usuario['nombre'],
        $usuario['email'],
        $usuario['telefono'],
        (int) $usuario['id_rol'],
        (int) $usuario['id_autor'],
        (bool) $usuario['activo']
    );
}

// Crear el objeto de aviso según su categoría
function construirAviso($categoria, $titulo, $autor)
{
    switch ($categoria) {

        case 1:

            return new AvisoLaboral(
                $titulo,
                $autor,
                $categoria,
                obtenerCampo('empresa'),
                obtenerCampo('descripcion_cargo'),
                obtenerImporteOpcional('sueldo')
            );

        case 2:

            return new AvisoProducto(
                $titulo,
                $autor,
                $categoria,
                obtenerCampo('descripcion_producto'),
                obtenerCampo('precio_producto')
            );

        case 3:

            return new AvisoInmobiliario(
                $titulo,
                $autor,
                $categoria,
                obtenerCampo('direccion'),
                obtenerCampo('tipo_inmueble'),
                obtenerCampo('inmobiliaria'),
                obtenerCampo('modalidad'),
                obtenerImporteOpcional('precio_venta'),
                obtenerImporteOpcional('precio_alquiler'),
                obtenerImporteOpcional('gastos_comunes')
            );

        default:

            throw new InvalidArgumentException(
                'La categoría seleccionada no es válida.'
            );
    }
}

try {

    // Recibir los datos generales
    $email = obtenerCampo('email');
    $password = obtenerCampo('password');
    $accion = obtenerCampo('accion');
    $origen = obtenerCampo('origen');

    // Determinar la página de retorno
    if (!in_array($origen, ['admin', 'publicador'], true)) {
        $origen = 'publicador';
    }

    // Autenticar al usuario
    $usuario = autenticarUsuario(
        $conexion,
        $email,
        $password
    );

    $rol = (int) $usuario['id_rol'];

    // =========================================
    // CREAR AVISO
    // =========================================

    if ($accion === 'crear_aviso') {

        // Solamente los publicadores pueden crear avisos
        if ($rol !== 2) {
            throw new RuntimeException(
                'No tiene permisos para crear avisos.'
            );
        }

        $titulo = obtenerCampo('titulo');
        $nombre = obtenerCampo('nombre');
        $telefono = obtenerCampo('telefono');

        // Determinar si el aviso será resaltado
        $resaltado = isset($_POST['resaltado']) &&
            $_POST['resaltado'] === '1';

        $categoria = filter_var(
            obtenerCampo('categoria'),
            FILTER_VALIDATE_INT
        );

        if ($titulo === '' || $nombre === '' || $telefono === '') {
            throw new InvalidArgumentException(
                'Debe completar los datos generales del aviso.'
            );
        }

        if (
            $categoria === false ||
            !in_array($categoria, [1, 2, 3], true)
        ) {
            throw new InvalidArgumentException(
                'La categoría seleccionada no es válida.'
            );
        }

        // Comprobar los datos del autor
        if (
            $nombre !== $usuario['nombre'] ||
            $telefono !== $usuario['telefono']
        ) {
            throw new InvalidArgumentException(
                'El nombre y el teléfono deben coincidir con los datos de su cuenta.'
            );
        }

        $autor = obtenerAutor($usuario);

        $aviso = construirAviso(
            $categoria,
            $titulo,
            $autor
        );

        // Asignar el estado de resaltado al aviso
        $aviso->setResaltado($resaltado);

        // Iniciar transacción
        $conexion->beginTransaction();

        // Insertar el aviso principal
        $sql = "INSERT INTO aviso (
                    titulo,
                    id_autor,
                    id_categoria,
                    resaltado
                ) VALUES (
                    :titulo,
                    :id_autor,
                    :id_categoria,
                    :resaltado
                )";

        $stmt = $conexion->prepare($sql);

        $stmt->execute([
            ':titulo' => $aviso->getTitulo(),
            ':id_autor' => $autor->getId(),
            ':id_categoria' => $categoria,
            ':resaltado' => $aviso->getResaltado() ? 1 : 0
        ]);

        $idAviso = (int) $conexion->lastInsertId();

        // Insertar los datos específicos
        switch ($categoria) {

            case 1:

                $sql = "INSERT INTO aviso_laboral (
                            id_aviso,
                            empresa_contratante,
                            descripcion_cargo,
                            sueldo_mensual
                        ) VALUES (
                            :id_aviso,
                            :empresa,
                            :descripcion,
                            :sueldo
                        )";

                $stmt = $conexion->prepare($sql);

                $stmt->execute([
                    ':id_aviso' => $idAviso,
                    ':empresa' => $aviso->getEmpresaContratante(),
                    ':descripcion' => $aviso->getDescripcionCargo(),
                    ':sueldo' => $aviso->getSueldoMensual()
                ]);

                break;

            case 2:

                $sql = "INSERT INTO aviso_producto (
                            id_aviso,
                            descripcion_producto,
                            precio
                        ) VALUES (
                            :id_aviso,
                            :descripcion,
                            :precio
                        )";

                $stmt = $conexion->prepare($sql);

                $stmt->execute([
                    ':id_aviso' => $idAviso,
                    ':descripcion' => $aviso->getDescripcionProducto(),
                    ':precio' => $aviso->getPrecio()
                ]);

                break;

            case 3:

                $sql = "SELECT id_tipo_inmueble
                        FROM tipo_inmueble
                        WHERE nombre = :nombre";

                $stmt = $conexion->prepare($sql);

                $stmt->execute([
                    ':nombre' => $aviso->getTipoInmueble()
                ]);

                $idTipoInmueble = $stmt->fetchColumn();

                if (!$idTipoInmueble) {
                    throw new RuntimeException(
                        'El tipo de inmueble no existe.'
                    );
                }

                $sql = "INSERT INTO aviso_inmobiliario (
                            id_aviso,
                            id_tipo_inmueble,
                            direccion,
                            nombre_inmobiliaria,
                            modalidad,
                            precio_venta,
                            precio_alquiler,
                            gastos_comunes
                        ) VALUES (
                            :id_aviso,
                            :id_tipo_inmueble,
                            :direccion,
                            :inmobiliaria,
                            :modalidad,
                            :precio_venta,
                            :precio_alquiler,
                            :gastos_comunes
                        )";

                $stmt = $conexion->prepare($sql);

                $stmt->execute([
                    ':id_aviso' => $idAviso,
                    ':id_tipo_inmueble' => $idTipoInmueble,
                    ':direccion' => $aviso->getDireccionInmueble(),
                    ':inmobiliaria' => $aviso->getNombreInmobiliaria(),
                    ':modalidad' => $aviso->getModalidad(),
                    ':precio_venta' => $aviso->getPrecioVenta(),
                    ':precio_alquiler' => $aviso->getPrecioAlquiler(),
                    ':gastos_comunes' => $aviso->getGastosComunes()
                ]);

                break;
        }

        $conexion->commit();

        redirigirAvisos(
            'publicador',
            'Aviso creado correctamente. Número de aviso: ' . $idAviso
        );

    // =========================================
    // MODIFICAR AVISO
    // =========================================

    } elseif ($accion === 'modificar_aviso') {

        // Solamente los publicadores pueden modificar avisos
        if ($rol !== 2) {
            throw new RuntimeException(
                'No tiene permisos para modificar avisos.'
            );
        }

        $idAviso = filter_var(
            obtenerCampo('id_aviso'),
            FILTER_VALIDATE_INT
        );

        if ($idAviso === false || $idAviso <= 0) {
            throw new InvalidArgumentException(
                'El aviso seleccionado no es válido.'
            );
        }

        // Consultar el aviso y verificar su propietario
        $sql = "SELECT id_categoria
                FROM aviso
                WHERE id_aviso = :id
                  AND id_autor = :id_autor";

        $stmt = $conexion->prepare($sql);

        $stmt->execute([
            ':id' => $idAviso,
            ':id_autor' => $usuario['id_autor']
        ]);

        $avisoExistente = $stmt->fetch();

        if (!$avisoExistente) {
            throw new RuntimeException(
                'No puede modificar este aviso porque no existe o no le pertenece.'
            );
        }

        // La categoría se obtiene de la BD.
        // No se permite modificarla desde el formulario.
        $categoria = (int) $avisoExistente['id_categoria'];

        $titulo = obtenerCampo('titulo');

        if ($titulo === '') {
            throw new InvalidArgumentException(
                'Debe ingresar el título del aviso.'
            );
        }

        $autor = obtenerAutor($usuario);

        $aviso = construirAviso(
            $categoria,
            $titulo,
            $autor
        );

        $conexion->beginTransaction();

        // Modificar el aviso principal
        $sql = "UPDATE aviso
                SET titulo = :titulo
                WHERE id_aviso = :id
                  AND id_autor = :id_autor";

        $stmt = $conexion->prepare($sql);

        $stmt->execute([
            ':titulo' => $aviso->getTitulo(),
            ':id' => $idAviso,
            ':id_autor' => $usuario['id_autor']
        ]);

        // Modificar los datos específicos
        switch ($categoria) {

            case 1:

                $sql = "UPDATE aviso_laboral
                        SET empresa_contratante = :empresa,
                            descripcion_cargo = :descripcion,
                            sueldo_mensual = :sueldo
                        WHERE id_aviso = :id";

                $stmt = $conexion->prepare($sql);

                $stmt->execute([
                    ':empresa' => $aviso->getEmpresaContratante(),
                    ':descripcion' => $aviso->getDescripcionCargo(),
                    ':sueldo' => $aviso->getSueldoMensual(),
                    ':id' => $idAviso
                ]);

                break;

            case 2:

                $sql = "UPDATE aviso_producto
                        SET descripcion_producto = :descripcion,
                            precio = :precio
                        WHERE id_aviso = :id";

                $stmt = $conexion->prepare($sql);

                $stmt->execute([
                    ':descripcion' => $aviso->getDescripcionProducto(),
                    ':precio' => $aviso->getPrecio(),
                    ':id' => $idAviso
                ]);

                break;

            case 3:

                $sql = "SELECT id_tipo_inmueble
                        FROM tipo_inmueble
                        WHERE nombre = :nombre";

                $stmt = $conexion->prepare($sql);

                $stmt->execute([
                    ':nombre' => $aviso->getTipoInmueble()
                ]);

                $idTipoInmueble = $stmt->fetchColumn();

                if (!$idTipoInmueble) {
                    throw new RuntimeException(
                        'El tipo de inmueble no existe.'
                    );
                }

                $sql = "UPDATE aviso_inmobiliario
                        SET id_tipo_inmueble = :tipo,
                            direccion = :direccion,
                            nombre_inmobiliaria = :inmobiliaria,
                            modalidad = :modalidad,
                            precio_venta = :precio_venta,
                            precio_alquiler = :precio_alquiler,
                            gastos_comunes = :gastos_comunes
                        WHERE id_aviso = :id";

                $stmt = $conexion->prepare($sql);

                $stmt->execute([
                    ':tipo' => $idTipoInmueble,
                    ':direccion' => $aviso->getDireccionInmueble(),
                    ':inmobiliaria' => $aviso->getNombreInmobiliaria(),
                    ':modalidad' => $aviso->getModalidad(),
                    ':precio_venta' => $aviso->getPrecioVenta(),
                    ':precio_alquiler' => $aviso->getPrecioAlquiler(),
                    ':gastos_comunes' => $aviso->getGastosComunes(),
                    ':id' => $idAviso
                ]);

                break;
        }

        $conexion->commit();

        redirigirAvisos(
            'publicador',
            'Aviso modificado correctamente.'
        );

    // =========================================
    // ELIMINAR AVISO
    // =========================================

    } elseif ($accion === 'eliminar_aviso') {

        // Solo administrador y publicador pueden eliminar
        if (!in_array($rol, [1, 2], true)) {
            throw new RuntimeException(
                'No tiene permisos para eliminar avisos.'
            );
        }

        $idAviso = filter_var(
            obtenerCampo('id_aviso'),
            FILTER_VALIDATE_INT
        );

        if ($idAviso === false || $idAviso <= 0) {
            throw new InvalidArgumentException(
                'El aviso seleccionado no es válido.'
            );
        }

        // Consultar el propietario del aviso
        $sql = "SELECT id_autor
                FROM aviso
                WHERE id_aviso = :id";

        $stmt = $conexion->prepare($sql);
        $stmt->execute([':id' => $idAviso]);

        $avisoExistente = $stmt->fetch();

        if (!$avisoExistente) {
            throw new InvalidArgumentException(
                'El aviso seleccionado no existe.'
            );
        }

        // El publicador solamente puede borrar sus propios avisos
        if (
            $rol === 2 &&
            (int) $avisoExistente['id_autor'] !== (int) $usuario['id_autor']
        ) {
            throw new RuntimeException(
                'No puede eliminar avisos de otros publicadores.'
            );
        }

        // El administrador puede eliminar cualquier aviso
        $sql = "DELETE FROM aviso
                WHERE id_aviso = :id";

        $stmt = $conexion->prepare($sql);
        $stmt->execute([':id' => $idAviso]);

        // Los datos específicos se eliminan mediante ON DELETE CASCADE
        redirigirAvisos(
            $rol === 1 ? 'admin' : 'publicador',
            'Aviso eliminado correctamente.'
        );

    } else {

        throw new InvalidArgumentException(
            'La operación solicitada no es válida.'
        );
    }

} catch (InvalidArgumentException $err) {

    if ($conexion->inTransaction()) {
        $conexion->rollBack();
    }

    redirigirAvisos(
        $origen ?? 'publicador',
        $err->getMessage(),
        'danger'
    );

} catch (Throwable $err) {

    if ($conexion->inTransaction()) {
        $conexion->rollBack();
    }

    error_log($err->getMessage());

    redirigirAvisos(
        $origen ?? 'publicador',
        'Ocurrió un error al procesar el aviso.',
        'danger'
    );
}
