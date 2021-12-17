-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-12-2021 a las 12:38:50
-- Versión del servidor: 10.4.21-MariaDB
-- Versión de PHP: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `symfoplaces`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `texto` varchar(4096) COLLATE utf8_unicode_ci DEFAULT NULL,
  `place_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `comment`
--

INSERT INTO `comment` (`id`, `texto`, `place_id`, `user_id`) VALUES
(1, 'Un lugar muy bonito de noche.', 1, 2),
(2, 'Es genial estar allí.', 1, 2),
(3, 'Moscú, que bonito.', 5, 5),
(4, 'Después de Moscú, visitaré Barcelona.', 1, 5),
(5, 'Siempre es bueno pasear por allí cuando estás estresado.', 1, 3),
(9, 'Yo fui allí la semana pasada.', 5, 4),
(10, 'Dicen que París tiene una hermosa vista.', 2, 2),
(12, 'Este comentario se eliminará?', 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20211201085049', '2021-12-01 09:51:07', 43),
('DoctrineMigrations\\Version20211201085827', '2021-12-01 09:58:43', 149),
('DoctrineMigrations\\Version20211207082346', '2021-12-07 09:24:05', 25),
('DoctrineMigrations\\Version20211207085037', '2021-12-07 09:50:48', 74),
('DoctrineMigrations\\Version20211209092649', '2021-12-09 10:26:59', 60);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `photo`
--

CREATE TABLE `photo` (
  `id` int(11) NOT NULL,
  `ruta` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `descripcion` varchar(4096) COLLATE utf8_unicode_ci DEFAULT NULL,
  `titulo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `place_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `photo`
--

INSERT INTO `photo` (`id`, `ruta`, `descripcion`, `titulo`, `fecha`, `place_id`) VALUES
(1, '61a8b204b48a9.jpg', 'Foto de la ciudad', 'Visitando Barcelona', '2021-12-01', 1),
(6, '61a9dd1713b9e.jpg', 'Playa de Barcelona', 'Un día en la playa de Barcelona', '2021-12-03', 1),
(7, '61a9dff43bb3a.jpg', 'Vista del parque y de la torre eiffel', 'Día 2 visitando París', '2020-12-02', 2),
(8, '61a9e03cd252e.jpg', 'Un día paseando por la ciudad de París, pero en la noche', 'París de noche', '2020-12-03', 2),
(9, '61a9f2b27e7d3.jpg', 'En ese día fuimos a pasear en bote, estuvo genial', 'Típico día en Venecia', '2021-01-03', 3),
(10, '61a9e1e4b3a22.jpg', 'El mismo día que fuimos a pasear en bote nos quedamos hasta la noche', 'Atardecer en Venecia', '2021-01-03', 3),
(11, '61a9f3c8d9cc5.jpg', 'Hoy solamente caminamos por los alrededores. Un día muy bonito la verdad.', 'Segundo día en Venecia', '2021-01-04', 3),
(12, '61b1e2898c3b1.jpg', 'Visitando la ciudad en la noche, se ve muy bonito', 'Visitando Lima', '2021-01-10', 4),
(13, '61af4263a3349.jpg', 'Visitando lo mejor de Rusia casi en la noche', 'Moscú en todo su esplendor', '2021-01-15', 5),
(14, '61a9fc30ecdff.jpg', 'Hoy nos tomamos una foto frente a esta bonita fuente', 'Un día en la famosa capital de España', '2021-01-16', 6),
(15, '61a9fd4cd7f9f.jpg', 'Lugar al que todos deben ir si quieren comprar recuerdos que les hagan recordar que fueron a Francia.', 'Visitando un lugar turístico', '2021-01-17', 7),
(16, '61a9fdd7b8284.jpg', 'Hospedándonos en el hotel california por una noche', 'Hotel California', '2021-01-18', 8),
(17, '61a9fe44d3629.jpg', 'Un día paseando en este hermoso parque de la ciudad de México', 'Parque de la ciudad de México', '2021-01-25', 9),
(19, '61ae3a7dd53a6.jpg', 'Visitando un fin de semana la Plaza Gaudi', 'Plaza Gaudi', '2021-12-05', 1),
(20, '61af2c6f9909f.jpg', 'Visitando una de las siete maravillas del mundo', 'Machu Picchu - Hogar de los Incas', '2021-12-29', 11),
(21, '61b1e2a4c2a17.jpg', 'Una noche visitando el parque de las aguas.', 'Parque de las aguas', '2021-12-09', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `place`
--

CREATE TABLE `place` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `descripcion` varchar(4096) COLLATE utf8_unicode_ci DEFAULT NULL,
  `valoracion` int(11) DEFAULT NULL,
  `pais` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `place`
--

INSERT INTO `place` (`id`, `nombre`, `descripcion`, `valoracion`, `pais`, `tipo`, `user_id`) VALUES
(1, 'Barcelona', 'Aquí está el Camp Nou', 4, 'España', 'Ciudad', 5),
(2, 'París', 'Aquí está la torre eiffel', 5, 'Francia', 'Ciudad', 4),
(3, 'Venecia', 'Ciudad donde te pasean en barquito', 5, 'Italia', 'Ciudad', 3),
(4, 'Lima', 'Capital de Perú', 5, 'Perú', 'Ciudad', 2),
(5, 'Moscú', 'Capital de Rusia', 4, 'Rusia', 'Ciudad', 5),
(6, 'Madrid', 'Capital de España', 4, 'España', 'Ciudad', 3),
(7, 'Carcasona', 'Un buen lugar para comprar cosas que te hagan recordar Francia.', 3, 'Francia', 'Lugar turístico', 4),
(8, 'California', 'Allí se encuentra el hotel california', 4, 'USA', 'Ciudad', 3),
(9, 'Ciudad de México', 'Allí hay muchos mexicanos', 4, 'México', 'Ciudad', 5),
(11, 'Machu Picchu', 'Lugar donde moraban los antiguos incas', 5, 'Perú', 'Lugar turístico', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reset_password_request`
--

