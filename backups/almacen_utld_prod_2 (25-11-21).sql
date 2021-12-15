-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3308
-- Tiempo de generación: 25-11-2021 a las 16:07:10
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
-- Base de datos: `almacen_utld_prod_2`
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
) ENGINE=InnoDB AUTO_INCREMENT=4419 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `catalogo`
--

INSERT INTO `catalogo` (`id`, `descripcion`, `codigo`, `numserie`, `tipo`, `consumible`, `activo`) VALUES
(4355, 'Pinzas de corte', 878787, NULL, 24, 0, 1),
(4359, 'Desarmador plano', 898989, NULL, 30, 0, 1),
(4360, 'Serrucho', 21200, NULL, 18, 0, 1),
(4361, 'Linterna', 2212, NULL, 24, 0, 0),
(4363, 'Pala', 21909, NULL, 35, 0, 1),
(4364, 'Tijeras', NULL, '56776', 18, 0, 0),
(4365, 'Desarmador de cruz', NULL, '89888', 30, 0, 1),
(4367, 'Tornillo hexagonal dos pulgadas allen bradley', 4232, NULL, 37, 0, 1),
(4368, 'Cables de corriente', 8412, NULL, 34, 0, 1),
(4372, 'Botas de casquillo', 92920, NULL, 28, 0, 0),
(4373, 'Contacto electrico', 555212, NULL, 24, 0, 1),
(4374, 'Cable electrico', 87573, NULL, 24, 0, 1),
(4375, 'Rotomartillo stanley', NULL, '23331', 34, 0, 0),
(4376, 'Escoba truper', 4451, NULL, 35, 0, 0),
(4377, 'Pulidora', NULL, '44421', 34, 0, 1),
(4378, 'Cortador de tubo', NULL, 'DWE-4010', 24, 0, 1),
(4379, 'Esmeriladora DEWALT', NULL, 'PAMXAMZLAG1', 34, 0, 1),
(4380, 'Careta para soldar', NULL, 'B08744TKV9', 20, 0, 0),
(4381, 'Esmeriladora DEWALT', NULL, 'PAMXAMZLAG2', 34, 0, 1),
(4382, 'Multimetro', NULL, '12ADBA23', 36, 0, 0),
(4384, 'Pinzas electricas', 33219, NULL, 24, 0, 0),
(4385, 'Segueta', 32221, NULL, 18, 0, 1),
(4389, 'Abrazaderas', 333412, NULL, 30, 0, 0),
(4390, 'Lima triangular', 566621, NULL, 18, 0, 1),
(4397, 'Navaja', 231113, NULL, 30, 0, 1),
(4399, 'Cinta aislante', 355511, NULL, 24, 0, 1),
(4408, 'Alicates de punta larga', 441212, NULL, 36, 0, 0),
(4409, 'Llaves hexagonales', 335631, NULL, 37, 0, 1),
(4410, 'Compresor DEWALT', NULL, '5aAb331', 34, 0, 1),
(4411, 'Rotomartillo', NULL, '3321ffe', 34, 0, 1),
(4412, 'Rotomartillo', NULL, '3313fw2', 34, 0, 1),
(4415, 'Sierra caladora', NULL, '343afv', 34, 0, 1),
(4416, 'Brochas', 222342, NULL, 18, 0, 0),
(4417, 'Pinzas de sujecion', 774234, NULL, 30, 0, 1),
(4418, 'Escalera', NULL, '994abe2', NULL, 0, 1);

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
) ENGINE=InnoDB AUTO_INCREMENT=126 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `detalle_peticion`
--

INSERT INTO `detalle_peticion` (`id`, `herramienta`, `qty_peticion`, `peticion_id`) VALUES
(125, 4355, 6, 82);

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
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `faltantes`
--

