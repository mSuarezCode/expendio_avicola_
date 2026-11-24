-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-11-2026 a las 15:32:34
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
-- Base de datos: `expendio_avicola`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auth_groups_users`
--

CREATE TABLE `auth_groups_users` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `group` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `auth_groups_users`
--

INSERT INTO `auth_groups_users` (`id`, `user_id`, `group`, `created_at`) VALUES
(18, 7, 'gerente', '2026-11-11 08:15:01'),
(19, 8, 'superadmin', '2026-11-16 13:52:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auth_identities`
--

CREATE TABLE `auth_identities` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `secret` varchar(255) NOT NULL,
  `secret2` varchar(255) DEFAULT NULL,
  `expires` datetime DEFAULT NULL,
  `extra` text DEFAULT NULL,
  `force_reset` tinyint(1) NOT NULL DEFAULT 0,
  `last_used_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `auth_identities`
--

INSERT INTO `auth_identities` (`id`, `user_id`, `type`, `name`, `secret`, `secret2`, `expires`, `extra`, `force_reset`, `last_used_at`, `created_at`, `updated_at`) VALUES
(5, 7, 'email_password', NULL, 'inemiketec@gmail.com', '$2y$12$ycqkAx.oCYO8/EiwMumcE.w85vTTWGX5B0I.HPjVEKXL.cIwD8Pcq', NULL, NULL, 0, '2026-11-11 08:14:43', '2026-11-08 10:52:30', '2026-11-11 08:14:43'),
(6, 8, 'email_password', NULL, 'administracion@expendio-de-gallinas-yireth.com', '$2y$12$kbPOJopj2DQd2pG1bh1lfuJwZfWQr2Iesn49bW9XN3y7M5Lihk2Oe', NULL, NULL, 0, '2026-11-24 05:33:37', '2026-11-08 11:16:40', '2026-11-24 05:33:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auth_logins`
--

CREATE TABLE `auth_logins` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `id_type` varchar(255) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `date` datetime NOT NULL,
  `success` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `auth_logins`
--

