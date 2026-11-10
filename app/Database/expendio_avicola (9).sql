-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-11-2026 a las 15:38:54
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
(1, 8, 'superadmin', '2026-11-08 11:16:41'),
(2, 7, 'gerente', '2026-11-08 10:52:31');

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
(5, 7, 'email_password', NULL, 'inemiketec@gmail.com', '$2y$12$XvHad322/bZOv7dOAAaireDlKuPBhOs3gWa2ni9xaC3yROUpV7RPe', NULL, NULL, 0, '2026-11-08 10:55:00', '2026-11-08 10:52:30', '2026-11-08 10:55:00'),
(6, 8, 'email_password', NULL, 'administracion@expendio-de-gallinas-yireth.com', '$2y$12$kbPOJopj2DQd2pG1bh1lfuJwZfWQr2Iesn49bW9XN3y7M5Lihk2Oe', NULL, NULL, 0, '2026-11-10 05:59:28', '2026-11-08 11:16:40', '2026-11-10 05:59:28');

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
(13, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'email_password', 'administracion@expendio-de-gallinas-yireth.com', 8, '2026-11-10 05:59:28', 1);

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
(1, 'Pollo Entero', 'piezas', 145.00, 1, '2026-10-19 01:02:25'),
(2, 'Gallina', 'piezas', 230.00, 1, '2026-10-19 01:02:25'),
(3, 'Alitas Naturales', 'kilos', 130.00, 1, '2026-10-19 01:02:25'),
(4, 'Alitas Adobadas', 'kilos', 140.00, 1, '2026-10-19 01:02:25'),
(5, 'Milanesa Natural', 'kilos', 130.00, 0, '2026-10-19 01:02:25'),
(6, 'Milanesa Marinada', 'kilos', 140.00, 0, '2026-10-19 01:02:25'),
(7, 'Milanesa Empanizada', 'kilos', 150.00, 0, '2026-10-19 01:02:25'),
(10, 'Menudencia', 'kilos', 95.00, 1, '2026-10-23 22:18:47'),
(11, 'Milanesas Empanizadas', 'kilos', 150.00, 1, '2026-10-25 22:20:04'),
(12, 'Milanesas Marinadas', 'kilos', 140.00, 1, '2026-10-25 22:20:04'),
(13, 'Milanesas Naturales', 'kilos', 130.00, 1, '2026-10-25 22:20:04'),
(14, 'Piernas y muslos', 'kilos', 45.00, 1, '2026-10-25 22:20:04'),
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
(5, '9371249863', 'Jose Aarón', 'domicilio', 'Cañales 1 ', 'Av. Cañaveral ', 'A un costado de la iglesia católica portón blanco ', '2026-11-01 12:08:24'),
(6, '9371210294', 'Nanci', 'domicilio', '', 'Cañales 3ra etapaa', 'Calle 11 norte casa azul ', '2026-11-01 13:52:53'),
(7, '9371123456', 'Angela Hernández', 'domicilio', 'Cañales 1', 'Alameda ', 'Calle 2 ', '2026-11-01 13:54:01'),
(8, '9376157876', 'Chepy mora', 'domicilio', '', 'Progreso ', 'Casa verde', '2026-11-01 13:54:37'),
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
(35, '9371030975', 'abarrotes nachito', 'domicilio', 'ocampo', 'ocmapo abarrotes nachito despues del pollo viajero', 'ocampo abarrotes nachito despues del pollo viajero', '2026-11-02 23:00:44');

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
(1, 1, 'Pollo Entero', 3.00, 145.00, 435.00),
(2, 2, 'Pollo Entero', 1.00, 145.00, 145.00),
(3, 2, 'Pollo Entero', 1.00, 145.00, 145.00),
(4, 3, 'Pollo Entero', 1.00, 145.00, 145.00),
(5, 4, 'Pollo Entero', 1.00, 145.00, 145.00),
(6, 4, 'Pollo Entero', 1.00, 145.00, 145.00),
(7, 5, 'Pollo Entero', 1.00, 145.00, 145.00),
(8, 6, 'Pollo Entero', 1.00, 145.00, 145.00),
(9, 7, 'Pollo Entero', 1.00, 145.00, 145.00),
(10, 8, 'Pollo Entero', 1.00, 145.00, 145.00),
(11, 9, 'Pollo Entero', 1.00, 145.00, 145.00),
(12, 10, 'Pollo Entero', 1.00, 145.00, 145.00),
(13, 11, 'Pollo Entero', 3.00, 145.00, 435.00),
(14, 12, 'Pollo Entero', 5.00, 145.00, 725.00),
(15, 13, 'Pollo Entero', 1.00, 145.00, 145.00),
(16, 13, 'Alitas Naturales', 3.50, 130.00, 455.00),
(17, 14, 'Pollo Entero', 1.00, 145.00, 145.00),
(18, 15, 'Pollo Entero', 1.00, 145.00, 145.00),
(19, 16, 'Pollo Entero', 1.00, 145.00, 145.00),
(20, 16, 'Pollo Entero', 1.00, 145.00, 145.00);

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
  `comprobante` varchar(255) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `fecha_recepcion` date NOT NULL,
  `estado` varchar(50) DEFAULT 'disponible',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `lotes`