INSERT INTO `faltantes` (`id`, `id_herramienta`, `motivo`, `cantidad`, `id_mov`, `estado`) VALUES
(78, 4379, 'Esto lo voy a regresar después', 2, 443, 3),
(80, 4355, 'Extravió las demás herramientas', 1, 449, 2),
(81, 4359, 'Extravió las demás herramientas', 2, 449, 2),
(82, 4378, 'Lo entrega mañana', 1, 450, 3),
(83, 4367, 'Las perdió', 5, 451, 2),
(85, 4360, 'Robo', 1, 454, 2),
(86, 4367, 'Mañana', 2, 455, 3),
(87, 4379, 'Robo', 2, 458, 2),
(89, 4355, 'Ahorita las regreso', 2, 495, 2),
(90, 4365, 'Ahorita las regreso', 2, 495, 2),
(91, 4367, 'Despues', 1, 491, 3),
(92, 4384, 'De rato las regreso', 4, 506, 3);

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
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `inventarioutl`
--

INSERT INTO `inventarioutl` (`id`, `herramienta`, `qtyo`, `qtyf`, `qtyc`) VALUES
(4, 4355, 47, 47, 0),
(5, 4359, 8, 8, 0),
(6, 4367, 45, 45, 0),
(7, 4365, 18, 18, 0),
(8, 4360, 9, 9, 0),
(10, 4378, 1, 1, 0),
(11, 4379, 1, 1, 0),
(12, 4381, 1, 1, 0),
(15, 4384, 15, 15, 0),
(17, 4389, 0, 0, 0),
(18, 4390, 0, 0, 0),
(25, 4397, 0, 0, 0),
(27, 4399, 0, 0, 0),
(36, 4408, 0, 0, 0),
(37, 4409, 0, 0, 0),
(38, 4410, 0, 0, 0),
(44, 4415, 1, 1, 0),
(45, 4416, 0, 0, 0),
(46, 4417, 0, 0, 0),
(47, 4418, 1, 1, 0);

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
  `idticket` int(11) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `movimiento` (`movimiento`)
) ENGINE=InnoDB AUTO_INCREMENT=558 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `kardex`
--

