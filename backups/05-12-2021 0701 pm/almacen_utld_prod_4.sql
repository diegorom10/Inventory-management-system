-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3308
-- Tiempo de generación: 06-12-2021 a las 01:03:34
-- Versión del servidor: 5.7.28-log
-- Versión de PHP: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `almacen_utld_prod_4`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalogo`
--

DROP TABLE IF EXISTS `catalogo`;
CREATE TABLE IF NOT EXISTS `catalogo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(255) NOT NULL,
  `codigo` int(11) DEFAULT NULL,
  `numserie` varchar(30) DEFAULT NULL,
  `tipo` int(11) DEFAULT NULL,
  `consumible` tinyint(1) DEFAULT '0',
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `tipo` (`tipo`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `catalogo`
--

INSERT INTO `catalogo` (`id`, `descripcion`, `codigo`, `numserie`, `tipo`, `consumible`, `activo`) VALUES
(1, 'Pinzas de corte', 1001, NULL, 24, 0, 1),
(2, 'Desarmador plano', 1002, NULL, 30, 0, 1),
(3, 'Serrucho', 1003, NULL, 18, 0, 1),
(4, 'Linterna', 1004, NULL, 28, 0, 1),
(5, 'Pala', 1005, NULL, 35, 0, 1),
(6, 'Desarmador cruz', 1006, NULL, 30, 0, 1),
(7, 'Tornillos', 1007, NULL, 37, 0, 1),
(8, 'Cables de corriente', 1008, NULL, 24, 0, 1),
(9, 'Esmeriladora DEWALT', NULL, '91ee23b', 34, 0, 1),
(10, 'Esmeriladora DEWALT', NULL, '99w3ee1', 34, 0, 1),
(11, 'Multimetro', NULL, '12ADBA23', 24, 0, 1),
(12, 'Navajas', 1009, NULL, 30, 0, 0),
(13, 'Lima triangular', 1010, NULL, 30, 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cortes`
--

DROP TABLE IF EXISTS `cortes`;
CREATE TABLE IF NOT EXISTS `cortes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `registradas` int(11) NOT NULL,
  `totalArticulos` int(11) NOT NULL,
  `totalDisponibles` int(11) NOT NULL,
  `totalComprometidas` int(11) NOT NULL,
  `personal` varchar(30) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cortes`
--

INSERT INTO `cortes` (`id`, `registradas`, `totalArticulos`, `totalDisponibles`, `totalComprometidas`, `personal`, `fecha`, `estado`) VALUES
(1, 11, 102, 82, 20, 'Darnell Armas', '2021-12-03 12:42:37', 1),
(2, 11, 105, 85, 20, 'Darnell Armas', '2021-12-03 12:43:09', 1),
(3, 11, 115, 95, 20, 'Darnell Armas', '2021-12-03 12:48:40', 1),
(4, 11, 115, 95, 20, 'Darnell Armas', '2021-12-03 13:02:41', 1),
(5, 11, 115, 95, 20, 'Darnell Armas', '2021-12-03 13:02:52', 1),
(6, 11, 115, 95, 20, 'Darnell Armas', '2021-12-03 13:05:00', 0),
(7, 11, 115, 95, 20, 'Darnell Armas', '2021-12-03 13:06:44', 1),
(8, 12, 115, 87, 28, 'Darnell Armas', '2021-12-03 13:10:56', 1),
(9, 12, 125, 97, 28, 'Darnell Armas', '2021-12-03 13:11:49', 1),
(10, 11, 115, 87, 28, 'Darnell Armas', '2021-12-03 13:19:11', 1),
(11, 11, 115, 87, 28, 'Darnell Armas', '2021-12-03 13:19:52', 1),
(12, 11, 115, 87, 28, 'Darnell Armas', '2021-12-03 15:23:57', 0),
(13, 11, 115, 87, 28, 'Darnell Armas', '2021-12-03 16:38:59', 0),
(14, 11, 115, 87, 28, 'Darnell Armas', '2021-12-03 17:48:20', 0),
(15, 11, 115, 87, 28, 'Darnell Armas', '2021-12-03 17:48:40', 0),
(16, 11, 115, 87, 28, 'Darnell Armas', '2021-12-03 18:02:00', 0),
(17, 11, 115, 87, 28, 'Darnell Armas', '2021-12-03 18:06:11', 0),
(18, 11, 115, 87, 28, 'Darnell Armas', '2021-12-03 18:24:42', 0),
(19, 11, 115, 87, 28, 'Darnell Armas', '2021-12-03 22:52:50', 0),
(20, 11, 115, 87, 28, 'Darnell Armas', '2021-12-03 23:01:19', 0),
(21, 11, 115, 87, 28, 'Darnell Armas', '2021-12-04 03:28:37', 0),
(22, 11, 115, 87, 28, 'Darnell Armas', '2021-12-04 06:07:00', 0),
(23, 11, 115, 87, 28, 'Darnell Armas', '2021-12-05 15:28:38', 1),
(24, 11, 115, 87, 28, 'Darnell Armas', '2021-12-05 16:07:05', 1),
(25, 11, 115, 87, 28, 'Darnell Armas', '2021-12-05 16:12:35', 1),
(26, 11, 115, 87, 28, 'Darnell Armas', '2021-12-05 16:19:07', 1),
(27, 11, 115, 87, 28, 'Darnell Armas', '2021-12-05 18:59:53', 1),
(28, 11, 115, 87, 28, 'Darnell Armas', '2021-12-05 19:10:22', 1),
(29, 11, 115, 87, 28, 'valeria', '2021-12-05 19:10:34', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cortes_detalle`
--

DROP TABLE IF EXISTS `cortes_detalle`;
CREATE TABLE IF NOT EXISTS `cortes_detalle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_corte` int(11) NOT NULL,
  `id_herramienta` int(11) NOT NULL,
  `qtyo` int(11) NOT NULL,
  `qtyf` int(11) NOT NULL,
  `qtyc` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_corte` (`id_corte`),
  KEY `id_herramienta` (`id_herramienta`)
) ENGINE=InnoDB AUTO_INCREMENT=322 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cortes_detalle`
--

INSERT INTO `cortes_detalle` (`id`, `id_corte`, `id_herramienta`, `qtyo`, `qtyf`, `qtyc`) VALUES
(1, 1, 1, 10, 8, 2),
(2, 1, 2, 15, 15, 0),
(3, 1, 3, 10, 10, 0),
(4, 1, 4, 7, 5, 2),
(5, 1, 5, 10, 6, 4),
(6, 1, 6, 15, 15, 0),
(7, 1, 7, 30, 18, 12),
(8, 1, 8, 2, 2, 0),
(9, 1, 9, 1, 1, 0),
(10, 1, 10, 1, 1, 0),
(11, 1, 11, 1, 1, 0),
(12, 2, 1, 10, 8, 2),
(13, 2, 2, 18, 18, 0),
(14, 2, 3, 10, 10, 0),
(15, 2, 4, 7, 5, 2),
(16, 2, 5, 10, 6, 4),
(17, 2, 6, 15, 15, 0),
(18, 2, 7, 30, 18, 12),
(19, 2, 8, 2, 2, 0),
(20, 2, 9, 1, 1, 0),
(21, 2, 10, 1, 1, 0),
(22, 2, 11, 1, 1, 0),
(23, 3, 1, 11, 9, 2),
(24, 3, 2, 28, 28, 0),
(25, 3, 3, 10, 10, 0),
(26, 3, 4, 6, 4, 2),
(27, 3, 5, 10, 6, 4),
(28, 3, 6, 15, 15, 0),
(29, 3, 7, 30, 18, 12),
(30, 3, 8, 2, 2, 0),
(31, 3, 9, 1, 1, 0),
(32, 3, 10, 1, 1, 0),
(33, 3, 11, 1, 1, 0),
(34, 4, 1, 11, 9, 2),
(35, 4, 2, 28, 28, 0),
(36, 4, 3, 10, 10, 0),
(37, 4, 4, 6, 4, 2),
(38, 4, 5, 10, 6, 4),
(39, 4, 6, 15, 15, 0),
(40, 4, 7, 30, 18, 12),
(41, 4, 8, 2, 2, 0),
(42, 4, 9, 1, 1, 0),
(43, 4, 10, 1, 1, 0),
(44, 4, 11, 1, 1, 0),
(45, 5, 1, 11, 9, 2),
(46, 5, 2, 28, 28, 0),
(47, 5, 3, 10, 10, 0),
(48, 5, 4, 6, 4, 2),
(49, 5, 5, 10, 6, 4),
(50, 5, 6, 15, 15, 0),
(51, 5, 7, 30, 18, 12),
(52, 5, 8, 2, 2, 0),
(53, 5, 9, 1, 1, 0),
(54, 5, 10, 1, 1, 0),
(55, 5, 11, 1, 1, 0),
(56, 6, 1, 11, 9, 2),
(57, 6, 2, 28, 28, 0),
(58, 6, 3, 10, 10, 0),
(59, 6, 4, 6, 4, 2),
(60, 6, 5, 10, 6, 4),
(61, 6, 6, 15, 15, 0),
(62, 6, 7, 30, 18, 12),
(63, 6, 8, 2, 2, 0),
(64, 6, 9, 1, 1, 0),
(65, 6, 10, 1, 1, 0),
(66, 6, 11, 1, 1, 0),
(67, 7, 1, 11, 9, 2),
(68, 7, 2, 28, 28, 0),
(69, 7, 3, 10, 10, 0),
(70, 7, 4, 6, 4, 2),
(71, 7, 5, 10, 6, 4),
(72, 7, 6, 15, 15, 0),
(73, 7, 7, 30, 18, 12),
(74, 7, 8, 2, 2, 0),
(75, 7, 9, 1, 1, 0),
(76, 7, 10, 1, 1, 0),
(77, 7, 11, 1, 1, 0),
(78, 8, 1, 11, 8, 3),
(79, 8, 2, 28, 23, 5),
(80, 8, 3, 10, 10, 0),
(81, 8, 4, 6, 4, 2),
(82, 8, 5, 10, 4, 6),
(83, 8, 6, 15, 15, 0),
(84, 8, 7, 30, 18, 12),
(85, 8, 8, 2, 2, 0),
(86, 8, 9, 1, 1, 0),
(87, 8, 10, 1, 1, 0),
(88, 8, 11, 1, 1, 0),
(89, 8, 12, 0, 0, 0),
(90, 9, 1, 11, 8, 3),
(91, 9, 2, 28, 23, 5),
(92, 9, 3, 10, 10, 0),
(93, 9, 4, 6, 4, 2),
(94, 9, 5, 10, 4, 6),
(95, 9, 6, 15, 15, 0),
(96, 9, 7, 30, 18, 12),
(97, 9, 8, 2, 2, 0),
(98, 9, 9, 1, 1, 0),
(99, 9, 10, 1, 1, 0),
(100, 9, 11, 1, 1, 0),
(101, 9, 12, 10, 10, 0),
(102, 10, 1, 11, 8, 3),
(103, 10, 2, 28, 23, 5),
(104, 10, 3, 10, 10, 0),
(105, 10, 4, 6, 4, 2),
(106, 10, 5, 10, 4, 6),
(107, 10, 6, 15, 15, 0),
(108, 10, 7, 30, 18, 12),
(109, 10, 8, 2, 2, 0),
(110, 10, 9, 1, 1, 0),
(111, 10, 10, 1, 1, 0),
(112, 10, 11, 1, 1, 0),
(113, 11, 1, 11, 8, 3),
(114, 11, 2, 28, 23, 5),
(115, 11, 3, 10, 10, 0),
(116, 11, 4, 6, 4, 2),
(117, 11, 5, 10, 4, 6),
(118, 11, 6, 15, 15, 0),
(119, 11, 7, 30, 18, 12),
(120, 11, 8, 2, 2, 0),
(121, 11, 9, 1, 1, 0),
(122, 11, 10, 1, 1, 0),
(123, 11, 11, 1, 1, 0),
(124, 12, 1, 11, 8, 3),
(125, 12, 2, 28, 23, 5),
(126, 12, 3, 10, 10, 0),
(127, 12, 4, 6, 4, 2),
(128, 12, 5, 10, 4, 6),
(129, 12, 6, 15, 15, 0),
(130, 12, 7, 30, 18, 12),
(131, 12, 8, 2, 2, 0),
(132, 12, 9, 1, 1, 0),
(133, 12, 10, 1, 1, 0),
(134, 12, 11, 1, 1, 0),
(135, 13, 1, 11, 8, 3),
(136, 13, 2, 28, 23, 5),
(137, 13, 3, 10, 10, 0),
(138, 13, 4, 6, 4, 2),
(139, 13, 5, 10, 4, 6),
(140, 13, 6, 15, 15, 0),
(141, 13, 7, 30, 18, 12),
(142, 13, 8, 2, 2, 0),
(143, 13, 9, 1, 1, 0),
(144, 13, 10, 1, 1, 0),
(145, 13, 11, 1, 1, 0),
(146, 14, 1, 11, 8, 3),
(147, 14, 2, 28, 23, 5),
(148, 14, 3, 10, 10, 0),
(149, 14, 4, 6, 4, 2),
(150, 14, 5, 10, 4, 6),
(151, 14, 6, 15, 15, 0),
(152, 14, 7, 30, 18, 12),
(153, 14, 8, 2, 2, 0),
(154, 14, 9, 1, 1, 0),
(155, 14, 10, 1, 1, 0),
(156, 14, 11, 1, 1, 0),
(157, 15, 1, 11, 8, 3),
(158, 15, 2, 28, 23, 5),
(159, 15, 3, 10, 10, 0),
(160, 15, 4, 6, 4, 2),
(161, 15, 5, 10, 4, 6),
(162, 15, 6, 15, 15, 0),
(163, 15, 7, 30, 18, 12),
(164, 15, 8, 2, 2, 0),
(165, 15, 9, 1, 1, 0),
(166, 15, 10, 1, 1, 0),
(167, 15, 11, 1, 1, 0),
(168, 16, 1, 11, 8, 3),
(169, 16, 2, 28, 23, 5),
(170, 16, 3, 10, 10, 0),
(171, 16, 4, 6, 4, 2),
(172, 16, 5, 10, 4, 6),
(173, 16, 6, 15, 15, 0),
(174, 16, 7, 30, 18, 12),
(175, 16, 8, 2, 2, 0),
(176, 16, 9, 1, 1, 0),
(177, 16, 10, 1, 1, 0),
(178, 16, 11, 1, 1, 0),
(179, 17, 1, 11, 8, 3),
(180, 17, 2, 28, 23, 5),
(181, 17, 3, 10, 10, 0),
(182, 17, 4, 6, 4, 2),
(183, 17, 5, 10, 4, 6),
(184, 17, 6, 15, 15, 0),
(185, 17, 7, 30, 18, 12),
(186, 17, 8, 2, 2, 0),
(187, 17, 9, 1, 1, 0),
(188, 17, 10, 1, 1, 0),
(189, 17, 11, 1, 1, 0),
(190, 18, 1, 11, 8, 3),
(191, 18, 2, 28, 23, 5),
(192, 18, 3, 10, 10, 0),
(193, 18, 4, 6, 4, 2),
(194, 18, 5, 10, 4, 6),
(195, 18, 6, 15, 15, 0),
(196, 18, 7, 30, 18, 12),
(197, 18, 8, 2, 2, 0),
(198, 18, 9, 1, 1, 0),
(199, 18, 10, 1, 1, 0),
(200, 18, 11, 1, 1, 0),
(201, 19, 1, 11, 8, 3),
(202, 19, 2, 28, 23, 5),
(203, 19, 3, 10, 10, 0),
(204, 19, 4, 6, 4, 2),
(205, 19, 5, 10, 4, 6),
(206, 19, 6, 15, 15, 0),
(207, 19, 7, 30, 18, 12),
(208, 19, 8, 2, 2, 0),
(209, 19, 9, 1, 1, 0),
(210, 19, 10, 1, 1, 0),
(211, 19, 11, 1, 1, 0),
(212, 20, 1, 11, 8, 3),
(213, 20, 2, 28, 23, 5),
(214, 20, 3, 10, 10, 0),
(215, 20, 4, 6, 4, 2),
(216, 20, 5, 10, 4, 6),
(217, 20, 6, 15, 15, 0),
(218, 20, 7, 30, 18, 12),
(219, 20, 8, 2, 2, 0),
(220, 20, 9, 1, 1, 0),
(221, 20, 10, 1, 1, 0),
(222, 20, 11, 1, 1, 0),
(223, 21, 1, 11, 8, 3),
(224, 21, 2, 28, 23, 5),
(225, 21, 3, 10, 10, 0),
(226, 21, 4, 6, 4, 2),
(227, 21, 5, 10, 4, 6),
(228, 21, 6, 15, 15, 0),
(229, 21, 7, 30, 18, 12),
(230, 21, 8, 2, 2, 0),
(231, 21, 9, 1, 1, 0),
(232, 21, 10, 1, 1, 0),
(233, 21, 11, 1, 1, 0),
(234, 22, 1, 11, 8, 3),
(235, 22, 2, 28, 23, 5),
(236, 22, 3, 10, 10, 0),
(237, 22, 4, 6, 4, 2),
(238, 22, 5, 10, 4, 6),
(239, 22, 6, 15, 15, 0),
(240, 22, 7, 30, 18, 12),
(241, 22, 8, 2, 2, 0),
(242, 22, 9, 1, 1, 0),
(243, 22, 10, 1, 1, 0),
(244, 22, 11, 1, 1, 0),
(245, 23, 1, 11, 8, 3),
(246, 23, 2, 28, 23, 5),
(247, 23, 3, 10, 10, 0),
(248, 23, 4, 6, 4, 2),
(249, 23, 5, 10, 4, 6),
(250, 23, 6, 15, 15, 0),
(251, 23, 7, 30, 18, 12),
(252, 23, 8, 2, 2, 0),
(253, 23, 9, 1, 1, 0),
(254, 23, 10, 1, 1, 0),
(255, 23, 11, 1, 1, 0),
(256, 24, 1, 11, 8, 3),
(257, 24, 2, 28, 23, 5),
(258, 24, 3, 10, 10, 0),
(259, 24, 4, 6, 4, 2),
(260, 24, 5, 10, 4, 6),
(261, 24, 6, 15, 15, 0),
(262, 24, 7, 30, 18, 12),
(263, 24, 8, 2, 2, 0),
(264, 24, 9, 1, 1, 0),
(265, 24, 10, 1, 1, 0),
(266, 24, 11, 1, 1, 0),
(267, 25, 1, 11, 8, 3),
(268, 25, 2, 28, 23, 5),
(269, 25, 3, 10, 10, 0),
(270, 25, 4, 6, 4, 2),
(271, 25, 5, 10, 4, 6),
(272, 25, 6, 15, 15, 0),
(273, 25, 7, 30, 18, 12),
(274, 25, 8, 2, 2, 0),
(275, 25, 9, 1, 1, 0),
(276, 25, 10, 1, 1, 0),
(277, 25, 11, 1, 1, 0),
(278, 26, 1, 11, 8, 3),
(279, 26, 2, 28, 23, 5),
(280, 26, 3, 10, 10, 0),
(281, 26, 4, 6, 4, 2),
(282, 26, 5, 10, 4, 6),
(283, 26, 6, 15, 15, 0),
(284, 26, 7, 30, 18, 12),
(285, 26, 8, 2, 2, 0),
(286, 26, 9, 1, 1, 0),
(287, 26, 10, 1, 1, 0),
(288, 26, 11, 1, 1, 0),
(289, 27, 1, 11, 8, 3),
(290, 27, 2, 28, 23, 5),
(291, 27, 3, 10, 10, 0),
(292, 27, 4, 6, 4, 2),
(293, 27, 5, 10, 4, 6),
(294, 27, 6, 15, 15, 0),
(295, 27, 7, 30, 18, 12),
(296, 27, 8, 2, 2, 0),
(297, 27, 9, 1, 1, 0),
(298, 27, 10, 1, 1, 0),
(299, 27, 11, 1, 1, 0),
(300, 28, 1, 11, 8, 3),
(301, 28, 2, 28, 23, 5),
(302, 28, 3, 10, 10, 0),
(303, 28, 4, 6, 4, 2),
(304, 28, 5, 10, 4, 6),
(305, 28, 6, 15, 15, 0),
(306, 28, 7, 30, 18, 12),
(307, 28, 8, 2, 2, 0),
(308, 28, 9, 1, 1, 0),
(309, 28, 10, 1, 1, 0),
(310, 28, 11, 1, 1, 0),
(311, 29, 1, 11, 8, 3),
(312, 29, 2, 28, 23, 5),
(313, 29, 3, 10, 10, 0),
(314, 29, 4, 6, 4, 2),
(315, 29, 5, 10, 4, 6),
(316, 29, 6, 15, 15, 0),
(317, 29, 7, 30, 18, 12),
(318, 29, 8, 2, 2, 0),
(319, 29, 9, 1, 1, 0),
(320, 29, 10, 1, 1, 0),
(321, 29, 11, 1, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_peticion`
--

DROP TABLE IF EXISTS `detalle_peticion`;
CREATE TABLE IF NOT EXISTS `detalle_peticion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `herramienta` int(11) NOT NULL,
  `qty_peticion` int(11) NOT NULL,
  `peticion_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `herramienta` (`herramienta`),
  KEY `peticion_id` (`peticion_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `detalle_peticion`
--

INSERT INTO `detalle_peticion` (`id`, `herramienta`, `qty_peticion`, `peticion_id`) VALUES
(1, 1, 2, 82),
(2, 3, 4, 82);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `faltantes`
--

DROP TABLE IF EXISTS `faltantes`;
CREATE TABLE IF NOT EXISTS `faltantes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_herramienta` int(11) NOT NULL,
  `motivo` varchar(255) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `id_mov` int(11) NOT NULL,
  `estado` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_mov` (`id_mov`),
  KEY `id_herramienta` (`id_herramienta`),
  KEY `estado` (`estado`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `faltantes`
--

INSERT INTO `faltantes` (`id`, `id_herramienta`, `motivo`, `cantidad`, `id_mov`, `estado`) VALUES
(1, 5, 'La seguirá usando', 4, 24, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `faltantes_estado`
--

DROP TABLE IF EXISTS `faltantes_estado`;
CREATE TABLE IF NOT EXISTS `faltantes_estado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `estado` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `faltantes_estado`
--

INSERT INTO `faltantes_estado` (`id`, `estado`) VALUES
(1, 'pendiente'),
(2, 'perdido'),
(3, 'recuperado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventarioutl`
--

DROP TABLE IF EXISTS `inventarioutl`;
CREATE TABLE IF NOT EXISTS `inventarioutl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `herramienta` int(11) NOT NULL,
  `qtyo` int(11) NOT NULL,
  `qtyf` int(11) NOT NULL,
  `qtyc` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `herramienta` (`herramienta`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `inventarioutl`
--

INSERT INTO `inventarioutl` (`id`, `herramienta`, `qtyo`, `qtyf`, `qtyc`) VALUES
(1, 1, 11, 2, 9),
(2, 2, 28, 23, 5),
(3, 3, 10, 1, 9),
(4, 4, 6, 4, 2),
(5, 5, 10, 4, 6),
(6, 6, 15, 15, 0),
(7, 7, 30, 18, 12),
(8, 8, 2, 1, 1),
(9, 9, 1, 1, 0),
(10, 10, 1, 1, 0),
(11, 11, 1, 1, 0),
(12, 12, 10, 10, 0),
(13, 13, 0, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kardex`
--

DROP TABLE IF EXISTS `kardex`;
CREATE TABLE IF NOT EXISTS `kardex` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `movimiento` int(11) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `descripcion` varchar(255) DEFAULT NULL,
  `solicitante` varchar(50) DEFAULT NULL,
  `personal` varchar(30) DEFAULT NULL,
  `idticket` int(11) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `movimiento` (`movimiento`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `kardex`
--

INSERT INTO `kardex` (`id`, `movimiento`, `fecha`, `descripcion`, `solicitante`, `personal`, `idticket`, `estado`) VALUES
(1, 6, '2021-12-03 05:46:03', 'Registro de herramienta', NULL, 'Darnell Armas', NULL, NULL),
(2, 6, '2021-12-03 06:08:21', 'Registro de herramienta', NULL, 'Darnell Armas', NULL, NULL),
(3, 6, '2021-12-03 06:10:30', 'Registro de herramienta', NULL, 'Darnell Armas', NULL, NULL),
(4, 6, '2021-12-03 06:10:48', 'Registro de herramienta', NULL, 'Darnell Armas', NULL, NULL),
(5, 6, '2021-12-03 06:11:07', 'Registro de herramienta', NULL, 'Darnell Armas', NULL, NULL),
(6, 6, '2021-12-03 06:11:19', 'Registro de herramienta', NULL, 'Darnell Armas', NULL, NULL),
(7, 6, '2021-12-03 06:11:29', 'Registro de herramienta', NULL, 'Darnell Armas', NULL, NULL),
(8, 6, '2021-12-03 06:11:44', 'Registro de herramienta', NULL, 'Darnell Armas', NULL, NULL),
(9, 6, '2021-12-03 06:11:56', 'Registro de herramienta', NULL, 'Darnell Armas', NULL, NULL),
(10, 6, '2021-12-03 06:12:15', 'Herramienta nueva', NULL, 'Darnell Armas', NULL, NULL),
(11, 3, '2021-12-03 06:12:15', 'Llegada de herramienta unica', NULL, NULL, NULL, NULL),
(12, 6, '2021-12-03 06:12:30', 'Herramienta nueva', NULL, 'Darnell Armas', NULL, NULL),
(13, 3, '2021-12-03 06:12:30', 'Llegada de herramienta unica', NULL, NULL, NULL, NULL),
(14, 6, '2021-12-03 06:12:45', 'Herramienta nueva', NULL, 'Darnell Armas', NULL, NULL),
(15, 3, '2021-12-03 06:12:45', 'Llegada de herramienta unica', NULL, NULL, NULL, NULL),
(16, 3, '2021-12-03 06:13:12', 'Entrada de material', NULL, 'Darnell Armas', NULL, NULL),
(17, 3, '2021-12-03 06:13:25', 'Entrada de material', NULL, 'Darnell Armas', NULL, NULL),
(18, 3, '2021-12-03 06:13:33', 'Entrada de material', NULL, 'Darnell Armas', NULL, NULL),
(19, 3, '2021-12-03 06:13:46', 'Entrada de material', NULL, 'Darnell Armas', NULL, NULL),
(20, 3, '2021-12-03 06:13:58', 'Entrada de material', NULL, 'Darnell Armas', NULL, NULL),
(21, 3, '2021-12-03 06:14:07', 'Entrada de material', NULL, 'Darnell Armas', NULL, NULL),
(22, 3, '2021-12-03 06:14:23', 'Entrada de material', NULL, 'Darnell Armas', NULL, NULL),
(23, 3, '2021-12-03 06:14:38', 'Entrada de material', NULL, 'Darnell Armas', NULL, NULL),
(24, 1, '2021-12-03 06:16:03', 'Préstamo ordinario', 'Valeria', 'Darnell Armas', NULL, 0),
(25, 1, '2021-12-03 06:15:45', 'Préstamo ordinario', 'Victor', 'Darnell Armas', NULL, 1),
(26, 2, '2021-12-03 06:16:03', 'Regreso ordinario', 'Valeria', 'Darnell Armas', NULL, NULL),
(27, 3, '2021-12-03 12:42:48', 'Entrada de material', NULL, 'Darnell Armas', NULL, NULL),
(28, 3, '2021-12-03 12:43:55', 'Entrada de material', NULL, 'Darnell Armas', NULL, NULL),
(29, 3, '2021-12-03 12:44:54', 'Entrada de material', NULL, 'Darnell Armas', NULL, NULL),
(30, 3, '2021-12-03 12:45:30', 'Entrada de material', NULL, 'Darnell Armas', NULL, NULL),
(31, 3, '2021-12-03 12:46:14', 'Entrada de material', NULL, 'Darnell Armas', NULL, NULL),
(32, 5, '2021-12-03 12:47:46', 'Se van a romper', NULL, 'Darnell Armas', NULL, NULL),
(33, 5, '2021-12-03 12:47:56', 'Ya no la quiero', NULL, 'Darnell Armas', NULL, NULL),
(34, 1, '2021-12-03 13:09:04', 'Préstamo ordinario', 'Diego', 'Darnell Armas', NULL, 1),
(35, 6, '2021-12-03 13:10:25', 'Registro de herramienta', NULL, 'Darnell Armas', NULL, NULL),
(36, 3, '2021-12-03 13:11:40', 'Entrada de material', NULL, 'Darnell Armas', NULL, NULL),
(37, 4, '2021-12-03 13:12:56', 'Ya no se comprará', 'valeria(admin)', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kardex_detalle`
--

DROP TABLE IF EXISTS `kardex_detalle`;
CREATE TABLE IF NOT EXISTS `kardex_detalle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_kardex` int(11) NOT NULL,
  `id_herramienta` int(11) NOT NULL,
  `qty` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_kardex` (`id_kardex`),
  KEY `id_herramienta` (`id_herramienta`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `kardex_detalle`
--

INSERT INTO `kardex_detalle` (`id`, `id_kardex`, `id_herramienta`, `qty`) VALUES
(1, 2, 1, NULL),
(2, 3, 2, NULL),
(3, 4, 3, NULL),
(4, 5, 4, NULL),
(5, 6, 5, NULL),
(6, 7, 6, NULL),
(7, 8, 7, NULL),
(8, 9, 8, NULL),
(9, 10, 9, NULL),
(10, 11, 9, 1),
(11, 12, 10, NULL),
(12, 13, 10, 1),
(13, 14, 11, NULL),
(14, 15, 11, 1),
(15, 16, 1, 10),
(16, 17, 2, 15),
(17, 18, 3, 10),
(18, 19, 4, 7),
(19, 20, 5, 10),
(20, 21, 6, 15),
(21, 22, 7, 30),
(22, 23, 8, 2),
(23, 24, 1, 3),
(24, 24, 5, 4),
(25, 25, 7, 12),
(26, 25, 1, 2),
(27, 25, 4, 2),
(28, 26, 1, 3),
(29, 27, 2, 3),
(30, 28, 1, 1),
(31, 29, 2, 2),
(32, 30, 2, 5),
(33, 31, 2, 5),
(34, 32, 2, 2),
(35, 33, 4, 1),
(36, 34, 1, 1),
(37, 34, 5, 2),
(38, 34, 2, 5),
(39, 35, 12, NULL),
(40, 36, 12, 10),
(41, 37, 12, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2021_09_13_160923_crear_tabla_inventario', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos`
--

DROP TABLE IF EXISTS `movimientos`;
CREATE TABLE IF NOT EXISTS `movimientos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entrada` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `movimientos`
--

INSERT INTO `movimientos` (`id`, `entrada`) VALUES
(1, 'Prestamo\r\n'),
(2, 'Regreso\r\n'),
(3, 'Entrada'),
(4, 'Baja'),
(5, 'Ajuste'),
(6, 'Alta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `peticiones`
--

DROP TABLE IF EXISTS `peticiones`;
CREATE TABLE IF NOT EXISTS `peticiones` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `solicitante` int(10) UNSIGNED NOT NULL,
  `ticket_id` int(11) UNSIGNED NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `solicitante` (`solicitante`),
  KEY `ticket_id` (`ticket_id`)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `peticiones`
--

INSERT INTO `peticiones` (`id`, `solicitante`, `ticket_id`, `fecha`) VALUES
(81, 26, 90, '2021-11-09 05:08:00'),
(82, 28, 91, '2021-11-04 16:42:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_herramienta`
--

DROP TABLE IF EXISTS `tipo_herramienta`;
CREATE TABLE IF NOT EXISTS `tipo_herramienta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tipo_herramienta`
--

INSERT INTO `tipo_herramienta` (`id`, `tipo`) VALUES
(18, 'Madera'),
(20, 'Herreria'),
(24, 'Electricidad'),
(28, 'Seguridad'),
(30, 'Herramientas manuales'),
(34, 'Herramientas electricas'),
(35, 'Jardineria'),
(36, 'Electrónica'),
(37, 'Tornilleria'),
(38, 'Herramientas manuales muy muy manuales pero te lo juro que son manuales');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Nietzche', 'nietzche@example.com', NULL, '$2y$10$tBc7Zd0APMnNUJeOWzBASusVHYLQrPBXOoAAjvJXfaS5UzGaK0RJ2', NULL, '2021-09-10 20:23:24', '2021-09-10 20:23:24'),
(2, 'Jesus Marrufo', 'st19030309@utlaguna.edu.mx', NULL, '$2y$10$67W.1CtAxdSRMEIYnnBpPeg2WBR6JSQgw.9L/EUYRA6.sCMahPK.6', NULL, '2021-09-13 23:56:48', '2021-09-13 23:56:48'),
(3, 'Diego Romero', 'st19030059@utlaguna.edu.mx', NULL, '$2y$10$T4xJGXJexRuI01AXWk96QOYBfCE0D2h1eTnK9KPyWZ/edKqXNU8dq', NULL, '2021-09-13 23:57:51', '2021-09-13 23:57:51'),
(4, 'Zahul Domínguez Chávez', 'st19030302@utlaguna.edu.mx', NULL, '$2y$10$GAk6lWBOkdMHC6wgrAW8D.7rgTpkuu51dN4ZSey9gPZzAjIIKP49.', 'UmwAto7wfEIeexpvsbTqe6mrer4T9OsJEEtKOWNoaN87gPJZzds6S0sCGtxc', '2021-09-15 07:59:13', '2021-09-15 07:59:13'),
(5, 'Darnell Armas', 'dar@gmail.com', NULL, '$2y$10$QcpxE8W/qQjhOjvlL46fS.1onAhNysEKWfSNrwcUx3SlACu/9mUsO', 'VCGZRtCXVSeZ9uhROtog138KJ0lKfOjsKH4KJR75CKUKTbBR8gFYsCuN1mZG', '2021-11-24 16:17:30', '2021-11-24 16:17:30'),
(6, 'valeria', 'valeria@gmail.com', NULL, '$2y$10$KfzjovfdMG3pJEKk3/raOug.1cRGkamsrFS1xTIdR2cPA27iD/LS.', 't4wVc6oihEVx68CnUG759yrkRiyHKBkuqZuBugyX5zFDOT1ua7oGR8FI2MPV', '2021-11-25 16:16:24', '2021-11-25 16:16:24');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `catalogo`
--
ALTER TABLE `catalogo`
  ADD CONSTRAINT `tipo` FOREIGN KEY (`tipo`) REFERENCES `tipo_herramienta` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `cortes_detalle`
--
ALTER TABLE `cortes_detalle`
  ADD CONSTRAINT `cortes_detalle_ibfk_1` FOREIGN KEY (`id_corte`) REFERENCES `cortes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cortes_detalle_ibfk_2` FOREIGN KEY (`id_herramienta`) REFERENCES `catalogo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_peticion`
--
ALTER TABLE `detalle_peticion`
  ADD CONSTRAINT `detalle_peticion_ibfk_1` FOREIGN KEY (`herramienta`) REFERENCES `inventarioutl` (`herramienta`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `peticionid` FOREIGN KEY (`peticion_id`) REFERENCES `peticiones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `faltantes`
--
ALTER TABLE `faltantes`
  ADD CONSTRAINT `faltantes_ibfk_1` FOREIGN KEY (`id_mov`) REFERENCES `kardex` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `faltantes_ibfk_2` FOREIGN KEY (`id_herramienta`) REFERENCES `catalogo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `faltantes_ibfk_3` FOREIGN KEY (`estado`) REFERENCES `faltantes_estado` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `inventarioutl`
--
ALTER TABLE `inventarioutl`
  ADD CONSTRAINT `herramienta` FOREIGN KEY (`herramienta`) REFERENCES `catalogo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `kardex`
--
ALTER TABLE `kardex`
  ADD CONSTRAINT `movimiento` FOREIGN KEY (`movimiento`) REFERENCES `movimientos` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Filtros para la tabla `kardex_detalle`
--
ALTER TABLE `kardex_detalle`
  ADD CONSTRAINT `id_kardex` FOREIGN KEY (`id_kardex`) REFERENCES `kardex` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kardex_detalle_ibfk_1` FOREIGN KEY (`id_herramienta`) REFERENCES `catalogo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `peticiones`
--
ALTER TABLE `peticiones`
  ADD CONSTRAINT `peticiones_ibfk_1` FOREIGN KEY (`solicitante`) REFERENCES `osticket_db`.`ost_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `peticiones_ibfk_2` FOREIGN KEY (`ticket_id`) REFERENCES `osticket_db`.`ost_ticket` (`ticket_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
