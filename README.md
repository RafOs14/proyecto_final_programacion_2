# Sistema de Avisos Clasificados

Proyecto integrador desarrollado en PHP y MySQL para la gestión y publicación de avisos clasificados.

## 1. Descripción

El sistema permite publicar, consultar, modificar y eliminar avisos clasificados, organizados en tres categorías:

- **Laboral:** empresa contratante, descripción del cargo y sueldo mensual.
- **Producto:** descripción del producto y precio.
- **Inmobiliario:** dirección, tipo de inmueble, inmobiliaria y modalidad de operación (venta o alquiler).

Los avisos pueden publicarse de forma destacada mediante un costo adicional.

El sistema cuenta con tres tipos de acceso:

- **Visitante:** puede consultar los avisos publicados.
- **Publicador:** puede crear, modificar y eliminar sus propios avisos.
- **Administrador:** puede eliminar cualquier aviso y gestionar las cuentas de los publicadores.

## 2. Tecnologías utilizadas

- PHP 8.2
- MySQL / MariaDB
- PDO
- HTML5
- CSS3
- JavaScript
- Programación Orientada a Objetos (POO)

## 3. Estructura del proyecto

```text
avisos_clasificados/
│
├── clases/
│   ├── Autor.php
│   ├── Aviso.php
│   ├── AvisoLaboral.php
│   ├── AvisoProducto.php
│   └── AvisoInmobiliario.php
│
├── config/
│   └── conexion.php
│
├── procesos/
│   ├── autenticar_usuario.php
│   ├── procesar_aviso.php
│   └── procesar_publicadores.php
│
├── public/
│   ├── index.php
│   ├── clasificados.php
│   ├── publicador.php
│   ├── admin.php
│   ├── crear_admin.php
│   ├── controladores/
│   ├── css/
│   ├── js/
│   └── img/
│
├── sql/
│   └── avisos_clasificados.sql
│
└── README.md
```

## 4. Instalación y configuración

### Requisitos

- PHP 8.2 o superior.
- MySQL o MariaDB.
- Servidor local XAMPP o equivalente.
- Navegador web.

### Paso 1: Base de datos

1. Iniciar Apache y MySQL desde XAMPP.
2. Acceder a phpMyAdmin.
3. Importar el archivo `sql/avisos_clasificados.sql`.
4. Verificar que se haya creado la base de datos `avisos_clasificados`.

El archivo SQL contiene la estructura de las tablas, sus relaciones y los registros iniciales de roles, categorías y tipos de inmueble.

### Paso 2: Conexión

Verificar los datos de conexión en:

`config/conexion.php`

Configurar según el entorno:

- Servidor de base de datos.
- Nombre de la base de datos.
- Usuario.
- Contraseña.

### Paso 3: Crear administrador inicial

Con la base de datos importada:

1. Iniciar el servidor web.
2. Acceder a `crear_admin.php`.
3. Completar el formulario con los datos del administrador.
4. Registrar la cuenta.

El sistema verifica que no exista previamente un administrador y almacena la contraseña mediante un hash seguro.

### Paso 4: Iniciar la aplicación

Desde la terminal, ubicarse en la carpeta raíz del proyecto y ejecutar:

```bash
C:\xampp\php\php.exe -S localhost:8000 -t public
```

Abrir en el navegador:

`http://localhost:8000/`

La página inicial redirige al listado público de avisos.

## 5. Funcionalidades principales

### Consulta de avisos

- Visualización pública de los avisos.
- Organización por categorías.
- Información del autor y fecha de creación.
- Identificación visual de avisos destacados.

### Gestión de publicadores

- Creación de avisos laborales, de productos e inmobiliarios.
- Modificación de avisos propios.
- Eliminación de avisos propios.
- Publicación de avisos destacados.

### Administración

- Eliminación de cualquier aviso.
- Creación de cuentas de publicadores.
- Eliminación de cuentas de publicadores.

### Seguridad

- Contraseñas almacenadas mediante hash.
- Consultas preparadas utilizando PDO.
- Validaciones de datos recibidos mediante formularios.
- Control de permisos según el rol del usuario.
- Restricción de modificación y eliminación de avisos ajenos por parte de los publicadores.
- Control de excepciones en los procesos.

## 6. Base de datos

La base de datos está compuesta por las siguientes tablas:

- `autor`
- `rol`
- `categoria`
- `aviso`
- `aviso_laboral`
- `aviso_producto`
- `aviso_inmobiliario`
- `tipo_inmueble`

Las tablas específicas de cada categoría se relacionan con la tabla principal `aviso`.

## 7. Consideraciones

- El administrador inicial debe crearse antes de comenzar a utilizar las funcionalidades administrativas.
- Los publicadores se gestionan desde el panel de administración.
- Los avisos destacados se identifican mediante el atributo `resaltado`.
- La categoría de un aviso no puede modificarse una vez creado.