--

INSERT INTO `lotes` (`id`, `sucursal_id`, `proveedor_id`, `tipo_ave`, `jaulas`, `piezas_por_jaula`, `piezas`, `peso`, `costo_unitario`, `comprobante`, `observaciones`, `fecha_recepcion`, `estado`, `created_at`) VALUES
(1, 1, 2, 'pollo', 0, 0, 192, 441.60, 120.00, NULL, NULL, '2026-11-08', 'disponible', '2026-11-08 21:30:09'),
(2, 2, 8, 'pollo', 0, 0, 168, 537.60, 125.00, NULL, NULL, '2026-11-08', 'disponible', '2026-11-08 21:30:54');

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
  `created_at` datetime DEFAULT current_timestamp(),
  `tipo_pago` varchar(50) DEFAULT NULL,
  `pago_verificado` tinyint(4) DEFAULT 0 COMMENT '0: Pendiente de verificar, 1: Verificado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `sucursal_id`, `cliente_id`, `cliente`, `telefono`, `tipo_entrega`, `colonia`, `direccion`, `referencia`, `repartidor`, `total`, `estado`, `created_at`, `tipo_pago`, `pago_verificado`) VALUES
(1, 1, NULL, 'Martha elena', '9372839572', 'tienda', 'Progreso', 'Calle Gregorio Méndez ', NULL, 'N/A', 435.00, 'entregado', '2026-11-09 21:26:46', 'transferencia', 1),
(2, 1, NULL, 'johana amiga moto negra', '9371009983', 'domicilio', 'cañales 2 sur', 'cañales 2 sur calle 5 casa rosa frente al spa', NULL, 'Prueba Miguel', 290.00, 'entregado', '2026-11-09 21:27:44', 'efectivo', 0),
(3, 1, NULL, 'abarrotes nachito', '9371030975', 'domicilio', 'ocampo', 'ocmapo abarrotes nachito despues del pollo viajero', NULL, 'Prueba Miguel', 145.00, 'entregado', '2026-11-09 21:29:01', 'transferencia', 1),
(4, 1, NULL, 'genoveva', '9371105579', 'domicilio', 'cañales 2 sur', 'cañales 2 sur calle 11 casa amarilla frente a la casa roja de dos plantas', NULL, 'Aaron Ramos', 290.00, 'entregado', '2026-11-09 21:39:57', 'transferencia', 1),
(5, 1, NULL, 'Estela', '9931151252', 'domicilio', 'Cañales', 'Calle melaza m:8 l:12 Col. Los cañales, Cárdenas tabasco', NULL, 'Aaron Ramos', 145.00, 'entregado', '2026-11-09 21:41:47', 'efectivo', 0),
(6, 1, NULL, 'Estela', '9931151252', 'domicilio', 'Cañales', 'Calle melaza m:8 l:12 Col. Los cañales, Cárdenas tabasco', NULL, 'Aaron Ramos', 145.00, 'entregado', '2026-11-09 21:42:52', 'efectivo', 0),
(7, 1, NULL, 'Estela', '9931151252', 'domicilio', 'Cañales', 'Calle melaza m:8 l:12 Col. Los cañales, Cárdenas tabasco', NULL, 'Aaron Ramos', 145.00, 'entregado', '2026-11-09 21:44:02', 'efectivo', 0),
(8, 1, NULL, 'Estela', '9931151252', 'domicilio', 'Cañales', 'Calle melaza m:8 l:12 Col. Los cañales, Cárdenas tabasco', NULL, 'Aaron Ramos', 145.00, 'entregado', '2026-11-09 21:44:09', 'efectivo', 0),
(9, 1, NULL, 'Estela', '9931151252', 'domicilio', 'Cañales', 'Calle melaza m:8 l:12 Col. Los cañales, Cárdenas tabasco', NULL, 'Aaron Ramos', 145.00, 'entregado', '2026-11-09 22:18:52', 'efectivo', 0),
(10, 1, NULL, 'Estela', '9931151252', 'domicilio', 'Cañales', 'Calle melaza m:8 l:12 Col. Los cañales, Cárdenas tabasco', NULL, 'Aaron Ramos', 145.00, 'entregado', '2026-11-09 22:19:26', 'efectivo', 0),
(11, 1, NULL, 'abarrotes nachito', '9371030975', 'domicilio', 'ocampo', 'ocmapo abarrotes nachito despues del pollo viajero', NULL, 'Sin asignar', 435.00, 'pendiente', '2026-11-09 22:23:10', 'transferencia', 1),
(12, 1, NULL, 'erika ruiz ramos prima de adriana', '9371060961', 'domicilio', 'cañales 2 sur', 'cañales 2 sur calle 8 la calle del gym al fondo ultima casa mano derecha', NULL, 'Sin asignar', 725.00, 'pendiente', '2026-11-09 22:23:42', 'efectivo', 0),
(13, 1, NULL, 'Angela Hernández', '9371123456', 'domicilio', 'Cañales 1', 'Alameda ', NULL, 'Sin asignar', 600.00, 'pendiente', '2026-11-09 22:24:31', 'efectivo', 0),
(14, 1, NULL, 'itzia', '9371220299', 'domicilio', 'cañales 3 sur', 'cañales 3 sur calle 4 entrando a mano izquierda segunda casa barda alta hay un carrito rojo afuera', NULL, 'Aaron Ramos', 145.00, 'entregado', '2026-11-09 22:29:21', 'efectivo', 0),
(15, 1, NULL, 'Maria estrada', '9376186578', 'domicilio', 'Cañales 1', 'Calle xd la guardia nacional casa azul', NULL, 'Sin asignar', 145.00, 'pendiente', '2026-11-09 22:57:17', 'efectivo', 0),
(16, 2, NULL, 'Anareli mendez', '9371178658', 'domicilio', 'Cañales 3ra sur', '', NULL, 'Sin asignar', 290.00, 'pendiente', '2026-11-09 22:58:48', 'efectivo', 0);

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

