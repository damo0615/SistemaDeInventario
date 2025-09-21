-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-09-2025 a las 06:05:18
-- Versión del servidor: 10.1.37-MariaDB
-- Versión de PHP: 7.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `inventario_mercamix`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora`
--

CREATE TABLE `bitacora` (
  `id` int(11) NOT NULL,
  `accion` varchar(100) DEFAULT NULL,
  `fecha_accion` datetime DEFAULT CURRENT_TIMESTAMP,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombrec` varchar(50) NOT NULL,
  `codigo` int(11) NOT NULL,
  `dni` varchar(10) NOT NULL,
  `telefono` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra`
--

CREATE TABLE `compra` (
  `id` int(11) NOT NULL,
  `item` int(4) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_cliente` int(11) NOT NULL,
  `total` float(9,2) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_compra`
--

CREATE TABLE `detalles_compra` (
  `id` int(11) NOT NULL,
  `id_compra` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `cantidad` int(3) DEFAULT NULL,
  `precio_unitario` float(7,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE `inventario` (
  `id` int(11) NOT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos_inventario`
--

CREATE TABLE `movimientos_inventario` (
  `id` int(11) NOT NULL,
  `id_inv` int(11) DEFAULT NULL,
  `tipo` enum('A','D','V') DEFAULT NULL,
  `cantidad` int(3) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `cantidad_actual` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(30) DEFAULT NULL,
  `create_user` tinyint(1) DEFAULT NULL,
  `update_user` tinyint(1) DEFAULT NULL,
  `delete_user` tinyint(1) DEFAULT NULL,
  `read_mov` tinyint(1) DEFAULT '1',
  `create_mov` tinyint(1) DEFAULT NULL,
  `update_move` tinyint(1) DEFAULT NULL,
  `delete_mov` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id`, `nombre`, `create_user`, `update_user`, `delete_user`, `read_mov`, `create_mov`, `update_move`, `delete_mov`) VALUES
(1, 'Administrador', 1, 1, 1, 1, 1, 1, 1),
(2, 'Supervisor', 0, 0, 0, 1, 1, 1, 0),
(3, 'Operador', 0, 0, 0, 1, 1, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id` int(11) NOT NULL,
  `nombre` varchar(25) DEFAULT NULL,
  `codigo` varchar(25) DEFAULT NULL,
  `precio` float(7,2) DEFAULT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  `id_tag` int(11) DEFAULT NULL,
  `descripcion` text NOT NULL,
  `stock` int(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `id` int(11) NOT NULL,
  `nombrep` varchar(50) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `codigop` varchar(25) DEFAULT NULL,
  `observacion` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`id`, `nombrep`, `direccion`, `codigop`, `observacion`) VALUES
(1, 'Kamila', 'Caracas, Dtto. Capital', '000001', 'Ropa interior, deportiva, del hogar Y accesorios'),
(2, 'Wilson', 'Mercado Viejo de Coro', '00002', 'es un proveedor nuevo, solo es test');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tag`
--

CREATE TABLE `tag` (
  `id` int(11) NOT NULL,
  `nombres` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tag`
--

INSERT INTO `tag` (`id`, `nombres`) VALUES
(3, 'Caballero'),
(5, 'Dama');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nombres` varchar(30) DEFAULT NULL,
  `dni` varchar(11) DEFAULT NULL,
  `username` varchar(25) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(125) DEFAULT NULL,
  `id_permiso` int(11) DEFAULT NULL,
  `estatus` tinyint(1) NOT NULL DEFAULT '0',
  `pregunta1` varchar(150) NOT NULL,
  `respuesta1` text NOT NULL,
  `pregunta2` varchar(150) NOT NULL,
  `respuesta2` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `nombres`, `dni`, `username`, `email`, `password`, `id_permiso`, `estatus`, `pregunta1`, `respuesta1`, `pregunta2`, `respuesta2`) VALUES
(11, 'Caballero', '32150150', 'Loquesea', 'realdo@gmail.com', '$2y$12$WY6QGzlUYLyg8D.a5hcNeuDqfY.nDZIPfSO4lAryNUxsFHZSWb6p.', 1, 1, '$2y$12$tmatq.FryHTpf85mXn206exFtesN0kYc3AhvbKL89ecy6whIxm2wK', '$2y$12$pU6.QZp3oKNMd9Ge3HS4O.wy6.5FmetQgZj4Cuxt4tAgAdvZaQihy', '$2y$12$/A.yNhqetCXh6Fb4.6jbKO2oZ6w5U.wQZFiE2APjAaZZgrSxfmUea', '$2y$12$IRyPcVgt49yxRa.6oHN9se7BCntfr8JKCY8Jk1Ipq3kfGUgEl7LCC'),
(12, 'Prueba', '126123450', 'operador', 'test@test.com', '$2y$12$7CL1Xz5fcvsgEMfG5EUYVePLujpXghDirT.7JwqdRk0F8P5/EMuIK', 3, 1, '$2y$12$bJRf9sdBKiIfzwxAF4BTp.qSmxw4VrS68M2BDWQ86Gkl7VvRbR0qa', '$2y$12$6bKQ8je09Ec/BKFEzQ2ncuVpSsU4mCJDa9Zl9q33Nc/75WV/5Je.q', '$2y$12$4hs0R7EFkzh72wGP1Wp0muxaMTwux7i8Re8kCd/KT3qi.sUyd6iZq', '$2y$12$iXYxR4Ql9X2BnKONCrJXueeMRnk6ZGilpbc.slVT25rN17.5aaDlG');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `DNI` (`dni`),
  ADD UNIQUE KEY `CODIGO` (`codigo`) USING BTREE;

--
-- Indices de la tabla `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalles_compra`
--
ALTER TABLE `detalles_compra`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `etiqueta` (`id_tag`),
  ADD KEY `proveedores` (`id_proveedor`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `compra`
--
ALTER TABLE `compra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalles_compra`
--
ALTER TABLE `detalles_compra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inventario`
--
ALTER TABLE `inventario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tag`
--
ALTER TABLE `tag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `etiqueta` FOREIGN KEY (`id_tag`) REFERENCES `tag` (`id`),
  ADD CONSTRAINT `proveedores` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
