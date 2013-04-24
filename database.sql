-- phpMyAdmin SQL Dump
-- version 3.3.9.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: 19 apr, 2013 at 02:20 PM
-- Versione MySQL: 5.5.9
-- Versione PHP: 5.4.14RC1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `otrariam`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `alliance`
--

CREATE TABLE IF NOT EXISTS `alliance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `date` date NOT NULL,
  `id_user` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `alliance`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `alliance_charges`
--

CREATE TABLE IF NOT EXISTS `alliance_charges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `charge1` tinyint(1) NOT NULL,
  `charge2` tinyint(1) NOT NULL,
  `charge3` tinyint(1) NOT NULL,
  `charge4` tinyint(1) NOT NULL,
  `charge5` tinyint(1) NOT NULL,
  `charge6` tinyint(1) NOT NULL,
  `charge7` tinyint(1) NOT NULL,
  `charge8` tinyint(1) NOT NULL,
  `charge9` tinyint(1) NOT NULL,
  `charge10` tinyint(1) NOT NULL,
  `name` varchar(100) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_alliance` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `alliance_charges`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `alliance_diplo`
--

CREATE TABLE IF NOT EXISTS `alliance_diplo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `id_alliance_declare` int(11) NOT NULL,
  `id_alliance_accept` int(11) NOT NULL,
  `date` date NOT NULL,
  `state` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `alliance_diplo`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `alliance_members`
--

CREATE TABLE IF NOT EXISTS `alliance_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_allliance` int(11) NOT NULL,
  `date` date NOT NULL,
  `state` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `alliance_members`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `attack`
--

CREATE TABLE IF NOT EXISTS `attack` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `objetivo` varchar(50) NOT NULL,
  `id_town_attacker` int(11) unsigned NOT NULL,
  `id_town_attacked` int(11) unsigned NOT NULL,
  `troop1` int(11) unsigned NOT NULL,
  `troop2` int(11) unsigned NOT NULL,
  `troop3` int(11) unsigned NOT NULL,
  `troop4` int(11) unsigned NOT NULL,
  `troop5` int(11) unsigned NOT NULL,
  `troop6` int(11) unsigned NOT NULL,
  `troop7` int(11) unsigned NOT NULL,
  `troop8` int(11) unsigned NOT NULL,
  `troop9` int(11) unsigned NOT NULL,
  `troop10` int(11) unsigned NOT NULL,
  `destroy` varchar(100) NOT NULL,
  `date` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `attack`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `buildings`
--

CREATE TABLE IF NOT EXISTS `buildings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `building` text(250) NOT NULL,
  `level` int(11) unsigned NOT NULL,
  `resource` varchar(50) NOT NULL,
  `production` double unsigned NOT NULL,
  `population` int(11) unsigned NOT NULL,
  `slot` int(10) unsigned NOT NULL,
  `id_town` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `buildings`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `construction_costs`
--

CREATE TABLE IF NOT EXISTS `construction_costs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `building` varchar(250) NOT NULL,
  `level` int(11) unsigned NOT NULL,
  `production` double unsigned NOT NULL,
  `population` int(11) unsigned NOT NULL,
  `wood` int(11) unsigned NOT NULL,
  `clay` int(11) unsigned NOT NULL,
  `iron` int(11) unsigned NOT NULL,
  `crop` int(11) unsigned NOT NULL,
  `time` int(11) unsigned NOT NULL,
  `requirements` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=99 ;

--
-- Dump dei dati per la tabella `construction_costs`
--