INSERT INTO `auth_logins` (`id`, `ip_address`, `user_agent`, `id_type`, `identifier`, `user_id`, `date`, `success`) VALUES
(1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'email_password', 'user.suarez2701@gmail.com', NULL, '2026-11-08 10:44:22', 0),
(2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'email_password', 'inemiketec@gmail.com', 7, '2026-11-08 10:53:01', 1),
(3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'email_password', 'user.suarez2701@gmail.com', 1, '2026-11-08 10:53:47', 1),
(4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'email_password', 'inemiketec@gmail.com', 7, '2026-11-08 10:55:00', 1),
(5, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'email_password', 'administracion@expendio-de-gallinas-yireth.com', 8, '2026-11-08 11:44:08', 1),
(6, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'email_password', 'administracion@expendio-de-gallinas-yireth.com', 8, '2026-11-08 12:05:33', 1),
(7, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'email_password', 'administracion@expendio-de-gallinas-yireth.com', 8, '2026-11-08 12:57:53', 1),
(8, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'email_password', 'administracion@expendio-de-gallinas-yireth.com', 8, '2026-11-08 17:15:03', 1),
(9, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'email_password', 'administracion@expendio-de-gallinas-yireth.com', 8, '2026-11-09 06:16:32', 1),
(10, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'email_password', 'user.suarez2701@gmail.com', NULL, '2026-11-09 07:34:49', 0),
(11, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'email_password', 'administracion@expendio-de-gallinas-yireth.com', 8, '2026-11-09 07:35:33', 1),
(12, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'email_password', 'administracion@expendio-de-gallinas-yireth.com', 8, '2026-11-09 18:56:33', 1),
(13, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'email_password', 'administracion@expendio-de-gallinas-yireth.com', 8, '2026-11-10 05:59:28', 1),
(14, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'email_password', 'administracion@expendio-de-gallinas-yireth.com', 8, '2026-11-10 18:36:57', 1),
(15, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'email_password', 'administracion@expendio-de-gallinas-yireth.com', 8, '2026-11-10 20:45:19', 1),
(16, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'email_password', 'administracion@expendio-de-gallinas-yireth.com', 8, '2026-11-10 21:29:39', 1),
(17, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'email_password', 'inemiketec@gmail.com', 7, '2026-11-10 21:30:21', 1),
(18, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'email_password', 'administracion@expendio-de-gallinas-yireth.com', 8, '2026-11-10 22:33:44', 1),
(19, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'email_password', 'inemiketec@gmail.com', 7, '2026-11-10 23:09:15', 1),
(20, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'email_password', 'administracion@expendio-de-gallinas-yireth.com', 8, '2026-11-11 06:16:18', 1),
(21, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'email_password', 'inemiketec@gmail.com', 7, '2026-11-11 07:38:45', 1),
(22, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'email_password', 'inemiketec@gmail.com', 7, '2026-11-11 08:14:02', 1),
(23, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'email_password', 'inemiketec@gmail.com', 7, '2026-11-11 08:14:43', 1),
(24, '189.230.25.11', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Mobile Safari/537.36', 'email_password', 'administracion@expendio-de-gallinas-yireth.com', 8, '2026-11-11 13:59:43', 1),
(25, '187.212.111.119', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Mobile Safari/537.36', 'email_password', 'administracion@expendio-de-gallinas-yireth.com', 8, '2026-11-11 19:20:35', 1),
(26, '189.230.67.178', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'email_password', 'administracion@expendio-de-gallinas-yireth.com', 8, '2026-11-14 05:31:22', 1),
(27, '38.43.88.3', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36 Edg/157.0.0.0', 'email_password', 'adry2830491401@gmail.com', NULL, '2026-11-14 07:42:21', 0),
(28, '38.43.88.4', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36 Edg/157.0.0.0', 'email_password', 'administracion@expendio-de-gallinas-yireth.com', 8, '2026-11-14 07:43:43', 1),
(29, '189.230.67.178', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'email_password', 'administracion@expendio-de-gallinas-yireth.com', 8, '2026-11-14 08:39:42', 1),
(30, '189.230.67.178', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'email_password', 'administracion@expendio-de-gallinas-yireth.com', 8, '2026-11-14 13:09:53', 1),
(31, '189.230.67.178', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'email_password', 'administracion@expendio-de-gallinas-yireth.com', 8, '2026-11-15 09:35:03', 1),
(32, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'email_password', 'administracion@expendio-de-gallinas-yireth.com', 8, '2026-11-15 20:15:01', 1),
(33, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'email_password', 'administracion@expendio-de-gallinas-yireth.com', 8, '2026-11-16 01:51:42', 1),
(34, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'email_password', 'administracion@expendio-de-gallinas-yireth.com', 8, '2026-11-16 07:12:06', 1),
(35, '189.230.67.178', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'email_password', 'administracion@expendio-de-gallinas-yireth.com', 8, '2026-11-16 19:30:25', 1),
(36, '38.43.88.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36 Edg/157.0.0.0', 'email_password', 'administracion@expendio-gallinas-yireth.com', NULL, '2026-11-17 05:18:14', 0),
(37, '38.43.88.4', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36 Edg/157.0.0.0', 'email_password', 'administracion@expendio-gallinas-yireth.com', NULL, '2026-11-17 05:18:24', 0),
(38, '38.43.88.4', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36 Edg/157.0.0.0', 'email_password', 'administracion@expendio-de-gallinas-yireth.com', 8, '2026-11-17 05:18:48', 1),
(39, '189.230.67.178', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'email_password', 'administracion@expendio-de-gallinas-yireth.com', 8, '2026-11-17 08:43:39', 1),
(40, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'email_password', 'administracion@expendio-de-gallinas-yireth.com', 8, '2026-11-17 18:10:46', 1),
(41, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'email_password', 'administracion@expendio-de-gallinas-yireth.com', 8, '2026-11-18 21:12:36', 1),
(42, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', 'email_password', 'administracion@expendio-de-gallinas-yireth.com', 8, '2026-11-21 07:46:36', 1),
(43, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', 'email_password', 'administracion@expendio-de-gallinas-yireth.com', 8, '2026-11-22 09:42:27', 1),
(44, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', 'email_password', 'administracion@expendio-de-gallinas-yireth.com', 8, '2026-11-23 07:14:12', 1),
(45, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', 'email_password', 'administracion@expendio-de-gallinas-yireth.com', 8, '2026-11-23 22:50:21', 1),
(46, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', 'email_password', 'administracion@expendio-de-gallinas-yireth.com', 8, '2026-11-24 05:33:37', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auth_permissions_users`
--

CREATE TABLE `auth_permissions_users` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `permission` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auth_remember_tokens`
--

CREATE TABLE `auth_remember_tokens` (
  `id` int(11) UNSIGNED NOT NULL,
  `selector` varchar(255) NOT NULL,
  `hashedValidator` varchar(255) NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `expires` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `auth_remember_tokens`
--

INSERT INTO `auth_remember_tokens` (`id`, `selector`, `hashedValidator`, `user_id`, `expires`, `created_at`, `updated_at`) VALUES
(1, 'c5b818099053e05b780a0f71', 'eb20cb946273a650c3982ed70417ed549a01fb8db213802b8776ec0fc7bf95fa', 8, '2026-12-15 08:47:00', '2026-11-14 07:43:43', '2026-11-15 08:47:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auth_token_logins`
--

CREATE TABLE `auth_token_logins` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `id_type` varchar(255) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `date` datetime NOT NULL,
  `success` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalogo_productos`
--

CREATE TABLE `catalogo_productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `tipo_unidad` enum('piezas','kilos') NOT NULL,
  `precio_venta_actual` decimal(10,2) NOT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `catalogo_productos`
--

INSERT INTO `catalogo_productos` (`id`, `nombre`, `tipo_unidad`, `precio_venta_actual`, `estado`, `created_at`) VALUES
(1, 'Pollo', 'piezas', 6.00, 1, '2026-10-19 01:02:25'),
(2, 'Gallina', 'piezas', 210.00, 1, '2026-10-19 01:02:25'),
(3, 'Alitas Naturales', 'kilos', 100.00, 1, '2026-10-19 01:02:25'),
(4, 'Alitas Adobadas', 'kilos', 100.00, 1, '2026-10-19 01:02:25'),
(5, 'Milanesa Natural', 'kilos', 130.00, 0, '2026-10-19 01:02:25'),
(6, 'Milanesa Marinada', 'kilos', 140.00, 0, '2026-10-19 01:02:25'),
(7, 'Milanesa Empanizada', 'kilos', 150.00, 0, '2026-10-19 01:02:25'),
(10, 'Menudencia', 'kilos', 45.00, 1, '2026-10-23 22:18:47'),
(11, 'Milanesas Empanizadas', 'kilos', 180.00, 1, '2026-10-25 22:20:04'),
(12, 'Milanesas Marinadas', 'kilos', 170.00, 1, '2026-10-25 22:20:04'),
(13, 'Milanesas Naturales', 'kilos', 170.00, 1, '2026-10-25 22:20:04'),
(14, 'Piernas y muslos', 'kilos', 90.00, 1, '2026-10-25 22:20:04'),
(15, 'Nugget', 'kilos', 85.00, 1, '2026-11-01 08:01:40'),
(16, 'Longaniza', 'kilos', 70.00, 1, '2026-11-01 08:01:40'),
(17, 'Albóndigas', 'kilos', 150.00, 1, '2026-11-01 08:01:40'),
(18, 'Pechugas rellenas', 'kilos', 145.00, 1, '2026-11-01 08:01:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cierres_diarios`
--

CREATE TABLE `cierres_diarios` (
  `id` int(11) NOT NULL,
  `fecha_cierre` date NOT NULL,
  `fondo_inicial` decimal(10,2) DEFAULT 0.00,
  `ventas_efectivo` decimal(10,2) DEFAULT 0.00,
  `ventas_transferencia` decimal(10,2) DEFAULT 0.00,
  `total_gastos` decimal(10,2) DEFAULT 0.00,
  `efectivo_esperado` decimal(10,2) DEFAULT 0.00,
  `efectivo_real` decimal(10,2) DEFAULT 0.00,
  `diferencia` decimal(10,2) DEFAULT 0.00,
  `usuario_cierre_id` int(11) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `tipo_entrega` varchar(50) DEFAULT 'domicilio',
  `colonia` varchar(100) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `referencia` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `telefono`, `nombre`, `tipo_entrega`, `colonia`, `direccion`, `referencia`, `created_at`) VALUES
(1, '9931151252', 'Estela', 'domicilio', 'Cañales', 'Calle melaza m:8 l:12 Col. Los cañales, Cárdenas tabasco', 'Casa azul con porton chocolate', '2026-11-01 03:01:18'),
(2, '993115225564', 'Estela', 'domicilio', 'Santa Rita', 'Calle sin nombre', 'Casa rosada con porton blanco', '2026-11-01 03:10:57'),
(3, '9936554898', 'Jose', 'tienda', 'Progreso', 'Calle amaranto 123', 'Casa amarilla ', '2026-11-01 03:11:44'),
(4, '937569846', 'MIguel', 'domicilio', 'Santa rita', 'Calle conocida', 'Casa morada', '2026-11-01 03:13:58'),
(5, '9371249863', 'Jose Aarón', 'tienda', 'Cañales 1 ', 'Av. Cañaveral ', 'A un costado de la iglesia católica portón blanco ', '2026-11-01 12:08:24'),
(6, '9371210294', 'Nanci', 'domicilio', '', 'Cañales 3ra etapaa', 'Calle 11 norte casa azul ', '2026-11-01 13:52:53'),
(7, '9371123456', 'Angela Hernández', 'domicilio', 'Cañales 1', 'Alameda ', 'Calle 2 ', '2026-11-01 13:54:01'),
(8, '9376157876', 'Chepy mora', 'tienda', '', 'Progreso ', 'Casa verde', '2026-11-01 13:54:37'),
(9, '9371579765', 'Sara progreso', 'domicilio', 'Progreso ', 'A un costado de chepi ', '', '2026-11-01 13:55:21'),
(10, '9387658167', 'Martha progreso', 'domicilio', 'Progreso ', 'Calle principal después de la Monterrey ', '', '2026-11-01 13:56:13'),
(11, '93716692781', 'Sara', 'domicilio', 'Cañales 3', 'Calle 12 norte', 'A un costado de la casa morada', '2026-11-01 13:56:59'),
(12, '9371126568', 'Sara', 'domicilio', 'Cañales ', 'Calle 12 norte ', 'A un costado de la casa morada ', '2026-11-01 14:00:29'),
(13, '9376186578', 'Maria estrada', 'domicilio', 'Cañales 1', 'Calle xd la guardia nacional casa azul', '', '2026-11-01 14:01:11'),
(14, '9931151254', 'Prueba', 'domicilio', 'LOS CAÑALES', 'MELAZA 12\r\nEntre Calles melaza y melaza Por el kinder', 'Por el kinder', '2026-11-01 21:04:03'),
(15, '9371178658', 'Anareli mendez', 'domicilio', 'Cañales 3ra sur', '', 'A un costado de el campo cañales 3ra sur cerca lámina.', '2026-11-01 21:51:34'),
(16, '9372839572', 'Martha elena', 'tienda', 'Progreso', 'Calle Gregorio Méndez ', 'Frente a antojitos sara casa verde a un costado de la tienda', '2026-11-02 05:39:06'),
(17, '9371178805', 'Mariela', 'domicilio', 'Cañales 2', 'Calle vengala número 32', 'Calle donde entran las combis casi por el primer andador casa de dos pisos lona afuera SE VENDE ', '2026-11-02 08:52:03'),
(18, '9371425140', 'monica amiga', 'domicilio', 'cañales 1 ', 'calle del kinder ', 'al fondo en la tienda', '2026-11-02 20:45:20'),
(19, '9373482839', 'señora que vende cerveza', 'domicilio', 'cañales 3 norte', 'cañales 3 norte calle 7 frente al mariachi la señora que vende cerveza', 'frente al mariachi la señora que vende cerveza', '2026-11-02 20:56:11'),
(20, '9371296291', 'peluquero calle 6 norte', 'tienda', '', 'cañales 3 norte calle 6 con el peluquero casa amarilla con rojo', '', '2026-11-02 21:03:05'),
(21, '9375932575', 'erika papeleria', 'domicilio', 'cañales 3 sur', 'papeleria los ku de cañales 3 sur frente a la calle 9', 'papeleria de lamina', '2026-11-02 21:20:06'),
(22, '9371842953', 'maria mamá de ramses', 'domicilio', 'ocampo', 'ocampo frente a la carniceria el toron en el callejon en la entrada hay una almendra al fondo', '', '2026-11-02 21:28:23'),
(23, '9371060961', 'erika ruiz ramos prima de adriana', 'domicilio', 'cañales 2 sur', 'cañales 2 sur calle 8 la calle del gym al fondo ultima casa mano derecha', '', '2026-11-02 21:38:44'),
(24, '9371752502', 'dora jesus romero', 'domicilio', 'nuevo progreso', 'nuevo progreso por el tanque de agua casa con porton blanco grande', '', '2026-11-02 21:47:19'),
(25, '9371165129', 'heydi', 'domicilio', 'cañales 3 norte', 'cañales 3 norte calle 14 a mitad de calle casa forrada de mosaico', '', '2026-11-02 21:56:38'),
(26, '9371660603', 'nueva esperanza casa de infonavit', 'domicilio', 'nueva esperanza', 'nueva esperanza pasando muelles y mofles frente a unos condominios casa de infonavit blanca a lado de una casa de dos plantas', '', '2026-11-02 22:02:39'),
(27, '5520437149', 'joel hernadez', 'tienda', 'c-29', 'pob. c-29', '', '2026-11-02 22:11:36'),
(28, '9371277926', 'francisca jimenez', 'domicilio', 'cañales 3 sur', 'cañales 3 sur calle 7 porton rojo', '', '2026-11-02 22:24:40'),
(29, '9371299172', 'luz mamá de yahir', 'domicilio', 'cañales 3 sur', 'cañales 3 sur calle 12 herreria roja', '', '2026-11-02 22:25:57'),
(30, '9371010466', 'livita', 'domicilio', 'cañales 1', 'cañales 1 frente al salon eliconias', 'frente al salon eliconias', '2026-11-02 22:27:54'),
(31, '9371055343', 'don lelo', 'tienda', 'cañales 3 sur', 'cañales 3 sur calle 5 porton blanco bugambilia afuera', 'bugambilia afuera', '2026-11-02 22:30:21'),
(32, '9371105579', 'genoveva', 'domicilio', 'cañales 2 sur', 'cañales 2 sur calle 11 casa amarilla frente a la casa roja de dos plantas', 'casa amarilla', '2026-11-02 22:32:41'),
(33, '9371009983', 'johana amiga moto negra', 'domicilio', 'cañales 2 sur', 'cañales 2 sur calle 5 casa rosa frente al spa', '', '2026-11-02 22:33:56'),
(34, '9371220299', 'itzia', 'domicilio', 'cañales 3 sur', 'cañales 3 sur calle 4 entrando a mano izquierda segunda casa barda alta hay un carrito rojo afuera', 'segunda casa mano izquierda barda alta', '2026-11-02 22:41:56'),
(35, '9371030975', 'abarrotes nachito', 'domicilio', 'ocampo', 'ocmapo abarrotes nachito despues del pollo viajero', 'ocampo abarrotes nachito despues del pollo viajero', '2026-11-02 23:00:44'),
(36, '9371704637', 'sarai', 'domicilio', '', 'cañales 2 calle 5 sur, pasillo a lado de una casa de dos plantas color naranja ', 'pasillo entre la casa de dos plnatas y el cuarto de gobierno', '2026-11-15 15:03:00'),
(37, '9372302451', 'hilda panadero', 'domicilio', '', 'calle 15 norte al fondo ', 'panaderos', '2026-11-15 15:06:59'),
(38, '9371198870', 'angel centro', 'domicilio', '', 'col centro', '', '2026-11-17 13:02:38'),
(39, '9371170212', 'mama de viviana', 'domicilio', 'Cañales 1 ', 'calle 12 norte casa amarilla', 'frente a la mata de tamarindo', '2026-11-17 13:05:40'),
(40, '9371166445', 'javier muletas', 'domicilio', '', 'calle 7 norte ', '', '2026-11-17 13:19:07'),
(41, '9372719266', 'carme', 'domicilio', '', 'calle 14 sur cañales 3 frente a la purificadora', 'porton verde ', '2026-11-17 13:33:21'),
(42, '9371281491', 'ana', 'domicilio', '', 'calle 10 CII al ultima casa mano izq.', 'cañales 2\r\n', '2026-11-17 13:36:04'),
(43, '9371268924', 'nohemi cordova', 'domicilio', '', 'c2 norte CIII casa naranja ', '', '2026-11-17 14:12:58'),
(44, '7831266552', 'maria', 'domicilio', 'ocampo', 'ocampo frente a la carniceria el toron en el callejon en la entrada hay una almendra al fondo', '', '2026-11-17 14:14:11'),
(45, '9370000000', 'ivan', 'domicilio', 'Cañales 1 ', 'cañales 3 sur calle 14 purificadora', 'en la purificadora', '2026-11-17 14:17:26'),
(46, '9371013072', 'landy vanesa', 'domicilio', '', 'cañales 2 atras de las rampas ', 'casa blanca', '2026-11-17 14:30:50'),
(47, '9932501471', 'madrigal', 'domicilio', '', 'calle 14 sur cañales 3', '', '2026-11-17 14:32:13'),
(48, '9373856948', 'fany', 'domicilio', '', 'calle 2 cañales 2 con el mecanico ', 'al fondo mano derecha', '2026-11-17 14:34:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedidos`
--

CREATE TABLE `detalle_pedidos` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `detalle_pedidos`
--

INSERT INTO `detalle_pedidos` (`id`, `pedido_id`, `nombre`, `cantidad`, `precio_unitario`, `subtotal`) VALUES
(1, 1, 'Lt#3 - Pollo', 1.00, 180.00, 180.00),
(2, 1, 'Lt#2 - Gallina', 1.00, 210.00, 210.00),
(3, 2, 'Lt#1 - Gallina', 6.00, 195.00, 1170.00),
(4, 3, 'Lt#2 - Gallina', 1.00, 210.00, 210.00),
(5, 4, 'Lt#2 - Gallina', 1.00, 210.00, 210.00),
(6, 5, 'Lt#2 - Gallina', 1.00, 210.00, 210.00),
(7, 6, 'Lt#3 - Pollo', 1.00, 180.00, 180.00),
(8, 7, 'Lt#2 - Gallina', 1.00, 210.00, 210.00),
(9, 8, 'Lt#1 - Gallina', 1.00, 210.00, 210.00),
(10, 9, 'Lt#1 - Gallina', 0.50, 210.00, 105.00),
(11, 10, 'Lt#3 - Pollo', 1.00, 180.00, 180.00),
(12, 11, 'Lt#3 - Pollo', 1.00, 180.00, 180.00),
(13, 12, 'Lt#1 - Gallina', 1.00, 210.00, 210.00),
(14, 13, 'Lt#1 - Gallina', 1.00, 210.00, 210.00),
(15, 14, 'Lt#1 - Gallina', 1.00, 210.00, 210.00),
(16, 15, 'Lt#3 - Pollo', 1.00, 180.00, 180.00),
(17, 16, 'Lt#3 - Pollo', 1.00, 180.00, 180.00),
(18, 17, 'Lt#3 - Pollo', 1.00, 180.00, 180.00),
(19, 18, 'Lt#3 - Pollo', 1.00, 180.00, 180.00),
(20, 19, 'Lt#3 - Pollo', 1.00, 180.00, 180.00),
(21, 20, 'Lt#3 - Pollo', 1.00, 180.00, 180.00),
(22, 21, 'Lt#3 - Pollo', 1.00, 180.00, 180.00),
(23, 22, 'Lt#3 - Pollo', 1.00, 180.00, 180.00),
(24, 23, 'Lt#3 - Pollo', 1.00, 180.00, 180.00),
(25, 24, 'Lt#3 - Pollo', 1.00, 180.00, 180.00),
(26, 25, 'Lt#3 - Pollo', 1.00, 180.00, 180.00),
(27, 26, 'Lt#3 - Pollo', 1.00, 180.00, 180.00),
(28, 27, 'Lt#2 - Gallina', 1.00, 210.00, 210.00),
(29, 28, 'Lt#1 - Gallina', 1.00, 210.00, 210.00),
(30, 29, 'Lt#1 - Gallina', 1.00, 210.00, 210.00),
(31, 30, 'Lt#1 - Gallina', 1.00, 210.00, 210.00),
(32, 31, 'Lt#1 - Gallina', 1.00, 210.00, 210.00),
(33, 32, 'Lt#4 - Pollo', 1.00, 6.00, 6.00),
(34, 33, 'Lt#1 - Gallina', 1.00, 200.00, 200.00),
(35, 34, 'Lt#3 - Pollo', 1.00, 175.00, 175.00),
(36, 35, 'Lt#3 - Pollo', 7.00, 175.00, 1225.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gastos_caja`
--

CREATE TABLE `gastos_caja` (
  `id` int(11) NOT NULL,
  `tipo_gasto` varchar(50) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `gastos_caja`
--

INSERT INTO `gastos_caja` (`id`, `tipo_gasto`, `monto`, `descripcion`, `usuario_id`, `created_at`) VALUES
(1, 'bolsas', 20.00, 'bolsa para la tripa', NULL, '2026-11-17 05:26:01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_ajustes_almacen`
--

CREATE TABLE `historial_ajustes_almacen` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `tipo` enum('merma','entrada') NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `motivo` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_precios`
--

CREATE TABLE `historial_precios` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `precio_anterior` decimal(10,2) NOT NULL,
  `precio_nuevo` decimal(10,2) NOT NULL,
  `fecha_cambio` datetime DEFAULT current_timestamp(),
  `usuario_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario_cierre_diario`
--

CREATE TABLE `inventario_cierre_diario` (
  `id` int(11) NOT NULL,
  `cierre_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `fecha_cierre` date NOT NULL,
  `stock_remanente` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario_sucursal`
--

CREATE TABLE `inventario_sucursal` (
  `id` int(11) NOT NULL,
  `sucursal_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `stock_actual` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stock_minimo` decimal(10,2) NOT NULL DEFAULT 0.00,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lotes`
--

CREATE TABLE `lotes` (
  `id` int(11) NOT NULL,
  `sucursal_id` int(11) NOT NULL DEFAULT 1,
  `proveedor_id` int(11) UNSIGNED NOT NULL,
  `tipo_ave` enum('pollo','gallina') NOT NULL,
  `jaulas` int(11) DEFAULT 0,
  `piezas_por_jaula` int(11) DEFAULT 0,
  `piezas` int(11) NOT NULL,
  `peso` decimal(10,2) NOT NULL,
  `costo_unitario` decimal(10,2) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL DEFAULT 0.00,
  `comprobante` varchar(255) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `fecha_recepcion` date NOT NULL,
  `estado` varchar(50) DEFAULT 'disponible',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `lotes`
--

INSERT INTO `lotes` (`id`, `sucursal_id`, `proveedor_id`, `tipo_ave`, `jaulas`, `piezas_por_jaula`, `piezas`, `peso`, `costo_unitario`, `precio_venta`, `comprobante`, `observaciones`, `fecha_recepcion`, `estado`, `created_at`) VALUES
(1, 1, 19, 'gallina', 12, 8, 96, 403.20, 165.00, 0.00, NULL, NULL, '2026-11-17', 'disponible', '2026-11-17 05:20:00'),
(2, 1, 17, 'gallina', 1, 5, 5, 20.50, 175.00, 0.00, NULL, NULL, '2026-11-17', 'disponible', '2026-11-17 05:20:42'),
(3, 1, 16, 'pollo', 1, 15, 15, 54.00, 40.00, 175.00, NULL, NULL, '2026-11-17', 'disponible', '2026-11-17 05:22:35');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(5, '2020-12-28-223112', 'CodeIgniter\\Shield\\Database\\Migrations\\CreateAuthTables', 'default', 'CodeIgniter\\Shield', 1792371557, 1),
(6, '2021-07-04-041948', 'CodeIgniter\\Settings\\Database\\Migrations\\CreateSettingsTable', 'default', 'CodeIgniter\\Settings', 1792371557, 1),
(7, '2021-11-14-143905', 'CodeIgniter\\Settings\\Database\\Migrations\\AddContextColumn', 'default', 'CodeIgniter\\Settings', 1792371557, 1),
(8, '2026-07-20-181246', 'CodeIgniter\\Settings\\Database\\Migrations\\ConvertSqlsrvValueColumn', 'default', 'CodeIgniter\\Settings', 1792371557, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `sucursal_id` int(11) NOT NULL DEFAULT 1,
  `cliente_id` int(11) DEFAULT NULL,
  `cliente` varchar(150) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `tipo_entrega` enum('domicilio','tienda') NOT NULL,
  `colonia` varchar(100) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `referencia` text DEFAULT NULL,
  `repartidor` varchar(100) DEFAULT 'Sin asignar',
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `estado` varchar(50) NOT NULL DEFAULT 'pendiente',
  `observaciones_cancelacion` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `tipo_pago` varchar(50) DEFAULT NULL,
  `monto_efectivo` decimal(10,2) NOT NULL DEFAULT 0.00,
  `monto_transferencia` decimal(10,2) NOT NULL DEFAULT 0.00,
  `pago_verificado` tinyint(4) DEFAULT 0 COMMENT '0: Pendiente de verificar, 1: Verificado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `sucursal_id`, `cliente_id`, `cliente`, `telefono`, `tipo_entrega`, `colonia`, `direccion`, `referencia`, `repartidor`, `total`, `estado`, `observaciones_cancelacion`, `created_at`, `tipo_pago`, `monto_efectivo`, `monto_transferencia`, `pago_verificado`) VALUES
(1, 1, NULL, 'Jose Aarón', '9371249863', 'tienda', 'Cañales 1 ', 'Av. Cañaveral ', NULL, 'N/A', 390.00, 'entregado', NULL, '2026-11-17 06:50:34', 'efectivo', 390.00, 0.00, 0),
(2, 1, NULL, 'angel centro', '9371198870', 'domicilio', '', 'col centro', NULL, 'Sin asignar', 1170.00, 'entregado', NULL, '2026-11-17 07:02:38', 'transferencia', 0.00, 1170.00, 0),
(3, 1, NULL, 'mama de viviana', '9371170212', 'domicilio', 'Cañales 1 ', 'calle 12 norte casa amarilla', NULL, 'Sin asignar', 210.00, 'cancelado', 'Error de captura', '2026-11-17 07:05:40', 'efectivo', 210.00, 0.00, 0),
(4, 1, NULL, 'mama de viviana', '9371170212', 'domicilio', 'Cañales 1 ', 'calle 12 norte casa amarilla', NULL, 'Sin asignar', 210.00, 'pendiente', NULL, '2026-11-17 07:05:41', 'efectivo', 210.00, 0.00, 0),
(5, 1, NULL, 'mama de viviana', '9371170212', 'domicilio', 'Cañales 1 ', 'calle 12 norte casa amarilla', NULL, 'Aaron Ramos', 210.00, 'en_camino', NULL, '2026-11-17 07:05:41', 'efectivo', 210.00, 0.00, 0),
(6, 1, NULL, 'javier muletas', '9371166445', 'domicilio', '', 'calle 7 norte ', NULL, 'Aaron Ramos', 180.00, 'en_camino', NULL, '2026-11-17 07:19:07', 'efectivo', 180.00, 0.00, 0),
(7, 1, NULL, 'carme', '9372719266', 'domicilio', '', 'calle 14 sur cañales 3 frente a la purificadora', NULL, 'Aaron Ramos', 210.00, 'en_camino', NULL, '2026-11-17 07:33:21', 'efectivo', 210.00, 0.00, 0),
(8, 1, NULL, 'ana', '9371281491', 'domicilio', '', 'calle 10 CII al ultima casa mano izq.', NULL, 'Aaron Ramos', 210.00, 'en_camino', NULL, '2026-11-17 07:36:04', 'efectivo', 210.00, 0.00, 0),
(9, 1, NULL, 'nohemi cordova', '9371268924', 'domicilio', '', 'c2 norte CIII casa naranja ', NULL, 'Jesus antonio', 105.00, 'en_camino', NULL, '2026-11-17 08:12:58', 'efectivo', 105.00, 0.00, 0),
(10, 1, NULL, 'maria', '7831266552', 'domicilio', 'ocampo', 'ocampo frente a la carniceria el toron en el callejon en la entrada hay una almendra al fondo', NULL, 'Jesus antonio', 180.00, 'en_camino', NULL, '2026-11-17 08:14:11', 'transferencia', 0.00, 180.00, 0),
(11, 1, NULL, 'ivan', '9370000000', 'domicilio', 'Cañales 1 ', 'cañales 3 sur calle 14 purificadora', NULL, 'Jesus antonio', 180.00, 'en_camino', NULL, '2026-11-17 08:17:26', 'efectivo', 180.00, 0.00, 0),
(12, 1, NULL, 'landy vanesa', '9371013072', 'domicilio', '', 'cañales 2 atras de las rampas ', NULL, 'Sin asignar', 210.00, 'pendiente', NULL, '2026-11-17 08:30:50', 'efectivo', 210.00, 0.00, 0),
(13, 1, NULL, 'madrigal', '9932501471', 'domicilio', '', 'calle 14 sur cañales 3', NULL, 'Sin asignar', 210.00, 'pendiente', NULL, '2026-11-17 08:32:13', 'efectivo', 210.00, 0.00, 0),
(14, 1, NULL, 'fany', '9373856948', 'domicilio', '', 'calle 2 cañales 2 con el mecanico ', NULL, 'Sin asignar', 210.00, 'pendiente', NULL, '2026-11-17 08:34:59', 'efectivo', 210.00, 0.00, 0),
(15, 1, NULL, 'Estela', '9931151252', 'domicilio', 'Cañales', 'Calle melaza m:8 l:12 Col. Los cañales, Cárdenas tabasco', NULL, 'Sin asignar', 180.00, 'cancelado', 'Cancelado por error en mostrador', '2026-11-17 11:16:33', 'efectivo', 180.00, 0.00, 0),
(16, 1, NULL, 'Estela', '9931151252', 'domicilio', 'Cañales', 'Calle melaza m:8 l:12 Col. Los cañales, Cárdenas tabasco', NULL, 'Sin asignar', 180.00, 'cancelado', 'Cancelado por error en mostrador', '2026-11-17 11:16:34', 'efectivo', 180.00, 0.00, 0),
(17, 1, NULL, 'Estela', '9931151252', 'domicilio', 'Cañales', 'Calle melaza m:8 l:12 Col. Los cañales, Cárdenas tabasco', NULL, 'Sin asignar', 180.00, 'cancelado', 'Cancelado por error en mostrador', '2026-11-17 11:16:36', 'efectivo', 180.00, 0.00, 0),
(18, 1, NULL, 'Estela', '9931151252', 'domicilio', 'Cañales', 'Calle melaza m:8 l:12 Col. Los cañales, Cárdenas tabasco', NULL, 'Sin asignar', 180.00, 'cancelado', 'Cancelado por error en mostrador', '2026-11-17 11:23:32', 'efectivo', 180.00, 0.00, 0),
(19, 1, NULL, 'Estela', '9931151252', 'domicilio', 'Cañales', 'Calle melaza m:8 l:12 Col. Los cañales, Cárdenas tabasco', NULL, 'Sin asignar', 180.00, 'cancelado', 'Cancelado por error en mostrador', '2026-11-17 11:23:50', 'efectivo', 180.00, 0.00, 0),
(20, 1, NULL, 'ivan', '9370000000', 'domicilio', 'Cañales 1 ', 'cañales 3 sur calle 14 purificadora', NULL, 'Sin asignar', 180.00, 'cancelado', 'Cancelado por error en mostrador', '2026-11-17 11:24:35', 'efectivo', 180.00, 0.00, 0),
(21, 1, NULL, 'ana', '9371281491', 'domicilio', '', 'calle 10 CII al ultima casa mano izq.', NULL, 'Sin asignar', 180.00, 'cancelado', 'Cancelado por error en mostrador', '2026-11-17 11:25:06', 'efectivo', 180.00, 0.00, 0),
(22, 1, NULL, 'ana', '9371281491', 'domicilio', '', 'calle 10 CII al ultima casa mano izq.', NULL, 'Sin asignar', 180.00, 'cancelado', 'Cancelado por error en mostrador', '2026-11-17 11:25:08', 'efectivo', 180.00, 0.00, 0),
(23, 1, NULL, 'ana', '9371281491', 'domicilio', '', 'calle 10 CII al ultima casa mano izq.', NULL, 'Sin asignar', 180.00, 'cancelado', 'Cancelado por error en mostrador', '2026-11-17 11:25:09', 'efectivo', 180.00, 0.00, 0),
(24, 1, NULL, 'ivan', '9370000000', 'domicilio', 'Cañales 1 ', 'cañales 3 sur calle 14 purificadora', NULL, 'Sin asignar', 180.00, 'cancelado', 'Cancelado por error en mostrador', '2026-11-17 11:25:35', 'efectivo', 180.00, 0.00, 0),
(25, 1, NULL, 'ivan', '9370000000', 'domicilio', 'Cañales 1 ', 'cañales 3 sur calle 14 purificadora', NULL, 'Sin asignar', 180.00, 'cancelado', 'Cancelado por error en mostrador', '2026-11-17 11:25:36', 'efectivo', 180.00, 0.00, 0),
(26, 1, NULL, 'ivan', '9370000000', 'domicilio', 'Cañales 1 ', 'cañales 3 sur calle 14 purificadora', NULL, 'Sin asignar', 180.00, 'cancelado', 'Cancelado por error en mostrador', '2026-11-17 11:25:38', 'efectivo', 180.00, 0.00, 0),
(27, 1, NULL, 'ana', '9371281491', 'domicilio', '', 'calle 10 CII al ultima casa mano izq.', NULL, 'Sin asignar', 210.00, 'cancelado', 'Cancelado por error en mostrador', '2026-11-17 11:27:11', 'efectivo', 210.00, 0.00, 0),
(28, 1, NULL, 'ivan', '9370000000', 'domicilio', 'Cañales 1 ', 'cañales 3 sur calle 14 purificadora', NULL, 'Sin asignar', 210.00, 'cancelado', 'Cancelado por error en mostrador', '2026-11-17 11:28:25', 'efectivo', 210.00, 0.00, 0),
(29, 1, NULL, 'ana', '9371281491', 'domicilio', '', 'calle 10 CII al ultima casa mano izq.', NULL, 'Sin asignar', 210.00, 'cancelado', 'Cancelado por error en mostrador', '2026-11-17 11:31:35', 'efectivo', 210.00, 0.00, 0),
(30, 1, NULL, 'ivan', '9370000000', 'domicilio', 'Cañales 1 ', 'cañales 3 sur calle 14 purificadora', NULL, 'Sin asignar', 210.00, 'cancelado', 'Cancelado por error en mostrador', '2026-11-17 11:31:48', 'efectivo', 210.00, 0.00, 0),
(31, 1, NULL, 'ana', '9371281491', 'domicilio', '', 'calle 10 CII al ultima casa mano izq.', NULL, 'Sin asignar', 210.00, 'cancelado', 'Cancelado por error en mostrador', '2026-11-17 11:33:01', 'efectivo', 210.00, 0.00, 0),
(32, 1, NULL, 'javier muletas', '9371166445', 'domicilio', '', 'calle 7 norte ', NULL, 'Sin asignar', 6.00, 'cancelado', 'Cancelado por error en mostrador', '2026-11-22 10:17:57', 'efectivo', 6.00, 0.00, 0),
(33, 1, NULL, 'ana', '9371281491', 'domicilio', '', 'calle 10 CII al ultima casa mano izq.', NULL, 'Aaron Ramos', 200.00, 'en_camino', NULL, '2026-11-22 10:57:31', 'efectivo', 200.00, 0.00, 0),
(34, 1, NULL, 'Cliente Mostrador', 'N/A', 'tienda', '', 'Recoge en mostrador', NULL, 'N/A', 175.00, 'entregado', NULL, '2026-11-23 07:29:36', 'transferencia', 0.00, 175.00, 1),
(35, 1, NULL, 'nohemi cordova', '9371268924', 'domicilio', '', 'c2 norte CIII casa naranja ', NULL, 'Sin asignar', 1225.00, 'cancelado', 'Cancelado por error en mostrador', '2026-11-23 23:00:12', 'mixto', 0.00, 0.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `procesamiento_detalles`
--

CREATE TABLE `procesamiento_detalles` (
  `id` int(11) NOT NULL,
  `proceso_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad_kg` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `procesamiento_mermas`
--

CREATE TABLE `procesamiento_mermas` (
  `id` int(11) NOT NULL,
  `lote_id` int(11) NOT NULL,
  `piezas_destinadas` int(11) DEFAULT NULL,
  `peso_inicial` decimal(10,2) NOT NULL,
  `peso_util` decimal(10,2) NOT NULL,
  `merma_kg` decimal(10,2) NOT NULL,
  `merma_porc` decimal(10,2) NOT NULL,
  `fecha_proceso` date NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id` int(11) UNSIGNED NOT NULL,
  `nombre_empresa` varchar(120) NOT NULL,
  `contacto` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id`, `nombre_empresa`, `contacto`, `telefono`, `estado`, `created_at`) VALUES
(2, 'Granja El Gallero', 'María López', '9937654321', 0, '2026-10-19 01:05:08'),
(8, 'Avicola Mike', 'Miguel', '9931151252', 0, '2026-10-23 02:59:12'),
(9, 'Avicola', 'Miguel', '9931151252', 0, '2026-10-25 11:25:43'),
(14, 'Avicola prueba', 'Juan Perez', '9931151252', 0, '2026-10-31 12:22:02'),
(15, 'Avicola prueba', 'Juan Perez', '9931151252', 0, NULL),
(16, 'gala', 'juanito ', '9371234566', 1, '2026-11-14 00:00:00'),
(17, 'duran', 'proveedora duran', '91279875467', 1, '2026-11-14 00:00:00'),
(18, 'lira', 'salvador lira', '9821035251', 1, '2026-11-14 00:00:00'),
(19, 'gapesf', 'conta', '9371190670', 1, '2026-11-14 00:00:00'),
(20, 'lupe', 'don lupe ', '9371234576', 1, '2026-11-14 00:00:00'),
(21, 'ibañez', 'conta fausto', '9372978508', 1, '2026-11-14 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `repartidores`
--

CREATE TABLE `repartidores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `vehiculo` varchar(50) DEFAULT 'Motocicleta',
  `estado` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `repartidores`
--

INSERT INTO `repartidores` (`id`, `nombre`, `telefono`, `vehiculo`, `estado`, `created_at`) VALUES
(4, 'Aaron Ramos', '9371553593', 'Motocicleta', 1, '2026-10-31 18:28:02'),
(5, 'Saúl', '9932083767', 'Motocicleta', 1, '2026-10-31 19:50:38'),
(6, 'Jesus antonio', '9372294774', 'Motocicleta', 1, '2026-10-31 19:51:21'),
(7, 'Prueba Miguel', '9931151252', 'Motocicleta', 1, '2026-11-03 17:21:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `settings`
--

CREATE TABLE `settings` (
  `id` int(9) NOT NULL,
  `class` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(31) NOT NULL DEFAULT 'string',
  `context` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `settings`
--

INSERT INTO `settings` (`id`, `class`, `key`, `value`, `type`, `context`, `created_at`, `updated_at`) VALUES
(1, 'App', 'company_name', 'Yireth', 'string', NULL, '2026-11-08 13:42:40', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursales`
--

CREATE TABLE `sucursales` (
  `id` int(11) NOT NULL,
  `nombre_municipio` varchar(150) NOT NULL,
  `direccion` text DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sucursales`
--

INSERT INTO `sucursales` (`id`, `nombre_municipio`, `direccion`, `telefono`, `estado`, `created_at`) VALUES
(1, 'Expendio Centro', 'Av. Principal #100, Centro', '9931234567', 1, '2026-11-08 17:57:14'),
(2, 'Expendio Cárdenas', 'Blvd. Colosio #50, Cárdenas', '9937654321', 1, '2026-11-08 17:57:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `sucursal_id` int(11) UNSIGNED DEFAULT NULL,
  `username` varchar(30) DEFAULT NULL,
  `nip` int(4) NOT NULL,
  `status` varchar(255) DEFAULT NULL,
  `status_message` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `last_active` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `sucursal_id`, `username`, `nip`, `status`, `status_message`, `active`, `last_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(7, 1, 'Prueba Miguel_2', 0, NULL, NULL, 1, NULL, '2026-11-08 10:52:30', '2026-11-11 08:15:01', NULL),
(8, 0, 'Administración Completa', 8545, NULL, NULL, 1, '2026-11-24 06:11:06', '2026-11-08 11:16:40', '2026-11-16 13:52:45', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `auth_groups_users`
--
ALTER TABLE `auth_groups_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auth_groups_users_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `auth_identities`
--
ALTER TABLE `auth_identities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `type_secret` (`type`,`secret`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `auth_logins`
--
ALTER TABLE `auth_logins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_type_identifier` (`id_type`,`identifier`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `auth_permissions_users`
--
ALTER TABLE `auth_permissions_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auth_permissions_users_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `auth_remember_tokens`
--
ALTER TABLE `auth_remember_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `selector` (`selector`),
  ADD KEY `auth_remember_tokens_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `auth_token_logins`
--
ALTER TABLE `auth_token_logins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_type_identifier` (`id_type`,`identifier`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `catalogo_productos`
--
ALTER TABLE `catalogo_productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cierres_diarios`
--
ALTER TABLE `cierres_diarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fecha_cierre` (`fecha_cierre`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `telefono` (`telefono`);

--
-- Indices de la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`);

--
-- Indices de la tabla `gastos_caja`
--
ALTER TABLE `gastos_caja`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `historial_ajustes_almacen`
--
ALTER TABLE `historial_ajustes_almacen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `historial_precios`
--
ALTER TABLE `historial_precios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `inventario_cierre_diario`
--
ALTER TABLE `inventario_cierre_diario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `inventario_sucursal`
--
ALTER TABLE `inventario_sucursal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sucursal_producto` (`sucursal_id`,`producto_id`);

--
-- Indices de la tabla `lotes`
--
ALTER TABLE `lotes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proveedor_id` (`proveedor_id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- Indices de la tabla `procesamiento_detalles`
--
ALTER TABLE `procesamiento_detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proceso_id` (`proceso_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `procesamiento_mermas`
--
ALTER TABLE `procesamiento_mermas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lote_id` (`lote_id`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `repartidores`
--
ALTER TABLE `repartidores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sucursales`
--
ALTER TABLE `sucursales`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `auth_groups_users`
--
ALTER TABLE `auth_groups_users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `auth_identities`
--
ALTER TABLE `auth_identities`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `auth_logins`
--
ALTER TABLE `auth_logins`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de la tabla `auth_permissions_users`
--
ALTER TABLE `auth_permissions_users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `auth_remember_tokens`
--
ALTER TABLE `auth_remember_tokens`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `auth_token_logins`
--
ALTER TABLE `auth_token_logins`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `catalogo_productos`
--
ALTER TABLE `catalogo_productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `cierres_diarios`
--
ALTER TABLE `cierres_diarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT de la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `gastos_caja`
--
ALTER TABLE `gastos_caja`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `historial_ajustes_almacen`
--
ALTER TABLE `historial_ajustes_almacen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_precios`
--
ALTER TABLE `historial_precios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inventario_cierre_diario`
--
ALTER TABLE `inventario_cierre_diario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inventario_sucursal`
--
ALTER TABLE `inventario_sucursal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lotes`
--
ALTER TABLE `lotes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `procesamiento_detalles`
--
ALTER TABLE `procesamiento_detalles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `procesamiento_mermas`
--
ALTER TABLE `procesamiento_mermas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `repartidores`
--
ALTER TABLE `repartidores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `sucursales`
--
ALTER TABLE `sucursales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `auth_groups_users`
--
ALTER TABLE `auth_groups_users`
  ADD CONSTRAINT `auth_groups_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `auth_identities`
--
ALTER TABLE `auth_identities`
  ADD CONSTRAINT `auth_identities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `auth_permissions_users`
--
ALTER TABLE `auth_permissions_users`
  ADD CONSTRAINT `auth_permissions_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `auth_remember_tokens`
--
ALTER TABLE `auth_remember_tokens`
  ADD CONSTRAINT `auth_remember_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  ADD CONSTRAINT `detalle_pedidos_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `historial_ajustes_almacen`
--
ALTER TABLE `historial_ajustes_almacen`
  ADD CONSTRAINT `fk_ajuste_producto` FOREIGN KEY (`producto_id`) REFERENCES `catalogo_productos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `historial_precios`
--
ALTER TABLE `historial_precios`
  ADD CONSTRAINT `historial_precios_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `catalogo_productos` (`id`),
  ADD CONSTRAINT `historial_precios_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `lotes`
--
ALTER TABLE `lotes`
  ADD CONSTRAINT `fk_lotes_proveedor` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `procesamiento_detalles`
--
ALTER TABLE `procesamiento_detalles`
  ADD CONSTRAINT `procesamiento_detalles_ibfk_1` FOREIGN KEY (`proceso_id`) REFERENCES `procesamiento_mermas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `procesamiento_detalles_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `catalogo_productos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `procesamiento_mermas`
--
ALTER TABLE `procesamiento_mermas`
  ADD CONSTRAINT `fk_proceso_lote` FOREIGN KEY (`lote_id`) REFERENCES `lotes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
