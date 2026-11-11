<?php
    require_once __DIR__ . '/../clases/Autor.php';
    require_once __DIR__ . '/../clases/Aviso.php';
    require_once __DIR__ . '/../clases/AvisoLaboral.php';
    require_once __DIR__ . '/../clases/AvisoProducto.php';
    require_once __DIR__ . '/../clases/AvisoInmobiliario.php';

    //Verificamos que el formulario se haya enviado mediante POST
    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        header('Location: ../public/public.php');
        exit;
    }

    //Obtener un campo del formulario
    function obtenerCampo($nombre){
        if(!isset($_POST[$nombre]) || !is_string($_POST[$nombre])){
            return '';
        }
        return trim($_POST[$nombre]);
    }

    //Convertir campos numericos opcionales vacios en null
    function obtenerImporteOpcional($nombre){
        $valor = obtenerCampo($nombre);

        return $valor === '' ? null : $valor;
    }

    try {
        //Recibir los datos generales
        $titulo = trim($_POST['titulo'] ?? '');
        $nombre = trim($_POST['nombre'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $categoria = trim($_POST['categoria'] ?? '');

        //Validar los datos generales
        if($titulo === '' || $nombre === '' || $telefono === ''){
            throw new InvalidArgumentException(
                'Debe completar los datos generales del aviso'
            );
        }

        if(!in_array($categoria, [1,2,3], true)){
            throw new InvalidArgumentException(
                'La categoria seleccionada no es valida';
            );
        }

        //Creo el autor del aviso
        $autor = new Autor($nombre, null, $telefono, 2);

        //Creo el aviso segun la categoria indicada
        switch ($categoria){
            case 1: 
                $aviso = new AvisoLaboral($titulo,$autor,$categoria,obtenerCampo('empresa'),obtenerCampo('descripcion_cargo'),obtenerImporteOpcional('sueldo'));
                $nombreCategoria = 'Laboral';
                $detalles = [
                    'Empresa' => $aviso->getEmpresaContratante(),
                    'Descripción del cargo' => $aviso->getDescripcionCargo(),
                    'Sueldo mensual' => $aviso->getSueldoMensual()
                ];
                break;
            
            case 2:
                $aviso = new AvisoProducto($titulo,$autor,$categoria,obtenerCampo('descripcion_producto'),obtenerCampo('precio_producto'));
                $nombreCategoria = 'Producto';
                $detalles = [
                    'Descripción' => $aviso->getDescripcionProducto(),
                    'Precio' => $aviso->getPrecio()
                ];
                break;
            
            case 3:
                $aviso = new AvisoInmobiliario($titulo,$autor,$categoria,obtenerCampo('direccion'),obtenerCampo('tipo_inmueble'),obtenerCampo('inmobiliaria'),obtenerCampo('modalidad'),obtenerImporteOpcional('precio_venta'),obtenerImporteOpcional('precio_alquiler'),obtenerImporteOpcional('gastos_comunes'));
                $nombreCategoria = 'Inmobiliario';

                $detalles = [
                    'Dirección' => $aviso->getDireccionInmueble(),
                    'Tipo de inmueble' => $aviso->getTipoInmueble(),
                    'Inmobiliaria' => $aviso->getNombreInmobiliaria(),
                    'Modalidad' => $aviso->getModalidad(),
                    'Precio de venta' => $aviso->getPrecioVenta(),
                    'Precio de alquiler' => $aviso->getPrecioAlquiler(),
                    'Gastos comunes' => $aviso->getGastosComunes()
                ];
                break;    
        }

        //Mostramos la confirmación 
        function mostrarDato($valor){
            if($valor === null || $valor === ''){
                return 'No corresponde';
            }
            return htmlspecialchars((strin) $valor,ENT_QUOTES,'UTF-8');
        }

        echo '<h1>Aviso creado correctamente</h1>';
        echo '<p><strong>Titulo:</strong> ' . mostrarDato($aviso->getTitulo()) . '</p>';
        echo '<p><strong>Autor:</strong> ' . mostrarDato($aviso->getAutor()->getNombre()) . '</p>';
        echo '<p><strong>Teléfono:</strong> ' . mostrarDato($aviso->getAutor()->getTelefono()) . '</p>';
        echo '<p><strong>Categoría:</strong> ' . mostrarDato($nombreCategoria) . '</p>';
        echo '<h2>Detalles del aviso</h2>'; 

        foreach ($detalles as $campo => $valor) {
            echo '<p><strong>' . mostrarDato($campo) . ':</strong> ' . mostrarDato($valor) . '</p>';
        }
                
        echo '<p><a href="../public/publicador.php">Volver al formulario</a></p>';

    }catch (InvalidArgumentException $err){
        echo '<h2>Error en los datos ingresados</h2>';
        echo '<p>' . htmlspecialchars($err->getMessage(),ENT_QUOTES,'UTF-8') . '</p>';
        echo '<a href="../public/publicador.php">Volver al formulario</a>';
    }catch (Exception $err){
        echo '<h2>Error al procesar el aviso</h2>';
        echo '<p>' . htmlspecialchars($err->getMessage(),ENT_QUOTES,'UTF-8') . '</p>';
        echo '<a href="../public/publicador.php">Volver al formulario</a>';
    }