INSERT INTO `construction_costs` (`id`, `building`, `level`, `production`, `population`, `wood`, `clay`, `iron`, `crop`, `time`, `requirements`) VALUES
(1, 'barrera', 1, 4, 2, 30, 15, 30, 5, 172, ''),
(2, 'barrera', 2, 9, 4, 45, 23, 45, 10, 272, ''),
(3, 'barrera', 3, 14, 5, 55, 28, 55, 15, 344, ''),
(4, 'barrera', 4, 20, 7, 70, 35, 70, 20, 444, ''),
(5, 'barrera', 5, 27, 9, 100, 50, 100, 25, 622, ''),
(6, 'barrera', 6, 35, 12, 125, 63, 125, 30, 780, ''),
(7, 'barrera', 7, 44, 16, 145, 73, 145, 35, 916, ''),
(8, 'barrera', 8, 56, 20, 170, 85, 170, 40, 1082, ''),
(9, 'barrera', 9, 66, 26, 185, 93, 185, 45, 1200, ''),
(10, 'barrera', 10, 79, 30, 215, 108, 215, 50, 1394, ''),
(11, 'barrera', 11, 96, 39, 235, 118, 235, 65, 1576, ''),
(12, 'barrera', 12, 116, 45, 265, 133, 265, 70, 1788, ''),
(13, 'barrera', 13, 131, 50, 303, 152, 303, 75, 2028, ''),
(14, 'barrera', 14, 144, 56, 352, 176, 352, 80, 2320, ''),
(15, 'barrera', 15, 166, 63, 404, 202, 404, 85, 2648, ''),
(16, 'barrera', 16, 196, 70, 451, 226, 451, 90, 2968, ''),
(17, 'woodcutter', 1, 4, 2, 15, 38, 19, 5, 165, ''),
(18, 'woodcutter', 2, 9, 4, 23, 56, 28, 10, 261, ''),
(19, 'woodcutter', 3, 14, 5, 28, 69, 34, 15, 330, ''),
(20, 'woodcutter', 4, 20, 7, 35, 88, 44, 20, 427, ''),
(21, 'woodcutter', 5, 27, 9, 50, 125, 63, 25, 597, ''),
(22, 'woodcutter', 6, 35, 12, 63, 156, 78, 30, 749, ''),
(23, 'woodcutter', 7, 44, 16, 73, 181, 91, 35, 880, ''),
(24, 'woodcutter', 8, 56, 20, 85, 213, 106, 40, 1040, ''),
(25, 'woodcutter', 9, 66, 26, 93, 231, 116, 45, 1200, ''),
(26, 'woodcutter', 10, 79, 30, 108, 269, 134, 50, 1340, ''),
(27, 'woodcutter', 11, 96, 39, 118, 294, 147, 65, 1517, ''),
(28, 'woodcutter', 12, 116, 45, 133, 331, 116, 70, 1722, ''),
(29, 'woodcutter', 13, 131, 50, 152, 379, 189, 75, 1952, ''),
(30, 'woodcutter', 14, 144, 56, 176, 440, 220, 80, 2232, ''),
(31, 'woodcutter', 15, 166, 63, 202, 505, 253, 85, 2547, ''),
(32, 'woodcutter', 16, 196, 70, 226, 564, 282, 90, 2885, ''),
(33, 'iron_mine', 1, 4, 2, 38, 30, 11, 5, 180, ''),
(34, 'iron_mine', 2, 9, 4, 56, 45, 17, 10, 282, ''),
(35, 'iron_mine', 3, 14, 5, 69, 55, 21, 15, 357, ''),
(36, 'iron_mine', 4, 20, 7, 88, 70, 26, 20, 462, ''),
(37, 'iron_mine', 5, 27, 9, 125, 100, 38, 25, 647, ''),
(38, 'iron_mine', 6, 35, 12, 156, 125, 47, 30, 810, ''),
(39, 'iron_mine', 7, 44, 16, 181, 145, 54, 35, 951, ''),
(40, 'iron_mine', 8, 56, 20, 213, 170, 64, 40, 1125, ''),
(41, 'iron_mine', 9, 66, 26, 231, 158, 69, 45, 1245, ''),
(42, 'iron_mine', 10, 79, 30, 269, 215, 81, 50, 1447, ''),
(43, 'iron_mine', 11, 96, 39, 294, 235, 88, 65, 1634, ''),
(44, 'iron_mine', 12, 116, 45, 331, 265, 99, 70, 1853, ''),
(45, 'iron_mine', 13, 131, 50, 379, 303, 114, 75, 2103, ''),
(46, 'iron_mine', 14, 144, 56, 440, 352, 132, 80, 2408, ''),
(47, 'iron_mine', 15, 166, 63, 505, 404, 152, 85, 2749, ''),
(48, 'iron_mine', 16, 196, 70, 564, 451, 169, 90, 3080, ''),
(49, 'farm', 1, 4, 2, 30, 38, 15, 1, 179, ''),
(50, 'farm', 2, 9, 4, 45, 56, 23, 2, 279, ''),
(51, 'farm', 3, 14, 5, 55, 69, 28, 3, 348, ''),
(52, 'farm', 4, 20, 7, 70, 88, 35, 4, 447, ''),
(53, 'farm', 5, 27, 9, 100, 125, 50, 5, 632, ''),
(54, 'farm', 6, 35, 12, 125, 156, 63, 6, 795, ''),
(55, 'farm', 7, 44, 16, 145, 181, 73, 7, 933, ''),
(56, 'farm', 8, 56, 20, 170, 213, 85, 8, 1103, ''),
(57, 'farm', 9, 66, 26, 185, 231, 93, 9, 1221, ''),
(58, 'farm', 10, 79, 30, 215, 269, 108, 10, 1422, ''),
(59, 'farm', 11, 96, 39, 235, 294, 118, 11, 1586, ''),
(60, 'farm', 12, 116, 45, 265, 331, 133, 12, 1805, ''),
(61, 'farm', 13, 131, 50, 303, 379, 152, 13, 2056, ''),
(62, 'farm', 14, 144, 56, 352, 440, 176, 14, 2364, ''),
(63, 'farm', 15, 166, 63, 404, 505, 202, 15, 2710, ''),
(64, 'farm', 16, 196, 70, 451, 564, 226, 16, 3046, ''),
(65, 'warehouse', 1, 1300, 3, 350, 290, 180, 30, 3230, ''),
(66, 'warehouse', 2, 1700, 6, 600, 410, 300, 50, 4599, ''),
(67, 'warehouse', 3, 2300, 10, 890, 630, 416, 100, 6519, ''),
(68, 'warehouse', 4, 3000, 15, 1130, 800, 526, 135, 8409, ''),
(69, 'warehouse', 5, 3900, 19, 1400, 1050, 780, 170, 10979, ''),
(70, 'warehouse', 6, 4500, 26, 1850, 1325, 980, 200, 13322, ''),
(71, 'warehouse', 7, 6000, 35, 2300, 1790, 1230, 270, 17438, ''),
(72, 'warehouse', 8, 7900, 47, 2900, 2175, 1470, 300, 22188, ''),
(73, 'town_hall', 1, 3, 6, 250, 320, 180, 10, 1538, ''),
(74, 'town_hall', 2, 7, 15, 390, 500, 260, 20, 2384, ''),
(75, 'town_hall', 3, 10, 23, 510, 720, 360, 45, 3336, ''),
(76, 'town_hall', 4, 15, 30, 670, 900, 510, 80, 4410, ''),
(77, 'cuartel', 1, 0, 6, 310, 270, 195, 20, 1602, ''),
(78, 'cuartel', 2, 0, 11, 520, 465, 315, 35, 2692, ''),
(79, 'cuartel', 3, 0, 19, 735, 590, 395, 60, 3598, ''),
(80, 'cuartel', 4, 0, 23, 910, 765, 515, 85, 4596, ''),
(81, 'embassy', 1, 3, 4, 170, 230, 95, 15, 1034, ''),
(82, 'embassy', 2, 5, 8, 340, 480, 190, 30, 2106, ''),
(83, 'embassy', 3, 7, 13, 510, 760, 245, 40, 3150, ''),
(84, 'embassy', 4, 10, 19, 760, 1010, 410, 65, 4548, ''),
(85, 'embassy', 5, 15, 26, 910, 1300, 590, 70, 5882, ''),
(86, 'establo', 1, 0, 6, 415, 300, 165, 40, 1852, 'cuartel-3|town_hall-4'),
(87, 'establo', 2, 0, 11, 625, 415, 290, 70, 2822, 'cuartel-3|town_hall-4'),
(88, 'establo', 3, 0, 19, 795, 595, 340, 120, 3738, 'cuartel-3|town_hall-4'),
(89, 'establo', 4, 0, 23, 910, 710, 590, 170, 4806, 'cuartel-3|town_hall-4'),
(90, 'market', 1, 1, 4, 130, 185, 85, 10, 830, ''),
(91, 'market', 2, 2, 7, 210, 345, 160, 25, 1498, ''),
(92, 'market', 3, 3, 11, 390, 560, 215, 45, 2448, ''),
(93, 'market', 4, 4, 19, 510, 665, 345, 60, 3206, ''),
(94, 'market', 5, 5, 26, 690, 815, 400, 80, 4032, ''),
(95, 'cranny', 1, 100, 1, 49, 67, 38, 5, 159, ''),
(96, 'cranny', 2, 170, 3, 89, 103, 49, 9, 250, ''),
(97, 'taller', 1, 0, 11, 400, 630, 230, 30, 1301, 'establo-4'),
(98, 'taller', 2, 0, 19, 780, 950, 490, 50, 2289, 'establo-4');

