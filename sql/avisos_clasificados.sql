-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-11-2026 a las 00:59:04
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `avisos_clasificados`
--
CREATE DATABASE IF NOT EXISTS `avisos_clasificados` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `avisos_clasificados`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `autor`
--

DROP TABLE IF EXISTS `autor`;
CREATE TABLE `autor` (
  `id_autor` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `id_rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aviso`
--

DROP TABLE IF EXISTS `aviso`;
CREATE TABLE `aviso` (
  `id_aviso` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `fecha_hora_creacion` datetime NOT NULL DEFAULT current_timestamp(),
  `resaltado` tinyint(1) NOT NULL DEFAULT 0,
  `id_autor` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aviso_inmobiliario`
--

DROP TABLE IF EXISTS `aviso_inmobiliario`;
CREATE TABLE `aviso_inmobiliario` (
  `id_aviso` int(11) NOT NULL,
  `id_tipo_inmueble` int(11) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `nombre_inmobiliaria` varchar(100) NOT NULL,
  `modalidad` varchar(10) NOT NULL,
  `precio_venta` decimal(12,2) DEFAULT NULL,
  `precio_alquiler` decimal(12,2) DEFAULT NULL,
  `gastos_comunes` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aviso_laboral`
--

DROP TABLE IF EXISTS `aviso_laboral`;
CREATE TABLE `aviso_laboral` (
  `id_aviso` int(11) NOT NULL,
  `empresa_contratante` varchar(100) NOT NULL,
  `descripcion_cargo` text NOT NULL,
  `sueldo_mensual` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aviso_producto`
--

DROP TABLE IF EXISTS `aviso_producto`;
CREATE TABLE `aviso_producto` (
  `id_aviso` int(11) NOT NULL,
  `descripcion_producto` text NOT NULL,
  `precio` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

DROP TABLE IF EXISTS `categoria`;
CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `nombre`) VALUES
(3, 'Inmobiliario'),
(1, 'Laboral'),
(2, 'Producto');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

DROP TABLE IF EXISTS `rol`;
CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `nombre_rol`) VALUES
(1, 'Administrador'),
(2, 'Publicador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_inmueble`
--

DROP TABLE IF EXISTS `tipo_inmueble`;
CREATE TABLE `tipo_inmueble` (
  `id_tipo_inmueble` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipo_inmueble`
--

INSERT INTO `tipo_inmueble` (`id_tipo_inmueble`, `nombre`) VALUES
(2, 'Apartamento'),
(1, 'Casa');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `autor`
--
ALTER TABLE `autor`
  ADD PRIMARY KEY (`id_autor`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_autor_rol` (`id_rol`);

--
-- Indices de la tabla `aviso`
--
ALTER TABLE `aviso`
  ADD PRIMARY KEY (`id_aviso`),
  ADD KEY `fk_aviso_autor` (`id_autor`),
  ADD KEY `fk_aviso_categoria` (`id_categoria`);

--
-- Indices de la tabla `aviso_inmobiliario`
--
ALTER TABLE `aviso_inmobiliario`
  ADD PRIMARY KEY (`id_aviso`),
  ADD KEY `fk_inmobiliario_tipo` (`id_tipo_inmueble`);

--
-- Indices de la tabla `aviso_laboral`
--
ALTER TABLE `aviso_laboral`
  ADD PRIMARY KEY (`id_aviso`);

--
-- Indices de la tabla `aviso_producto`
--
ALTER TABLE `aviso_producto`
  ADD PRIMARY KEY (`id_aviso`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `tipo_inmueble`
--
ALTER TABLE `tipo_inmueble`
  ADD PRIMARY KEY (`id_tipo_inmueble`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `autor`
--
ALTER TABLE `autor`
  MODIFY `id_autor` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `aviso`
--
ALTER TABLE `aviso`
  MODIFY `id_aviso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipo_inmueble`
--
ALTER TABLE `tipo_inmueble`
  MODIFY `id_tipo_inmueble` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `autor`
--
ALTER TABLE `autor`
  ADD CONSTRAINT `fk_autor_rol` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`);

--
-- Filtros para la tabla `aviso`
--
ALTER TABLE `aviso`
  ADD CONSTRAINT `fk_aviso_autor` FOREIGN KEY (`id_autor`) REFERENCES `autor` (`id_autor`),
  ADD CONSTRAINT `fk_aviso_categoria` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`);

--
-- Filtros para la tabla `aviso_inmobiliario`
--
ALTER TABLE `aviso_inmobiliario`
  ADD CONSTRAINT `fk_inmobiliario_aviso` FOREIGN KEY (`id_aviso`) REFERENCES `aviso` (`id_aviso`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_inmobiliario_tipo` FOREIGN KEY (`id_tipo_inmueble`) REFERENCES `tipo_inmueble` (`id_tipo_inmueble`);

--
-- Filtros para la tabla `aviso_laboral`
--
ALTER TABLE `aviso_laboral`
  ADD CONSTRAINT `fk_laboral_aviso` FOREIGN KEY (`id_aviso`) REFERENCES `aviso` (`id_aviso`) ON DELETE CASCADE;

--
-- Filtros para la tabla `aviso_producto`
--
ALTER TABLE `aviso_producto`
  ADD CONSTRAINT `fk_producto_aviso` FOREIGN KEY (`id_aviso`) REFERENCES `aviso` (`id_aviso`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
