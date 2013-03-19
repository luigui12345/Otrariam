-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 18-02-2013 a las 22:26:16
-- Versión del servidor: 5.5.24-log
-- Versión de PHP: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `juego_navegador`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alianzas`
--

CREATE TABLE IF NOT EXISTS `alianzas` (
  `id_alianza` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` date NOT NULL,
  `id_ciudad` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_alianza`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ataques`
--

CREATE TABLE IF NOT EXISTS `ataques` (
  `id_ataque` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `objetivo` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `id_ciudad_atacante` int(11) unsigned NOT NULL,
  `id_ciudad_atacada` int(11) unsigned NOT NULL,
  `tropa1` int(11) unsigned NOT NULL,
  `tropa2` int(11) unsigned NOT NULL,
  `tropa3` int(11) unsigned NOT NULL,
  `tropa4` int(11) unsigned NOT NULL,
  `tropa5` int(11) unsigned NOT NULL,
  `tropa6` int(11) unsigned NOT NULL,
  `tropa7` int(11) unsigned NOT NULL,
  `tropa8` int(11) unsigned NOT NULL,
  `tropa9` int(11) unsigned NOT NULL,
  `tropa10` int(11) unsigned NOT NULL,
  `fecha` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id_ataque`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargos_alianzas`
--

CREATE TABLE IF NOT EXISTS `cargos_alianzas` (
  `id_cargo` int(11) NOT NULL AUTO_INCREMENT,
  `cargo1` tinyint(1) NOT NULL,
  `cargo2` tinyint(1) NOT NULL,
  `cargo3` tinyint(1) NOT NULL,
  `cargo4` tinyint(1) NOT NULL,
  `cargo5` tinyint(1) NOT NULL,
  `cargo6` tinyint(1) NOT NULL,
  `cargo7` tinyint(1) NOT NULL,
  `cargo8` tinyint(1) NOT NULL,
  `cargo9` tinyint(1) NOT NULL,
  `cargo10` tinyint(1) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_alianza` int(11) NOT NULL,
  PRIMARY KEY (`id_cargo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cola_produccion`
--

CREATE TABLE IF NOT EXISTS `cola_produccion` (
  `id_produccion` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tropa` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `n_tropas` int(11) unsigned NOT NULL,
  `n_tropas_reclutadas` int(11) unsigned NOT NULL,
  `id_ciudad` int(11) unsigned NOT NULL,
  `fecha` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id_produccion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `costes_construcciones`
--

CREATE TABLE IF NOT EXISTS `costes_construcciones` (
  `id_costo` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `edificio` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `nivel` int(11) unsigned NOT NULL,
  `produccion` double unsigned NOT NULL,
  `habitantes` int(11) unsigned NOT NULL,
  `madera` int(11) unsigned NOT NULL,
  `barro` int(11) unsigned NOT NULL,
  `hierro` int(11) unsigned NOT NULL,
  `cereal` int(11) unsigned NOT NULL,
  `tiempo` int(11) unsigned NOT NULL,
  `requerimientos` text COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id_costo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=37 ;

--
-- Volcado de datos para la tabla `costes_construcciones`
--

INSERT INTO `costes_construcciones` (`id_costo`, `edificio`, `nivel`, `produccion`, `habitantes`, `madera`, `barro`, `hierro`, `cereal`, `tiempo`, `requerimientos`) VALUES
(1, 'Granja', 1, 8, 2, 50, 50, 10, 0, 60, ''),
(2, 'Barrera', 1, 8, 2, 60, 30, 40, 10, 60, ''),
(3, 'Mina', 1, 8, 2, 50, 50, 10, 10, 60, ''),
(4, 'Leñador', 1, 8, 2, 10, 60, 30, 10, 60, ''),
(5, 'Ayuntamiento', 1, 3, 4, 100, 70, 70, 20, 180, ''),
(6, 'Granja', 2, 14, 5, 80, 110, 30, 0, 140, ''),
(7, 'Barrera', 2, 14, 4, 90, 55, 60, 10, 140, ''),
(8, 'Mina', 2, 14, 4, 90, 120, 20, 10, 140, ''),
(9, 'Leñador', 2, 14, 4, 30, 110, 100, 10, 140, ''),
(10, 'Ayuntamiento', 2, 5, 6, 200, 160, 150, 50, 500, ''),
(11, 'Granja', 3, 25, 6, 200, 210, 100, 0, 300, ''),
(12, 'Barrera', 3, 25, 6, 140, 100, 100, 40, 300, ''),
(13, 'Mina', 3, 25, 6, 120, 160, 60, 40, 300, ''),
(14, 'Leñador', 3, 25, 6, 40, 210, 100, 40, 300, ''),
(15, 'Ayuntamiento', 3, 7, 8, 300, 300, 90, 150, 1000, ''),
(16, 'Almacen', 1, 1200, 3, 180, 120, 80, 30, 350, ''),
(17, 'Almacen', 2, 2000, 5, 300, 190, 120, 50, 500, ''),
(18, 'Almacen', 3, 3000, 6, 400, 360, 200, 100, 800, ''),
(19, 'Mercado', 1, 1, 2, 120, 120, 80, 40, 300, ''),
(20, 'Mercado', 2, 2, 5, 200, 220, 150, 100, 500, ''),
(21, 'Mercado', 4, 4, 8, 400, 410, 310, 150, 850, ''),
(22, 'Mercado', 3, 3, 12, 300, 310, 270, 100, 800, ''),
(23, 'Cuartel', 1, 0, 3, 300, 70, 50, 20, 120, ''),
(24, 'Cuartel', 2, 0, 5, 200, 140, 150, 50, 200, ''),
(25, 'Cuartel', 3, 0, 8, 400, 260, 350, 200, 300, ''),
(26, 'Barrera', 4, 40, 9, 200, 180, 170, 40, 500, ''),
(27, 'Leñador', 4, 40, 9, 60, 290, 170, 40, 500, ''),
(28, 'Granja', 4, 40, 9, 250, 230, 200, 0, 500, ''),
(29, 'Mina', 4, 40, 9, 190, 260, 100, 40, 500, ''),
(30, 'Ayuntamiento', 4, 9, 12, 380, 390, 150, 200, 900, ''),
(31, 'Mercado', 5, 5, 15, 620, 630, 310, 130, 1200, ''),
(32, 'Almacen', 4, 4500, 10, 630, 620, 510, 200, 1200, ''),
(33, 'Cuartel', 4, 0, 11, 480, 350, 440, 200, 1200, ''),
(34, 'embajada', 1, 3, 3, 200, 250, 100, 20, 600, ''),
(35, 'embajada', 2, 5, 5, 400, 500, 150, 30, 750, ''),
(36, 'embajada', 3, 8, 8, 600, 750, 350, 100, 1150, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos_tropas`
--

CREATE TABLE IF NOT EXISTS `datos_tropas` (
  `id_tropa` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tropa` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `ataque` int(11) unsigned NOT NULL,
  `defensa` int(11) unsigned NOT NULL,
  `defensa_caballeria` int(11) unsigned NOT NULL,
  `madera` int(11) unsigned NOT NULL,
  `barro` int(11) unsigned NOT NULL,
  `hierro` int(11) unsigned NOT NULL,
  `cereal` int(11) unsigned NOT NULL,
  `consumo` int(11) unsigned NOT NULL,
  `velocidad` int(11) unsigned NOT NULL,
  `capacidad` int(11) unsigned NOT NULL,
  `parte_ejercito` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `tiempo` int(11) unsigned NOT NULL,
  `requisitos` text COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id_tropa`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `datos_tropas`
--

INSERT INTO `datos_tropas` (`id_tropa`, `tropa`, `nombre`, `ataque`, `defensa`, `defensa_caballeria`, `madera`, `barro`, `hierro`, `cereal`, `consumo`, `velocidad`, `capacidad`, `parte_ejercito`, `tiempo`, `requisitos`) VALUES
(1, 'tropa1', 'legionario', 20, 45, 30, 50, 20, 70, 10, 1, 15, 70, 'infanteria', 60, 'cuartel_1'),
(2, 'tropa2', 'pretoriano', 50, 60, 50, 80, 40, 140, 20, 1, 15, 50, 'infanteria', 120, 'cuartel_2'),
(3, 'tropa3', 'triario', 100, 30, 80, 100, 50, 200, 40, 1, 15, 40, 'infanteria', 180, 'cuartel_3|ayuntamiento_1'),
(4, 'tropa4', 'caballeria_ligera', 50, 30, 45, 250, 150, 200, 100, 2, 25, 120, 'caballeria', 300, 'cuartel_1'),
(5, 'tropa5', 'caballeria_pesada', 200, 50, 100, 500, 300, 600, 300, 2, 20, 50, 'caballeria', 750, 'cuartel_1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `diplomacia_alianzas`
--

CREATE TABLE IF NOT EXISTS `diplomacia_alianzas` (
  `id_diplomacia` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `id_alianza_declara` int(11) NOT NULL,
  `id_alianza_acepta` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `estado` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_diplomacia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `edificios_aldea`
--

CREATE TABLE IF NOT EXISTS `edificios_aldea` (
  `id_edificio` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `edificio` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `nivel` int(11) unsigned NOT NULL,
  `recurso` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `produccion` double unsigned NOT NULL,
  `habitantes` int(11) unsigned NOT NULL,
  `slot` int(10) unsigned NOT NULL,
  `id_ciudad` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id_edificio`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=422 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos`
--

CREATE TABLE IF NOT EXISTS `eventos` (
  `id_evento` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `edificio` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `tiempo` int(11) unsigned NOT NULL,
  `slot` int(10) unsigned NOT NULL,
  `id_ciudad` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id_evento`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `intercambios`
--

CREATE TABLE IF NOT EXISTS `intercambios` (
  `id_intercambio` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_ciudad_ofrece` int(11) unsigned NOT NULL,
  `recurso_ofrece` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `cantidad_ofrece` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `id_ciudad_busca` int(11) unsigned NOT NULL,
  `recurso_busca` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `cantidad_busca` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `fecha` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id_intercambio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mapa`
--

CREATE TABLE IF NOT EXISTS `mapa` (
  `id_casilla` int(11) NOT NULL AUTO_INCREMENT,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `tipo` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `habitantes` int(11) unsigned NOT NULL,
  `madera` double unsigned NOT NULL,
  `barro` double unsigned NOT NULL,
  `hierro` double unsigned NOT NULL,
  `cereal` double unsigned NOT NULL,
  `capital` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `last_update` double unsigned NOT NULL,
  `id_usuario` int(11) NOT NULL,
  PRIMARY KEY (`id_casilla`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=101 ;

--
-- Volcado de datos para la tabla `mapa`
--

INSERT INTO `mapa` (`id_casilla`, `x`, `y`, `nombre`, `tipo`, `habitantes`, `madera`, `barro`, `hierro`, `cereal`, `capital`, `last_update`, `id_usuario`) VALUES
(1, 1, 1, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(2, 2, 1, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(3, 3, 1, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(4, 4, 1, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(5, 5, 1, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(6, 6, 1, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(7, 7, 1, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(8, 8, 1, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(9, 9, 1, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(10, 10, 1, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(11, 1, 2, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(12, 2, 2, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(13, 3, 2, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(14, 4, 2, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(15, 5, 2, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(16, 6, 2, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(17, 7, 2, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(18, 8, 2, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(19, 9, 2, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(20, 10, 2, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(21, 1, 3, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(22, 2, 3, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(23, 3, 3, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(24, 4, 3, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(25, 5, 3, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(26, 6, 3, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(27, 7, 3, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(28, 8, 3, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(29, 9, 3, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(30, 10, 3, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(31, 1, 4, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(32, 2, 4, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(33, 3, 4, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(34, 4, 4, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(35, 5, 4, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(36, 6, 4, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(37, 7, 4, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(38, 8, 4, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(39, 9, 4, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(40, 10, 4, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(41, 1, 5, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(42, 2, 5, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(43, 3, 5, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(44, 4, 5, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(45, 5, 5, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(46, 6, 5, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(47, 7, 5, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(48, 8, 5, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(49, 9, 5, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(50, 10, 5, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(51, 1, 6, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(52, 2, 6, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(53, 3, 6, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(54, 4, 6, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(55, 5, 6, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(56, 6, 6, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(57, 7, 6, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(58, 8, 6, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(59, 9, 6, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(60, 10, 6, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(61, 1, 7, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(62, 2, 7, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(63, 3, 7, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(64, 4, 7, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(65, 5, 7, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(66, 6, 7, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(67, 7, 7, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(68, 8, 7, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(69, 9, 7, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(70, 10, 7, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(71, 1, 8, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(72, 2, 8, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(73, 3, 8, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(74, 4, 8, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(75, 5, 8, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(76, 6, 8, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(77, 7, 8, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(78, 8, 8, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(79, 9, 8, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(80, 10, 8, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(81, 1, 9, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(82, 2, 9, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(83, 3, 9, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(84, 4, 9, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(85, 5, 9, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(86, 6, 9, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(87, 7, 9, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(88, 8, 9, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(89, 9, 9, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(90, 10, 9, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(91, 1, 10, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(92, 2, 10, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(93, 3, 10, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(94, 4, 10, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(95, 5, 10, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(96, 6, 10, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(97, 7, 10, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(98, 8, 10, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(99, 9, 10, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0),
(100, 10, 10, 'Terreno Libre', 'Naturaleza', 0, 0, 0, 0, 0, '', 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE IF NOT EXISTS `mensajes` (
  `id_mensaje` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_emisor` int(11) unsigned NOT NULL,
  `id_destinatario` int(11) unsigned NOT NULL,
  `asunto` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `mensaje` text COLLATE utf8_spanish_ci NOT NULL,
  `leido_emisor` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `leido_destinatario` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `eliminado_emisor` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `eliminado_destinatario` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `fecha` date NOT NULL,
  `id_respuesta` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id_mensaje`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `miembros_alianzas`
--

CREATE TABLE IF NOT EXISTS `miembros_alianzas` (
  `id_miembro` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_alianza` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `estado` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_miembro`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ofertas`
--

CREATE TABLE IF NOT EXISTS `ofertas` (
  `id_oferta` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `recurso_ofrece` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `cantidad_ofrece` int(11) unsigned NOT NULL,
  `recurso_busca` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `cantidad_busca` int(11) unsigned NOT NULL,
  `id_ciudad` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id_oferta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reportes_tropas`
--

CREATE TABLE IF NOT EXISTS `reportes_tropas` (
  `id_reporte` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `objetivo` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `botin` text COLLATE utf8_spanish_ci NOT NULL,
  `id_ciudad_atacante` int(11) unsigned NOT NULL,
  `id_ciudad_atacada` int(11) unsigned NOT NULL,
  `tropas_atacante` text COLLATE utf8_spanish_ci NOT NULL,
  `tropasp_atacante` text COLLATE utf8_spanish_ci NOT NULL,
  `tropas_atacadas` text COLLATE utf8_spanish_ci NOT NULL,
  `tropasp_atacadas` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id_reporte`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=206 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tropas`
--

CREATE TABLE IF NOT EXISTS `tropas` (
  `id_tropas` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tropa1` int(11) unsigned NOT NULL,
  `tropa2` int(11) unsigned NOT NULL,
  `tropa3` int(11) unsigned NOT NULL,
  `tropa4` int(11) unsigned NOT NULL,
  `tropa5` int(11) unsigned NOT NULL,
  `tropa6` int(11) unsigned NOT NULL,
  `tropa7` int(11) unsigned NOT NULL,
  `tropa8` int(11) unsigned NOT NULL,
  `tropa9` int(11) unsigned NOT NULL,
  `tropa10` int(11) unsigned NOT NULL,
  `id_ciudad` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id_tropas`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=40 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tropas_refuerzos`
--

CREATE TABLE IF NOT EXISTS `tropas_refuerzos` (
  `id_refuerzos` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_ciudad_refuerza` int(11) unsigned NOT NULL,
  `id_ciudad_reforzada` int(11) unsigned NOT NULL,
  `tropa1` int(11) unsigned NOT NULL,
  `tropa2` int(11) unsigned NOT NULL,
  `tropa3` int(11) unsigned NOT NULL,
  `tropa4` int(11) unsigned NOT NULL,
  `tropa5` int(11) unsigned NOT NULL,
  `tropa6` int(11) unsigned NOT NULL,
  `tropa7` int(11) unsigned NOT NULL,
  `tropa8` int(11) unsigned NOT NULL,
  `tropa9` int(11) unsigned NOT NULL,
  `tropa10` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id_refuerzos`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(25) COLLATE utf8_spanish_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `correo` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  `perfil` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha_ingreso` datetime NOT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=56 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vuelta_ataques`
--

CREATE TABLE IF NOT EXISTS `vuelta_ataques` (
  `id_vuelta` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `objetivo` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `botin` text COLLATE utf8_spanish_ci NOT NULL,
  `id_ciudad_atacante` int(11) unsigned NOT NULL,
  `id_ciudad_atacada` int(11) unsigned NOT NULL,
  `tropa1` int(11) unsigned NOT NULL,
  `tropa2` int(11) unsigned NOT NULL,
  `tropa3` int(11) unsigned NOT NULL,
  `tropa4` int(11) unsigned NOT NULL,
  `tropa5` int(11) unsigned NOT NULL,
  `tropa6` int(11) unsigned NOT NULL,
  `tropa7` int(11) unsigned NOT NULL,
  `tropa8` int(11) unsigned NOT NULL,
  `tropa9` int(11) unsigned NOT NULL,
  `tropa10` int(11) unsigned NOT NULL,
  `fecha` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id_vuelta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