-- --------------------------------------------------------

--
-- Struttura della tabella `data_troops`
--

CREATE TABLE IF NOT EXISTS `data_troops` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `troop` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `attack` int(11) unsigned NOT NULL,
  `defense` int(11) unsigned NOT NULL,
  `cavalry_defense` int(11) unsigned NOT NULL,
  `wood` int(11) unsigned NOT NULL,
  `clay` int(11) unsigned NOT NULL,
  `iron` int(11) unsigned NOT NULL,
  `crop` int(11) unsigned NOT NULL,
  `consumption` int(11) unsigned NOT NULL,
  `speed` int(11) unsigned NOT NULL,
  `capacity` int(11) unsigned NOT NULL,
  `part_army` varchar(50) NOT NULL,
  `time` int(11) unsigned NOT NULL,
  `requirements` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dump dei dati per la tabella `data_troops`
--

INSERT INTO `data_troops` (`id`, `troop`, `name`, `attack`, `defense`, `cavalry_defense`, `wood`, `clay`, `iron`, `crop`, `consumption`, `speed`, `capacity`, `part_army`, `time`, `requirements`) VALUES
(1, 'tropa1', 'legionario', 20, 40, 30, 70, 35, 70, 10, 1, 12, 60, 'infanteria', 75, 'cuartel_1'),
(2, 'tropa2', 'pretoriano', 50, 60, 50, 110, 60, 140, 20, 1, 12, 50, 'infanteria', 135, 'cuartel_2'),
(3, 'tropa3', 'triario', 100, 30, 80, 140, 85, 200, 40, 1, 12, 40, 'infanteria', 195, 'cuartel_3|ayuntamiento_1'),
(4, 'tropa4', 'caballeria_ligera', 50, 30, 45, 260, 165, 200, 100, 2, 25, 120, 'caballeria', 315, 'establo_1'),
(5, 'tropa5', 'caballeria_pesada', 200, 50, 100, 500, 300, 600, 300, 2, 20, 50, 'caballeria', 765, 'establo_3'),
(6, 'tropa6', 'general', 600, 490, 580, 1300, 700, 1800, 500, 4, 25, 0, 'caballeria', 2400, 'establo_4'),
(7, 'tropa7', 'ariete', 20, 20, 10, 600, 300, 550, 10, 4, 10, 10, 'artilleria', 800, 'taller_1'),
(8, 'tropa8', 'onagro', 60, 60, 40, 850, 620, 710, 100, 4, 10, 10, 'artilleria', 1000, 'taller_2');

