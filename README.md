# Clasificados. — Sistema de Avisos Clasificados

<p align="center">

**Encontrá lo que buscás. Publicá lo que ofrecés.**

Sistema web de avisos clasificados desarrollado en PHP y MySQL, como proyecto integrador de Programación PHP y Base de Datos 2.

</p>

---

## Acerca del proyecto

Clasificados es una aplicación web que permite publicar, consultar y administrar avisos de distintas categorías, conectando personas que ofrecen productos, servicios y oportunidades laborales con quienes los buscan.

El sistema contempla tres categorías principales:

- **Laboral:** ofertas de empleo con información de la empresa, descripción del cargo y remuneración.
- **Productos:** publicación de artículos con descripción y precio.
- **Inmobiliario:** avisos de propiedades en venta o alquiler.

La aplicación contempla distintos perfiles de usuario, con permisos específicos para consultar, publicar y administrar avisos.

## Estado del desarrollo

El proyecto se encuentra en desarrollo. Las funcionalidades se incorporan
progresivamente siguiendo los requerimientos del proyecto integrador.

## Funcionalidades previstas

### Visitantes
- Consultar los avisos publicados.
- Explorar las diferentes categorías.
- Buscar avisos.

### Publicadores
- Crear avisos de distintas categorías.
- Consultar sus propios avisos.
- Modificar avisos existentes, sin cambiar su categoría.
- Eliminar sus publicaciones.
- Gestionar sus publicaciones sin intervenir en las de otros usuarios.

### Administradores
- Consultar todos los avisos.
- Eliminar cualquier publicación.
- Crear usuarios publicadores.
- Eliminar usuarios publicadores.

## Tecnologías utilizadas

| Tecnología | Aplicación |
|---|---|
| PHP | Lógica de aplicación y programación orientada a objetos |
| MySQL 8 | Gestión y almacenamiento de datos |
| PDO | Conexión e interacción entre PHP y MySQL |
| HTML5 | Estructura de las páginas |
| CSS3 | Estilos personalizados |
| Bootstrap 5.3 | Diseño responsive e interfaz |
| Bootstrap Icons | Iconografía |
| Git / GitHub | Control de versiones |

## Arquitectura del proyecto

La aplicación se organiza separando las clases, configuración, procesos, interfaz y scripts SQL.

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
│   └── procesar_aviso.php
│
├── public/
│   ├── index.php
│   ├── clasificados.php
│   ├── publicador.php
│   └── admin.php
│
├── css/
│   └── estilos.css
│
├── js/
│   └── publicador.js
├── sql/
│   └── avisos_clasificados.sql
│
└── README.md
```

*La estructura y los archivos podrán ampliarse durante el desarrollo.*

## Instalación y configuración

### Requisitos

- PHP.
- MySQL 8.0 o superior.
- Apache o servidor web compatible.
- Visual Studio Code (opcional).
- Navegador web moderno.

Se puede utilizar XAMPP como entorno de desarrollo local.

### 1. Clonar el repositorio

```bash
git clone URL_DEL_REPOSITORIO
```

### 2. Ubicar el proyecto

Copiar o clonar la carpeta dentro del directorio público del servidor web.

En XAMPP:

```text
C:\xampp\htdocs\avisos_clasificados
```

### 3. Configurar la base de datos

El script SQL de creación y configuración de la base de datos estará
disponible en:

```text
sql/avisos_clasificados.sql
```

Ejecutarlo en MySQL para crear la estructura y los datos iniciales de la aplicación.

### 4. Configurar la conexión

Editar el archivo:

```text
config/conexion.php
```

Configurar los parámetros de conexión correspondientes al entorno local:

- Servidor.
- Nombre de la base de datos.
- Usuario.
- Contraseña.




### 5. Ejecutar la aplicación

Iniciar Apache y MySQL y acceder desde el navegador:

```text
http://localhost/avisos_clasificados/public/clasificados.php
```

## Programación orientada a objetos

El desarrollo utiliza los principios de programación orientada a objetos, incorporando:

- **Encapsulamiento:** atributos privados y acceso controlado mediante getters y setters.
- **Herencia:** especialización de la clase base `Aviso` en las distintas categorías.
- **Agregación y composición:** relaciones entre los objetos del sistema.
- **Excepciones:** control y tratamiento de errores.
- **Validaciones:** control de los datos ingresados por los usuarios.

## Seguridad

Se contemplan mecanismos para proteger la aplicación:

- Consultas preparadas mediante PDO.
- Prevención de inyección SQL.
- Contraseñas almacenadas mediante funciones de hash seguro de PHP.
- Validación de los datos recibidos desde formularios.
- Control de permisos según el perfil del usuario.
- Protección frente a la modificación o eliminación de avisos pertenecientes a otros publicadores.

## Proyecto académico

**Proyecto Integrador 2026**

- Asignatura: Programación PHP.
- Asignatura: Base de Datos 2.
- Institución: CTT Redes y Software.
- Fecha de entrega de Programación PHP: 17 de noviembre de 2026.
- Fecha de entrega de Base de Datos 2: 24 de noviembre de 2026.

---

<p align="center">

**Clasificados.**

*Una oportunidad, una publicación, una conexión.*

</p>