INSERT INTO `kardex` (`id`, `movimiento`, `fecha`, `descripcion`, `solicitante`, `idticket`, `estado`) VALUES
(435, 1, '2021-11-22 13:27:08', 'Préstamo ordinario', 'Victor', NULL, 0),
(436, 2, '2021-11-22 13:27:08', 'Regreso ordinario', 'Victor', NULL, NULL),
(437, 1, '2021-11-22 13:37:25', 'Préstamo ordinario', 'Valeria', NULL, 0),
(438, 2, '2021-11-22 13:37:25', 'Regreso ordinario', 'Valeria', NULL, NULL),
(439, 1, '2021-11-22 13:38:14', 'Préstamo ordinario', 'Valeria', NULL, 0),
(440, 2, '2021-11-22 13:38:14', 'Regreso ordinario', 'Valeria', NULL, NULL),
(441, 1, '2021-11-22 18:22:35', 'El cautín estaba defectuoso', 'Valeria', NULL, 0),
(442, 2, '2021-11-22 18:22:35', 'Regreso ordinario', 'Valeria', NULL, NULL),
(443, 1, '2021-11-22 18:24:28', 'Hola soy un prestamo', 'Victor', NULL, 0),
(444, 1, '2021-11-22 18:28:03', 'Préstamo ordinario', 'Valeria', NULL, 0),
(445, 2, '2021-11-22 18:24:28', 'Regreso ordinario', 'Victor', NULL, NULL),
(446, 4, '2021-11-22 18:26:24', 'Robo o extravío', 'Victor', NULL, NULL),
(447, 2, '2021-11-22 18:27:29', 'Regreso tardío', 'Victor', NULL, NULL),
(448, 2, '2021-11-22 18:28:03', 'Regreso ordinario', 'Valeria', NULL, NULL),
(449, 1, '2021-11-22 19:00:21', 'Préstamo ordinario', 'Ángel', NULL, 0),
(450, 1, '2021-11-22 19:00:56', 'Préstamo ordinario', 'Zahul', NULL, 0),
(451, 1, '2021-11-22 19:01:51', 'Préstamo ordinario', 'Adrián', NULL, 0),
(452, 1, '2021-11-22 19:02:14', 'Préstamo ordinario', 'Baldo', NULL, 0),
(453, 1, '2021-11-22 19:02:27', 'Préstamo ordinario', 'Ancona', NULL, 0),
(454, 1, '2021-11-22 19:02:47', 'Préstamo ordinario', 'Orduña', NULL, 0),
(455, 1, '2021-11-22 19:03:07', 'Préstamo ordinario', 'Marrufo', NULL, 0),
(456, 1, '2021-11-22 19:03:27', 'Préstamo ordinario', 'Mafer', NULL, 0),
(457, 1, '2021-11-22 19:03:37', 'Préstamo ordinario', 'Carlos', NULL, 0),
(458, 1, '2021-11-22 19:03:48', 'Préstamo ordinario', 'Chobby', NULL, 0),
(459, 2, '2021-11-22 19:00:21', 'Regreso ordinario', 'Ángel', NULL, NULL),
(460, 2, '2021-11-22 19:00:56', 'Regreso ordinario', 'Zahul', NULL, NULL),
(461, 2, '2021-11-22 19:01:51', 'Regreso ordinario', 'Adrián', NULL, NULL),
(462, 2, '2021-11-22 19:02:14', 'Regreso ordinario', 'Baldo', NULL, NULL),
(463, 2, '2021-11-22 19:02:27', 'Regreso ordinario', 'Ancona', NULL, NULL),
(464, 2, '2021-11-22 19:02:47', 'Regreso ordinario', 'Orduña', NULL, NULL),
(465, 2, '2021-11-22 19:03:07', 'Regreso ordinario', 'Marrufo', NULL, NULL),
(466, 2, '2021-11-22 19:03:27', 'Regreso ordinario', 'Mafer', NULL, NULL),
(467, 2, '2021-11-22 19:03:37', 'Regreso ordinario', 'Carlos', NULL, NULL),
(468, 2, '2021-11-22 19:03:48', 'Regreso ordinario', 'Chobby', NULL, NULL),
(469, 4, '2021-11-22 19:04:11', 'Robo o extravío', 'Ángel', NULL, NULL),
(470, 4, '2021-11-22 19:04:17', 'Robo o extravío', 'Ángel', NULL, NULL),
(471, 2, '2021-11-22 19:04:20', 'Regreso tardío', 'Zahul', NULL, NULL),
(472, 4, '2021-11-22 19:04:23', 'Robo o extravío', 'Adrián', NULL, NULL),
(473, 2, '2021-11-22 19:04:26', 'Regreso tardío', 'Ancona', NULL, NULL),
(474, 4, '2021-11-22 19:04:30', 'Robo o extravío', 'Orduña', NULL, NULL),
(475, 2, '2021-11-22 19:04:32', 'Regreso tardío', 'Marrufo', NULL, NULL),
(476, 4, '2021-11-22 19:04:35', 'Robo o extravío', 'Chobby', NULL, NULL),
(477, 1, '2021-11-22 20:06:36', 'Préstamo ordinario', 'Valeria', NULL, 0),
(478, 2, '2021-11-22 20:06:36', 'Regreso ordinario', 'Valeria', NULL, NULL),
(479, 1, '2021-11-22 20:11:41', 'Préstamo ordinario', 'Valeria', NULL, 0),
(480, 2, '2021-11-22 20:11:41', 'Regreso ordinario', 'Valeria', NULL, NULL),
(481, 1, '2021-11-22 20:17:05', 'Préstamo ordinario', 'Darnell', NULL, 0),
(482, 2, '2021-11-22 20:17:05', 'Regreso ordinario', 'Darnell', NULL, NULL),
(483, 1, '2021-11-22 20:17:41', 'Préstamo ordinario', 'Darnell', NULL, 0),
(484, 2, '2021-11-22 20:17:41', 'Regreso ordinario', 'Darnell', NULL, NULL),
(485, 4, '2021-11-22 20:18:58', 'Robo o extravío', 'Darnell', NULL, NULL),
(486, 1, '2021-11-23 16:15:09', 'Préstamo ordinario', 'Valeria', NULL, 0),
(487, 2, '2021-11-23 16:15:09', 'Regreso ordinario', 'Valeria', NULL, NULL),
(488, 1, '2021-11-25 15:47:05', 'Las pinzas ya estaban rotas', 'Fanny', NULL, 0),
(489, 1, '2021-11-25 15:47:12', 'Pinzas rotas', 'Fanny', NULL, 0),
(490, 1, '2021-11-25 15:46:58', 'Préstamo ordinario', 'Valeria', NULL, 0),
(491, 1, '2021-11-24 13:07:49', 'Préstamo ordinario', 'Darnell', NULL, 0),
(492, 4, '2021-11-24 04:20:28', 'Baja de herramienta', NULL, NULL, 0),
(493, 4, '2021-11-24 12:39:13', 'No se usará mas', NULL, NULL, 0),
(494, 4, '2021-11-24 12:41:31', 'No se comprarán mas', NULL, NULL, NULL),
(495, 1, '2021-11-24 13:00:36', 'Préstamo ordinario', 'Valeria', NULL, 0),
(496, 2, '2021-11-24 13:00:36', 'Regreso ordinario', 'Valeria', NULL, NULL),
(497, 5, '2021-11-24 13:01:18', 'Robo o extravío', 'Valeria', NULL, NULL),
(498, 5, '2021-11-24 13:01:21', 'Robo o extravío', 'Valeria', NULL, NULL),
(499, 2, '2021-11-24 13:07:49', 'Regreso ordinario', 'Darnell', NULL, NULL),
(500, 4, '2021-11-24 13:12:00', 'No se comprarán mas', NULL, NULL, NULL),
(501, 4, '2021-11-24 19:19:17', 'Blabla', NULL, NULL, NULL),
(502, 1, '2021-11-24 19:54:40', 'Préstamo ordinario', 'Valeria', NULL, 0),
(503, 1, '2021-11-24 19:54:17', 'Préstamo ordinario', 'Victor', NULL, 0),
(504, 2, '2021-11-24 19:54:17', 'Regreso ordinario', 'Victor', NULL, NULL),
(505, 2, '2021-11-24 19:54:40', 'Regreso ordinario', 'Valeria', NULL, NULL),
(506, 1, '2021-11-24 19:55:50', 'Préstamo ordinario', 'Valeria', NULL, 0),
(507, 2, '2021-11-24 19:55:50', 'Regreso ordinario', 'Valeria', NULL, NULL),
(508, 2, '2021-11-24 19:57:09', 'Regreso tardío', 'Valeria', NULL, NULL),
(509, 4, '2021-11-24 19:57:29', 'Porque si', NULL, NULL, NULL),
(510, 6, '2021-11-25 02:02:25', 'Alta inicial', NULL, NULL, 0),
(511, 6, '2021-11-25 02:02:30', 'Alta inicial', NULL, NULL, 0),
(512, 6, '2021-11-25 02:03:21', 'Alta inicial', NULL, NULL, 0),
(513, 6, '2021-11-25 02:04:26', 'Alta inicial', NULL, NULL, 0),
(514, 6, '2021-11-25 02:05:29', 'Alta inicial', NULL, NULL, 0),
(515, 6, '2021-11-25 02:07:05', 'Alta inicial', NULL, NULL, 0),
(516, 6, '2021-11-25 02:07:50', 'Alta inicial', NULL, NULL, 0),
(517, 6, '2021-11-25 02:08:16', 'Alta inicial', NULL, NULL, 0),
(518, 6, '2021-11-25 02:08:16', 'Alta inicial', NULL, NULL, 0),
(519, 6, '2021-11-25 02:08:16', 'Alta inicial', NULL, NULL, 0),
(520, 6, '2021-11-25 02:11:28', 'Alta inicial', NULL, NULL, 0),
(521, 6, '2021-11-25 02:11:28', 'Alta inicial', NULL, NULL, 0),
(522, 6, '2021-11-25 02:11:50', 'Alta inicial', NULL, NULL, 0),
(523, 6, '2021-11-25 02:14:59', 'Alta inicial', NULL, NULL, 0),
(524, 6, '2021-11-25 02:15:11', 'Alta inicial', NULL, NULL, 0),
(525, 6, '2021-11-25 02:15:12', 'Alta inicial', NULL, NULL, 0),
(526, 6, '2021-11-25 02:15:41', 'Alta inicial', NULL, NULL, 0),
(527, 6, '2021-11-25 02:15:41', 'Alta inicial', NULL, NULL, 0),
(528, 6, '2021-11-25 02:16:32', 'Alta inicial', NULL, NULL, 0),
(529, 6, '2021-11-25 02:16:55', 'Alta inicial', NULL, NULL, 0),
(530, 6, '2021-11-25 02:17:18', 'Alta inicial', NULL, NULL, 0),
(531, 6, '2021-11-25 02:17:40', 'Alta inicial', NULL, NULL, 0),
(532, 4, '2021-11-25 04:42:11', 'Cuidado', NULL, NULL, NULL),
(533, 6, '2021-11-25 06:23:03', 'Alta inicial', NULL, NULL, 0),
(534, 6, '2021-11-25 06:24:45', 'Herramienta nueva', NULL, NULL, 0),
(535, 6, '2021-11-25 06:30:33', 'Herramienta nueva', NULL, NULL, 0),
(536, 4, '2021-11-25 06:31:49', 'No se comprarán mas', NULL, NULL, NULL),
(541, 6, '2021-11-25 13:46:24', 'Herramienta nueva', NULL, NULL, 0),
(543, 6, '2021-11-25 13:50:07', 'Herramienta nueva', NULL, NULL, 0),
(545, 6, '2021-11-25 13:51:12', 'Herramienta nueva', NULL, NULL, 0),
(546, 3, '2021-11-25 13:51:12', 'Llegada de herramienta unica', NULL, NULL, 0),
(547, 6, '2021-11-25 15:31:32', 'Herramienta nueva', NULL, NULL, 0),
(548, 1, '2021-11-25 15:46:38', 'Préstamo ordinario', 'Valeria', NULL, 0),
(549, 2, '2021-11-25 15:46:38', 'Regreso ordinario', 'Valeria', NULL, NULL),
(550, 2, '2021-11-25 15:46:50', 'Regreso tardío', 'Darnell', NULL, NULL),
(551, 2, '2021-11-25 15:46:58', 'Regreso ordinario', 'Valeria', NULL, NULL),
(552, 2, '2021-11-25 15:47:05', 'Regreso ordinario', 'Fanny', NULL, NULL),
(553, 2, '2021-11-25 15:47:12', 'Regreso ordinario', 'Fanny', NULL, NULL),
(554, 6, '2021-11-25 15:49:21', 'Herramienta nueva', NULL, NULL, NULL),
(555, 6, '2021-11-25 15:52:04', 'Herramienta nueva', NULL, NULL, NULL),
(556, 3, '2021-11-25 15:52:04', 'Llegada de herramienta unica', NULL, NULL, NULL),
(557, 4, '2021-11-25 16:02:51', 'No eran solicitadas', 'Darnell', NULL, NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=957 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `kardex_detalle`
--

INSERT INTO `kardex_detalle` (`id`, `id_kardex`, `id_herramienta`, `qty`) VALUES
(807, 435, 4378, 1),
(808, 435, 4379, 1),
(809, 435, 4355, 3),
(811, 436, 4378, 1),
(812, 436, 4379, 1),
(813, 436, 4355, 3),
(815, 437, 4359, 1),
(817, 438, 4359, 1),
(819, 439, 4355, 1),
(821, 440, 4355, 1),
(823, 441, 4378, 2),
(825, 442, 4378, 2),
(826, 443, 4379, 2),
(827, 443, 4365, 2),
(831, 445, 4365, 2),
(833, 447, 4379, 2),
(837, 449, 4355, 1),
(838, 449, 4359, 2),
(839, 450, 4378, 1),
(840, 450, 4378, 1),
(841, 451, 4367, 5),
(842, 451, 4378, 1),
(843, 452, 4379, 1),
(845, 453, 4378, 1),
(846, 454, 4360, 1),
(847, 454, 4355, 3),
(848, 455, 4367, 2),
(849, 455, 4360, 2),
(851, 457, 4355, 6),
(852, 458, 4379, 2),
(853, 458, 4365, 1),
(855, 460, 4378, 1),
(856, 461, 4378, 1),
(857, 462, 4379, 1),
(858, 463, 4378, 1),
(859, 464, 4355, 3),
(860, 465, 4360, 2),
(862, 467, 4355, 6),
(863, 468, 4365, 1),
(864, 469, 4355, 1),
(865, 470, 4359, 2),
(866, 471, 4378, 1),
(867, 472, 4367, 5),
(869, 474, 4360, 1),
(870, 475, 4367, 2),
(871, 476, 4379, 2),
(882, 481, 4359, 7),
(885, 482, 4359, 7),
(890, 486, 4379, 1),
(891, 486, 4381, 1),
(892, 486, 4355, 5),
(893, 487, 4379, 1),
(894, 487, 4381, 1),
(895, 487, 4355, 5),
(896, 488, 4355, 10),
(897, 489, 4355, 15),
(898, 490, 4355, 2),
(899, 490, 4367, 1),
(900, 490, 4378, 1),
(901, 490, 4381, 1),
(902, 491, 4355, 1),
(903, 491, 4367, 1),
(904, 492, 4380, NULL),
(905, 493, 4376, NULL),
(906, 494, 4382, NULL),
(907, 495, 4355, 2),
(908, 495, 4359, 1),
(909, 495, 4365, 2),
(910, 496, 4359, 1),
(911, 497, 4355, 2),
(912, 498, 4365, 2),
(913, 499, 4355, 1),
(914, 500, 4372, NULL),
(916, 502, 4384, 12),
(917, 503, 4384, 1),
(918, 504, 4384, 1),
(919, 505, 4384, 12),
(920, 506, 4384, 4),
(921, 506, 4355, 4),
(922, 507, 4355, 4),
(923, 508, 4384, 4),
(924, 509, 4384, NULL),
(925, 532, 4389, NULL),
(926, 533, 4408, NULL),
(927, 534, 4409, NULL),
(928, 535, 4410, NULL),
(929, 536, 4408, NULL),
(937, 545, 4415, NULL),
(938, 546, 4415, 1),
(939, 547, 4416, NULL),
(940, 548, 4367, 1),
(941, 548, 4355, 2),
(942, 548, 4384, 1),
(943, 549, 4367, 1),
(944, 549, 4355, 2),
(945, 549, 4384, 1),
(946, 550, 4367, 1),
(947, 551, 4355, 2),
(948, 551, 4367, 1),
(949, 551, 4378, 1),
(950, 551, 4381, 1),
(951, 552, 4355, 10),
(952, 553, 4355, 15),
(953, 554, 4417, NULL),
(954, 555, 4418, NULL),
(955, 556, 4418, 1),
(956, 557, 4416, NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4;

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
(37, 'Tornilleria');

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Nietzche', 'nietzche@example.com', NULL, '$2y$10$tBc7Zd0APMnNUJeOWzBASusVHYLQrPBXOoAAjvJXfaS5UzGaK0RJ2', NULL, '2021-09-10 20:23:24', '2021-09-10 20:23:24'),
(2, 'Jesus Marrufo', 'st19030309@utlaguna.edu.mx', NULL, '$2y$10$67W.1CtAxdSRMEIYnnBpPeg2WBR6JSQgw.9L/EUYRA6.sCMahPK.6', NULL, '2021-09-13 23:56:48', '2021-09-13 23:56:48'),
(3, 'Diego Romero', 'st19030059@utlaguna.edu.mx', NULL, '$2y$10$T4xJGXJexRuI01AXWk96QOYBfCE0D2h1eTnK9KPyWZ/edKqXNU8dq', NULL, '2021-09-13 23:57:51', '2021-09-13 23:57:51'),
(4, 'Zahul Domínguez Chávez', 'st19030302@utlaguna.edu.mx', NULL, '$2y$10$GAk6lWBOkdMHC6wgrAW8D.7rgTpkuu51dN4ZSey9gPZzAjIIKP49.', 'UmwAto7wfEIeexpvsbTqe6mrer4T9OsJEEtKOWNoaN87gPJZzds6S0sCGtxc', '2021-09-15 07:59:13', '2021-09-15 07:59:13'),
(5, 'Darnell', 'dar@gmail.com', NULL, '$2y$10$QcpxE8W/qQjhOjvlL46fS.1onAhNysEKWfSNrwcUx3SlACu/9mUsO', NULL, '2021-11-24 16:17:30', '2021-11-24 16:17:30');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `catalogo`
--
ALTER TABLE `catalogo`
  ADD CONSTRAINT `tipo` FOREIGN KEY (`tipo`) REFERENCES `tipo_herramienta` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

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