CREATE TABLE `reset_password_request` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `selector` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `hashed_token` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `requested_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `expires_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `imagen` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `poblacion` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telefono` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `imagen`, `nombre`, `poblacion`, `telefono`, `is_verified`) VALUES
(2, 'kevinlarriega@gmail.com', '[\"ROLE_USER\",\"ROLE_ADMIN\"]', '$2y$13$i8DE/5JX8fIzYRsjoIf7Wexwhd5RnucMF8yIi8jw9cPHneDjIzVoW', '61af33f53a2c6.jpg', 'Kevin', 'Barcelona', '677159826', 1),
(3, 'kevineditor@gmail.com', '[\"ROLE_USER\",\"ROLE_EDITOR\"]', '$2y$13$jKvjwF4lpsO0YolqyKGurel/ic3Snec2hxbwTEi8d7y32WNhCFl8i', '61af341c9c30c.jpg', 'KevinEditor', 'Barcelona', '123456789', 1),
(4, 'kevinsupervisor@gmail.com', '[\"ROLE_USER\",\"ROLE_SUPERVISOR\"]', '$2y$13$0gjVl8YoPsdixezY6DGZOuc4HA51Uv2MsTJPxSolRVVRwqoMpSvu2', '61af3435e5103.jpg', 'KevinSupervisor', 'Barcelona', '987654321', 1),
(5, 'kevinuser@gmail.com', '[]', '$2y$13$/hqxqycgRBuyi.RRYoTdlOYcZb/w/NMOg3NYFwcc0ZULd/6C/Zpii', '61af34505646a.jpg', 'KevinUser', 'Barcelona', '111111111', 1),
(10, 'kevinuser2@gmail.com', '[]', '$2y$13$WtL0SAz9EFkBmI1d29P25evv5ZmRdcNSL73w6vHb.9Xf15hSUIEkG', '61b1e2ee8dfa4.jpg', 'KevinUser2', 'Barcelona', '222222222', 1),
(11, 'kevinuser3@gmail.com', '[]', '$2y$13$a1XVGrL8ymhAqgaI6OTXMuJXSBs6d4wBsz1DucKW0EwF1ztebiY6m', '61b1e35914efa.jpg', 'KevinUser3', 'Barcelona', '333333333', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_9474526CDA6A219` (`place_id`),
  ADD KEY `IDX_9474526CA76ED395` (`user_id`);

--
-- Indices de la tabla `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indices de la tabla `photo`
--
ALTER TABLE `photo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_14B78418DA6A219` (`place_id`);

--
-- Indices de la tabla `place`
--
ALTER TABLE `place`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_741D53CDA76ED395` (`user_id`);

--
-- Indices de la tabla `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_7CE748AA76ED395` (`user_id`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `photo`
--
ALTER TABLE `photo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `place`
--
ALTER TABLE `place`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `reset_password_request`
--
ALTER TABLE `reset_password_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `FK_9474526CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_9474526CDA6A219` FOREIGN KEY (`place_id`) REFERENCES `place` (`id`);

--
-- Filtros para la tabla `photo`
--
ALTER TABLE `photo`
  ADD CONSTRAINT `FK_14B78418DA6A219` FOREIGN KEY (`place_id`) REFERENCES `place` (`id`);

--
-- Filtros para la tabla `place`
--
ALTER TABLE `place`
  ADD CONSTRAINT `FK_741D53CDA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Filtros para la tabla `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD CONSTRAINT `FK_7CE748AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