-- --------------------------------------------------------

--
-- Struttura della tabella `errors`
--

CREATE TABLE IF NOT EXISTS `errors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(100) NOT NULL,
  `error` text NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `errors`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `building` varchar(250) NOT NULL,
  `time` int(11) unsigned NOT NULL,
  `slot` int(10) unsigned NOT NULL,
  `id_town` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `events`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `exchanges`
--

CREATE TABLE IF NOT EXISTS `exchanges` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_town_offers` int(11) unsigned NOT NULL,
  `resource_offers` varchar(100) NOT NULL,
  `amount_offers` varchar(250) NOT NULL,
  `id_city_search` int(11) unsigned NOT NULL,
  `resource_search` varchar(100) NOT NULL,
  `amount_search` varchar(250) NOT NULL,
  `date` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `exchanges`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `map`
--

CREATE TABLE IF NOT EXISTS `map` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  `state` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `population` int(11) unsigned NOT NULL,
  `wood` double unsigned NOT NULL,
  `clay` double unsigned NOT NULL,
  `iron` double unsigned NOT NULL,
  `crop` double unsigned NOT NULL,
  `capital` varchar(50) NOT NULL,
  `last_update` double unsigned NOT NULL,
  `id_user` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101 ;

--
-- Dump dei dati per la tabella `map`
--