--
-- Volcado de datos para la tabla `procesamiento_detalles`
--

INSERT INTO `procesamiento_detalles` (`id`, `proceso_id`, `producto_id`, `cantidad_kg`) VALUES
(1, 1, 3, 20.00),
(2, 1, 4, 20.00),
(3, 1, 14, 30.00),
(4, 2, 3, 10.00),
(5, 2, 4, 10.00),
(6, 2, 11, 15.00),
(7, 2, 12, 10.00);

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

--
-- Volcado de datos para la tabla `procesamiento_mermas`
--

INSERT INTO `procesamiento_mermas` (`id`, `lote_id`, `piezas_destinadas`, `peso_inicial`, `peso_util`, `merma_kg`, `merma_porc`, `fecha_proceso`, `created_at`) VALUES
(1, 2, 28, 89.60, 70.00, 19.60, 21.88, '2026-11-08', '2026-11-08 21:31:42'),
(2, 1, 22, 50.60, 45.00, 5.60, 11.07, '2026-11-08', '2026-11-08 21:33:16');

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
(2, 'Granja El Gallero', 'María López', '9937654321', 1, '2026-10-19 01:05:08'),
(8, 'Avicola Mike', 'Miguel', '9931151252', 1, '2026-10-23 02:59:12'),
(9, 'Avicola', 'Miguel', '9931151252', 1, '2026-10-25 11:25:43'),
(14, 'Avicola prueba', 'Juan Perez', '9931151252', 0, '2026-10-31 12:22:02'),
(15, 'Avicola prueba', 'Juan Perez', '9931151252', 0, NULL);

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
  `username` varchar(30) DEFAULT NULL,
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

INSERT INTO `users` (`id`, `username`, `status`, `status_message`, `active`, `last_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(7, 'Prueba Miguel', NULL, NULL, 1, NULL, '2026-11-08 10:52:30', '2026-11-08 10:52:31', NULL),
(8, 'Administración Completa', NULL, NULL, 1, NULL, '2026-11-08 11:16:40', '2026-11-08 11:16:41', NULL);

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
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `auth_identities`
--
ALTER TABLE `auth_identities`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `auth_logins`
--
ALTER TABLE `auth_logins`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `auth_permissions_users`
--
ALTER TABLE `auth_permissions_users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `auth_remember_tokens`
--
ALTER TABLE `auth_remember_tokens`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `auth_token_logins`
--
ALTER TABLE `auth_token_logins`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `catalogo_productos`
--
ALTER TABLE `catalogo_productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `cierres_diarios`
--
ALTER TABLE `cierres_diarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `gastos_caja`
--
ALTER TABLE `gastos_caja`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT de la tabla `lotes`
--
ALTER TABLE `lotes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `procesamiento_detalles`
--
ALTER TABLE `procesamiento_detalles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `procesamiento_mermas`
--
ALTER TABLE `procesamiento_mermas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
