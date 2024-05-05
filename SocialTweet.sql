-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 05-05-2024 a las 20:06:06
-- Versión del servidor: 8.0.36-0ubuntu0.22.04.1
-- Versión de PHP: 8.1.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `SocialTweet`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Guardados`
--

CREATE TABLE `Guardados` (
  `idguardado` int NOT NULL,
  `idpublicacion` int DEFAULT NULL,
  `idusuario` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Megustas`
--

CREATE TABLE `Megustas` (
  `idmg` int NOT NULL,
  `idpublicacion` int DEFAULT NULL,
  `idusuario` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Mensajes`
--

CREATE TABLE `Mensajes` (
  `idmensaje` int NOT NULL,
  `mensaje` text COLLATE utf8mb4_spanish_ci,
  `idpublicacion` int DEFAULT NULL,
  `idusuario` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Publicaciones`
--

CREATE TABLE `Publicaciones` (
  `idpublicacion` int NOT NULL,
  `fecha` date DEFAULT NULL,
  `mensaje` text COLLATE utf8mb4_spanish_ci,
  `idusuario` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Usuarios`
--

CREATE TABLE `Usuarios` (
  `idusuario` int NOT NULL,
  `apellidos` varchar(255) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `localidad` varchar(255) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `nombreusuario` varchar(255) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `rol` varchar(50) COLLATE utf8mb4_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `Guardados`
--
ALTER TABLE `Guardados`
  ADD PRIMARY KEY (`idguardado`),
  ADD KEY `idpublicacion` (`idpublicacion`),
  ADD KEY `idusuario` (`idusuario`);

--
-- Indices de la tabla `Megustas`
--
ALTER TABLE `Megustas`
  ADD PRIMARY KEY (`idmg`),
  ADD KEY `idpublicacion` (`idpublicacion`),
  ADD KEY `idusuario` (`idusuario`);

--
-- Indices de la tabla `Mensajes`
--
ALTER TABLE `Mensajes`
  ADD PRIMARY KEY (`idmensaje`),
  ADD KEY `idpublicacion` (`idpublicacion`),
  ADD KEY `idusuario` (`idusuario`);

--
-- Indices de la tabla `Publicaciones`
--
ALTER TABLE `Publicaciones`
  ADD PRIMARY KEY (`idpublicacion`),
  ADD KEY `idusuario` (`idusuario`);

--
-- Indices de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  ADD PRIMARY KEY (`idusuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `Guardados`
--
ALTER TABLE `Guardados`
  MODIFY `idguardado` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Megustas`
--
ALTER TABLE `Megustas`
  MODIFY `idmg` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Mensajes`
--
ALTER TABLE `Mensajes`
  MODIFY `idmensaje` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Publicaciones`
--
ALTER TABLE `Publicaciones`
  MODIFY `idpublicacion` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  MODIFY `idusuario` int NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `Guardados`
--
ALTER TABLE `Guardados`
  ADD CONSTRAINT `Guardados_ibfk_1` FOREIGN KEY (`idpublicacion`) REFERENCES `Publicaciones` (`idpublicacion`),
  ADD CONSTRAINT `Guardados_ibfk_2` FOREIGN KEY (`idusuario`) REFERENCES `Usuarios` (`idusuario`);

--
-- Filtros para la tabla `Megustas`
--
ALTER TABLE `Megustas`
  ADD CONSTRAINT `Megustas_ibfk_1` FOREIGN KEY (`idpublicacion`) REFERENCES `Publicaciones` (`idpublicacion`),
  ADD CONSTRAINT `Megustas_ibfk_2` FOREIGN KEY (`idusuario`) REFERENCES `Usuarios` (`idusuario`);

--
-- Filtros para la tabla `Mensajes`
--
ALTER TABLE `Mensajes`
  ADD CONSTRAINT `Mensajes_ibfk_1` FOREIGN KEY (`idpublicacion`) REFERENCES `Publicaciones` (`idpublicacion`),
  ADD CONSTRAINT `Mensajes_ibfk_2` FOREIGN KEY (`idusuario`) REFERENCES `Usuarios` (`idusuario`);

--
-- Filtros para la tabla `Publicaciones`
--
ALTER TABLE `Publicaciones`
  ADD CONSTRAINT `Publicaciones_ibfk_1` FOREIGN KEY (`idusuario`) REFERENCES `Usuarios` (`idusuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