INSERT INTO `map` (`id`, `x`, `y`, `state`, `type`, `population`, `wood`, `clay`, `iron`, `crop`, `capital`, `last_update`, `id_user`) VALUES
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
(12, 2, 2, 'Santuario de Murrel', 'Barbaros', 8, 500, 500, 500, 500, 'tesoro', 1365947380, 46),
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
(26, 6, 3, 'Templo de Murrata', 'Barbaros', 8, 500, 500.5, 500, 500, 'tesoro', 1365947938, 44),
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
(78, 8, 8, 'Ruinas de Marruta', 'Barbaros', 8, 500, 500, 500, 500, 'tesoro', 1365947313, 45),
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
-- Struttura della tabella `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_sender` int(11) unsigned NOT NULL,
  `id_receiver` int(11) unsigned NOT NULL,
  `issue` varchar(250) NOT NULL,
  `text` text NOT NULL,
  `read_issuer` tinyint(4) NOT NULL,
  `read_recipient` tinyint(4) NOT NULL,
  `issuer_removed` tinyint(4) NOT NULL,
  `recipient_removed` tinyint(4) NOT NULL,
  `date` date NOT NULL,
  `id_reply` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `messages`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `offers`
--

CREATE TABLE IF NOT EXISTS `offers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `resource_offers` varchar(30) NOT NULL,
  `amount_offers` int(11) unsigned NOT NULL,
  `resource_search` varchar(30) NOT NULL,
  `amount_search` int(11) unsigned NOT NULL,
  `id_user` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `offers`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `production_queue`
--

CREATE TABLE IF NOT EXISTS `production_queue` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `troop` varchar(50) NOT NULL,
  `n_troops` int(11) unsigned NOT NULL,
  `n_troops_recruited` int(11) unsigned NOT NULL,
  `id_town` int(11) unsigned NOT NULL,
  `date` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `production_queue`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `reportes_comercio`
--

CREATE TABLE IF NOT EXISTS `reportes_comercio` (
  `id_reporte` int(11) NOT NULL AUTO_INCREMENT,
  `id_ciudad_ofrece` int(11) NOT NULL,
  `recurso_ofrece` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `cantidad_ofrece` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `id_ciudad_busca` int(11) NOT NULL,
  `recurso_busca` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `cantidad_busca` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `leido_ofrece` tinyint(4) NOT NULL,
  `leido_busca` tinyint(4) NOT NULL,
  `fecha` int(11) NOT NULL,
  PRIMARY KEY (`id_reporte`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=8 ;

--
-- Dump dei dati per la tabella `reportes_comercio`
--

INSERT INTO `reportes_comercio` (`id_reporte`, `id_ciudad_ofrece`, `recurso_ofrece`, `cantidad_ofrece`, `id_ciudad_busca`, `recurso_busca`, `cantidad_busca`, `leido_ofrece`, `leido_busca`, `fecha`) VALUES
(3, 42, 'madera', '1', 33, 'barro', '1', 1, 1, 1365260750),
(4, 33, 'todo', '10', 42, 'enviar', '0', 1, 1, 1365210396),
(5, 33, 'todo', '-8', 42, 'enviar', '0', 1, 1, 1365202120),
(6, 33, 'todo', '1-2-3-4', 42, 'enviar', '0', 1, 1, 1365202201),
(7, 33, 'todo', '0-1-2-3', 42, 'enviar', '0', 1, 1, 1365601108);

-- --------------------------------------------------------

--
-- Struttura della tabella `reports_troops`
--

CREATE TABLE IF NOT EXISTS `reports_troops` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `target` varchar(50) NOT NULL,
  `booty` text NOT NULL,
  `id_town_attackers` int(11) unsigned NOT NULL,
  `id_town_attacked` int(11) unsigned NOT NULL,
  `attacking_troops` text NOT NULL,
  `tropasp_atacante` text NOT NULL,
  `troops_attacked` text NOT NULL,
  `tropasp_atacadas` text NOT NULL,
  `read_attacker` tinyint(4) NOT NULL,
  `read_attacked` tinyint(4) NOT NULL,
  `date` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `reports_troops`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `return_attack`
--

CREATE TABLE IF NOT EXISTS `return_attack` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `target` varchar(50) NOT NULL,
  `booty` text CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `id_town_attacker` int(11) unsigned NOT NULL,
  `id_town_attacked` int(11) unsigned NOT NULL,
  `troop1` int(11) unsigned NOT NULL,
  `troop2` int(11) unsigned NOT NULL,
  `troop3` int(11) unsigned NOT NULL,
  `troop4` int(11) unsigned NOT NULL,
  `troop5` int(11) unsigned NOT NULL,
  `troop6` int(11) unsigned NOT NULL,
  `troop7` int(11) unsigned NOT NULL,
  `troop8` int(11) unsigned NOT NULL,
  `troop9` int(11) unsigned NOT NULL,
  `troop10` int(11) unsigned NOT NULL,
  `date` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `return_attack`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `treasures`
--

CREATE TABLE IF NOT EXISTS `treasures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `date` int(11) NOT NULL,
  `id_town` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dump dei dati per la tabella `treasures`
