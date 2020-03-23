-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 21-03-2020 a las 11:37:53
-- Versión del servidor: 5.7.26
-- Versión de PHP: 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `blog`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `passwordHash` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `registeredAt` datetime NOT NULL,
  `lastLogin` datetime DEFAULT NULL,
  `intro` tinytext,
  `profile` text,
  `avatar` varchar(255) DEFAULT NULL,
  `role` tinyint(4) NOT NULL,
  `status` int(1) DEFAULT '0',
  `updatedAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id`, `firstname`, `lastname`, `email`, `passwordHash`, `phone`, `username`, `registeredAt`, `lastLogin`, `intro`, `profile`, `avatar`, `role`, `status`, `updatedAt`) VALUES
(14, 'corona', 'virus', 'corona@mail.fr', '$2y$10$c3fyRDykKPOtuULyNsNpouUuv7PWAcU0GspK2/SerNVzgu4kMypUq', '12345', 'coronavirus', '2020-03-09 00:00:00', '2020-03-21 11:07:42', 'fadsfadfad', 'fadfadfadfadf', 'corona.png', 3, 1, '2020-03-21 11:29:47'),
(15, 'heik', 'neken', 'heineken@mail.com', '$2y$10$aMFeWFfnBxjgcLGKrWDOgemP4zNbEFVvz5TLjX6DFS.NEevCYkMC.', '123454345335435', 'hein', '2020-03-09 00:00:00', NULL, 'dfdsf', 'fadfad', 'heineken.png', 1, 1, '2020-03-21 11:29:47'),
(16, 'linux', 'ubuntu', 'linux@mail.fr', '$2y$10$OVfOLSP8TVdayM6Z3m7LSuvguFqzgFxjqKVRQbc0CkV8P2yYw0tem', '111111111111', 'linux', '2020-03-09 00:00:00', '2020-03-09 15:09:54', 'dfadf<br> <span> &  <php? fadlfadkjfl <?= \'hello\'?>', '                        dfadf<br> <span> &  <php? fadlfadkjfl      dfadf<br> <span> &  <php? fadlfadkjfl      dfadf<br> <span> &  <php? fadlfadkjfl      dfadf<br> <span> &  <php? fadlfadkjfl ', 'icons8-linux-48.png', 1, 1, '2020-03-21 11:29:47'),
(17, 'clau', 'hexe', 'clauhexe@mail.fr', '$2y$10$8aHpUkP8VV5KlvtlamK.qOLB7qplWlKDsptbyq9IlJneb6WhEKlv2', '1234567891011', 'clauhexe', '2020-03-18 00:00:00', NULL, '', '', NULL, 1, 1, '2020-03-21 11:29:47');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