--

INSERT INTO `treasures` (`id`, `name`, `date`, `id_town`) VALUES
(1, 'Espada de Rómulo', 10000, 26),
(2, 'Memorias de Remo', 10000, 78),
(3, 'Amuleto de Marte', 10000, 12);

-- --------------------------------------------------------

--
-- Struttura della tabella `troops`
--

CREATE TABLE IF NOT EXISTS `troops` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `troop1` int(11) unsigned NOT NULL,
  `troop2` int(11) unsigned NOT NULL,
  `troop3` int(11) unsigned NOT NULL,
  `troop4` int(11) unsigned NOT NULL,
  `troop5` int(11) unsigned NOT NULL,
  `troop6` int(11) unsigned NOT NULL,
  `troop7` int(11) unsigned NOT NULL,
  `troop8` int(11) unsigned NOT NULL,
  `troop9` int(11) unsigned NOT NULL,
  `troop10` int(11) unsigned NOT NULL,
  `id_town` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dump dei dati per la tabella `troops`
--

INSERT INTO `troops` (`id`, `troop1`, `troop2`, `troop3`, `troop4`, `troop5`, `troop6`, `troop7`, `troop8`, `troop9`, `troop10`, `id_town`) VALUES
(1, 80, 100, 20, 30, 30, 1, 0, 0, 0, 0, 26),
(2, 80, 100, 20, 30, 30, 1, 0, 0, 0, 0, 78),
(3, 80, 100, 20, 30, 30, 1, 0, 0, 0, 0, 12);

-- --------------------------------------------------------

--
-- Struttura della tabella `troops_reinforcements`
--

CREATE TABLE IF NOT EXISTS `troops_reinforcements` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_town_reinforces` int(11) unsigned NOT NULL,
  `id_town_reinforced` int(11) unsigned NOT NULL,
  `troop1` int(11) unsigned NOT NULL,
  `troop2` int(11) unsigned NOT NULL,
  `troop3` int(11) unsigned NOT NULL,
  `troop4` int(11) unsigned NOT NULL,
  `troop5` int(11) unsigned NOT NULL,
  `troop6` int(11) unsigned NOT NULL,
  `troop7` int(11) unsigned NOT NULL,
  `troop8` int(11) unsigned NOT NULL,
  `troop9` int(11) unsigned NOT NULL,
  `troop10` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `troops_reinforcements`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(25) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `perfil` text NOT NULL,
  `last_login` datetime NOT NULL,
  `register_time` datetime NOT NULL,
  `ip` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `users`
--

