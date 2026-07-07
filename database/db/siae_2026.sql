-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-07-2026 a las 18:16:02
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
-- Base de datos: `siae_2026`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnos`
--

CREATE TABLE `alumnos` (
  `id` int(11) NOT NULL,
  `persona_id` int(11) NOT NULL,
  `codigo_matricula` varchar(20) NOT NULL,
  `tipo_sangre` varchar(5) DEFAULT NULL,
  `alergias` text DEFAULT NULL,
  `discapacidad` tinyint(1) DEFAULT 0,
  `porcentaje_discapacidad` decimal(5,2) DEFAULT NULL,
  `carnet_conadis` varchar(30) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `alumnos`
--

INSERT INTO `alumnos` (`id`, `persona_id`, `codigo_matricula`, `tipo_sangre`, `alergias`, `discapacidad`, `porcentaje_discapacidad`, `carnet_conadis`, `observaciones`, `estado`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, 'MAT-2026-0001', 'O+', NULL, 0, NULL, NULL, 'Alumno base registrado automáticamente por el sistema de semillas.', 1, '2026-07-07 16:07:34', '2026-07-07 16:07:34', NULL),
(2, 3, 'MAT-2026-0002', 'O+', NULL, 0, NULL, NULL, 'Alumno base registrado automáticamente por el sistema de semillas.', 1, '2026-07-07 16:07:34', '2026-07-07 16:07:34', NULL),
(3, 4, 'MAT-2026-0003', 'O+', NULL, 0, NULL, NULL, 'Alumno base registrado automáticamente por el sistema de semillas.', 1, '2026-07-07 16:07:34', '2026-07-07 16:07:34', NULL),
(4, 5, 'MAT-2026-0004', 'O+', NULL, 0, NULL, NULL, 'Alumno base registrado automáticamente por el sistema de semillas.', 1, '2026-07-07 16:07:34', '2026-07-07 16:07:34', NULL),
(5, 6, 'MAT-2026-0005', 'O+', NULL, 0, NULL, NULL, 'Alumno base registrado automáticamente por el sistema de semillas.', 1, '2026-07-07 16:07:34', '2026-07-07 16:07:34', NULL),
(6, 7, 'MAT-2026-0006', 'O+', NULL, 0, NULL, NULL, 'Alumno base registrado automáticamente por el sistema de semillas.', 1, '2026-07-07 16:07:34', '2026-07-07 16:07:34', NULL),
(7, 8, 'MAT-2026-0007', 'O+', NULL, 0, NULL, NULL, 'Alumno base registrado automáticamente por el sistema de semillas.', 1, '2026-07-07 16:07:34', '2026-07-07 16:07:34', NULL),
(8, 9, 'MAT-2026-0008', 'O+', NULL, 0, NULL, NULL, 'Alumno base registrado automáticamente por el sistema de semillas.', 1, '2026-07-07 16:07:34', '2026-07-07 16:07:34', NULL),
(9, 10, 'MAT-2026-0009', 'O+', NULL, 0, NULL, NULL, 'Alumno base registrado automáticamente por el sistema de semillas.', 1, '2026-07-07 16:07:34', '2026-07-07 16:07:34', NULL),
(10, 11, 'MAT-2026-0010', 'O+', NULL, 0, NULL, NULL, 'Alumno base registrado automáticamente por el sistema de semillas.', 1, '2026-07-07 16:07:34', '2026-07-07 16:07:34', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignaturas`
--

CREATE TABLE `asignaturas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `codigo` varchar(20) NOT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asignaturas`
--

INSERT INTO `asignaturas` (`id`, `nombre`, `codigo`, `estado`, `created_at`, `deleted_at`) VALUES
(1, 'Matemáticas', 'MAT-01', 1, '2026-07-07 16:07:33', NULL),
(2, 'Lengua y Literatura', 'LEN-01', 1, '2026-07-07 16:07:33', NULL),
(3, 'Ciencias Naturales', 'CNA-01', 1, '2026-07-07 16:07:33', NULL),
(4, 'Estudios Sociales', 'ESS-01', 1, '2026-07-07 16:07:33', NULL),
(5, 'Inglés', 'ING-01', 1, '2026-07-07 16:07:33', NULL),
(6, 'Educación Física', 'EFI-01', 1, '2026-07-07 16:07:33', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aulas_periodo`
--

CREATE TABLE `aulas_periodo` (
  `id` int(11) NOT NULL,
  `periodo_lectivo_id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `paralelo_id` int(11) NOT NULL,
  `jornada` enum('Matutina','Vespertina','Nocturna') NOT NULL DEFAULT 'Matutina',
  `cupo_maximo` tinyint(4) NOT NULL DEFAULT 40
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `aulas_periodo`
--

INSERT INTO `aulas_periodo` (`id`, `periodo_lectivo_id`, `curso_id`, `paralelo_id`, `jornada`, `cupo_maximo`) VALUES
(1, 1, 1, 1, '', 40),
(2, 1, 1, 2, '', 40),
(3, 1, 2, 1, '', 40),
(4, 1, 2, 2, '', 40),
(5, 1, 3, 1, '', 40),
(6, 1, 3, 2, '', 40),
(7, 1, 4, 1, '', 40),
(8, 1, 4, 2, '', 40),
(9, 1, 5, 1, '', 40),
(10, 1, 5, 2, '', 40),
(11, 1, 6, 1, '', 40),
(12, 1, 6, 2, '', 40);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `seccion` enum('Matutina','Vespertina','Nocturna') NOT NULL DEFAULT 'Matutina'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`id`, `nombre`, `seccion`) VALUES
(1, 'Octavo Año de EGB', 'Matutina'),
(2, 'Noveno Año de EGB', 'Matutina'),
(3, 'Décimo Año de EGB', 'Matutina'),
(4, 'Primer Año de Bachillerato', 'Matutina'),
(5, 'Segundo Año de Bachillerato', 'Matutina'),
(6, 'Tercer Año de Bachillerato', 'Matutina');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `instituciones`
--

CREATE TABLE `instituciones` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `url` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `instituciones`
--

INSERT INTO `instituciones` (`id`, `nombre`, `url`) VALUES
(1, 'UNIDAD EDUCATIVA SALAMANCA', 'https://colegionocturnosalamanca.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `insumos_evaluacion`
--

CREATE TABLE `insumos_evaluacion` (
  `id` int(11) NOT NULL,
  `periodo_academico_id` int(11) NOT NULL,
  `tipo_evaluacion_id` int(11) NOT NULL,
  `malla_curricular_id` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `fecha_actividad` date NOT NULL,
  `descripcion` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `insumos_evaluacion`
--

INSERT INTO `insumos_evaluacion` (`id`, `periodo_academico_id`, `tipo_evaluacion_id`, `malla_curricular_id`, `titulo`, `fecha_actividad`, `descripcion`, `created_at`, `deleted_at`) VALUES
(1, 1, 1, 1, 'Deber 1: Investigación General', '2025-09-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(2, 1, 1, 1, 'Lección Escrita N°1', '2025-10-05', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(3, 1, 2, 1, 'Taller Práctico en Clase', '2025-10-20', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(4, 1, 2, 1, 'Exposición del Proyecto Aula', '2025-11-12', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(5, 1, 3, 1, 'Fase 1: Proyecto Interdisciplinar', '2025-12-01', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(6, 1, 4, 1, 'Examen Cuantitativo Trimestral', '2025-12-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(7, 1, 1, 2, 'Deber 1: Investigación General', '2025-09-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(8, 1, 1, 2, 'Lección Escrita N°1', '2025-10-05', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(9, 1, 2, 2, 'Taller Práctico en Clase', '2025-10-20', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(10, 1, 2, 2, 'Exposición del Proyecto Aula', '2025-11-12', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(11, 1, 3, 2, 'Fase 1: Proyecto Interdisciplinar', '2025-12-01', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(12, 1, 4, 2, 'Examen Cuantitativo Trimestral', '2025-12-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(13, 1, 1, 3, 'Deber 1: Investigación General', '2025-09-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(14, 1, 1, 3, 'Lección Escrita N°1', '2025-10-05', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(15, 1, 2, 3, 'Taller Práctico en Clase', '2025-10-20', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(16, 1, 2, 3, 'Exposición del Proyecto Aula', '2025-11-12', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(17, 1, 3, 3, 'Fase 1: Proyecto Interdisciplinar', '2025-12-01', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(18, 1, 4, 3, 'Examen Cuantitativo Trimestral', '2025-12-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(19, 1, 1, 4, 'Deber 1: Investigación General', '2025-09-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(20, 1, 1, 4, 'Lección Escrita N°1', '2025-10-05', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(21, 1, 2, 4, 'Taller Práctico en Clase', '2025-10-20', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(22, 1, 2, 4, 'Exposición del Proyecto Aula', '2025-11-12', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(23, 1, 3, 4, 'Fase 1: Proyecto Interdisciplinar', '2025-12-01', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(24, 1, 4, 4, 'Examen Cuantitativo Trimestral', '2025-12-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(25, 1, 1, 5, 'Deber 1: Investigación General', '2025-09-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(26, 1, 1, 5, 'Lección Escrita N°1', '2025-10-05', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(27, 1, 2, 5, 'Taller Práctico en Clase', '2025-10-20', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(28, 1, 2, 5, 'Exposición del Proyecto Aula', '2025-11-12', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(29, 1, 3, 5, 'Fase 1: Proyecto Interdisciplinar', '2025-12-01', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(30, 1, 4, 5, 'Examen Cuantitativo Trimestral', '2025-12-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(31, 1, 1, 6, 'Deber 1: Investigación General', '2025-09-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(32, 1, 1, 6, 'Lección Escrita N°1', '2025-10-05', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(33, 1, 2, 6, 'Taller Práctico en Clase', '2025-10-20', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(34, 1, 2, 6, 'Exposición del Proyecto Aula', '2025-11-12', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(35, 1, 3, 6, 'Fase 1: Proyecto Interdisciplinar', '2025-12-01', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(36, 1, 4, 6, 'Examen Cuantitativo Trimestral', '2025-12-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(37, 1, 1, 7, 'Deber 1: Investigación General', '2025-09-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(38, 1, 1, 7, 'Lección Escrita N°1', '2025-10-05', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(39, 1, 2, 7, 'Taller Práctico en Clase', '2025-10-20', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(40, 1, 2, 7, 'Exposición del Proyecto Aula', '2025-11-12', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(41, 1, 3, 7, 'Fase 1: Proyecto Interdisciplinar', '2025-12-01', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(42, 1, 4, 7, 'Examen Cuantitativo Trimestral', '2025-12-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(43, 1, 1, 8, 'Deber 1: Investigación General', '2025-09-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(44, 1, 1, 8, 'Lección Escrita N°1', '2025-10-05', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(45, 1, 2, 8, 'Taller Práctico en Clase', '2025-10-20', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(46, 1, 2, 8, 'Exposición del Proyecto Aula', '2025-11-12', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(47, 1, 3, 8, 'Fase 1: Proyecto Interdisciplinar', '2025-12-01', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(48, 1, 4, 8, 'Examen Cuantitativo Trimestral', '2025-12-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(49, 1, 1, 9, 'Deber 1: Investigación General', '2025-09-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(50, 1, 1, 9, 'Lección Escrita N°1', '2025-10-05', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(51, 1, 2, 9, 'Taller Práctico en Clase', '2025-10-20', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(52, 1, 2, 9, 'Exposición del Proyecto Aula', '2025-11-12', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(53, 1, 3, 9, 'Fase 1: Proyecto Interdisciplinar', '2025-12-01', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(54, 1, 4, 9, 'Examen Cuantitativo Trimestral', '2025-12-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(55, 1, 1, 10, 'Deber 1: Investigación General', '2025-09-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(56, 1, 1, 10, 'Lección Escrita N°1', '2025-10-05', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(57, 1, 2, 10, 'Taller Práctico en Clase', '2025-10-20', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(58, 1, 2, 10, 'Exposición del Proyecto Aula', '2025-11-12', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(59, 1, 3, 10, 'Fase 1: Proyecto Interdisciplinar', '2025-12-01', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(60, 1, 4, 10, 'Examen Cuantitativo Trimestral', '2025-12-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(61, 1, 1, 11, 'Deber 1: Investigación General', '2025-09-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(62, 1, 1, 11, 'Lección Escrita N°1', '2025-10-05', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(63, 1, 2, 11, 'Taller Práctico en Clase', '2025-10-20', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(64, 1, 2, 11, 'Exposición del Proyecto Aula', '2025-11-12', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(65, 1, 3, 11, 'Fase 1: Proyecto Interdisciplinar', '2025-12-01', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(66, 1, 4, 11, 'Examen Cuantitativo Trimestral', '2025-12-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(67, 1, 1, 12, 'Deber 1: Investigación General', '2025-09-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(68, 1, 1, 12, 'Lección Escrita N°1', '2025-10-05', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(69, 1, 2, 12, 'Taller Práctico en Clase', '2025-10-20', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(70, 1, 2, 12, 'Exposición del Proyecto Aula', '2025-11-12', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(71, 1, 3, 12, 'Fase 1: Proyecto Interdisciplinar', '2025-12-01', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(72, 1, 4, 12, 'Examen Cuantitativo Trimestral', '2025-12-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(73, 1, 1, 13, 'Deber 1: Investigación General', '2025-09-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(74, 1, 1, 13, 'Lección Escrita N°1', '2025-10-05', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(75, 1, 2, 13, 'Taller Práctico en Clase', '2025-10-20', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(76, 1, 2, 13, 'Exposición del Proyecto Aula', '2025-11-12', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(77, 1, 3, 13, 'Fase 1: Proyecto Interdisciplinar', '2025-12-01', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(78, 1, 4, 13, 'Examen Cuantitativo Trimestral', '2025-12-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(79, 1, 1, 14, 'Deber 1: Investigación General', '2025-09-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(80, 1, 1, 14, 'Lección Escrita N°1', '2025-10-05', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(81, 1, 2, 14, 'Taller Práctico en Clase', '2025-10-20', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(82, 1, 2, 14, 'Exposición del Proyecto Aula', '2025-11-12', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(83, 1, 3, 14, 'Fase 1: Proyecto Interdisciplinar', '2025-12-01', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(84, 1, 4, 14, 'Examen Cuantitativo Trimestral', '2025-12-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(85, 1, 1, 15, 'Deber 1: Investigación General', '2025-09-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(86, 1, 1, 15, 'Lección Escrita N°1', '2025-10-05', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(87, 1, 2, 15, 'Taller Práctico en Clase', '2025-10-20', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(88, 1, 2, 15, 'Exposición del Proyecto Aula', '2025-11-12', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(89, 1, 3, 15, 'Fase 1: Proyecto Interdisciplinar', '2025-12-01', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(90, 1, 4, 15, 'Examen Cuantitativo Trimestral', '2025-12-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(91, 1, 1, 16, 'Deber 1: Investigación General', '2025-09-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(92, 1, 1, 16, 'Lección Escrita N°1', '2025-10-05', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(93, 1, 2, 16, 'Taller Práctico en Clase', '2025-10-20', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(94, 1, 2, 16, 'Exposición del Proyecto Aula', '2025-11-12', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(95, 1, 3, 16, 'Fase 1: Proyecto Interdisciplinar', '2025-12-01', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(96, 1, 4, 16, 'Examen Cuantitativo Trimestral', '2025-12-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(97, 1, 1, 17, 'Deber 1: Investigación General', '2025-09-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(98, 1, 1, 17, 'Lección Escrita N°1', '2025-10-05', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(99, 1, 2, 17, 'Taller Práctico en Clase', '2025-10-20', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(100, 1, 2, 17, 'Exposición del Proyecto Aula', '2025-11-12', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(101, 1, 3, 17, 'Fase 1: Proyecto Interdisciplinar', '2025-12-01', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(102, 1, 4, 17, 'Examen Cuantitativo Trimestral', '2025-12-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:33', NULL),
(103, 1, 1, 18, 'Deber 1: Investigación General', '2025-09-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(104, 1, 1, 18, 'Lección Escrita N°1', '2025-10-05', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(105, 1, 2, 18, 'Taller Práctico en Clase', '2025-10-20', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(106, 1, 2, 18, 'Exposición del Proyecto Aula', '2025-11-12', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(107, 1, 3, 18, 'Fase 1: Proyecto Interdisciplinar', '2025-12-01', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(108, 1, 4, 18, 'Examen Cuantitativo Trimestral', '2025-12-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(109, 1, 1, 19, 'Deber 1: Investigación General', '2025-09-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(110, 1, 1, 19, 'Lección Escrita N°1', '2025-10-05', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(111, 1, 2, 19, 'Taller Práctico en Clase', '2025-10-20', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(112, 1, 2, 19, 'Exposición del Proyecto Aula', '2025-11-12', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(113, 1, 3, 19, 'Fase 1: Proyecto Interdisciplinar', '2025-12-01', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(114, 1, 4, 19, 'Examen Cuantitativo Trimestral', '2025-12-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(115, 1, 1, 20, 'Deber 1: Investigación General', '2025-09-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(116, 1, 1, 20, 'Lección Escrita N°1', '2025-10-05', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(117, 1, 2, 20, 'Taller Práctico en Clase', '2025-10-20', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(118, 1, 2, 20, 'Exposición del Proyecto Aula', '2025-11-12', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(119, 1, 3, 20, 'Fase 1: Proyecto Interdisciplinar', '2025-12-01', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(120, 1, 4, 20, 'Examen Cuantitativo Trimestral', '2025-12-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(121, 1, 1, 21, 'Deber 1: Investigación General', '2025-09-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(122, 1, 1, 21, 'Lección Escrita N°1', '2025-10-05', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(123, 1, 2, 21, 'Taller Práctico en Clase', '2025-10-20', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(124, 1, 2, 21, 'Exposición del Proyecto Aula', '2025-11-12', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(125, 1, 3, 21, 'Fase 1: Proyecto Interdisciplinar', '2025-12-01', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(126, 1, 4, 21, 'Examen Cuantitativo Trimestral', '2025-12-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(127, 1, 1, 22, 'Deber 1: Investigación General', '2025-09-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(128, 1, 1, 22, 'Lección Escrita N°1', '2025-10-05', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(129, 1, 2, 22, 'Taller Práctico en Clase', '2025-10-20', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(130, 1, 2, 22, 'Exposición del Proyecto Aula', '2025-11-12', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(131, 1, 3, 22, 'Fase 1: Proyecto Interdisciplinar', '2025-12-01', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(132, 1, 4, 22, 'Examen Cuantitativo Trimestral', '2025-12-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(133, 1, 1, 23, 'Deber 1: Investigación General', '2025-09-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(134, 1, 1, 23, 'Lección Escrita N°1', '2025-10-05', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(135, 1, 2, 23, 'Taller Práctico en Clase', '2025-10-20', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(136, 1, 2, 23, 'Exposición del Proyecto Aula', '2025-11-12', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(137, 1, 3, 23, 'Fase 1: Proyecto Interdisciplinar', '2025-12-01', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(138, 1, 4, 23, 'Examen Cuantitativo Trimestral', '2025-12-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(139, 1, 1, 24, 'Deber 1: Investigación General', '2025-09-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(140, 1, 1, 24, 'Lección Escrita N°1', '2025-10-05', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(141, 1, 2, 24, 'Taller Práctico en Clase', '2025-10-20', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(142, 1, 2, 24, 'Exposición del Proyecto Aula', '2025-11-12', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(143, 1, 3, 24, 'Fase 1: Proyecto Interdisciplinar', '2025-12-01', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(144, 1, 4, 24, 'Examen Cuantitativo Trimestral', '2025-12-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(145, 1, 1, 25, 'Deber 1: Investigación General', '2025-09-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(146, 1, 1, 25, 'Lección Escrita N°1', '2025-10-05', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(147, 1, 2, 25, 'Taller Práctico en Clase', '2025-10-20', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(148, 1, 2, 25, 'Exposición del Proyecto Aula', '2025-11-12', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(149, 1, 3, 25, 'Fase 1: Proyecto Interdisciplinar', '2025-12-01', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(150, 1, 4, 25, 'Examen Cuantitativo Trimestral', '2025-12-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(151, 1, 1, 26, 'Deber 1: Investigación General', '2025-09-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(152, 1, 1, 26, 'Lección Escrita N°1', '2025-10-05', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(153, 1, 2, 26, 'Taller Práctico en Clase', '2025-10-20', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(154, 1, 2, 26, 'Exposición del Proyecto Aula', '2025-11-12', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(155, 1, 3, 26, 'Fase 1: Proyecto Interdisciplinar', '2025-12-01', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(156, 1, 4, 26, 'Examen Cuantitativo Trimestral', '2025-12-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(157, 1, 1, 27, 'Deber 1: Investigación General', '2025-09-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(158, 1, 1, 27, 'Lección Escrita N°1', '2025-10-05', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(159, 1, 2, 27, 'Taller Práctico en Clase', '2025-10-20', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(160, 1, 2, 27, 'Exposición del Proyecto Aula', '2025-11-12', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(161, 1, 3, 27, 'Fase 1: Proyecto Interdisciplinar', '2025-12-01', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(162, 1, 4, 27, 'Examen Cuantitativo Trimestral', '2025-12-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(163, 1, 1, 28, 'Deber 1: Investigación General', '2025-09-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(164, 1, 1, 28, 'Lección Escrita N°1', '2025-10-05', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(165, 1, 2, 28, 'Taller Práctico en Clase', '2025-10-20', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(166, 1, 2, 28, 'Exposición del Proyecto Aula', '2025-11-12', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(167, 1, 3, 28, 'Fase 1: Proyecto Interdisciplinar', '2025-12-01', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(168, 1, 4, 28, 'Examen Cuantitativo Trimestral', '2025-12-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(169, 1, 1, 29, 'Deber 1: Investigación General', '2025-09-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(170, 1, 1, 29, 'Lección Escrita N°1', '2025-10-05', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(171, 1, 2, 29, 'Taller Práctico en Clase', '2025-10-20', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(172, 1, 2, 29, 'Exposición del Proyecto Aula', '2025-11-12', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(173, 1, 3, 29, 'Fase 1: Proyecto Interdisciplinar', '2025-12-01', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(174, 1, 4, 29, 'Examen Cuantitativo Trimestral', '2025-12-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(175, 1, 1, 30, 'Deber 1: Investigación General', '2025-09-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(176, 1, 1, 30, 'Lección Escrita N°1', '2025-10-05', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(177, 1, 2, 30, 'Taller Práctico en Clase', '2025-10-20', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(178, 1, 2, 30, 'Exposición del Proyecto Aula', '2025-11-12', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(179, 1, 3, 30, 'Fase 1: Proyecto Interdisciplinar', '2025-12-01', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(180, 1, 4, 30, 'Examen Cuantitativo Trimestral', '2025-12-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(181, 1, 1, 31, 'Deber 1: Investigación General', '2025-09-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(182, 1, 1, 31, 'Lección Escrita N°1', '2025-10-05', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(183, 1, 2, 31, 'Taller Práctico en Clase', '2025-10-20', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(184, 1, 2, 31, 'Exposición del Proyecto Aula', '2025-11-12', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(185, 1, 3, 31, 'Fase 1: Proyecto Interdisciplinar', '2025-12-01', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(186, 1, 4, 31, 'Examen Cuantitativo Trimestral', '2025-12-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(187, 1, 1, 32, 'Deber 1: Investigación General', '2025-09-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(188, 1, 1, 32, 'Lección Escrita N°1', '2025-10-05', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(189, 1, 2, 32, 'Taller Práctico en Clase', '2025-10-20', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(190, 1, 2, 32, 'Exposición del Proyecto Aula', '2025-11-12', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(191, 1, 3, 32, 'Fase 1: Proyecto Interdisciplinar', '2025-12-01', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(192, 1, 4, 32, 'Examen Cuantitativo Trimestral', '2025-12-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(193, 1, 1, 33, 'Deber 1: Investigación General', '2025-09-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(194, 1, 1, 33, 'Lección Escrita N°1', '2025-10-05', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(195, 1, 2, 33, 'Taller Práctico en Clase', '2025-10-20', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(196, 1, 2, 33, 'Exposición del Proyecto Aula', '2025-11-12', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(197, 1, 3, 33, 'Fase 1: Proyecto Interdisciplinar', '2025-12-01', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(198, 1, 4, 33, 'Examen Cuantitativo Trimestral', '2025-12-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(199, 1, 1, 34, 'Deber 1: Investigación General', '2025-09-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(200, 1, 1, 34, 'Lección Escrita N°1', '2025-10-05', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(201, 1, 2, 34, 'Taller Práctico en Clase', '2025-10-20', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(202, 1, 2, 34, 'Exposición del Proyecto Aula', '2025-11-12', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(203, 1, 3, 34, 'Fase 1: Proyecto Interdisciplinar', '2025-12-01', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(204, 1, 4, 34, 'Examen Cuantitativo Trimestral', '2025-12-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(205, 1, 1, 35, 'Deber 1: Investigación General', '2025-09-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(206, 1, 1, 35, 'Lección Escrita N°1', '2025-10-05', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(207, 1, 2, 35, 'Taller Práctico en Clase', '2025-10-20', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(208, 1, 2, 35, 'Exposición del Proyecto Aula', '2025-11-12', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(209, 1, 3, 35, 'Fase 1: Proyecto Interdisciplinar', '2025-12-01', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(210, 1, 4, 35, 'Examen Cuantitativo Trimestral', '2025-12-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(211, 1, 1, 36, 'Deber 1: Investigación General', '2025-09-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(212, 1, 1, 36, 'Lección Escrita N°1', '2025-10-05', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(213, 1, 2, 36, 'Taller Práctico en Clase', '2025-10-20', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(214, 1, 2, 36, 'Exposición del Proyecto Aula', '2025-11-12', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(215, 1, 3, 36, 'Fase 1: Proyecto Interdisciplinar', '2025-12-01', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL),
(216, 1, 4, 36, 'Examen Cuantitativo Trimestral', '2025-12-15', 'Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.', '2026-07-07 16:07:34', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `malla_curricular`
--

CREATE TABLE `malla_curricular` (
  `id` int(11) NOT NULL,
  `periodo_lectivo_id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `asignatura_id` int(11) NOT NULL,
  `horas_semanales` tinyint(4) NOT NULL DEFAULT 4
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `malla_curricular`
--

INSERT INTO `malla_curricular` (`id`, `periodo_lectivo_id`, `curso_id`, `asignatura_id`, `horas_semanales`) VALUES
(1, 1, 1, 3, 4),
(2, 1, 1, 6, 4),
(3, 1, 1, 4, 4),
(4, 1, 1, 5, 4),
(5, 1, 1, 2, 4),
(6, 1, 1, 1, 4),
(7, 1, 2, 3, 4),
(8, 1, 2, 6, 4),
(9, 1, 2, 4, 4),
(10, 1, 2, 5, 4),
(11, 1, 2, 2, 4),
(12, 1, 2, 1, 4),
(13, 1, 3, 3, 4),
(14, 1, 3, 6, 4),
(15, 1, 3, 4, 4),
(16, 1, 3, 5, 4),
(17, 1, 3, 2, 4),
(18, 1, 3, 1, 4),
(19, 1, 4, 3, 4),
(20, 1, 4, 6, 4),
(21, 1, 4, 4, 4),
(22, 1, 4, 5, 4),
(23, 1, 4, 2, 4),
(24, 1, 4, 1, 4),
(25, 1, 5, 3, 4),
(26, 1, 5, 6, 4),
(27, 1, 5, 4, 4),
(28, 1, 5, 5, 4),
(29, 1, 5, 2, 4),
(30, 1, 5, 1, 4),
(31, 1, 6, 3, 4),
(32, 1, 6, 6, 4),
(33, 1, 6, 4, 4),
(34, 1, 6, 5, 4),
(35, 1, 6, 2, 4),
(36, 1, 6, 1, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `matriculas`
--

CREATE TABLE `matriculas` (
  `id` int(11) NOT NULL,
  `aula_periodo_id` int(11) NOT NULL,
  `alumno_id` int(11) NOT NULL,
  `fecha_matricula` date NOT NULL,
  `numero_matricula` varchar(30) NOT NULL,
  `estado_matricula` enum('Aspirante','Matriculado','Retirado','Anulado') NOT NULL DEFAULT 'Matriculado',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `matriculas`
--

INSERT INTO `matriculas` (`id`, `aula_periodo_id`, `alumno_id`, `fecha_matricula`, `numero_matricula`, `estado_matricula`, `created_at`) VALUES
(1, 1, 1, '2026-07-07', 'REG-2026-0001', 'Matriculado', '2026-07-07 16:07:34'),
(2, 1, 2, '2026-07-07', 'REG-2026-0002', 'Matriculado', '2026-07-07 16:07:34'),
(3, 1, 3, '2026-07-07', 'REG-2026-0003', 'Matriculado', '2026-07-07 16:07:34'),
(4, 1, 4, '2026-07-07', 'REG-2026-0004', 'Matriculado', '2026-07-07 16:07:34'),
(5, 1, 5, '2026-07-07', 'REG-2026-0005', 'Matriculado', '2026-07-07 16:07:34'),
(6, 1, 6, '2026-07-07', 'REG-2026-0006', 'Matriculado', '2026-07-07 16:07:34'),
(7, 1, 7, '2026-07-07', 'REG-2026-0007', 'Matriculado', '2026-07-07 16:07:34'),
(8, 1, 8, '2026-07-07', 'REG-2026-0008', 'Matriculado', '2026-07-07 16:07:34'),
(9, 1, 9, '2026-07-07', 'REG-2026-0009', 'Matriculado', '2026-07-07 16:07:34'),
(10, 1, 10, '2026-07-07', 'REG-2026-0010', 'Matriculado', '2026-07-07 16:07:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menus`
--

CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `url` varchar(100) NOT NULL,
  `icono` varchar(50) DEFAULT NULL,
  `permiso_slug` varchar(50) DEFAULT NULL,
  `padre_id` int(11) DEFAULT NULL,
  `orden` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `menus`
--

INSERT INTO `menus` (`id`, `nombre`, `url`, `icono`, `permiso_slug`, `padre_id`, `orden`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Dashboard', 'admin/dashboard', 'mdi mdi-airplay', 'admin-dashboard', NULL, 1, '2026-07-07 16:07:34', '2026-07-07 16:07:34', NULL),
(2, 'Administración', '#', 'mdi mdi-layers', 'ver-modulos-del-administrador', NULL, 2, '2026-07-07 16:07:34', '2026-07-07 16:07:34', NULL),
(3, 'Permisos', 'permisos', NULL, 'listar-permisos', 2, 1, '2026-07-07 16:07:34', '2026-07-07 16:07:34', NULL),
(4, 'Roles', 'roles', NULL, 'listar-roles', 2, 2, '2026-07-07 16:07:34', '2026-07-07 16:07:34', NULL),
(5, 'Usuarios', 'usuarios', NULL, 'listar-usuarios', 2, 3, '2026-07-07 16:07:34', '2026-07-07 16:07:34', NULL),
(6, 'Menús', 'menus', NULL, 'listar-menus', 2, 4, '2026-07-07 16:07:34', '2026-07-07 16:07:34', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(11) NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  `executed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`, `executed_at`) VALUES
(622, '2026_06_18_141434_create_roles_table.php', 1, '2026-07-07 16:07:33'),
(623, '2026_06_18_142630_create_usuarios_table.php', 1, '2026-07-07 16:07:33'),
(624, '2026_06_18_143520_create_usuarios_roles_table.php', 1, '2026-07-07 16:07:33'),
(625, '2026_06_24_064702_create_permisos_table.php', 1, '2026-07-07 16:07:33'),
(626, '2026_06_25_115351_create_roles_permisos_table.php', 1, '2026-07-07 16:07:33'),
(627, '2026_06_27_094820_create_tipo_documento_table.php', 1, '2026-07-07 16:07:33'),
(628, '2026_06_27_095156_create_nacionalidades_table.php', 1, '2026-07-07 16:07:33'),
(629, '2026_06_27_095627_create_personas_table.php', 1, '2026-07-07 16:07:33'),
(630, '2026_06_27_152936_modificar_tabla_usuarios.php', 1, '2026-07-07 16:07:33'),
(631, '2026_07_03_093049_create_menus_table.php', 1, '2026-07-07 16:07:33'),
(632, '2026_07_05_150849_create_unidad_educativa_table.php', 1, '2026-07-07 16:07:33'),
(633, '2026_07_05_215713_create_periodos_lectivos_table.php', 1, '2026-07-07 16:07:33'),
(634, '2026_07_05_222943_create_periodos_academicos_table.php', 1, '2026-07-07 16:07:33'),
(635, '2026_07_05_231055_create_tipos_evaluacion_table.php', 1, '2026-07-07 16:07:33'),
(636, '2026_07_06_062730_create_alumnos_table.php', 1, '2026-07-07 16:07:33'),
(637, '2026_07_06_101646_create_asignaturas_table.php', 1, '2026-07-07 16:07:33'),
(638, '2026_07_06_132958_create_cursos_table.php', 1, '2026-07-07 16:07:33'),
(639, '2026_07_06_150829_create_malla_curricular_table.php', 1, '2026-07-07 16:07:33'),
(640, '2026_07_06_164250_create_paralelos_table.php', 1, '2026-07-07 16:07:33'),
(641, '2026_07_06_165201_create_aulas_periodo_table.php', 1, '2026-07-07 16:07:33'),
(642, '2026_07_06_213425_create_matriculas_table.php', 1, '2026-07-07 16:07:33'),
(643, '2026_07_06_231424_create_insumos_evaluacion_table.php', 1, '2026-07-07 16:07:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nacionalidades`
--

CREATE TABLE `nacionalidades` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `nacionalidades`
--

INSERT INTO `nacionalidades` (`id`, `nombre`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Ecuatoriana', '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(2, 'Colombiana', '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(3, 'Venezolana', '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(4, 'Haitiana', '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(5, 'Peruana', '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paralelos`
--

CREATE TABLE `paralelos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `paralelos`
--

INSERT INTO `paralelos` (`id`, `nombre`) VALUES
(1, 'A'),
(2, 'B'),
(3, 'C'),
(4, 'D');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `periodos_academicos`
--

CREATE TABLE `periodos_academicos` (
  `id` int(11) NOT NULL,
  `periodo_lectivo_id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `tipo` enum('trimestre','bimestre') NOT NULL DEFAULT 'trimestre',
  `orden` tinyint(4) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `periodos_academicos`
--

INSERT INTO `periodos_academicos` (`id`, `periodo_lectivo_id`, `nombre`, `tipo`, `orden`, `fecha_inicio`, `fecha_fin`, `deleted_at`) VALUES
(1, 1, 'Primer Trimestre', 'trimestre', 1, '2025-09-01', '2025-12-22', NULL),
(2, 1, 'Segundo Trimestre', 'trimestre', 2, '2026-01-03', '2026-03-20', NULL),
(3, 1, 'Tercer Trimestre', 'trimestre', 3, '2026-04-01', '2026-06-30', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `periodos_lectivos`
--

CREATE TABLE `periodos_lectivos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `periodos_lectivos`
--

INSERT INTO `periodos_lectivos` (`id`, `nombre`, `fecha_inicio`, `fecha_fin`, `estado`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Ciclo Sierra 2025-2026', '2025-09-01', '2026-06-30', 1, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id`, `nombre`, `slug`, `descripcion`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Crear Usuario', 'crear-usuario', 'Puede insertar nuevos usuarios.', '2026-07-07 16:07:34', '2026-07-07 16:07:34', NULL),
(2, 'Actualizar Usuario', 'actualizar-usuario', 'Puede actualizar usuarios.', '2026-07-07 16:07:34', '2026-07-07 16:07:34', NULL),
(3, 'Eliminar Usuario', 'eliminar-usuario', 'Puede eliminar usuarios.', '2026-07-07 16:07:34', '2026-07-07 16:07:34', NULL),
(4, 'Listar Usuarios', 'listar-usuarios', 'Puede ver el listado de usuarios.', '2026-07-07 16:07:34', '2026-07-07 16:07:34', NULL),
(5, 'Crear Rol', 'crear-rol', 'Puede insertar nuevos roles.', '2026-07-07 16:07:34', '2026-07-07 16:07:34', NULL),
(6, 'Actualizar Rol', 'actualizar-rol', 'Puede actualizar roles.', '2026-07-07 16:07:34', '2026-07-07 16:07:34', NULL),
(7, 'Eliminar Rol', 'eliminar-rol', 'Puede eliminar roles.', '2026-07-07 16:07:34', '2026-07-07 16:07:34', NULL),
(8, 'Listar Roles', 'listar-roles', 'Puede ver el listado de roles.', '2026-07-07 16:07:34', '2026-07-07 16:07:34', NULL),
(9, 'Crear Permiso', 'crear-permiso', 'Puede crear nuevos permisos.', '2026-07-07 16:07:34', '2026-07-07 16:07:34', NULL),
(10, 'Actualizar Permiso', 'actualizar-permiso', 'Puede actualizar permisos.', '2026-07-07 16:07:34', '2026-07-07 16:07:34', NULL),
(11, 'Eliminar Permiso', 'eliminar-permiso', 'Puede eliminar permisos.', '2026-07-07 16:07:34', '2026-07-07 16:07:34', NULL),
(12, 'Listar Permisos', 'listar-permisos', 'Puede ver el listado de permisos.', '2026-07-07 16:07:34', '2026-07-07 16:07:34', NULL),
(13, 'Ver Módulos del Administrador', 'ver-modulos-del-administrador', 'Sólo el rol con este permiso tendrá acceso a este menú.', '2026-07-07 16:07:34', '2026-07-07 16:07:34', NULL),
(14, 'Listar Menús', 'listar-menus', 'Puede ver el listado de menús.', '2026-07-07 16:07:34', '2026-07-07 16:07:34', NULL),
(15, 'Admin Dashboard', 'admin-dashboard', 'Permite ver el dashboard del panel del Administrador.', '2026-07-07 16:07:34', '2026-07-07 16:07:34', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas`
--

CREATE TABLE `personas` (
  `id` int(11) NOT NULL,
  `tipo_documento_id` int(11) NOT NULL DEFAULT 1,
  `nacionalidad_id` int(11) NOT NULL DEFAULT 1,
  `dni` varchar(20) NOT NULL,
  `primer_nombre` varchar(32) NOT NULL,
  `segundo_nombre` varchar(32) DEFAULT NULL,
  `primer_apellido` varchar(32) NOT NULL,
  `segundo_apellido` varchar(32) DEFAULT NULL,
  `nombre_corto` varchar(64) DEFAULT NULL,
  `nombre_completo` varchar(128) DEFAULT NULL,
  `titulo` varchar(16) DEFAULT NULL,
  `descripcion_titulo` varchar(96) DEFAULT NULL,
  `genero` enum('Femenino','Masculino','Otro') NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `telefono` varchar(32) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `sector` varchar(64) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `personas`
--

INSERT INTO `personas` (`id`, `tipo_documento_id`, `nacionalidad_id`, `dni`, `primer_nombre`, `segundo_nombre`, `primer_apellido`, `segundo_apellido`, `nombre_corto`, `nombre_completo`, `titulo`, `descripcion_titulo`, `genero`, `fecha_nacimiento`, `telefono`, `direccion`, `sector`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, '1709290207', 'Gonzalo', 'Nicolás', 'Peñaherrera', 'Escobar', 'Ing. Gonzalo Peñaherrera', 'Peñaherrera Escobar Gonzalo Nicolás', NULL, NULL, 'Masculino', NULL, NULL, NULL, NULL, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(2, 1, 1, '0111498739', 'Ángel', 'Luis', 'Suárez', 'Romero', 'Dr. Ángel Suárez', 'Suárez Romero Ángel Luis', NULL, NULL, 'Masculino', NULL, NULL, NULL, NULL, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(3, 1, 1, '1358238861', 'Christian', 'Rafael', 'Cueva', 'Pinto', 'Dr. Christian Cueva', 'Cueva Pinto Christian Rafael', NULL, NULL, 'Masculino', NULL, NULL, NULL, NULL, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(4, 1, 1, '0410840540', 'Lucía', 'Estefanía', 'Vaca', 'Vallejo', 'MSc. Lucía Vaca', 'Vaca Vallejo Lucía Estefanía', NULL, NULL, 'Femenino', NULL, NULL, NULL, NULL, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(5, 1, 1, '1425838644', 'Edison', 'Roberto', 'Ochoa', 'Guerrero', 'Lic. Edison Ochoa', 'Ochoa Guerrero Edison Roberto', NULL, NULL, 'Masculino', NULL, NULL, NULL, NULL, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(6, 1, 1, '1505108462', 'Francisco', 'Édgar', 'Muñoz', 'León', 'Dr. Francisco Muñoz', 'Muñoz León Francisco Édgar', NULL, NULL, 'Masculino', NULL, NULL, NULL, NULL, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(7, 1, 1, '1759555509', 'Francisco', 'Daniel', 'Pinto', 'Paredes', 'MSc. Francisco Pinto', 'Pinto Paredes Francisco Daniel', NULL, NULL, 'Masculino', NULL, NULL, NULL, NULL, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(8, 1, 1, '1449916699', 'Ángel', 'Jorge', 'García', 'Velásquez', 'Lic. Ángel García', 'García Velásquez Ángel Jorge', NULL, NULL, 'Masculino', NULL, NULL, NULL, NULL, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(9, 1, 1, '0540378858', 'Verónica', 'Daniela', 'Vallejo', 'Peña', 'Lic. Verónica Vallejo', 'Vallejo Peña Verónica Daniela', NULL, NULL, 'Femenino', NULL, NULL, NULL, NULL, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(10, 1, 1, '1403212721', 'Mayra', 'Tatiana', 'Vera', 'Morales', 'Dr. Mayra Vera', 'Vera Morales Mayra Tatiana', NULL, NULL, 'Femenino', NULL, NULL, NULL, NULL, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(11, 1, 1, '2128990641', 'Paola', 'Ana', 'García', 'Maldonado', 'Lic. Paola García', 'García Maldonado Paola Ana', NULL, NULL, 'Femenino', NULL, NULL, NULL, NULL, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(12, 1, 1, '1548461977', 'Carlos', 'Fernando', 'Velasco', 'Jiménez', 'Dr. Carlos Velasco', 'Velasco Jiménez Carlos Fernando', NULL, NULL, 'Masculino', NULL, NULL, NULL, NULL, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(13, 1, 1, '0954518692', 'Mayra', 'Adriana', 'Velasco', 'Quishpe', 'MSc. Mayra Velasco', 'Velasco Quishpe Mayra Adriana', NULL, NULL, 'Femenino', NULL, NULL, NULL, NULL, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(14, 1, 1, '1026486637', 'Silvia', 'Mónica', 'Muñoz', 'Guerrero', 'Dr. Silvia Muñoz', 'Muñoz Guerrero Silvia Mónica', NULL, NULL, 'Femenino', NULL, NULL, NULL, NULL, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(15, 1, 1, '0131619496', 'Jonathan', 'Alejandro', 'Mendoza', 'Ramos', 'Lic. Jonathan Mendoza', 'Mendoza Ramos Jonathan Alejandro', NULL, NULL, 'Masculino', NULL, NULL, NULL, NULL, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(16, 1, 1, '0244066155', 'Camila', 'Mayra', 'Guanoluisa', 'Peña', 'Dr. Camila Guanoluisa', 'Guanoluisa Peña Camila Mayra', NULL, NULL, 'Femenino', NULL, NULL, NULL, NULL, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(17, 1, 1, '0816413488', 'Pablo', 'Mateo', 'Cueva', 'Villacís', 'Dr. Pablo Cueva', 'Cueva Villacís Pablo Mateo', NULL, NULL, 'Masculino', NULL, NULL, NULL, NULL, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(18, 1, 1, '1857123325', 'Fernando', 'Jorge', 'Suárez', 'Quishpe', 'MSc. Fernando Suárez', 'Suárez Quishpe Fernando Jorge', NULL, NULL, 'Masculino', NULL, NULL, NULL, NULL, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(19, 1, 1, '1902785177', 'Miguel', 'Ángel', 'Peña', 'Ramírez', 'Lic. Miguel Peña', 'Peña Ramírez Miguel Ángel', NULL, NULL, 'Masculino', NULL, NULL, NULL, NULL, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(20, 1, 1, '1811257185', 'Juan', 'Geovanny', 'Toapanta', 'Fernández', 'Dr. Juan Toapanta', 'Toapanta Fernández Juan Geovanny', NULL, NULL, 'Masculino', NULL, NULL, NULL, NULL, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(21, 1, 1, '0759741408', 'Marco', 'José', 'Vera', 'Villacís', 'MSc. Marco Vera', 'Vera Villacís Marco José', NULL, NULL, 'Masculino', NULL, NULL, NULL, NULL, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`, `slug`, `descripcion`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Administrador', 'administrador', 'Acceso total a todos los módulos del sistema administrativo.', '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(2, 'Autoridad', 'autoridad', 'Acceso a reportes de gestión educativa.', '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(3, 'Coordinador', 'coordinador', 'Gestión y supervisión de ofertas educativas y asignaciones.', '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(4, 'Docente', 'docente', 'Registro de calificaciones, asistencias y rúbricas.', '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(5, 'Estudiante', 'estudiante', 'Consulta de historial académico y perfiles.', '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(6, 'Secretaría', 'secretaria', 'Acceso a Matriculación y Reportes.', '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(7, 'Tutor', 'tutor', 'Acceso a reportes de calificaciones y comportamiento.', '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles_permisos`
--

CREATE TABLE `roles_permisos` (
  `rol_id` int(11) NOT NULL,
  `permiso_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles_permisos`
--

INSERT INTO `roles_permisos` (`rol_id`, `permiso_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 11),
(1, 12),
(1, 13),
(1, 14),
(1, 15);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_evaluacion`
--

CREATE TABLE `tipos_evaluacion` (
  `id` int(11) NOT NULL,
  `periodo_lectivo_id` int(11) NOT NULL,
  `macro_categoria` enum('formativa','sumativa') NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `ponderacion_macro` decimal(5,2) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos_evaluacion`
--

INSERT INTO `tipos_evaluacion` (`id`, `periodo_lectivo_id`, `macro_categoria`, `nombre`, `ponderacion_macro`, `descripcion`) VALUES
(1, 1, 'formativa', 'Actividades Individuales', 70.00, 'Lecciones, pruebas, tareas escritas o trabajos prácticos realizados de forma autónoma.'),
(2, 1, 'formativa', 'Actividades Grupales', 70.00, 'Proyectos en equipo, debates, exposiciones o talleres prácticos en clase.'),
(3, 1, 'sumativa', 'Evaluación de Periodo Académico', 30.00, 'Examen de base estructurada que mide los logros de aprendizaje del trimestre/quimestre.'),
(4, 1, 'sumativa', 'Proyecto Interdisciplinar', 30.00, 'Evidencia final de la aplicación integrada de saberes de múltiples asignaturas.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_documento`
--

CREATE TABLE `tipo_documento` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_documento`
--

INSERT INTO `tipo_documento` (`id`, `descripcion`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Cédula de Identidad', '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(2, 'Pasaporte', '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(3, 'Carnet de refugiado', '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(4, 'Cédula colombiana', '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(5, 'Cédula venezolana', '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidad_educativa`
--

CREATE TABLE `unidad_educativa` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `nombre` varchar(64) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `telefono` varchar(32) DEFAULT NULL,
  `regimen` varchar(45) DEFAULT NULL,
  `nombre_rector` varchar(45) DEFAULT NULL,
  `genero_rector` enum('Femenino','Masculino') DEFAULT 'Masculino',
  `nombre_vicerrector` varchar(45) DEFAULT NULL,
  `genero_vicerrector` enum('Femenino','Masculino') DEFAULT 'Masculino',
  `nombre_secretario` varchar(45) DEFAULT NULL,
  `genero_secretario` enum('Femenino','Masculino') DEFAULT 'Masculino',
  `email` varchar(64) DEFAULT NULL,
  `url` varchar(64) DEFAULT NULL,
  `logo` varchar(64) DEFAULT NULL,
  `amie` varchar(16) DEFAULT NULL,
  `ciudad` varchar(64) DEFAULT NULL,
  `copiar_y_pegar` tinyint(1) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `persona_id` int(11) NOT NULL,
  `username` varchar(64) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `password` varchar(535) DEFAULT NULL,
  `request_password` enum('0','1') DEFAULT '0',
  `token_password` varchar(200) DEFAULT NULL,
  `expired_session` varchar(40) DEFAULT NULL,
  `avatar` varchar(100) DEFAULT NULL,
  `activo` int(1) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `persona_id`, `username`, `email`, `password`, `request_password`, `token_password`, `expired_session`, `avatar`, `activo`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Administrador', 'gonzalop67@gmail.com', 'eJCYkBmXtXugXg==', '0', NULL, NULL, '992919889.png', 1, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(2, 2, 'Ángel.suárez2', 'Ángel.suárez2@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(3, 3, 'christian.cueva3', 'christian.cueva3@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(4, 4, 'lucía.vaca4', 'lucía.vaca4@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(5, 5, 'edison.ochoa5', 'edison.ochoa5@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(6, 6, 'francisco.muñoz6', 'francisco.muñoz6@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(7, 7, 'francisco.pinto7', 'francisco.pinto7@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(8, 8, 'Ángel.garcía8', 'Ángel.garcía8@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(9, 9, 'verónica.vallejo9', 'verónica.vallejo9@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(10, 10, 'mayra.vera10', 'mayra.vera10@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(11, 11, 'paola.garcía11', 'paola.garcía11@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(12, 12, 'carlos.velasco12', 'carlos.velasco12@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(13, 13, 'mayra.velasco13', 'mayra.velasco13@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(14, 14, 'silvia.muñoz14', 'silvia.muñoz14@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(15, 15, 'jonathan.mendoza15', 'jonathan.mendoza15@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(16, 16, 'camila.guanoluisa16', 'camila.guanoluisa16@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(17, 17, 'pablo.cueva17', 'pablo.cueva17@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(18, 18, 'fernando.suárez18', 'fernando.suárez18@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(19, 19, 'miguel.peña19', 'miguel.peña19@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(20, 20, 'juan.toapanta20', 'juan.toapanta20@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL),
(21, 21, 'marco.vera21', 'marco.vera21@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-07 16:07:33', '2026-07-07 16:07:33', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_roles`
--

CREATE TABLE `usuarios_roles` (
  `usuario_id` int(11) NOT NULL,
  `rol_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios_roles`
--

INSERT INTO `usuarios_roles` (`usuario_id`, `rol_id`, `created_at`) VALUES
(1, 1, '2026-07-07 16:07:33'),
(2, 4, '2026-07-07 16:07:33'),
(3, 4, '2026-07-07 16:07:33'),
(4, 4, '2026-07-07 16:07:33'),
(5, 4, '2026-07-07 16:07:33'),
(6, 4, '2026-07-07 16:07:33'),
(7, 4, '2026-07-07 16:07:33'),
(8, 4, '2026-07-07 16:07:33'),
(9, 4, '2026-07-07 16:07:33'),
(10, 4, '2026-07-07 16:07:33'),
(11, 4, '2026-07-07 16:07:33'),
(12, 4, '2026-07-07 16:07:33'),
(13, 4, '2026-07-07 16:07:33'),
(14, 4, '2026-07-07 16:07:33'),
(15, 4, '2026-07-07 16:07:33'),
(16, 4, '2026-07-07 16:07:33'),
(17, 4, '2026-07-07 16:07:33'),
(18, 4, '2026-07-07 16:07:33'),
(19, 4, '2026-07-07 16:07:33'),
(20, 4, '2026-07-07 16:07:33'),
(21, 4, '2026-07-07 16:07:33');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_persona_alumno` (`persona_id`),
  ADD UNIQUE KEY `unique_codigo_matricula` (`codigo_matricula`);

--
-- Indices de la tabla `asignaturas`
--
ALTER TABLE `asignaturas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_codigo_asignatura` (`codigo`);

--
-- Indices de la tabla `aulas_periodo`
--
ALTER TABLE `aulas_periodo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_aula_periodo_jornada` (`periodo_lectivo_id`,`curso_id`,`paralelo_id`,`jornada`),
  ADD KEY `curso_id` (`curso_id`),
  ADD KEY `paralelo_id` (`paralelo_id`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `instituciones`
--
ALTER TABLE `instituciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `insumos_evaluacion`
--
ALTER TABLE `insumos_evaluacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `periodo_academico_id` (`periodo_academico_id`),
  ADD KEY `tipo_evaluacion_id` (`tipo_evaluacion_id`),
  ADD KEY `malla_curricular_id` (`malla_curricular_id`);

--
-- Indices de la tabla `malla_curricular`
--
ALTER TABLE `malla_curricular`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_malla_periodo` (`periodo_lectivo_id`,`curso_id`,`asignatura_id`),
  ADD KEY `curso_id` (`curso_id`),
  ADD KEY `asignatura_id` (`asignatura_id`);

--
-- Indices de la tabla `matriculas`
--
ALTER TABLE `matriculas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_alumno_periodo_lectivo` (`alumno_id`,`aula_periodo_id`),
  ADD KEY `aula_periodo_id` (`aula_periodo_id`);

--
-- Indices de la tabla `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permiso_slug` (`permiso_slug`),
  ADD KEY `padre_id` (`padre_id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `nacionalidades`
--
ALTER TABLE `nacionalidades`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `paralelos`
--
ALTER TABLE `paralelos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `periodos_academicos`
--
ALTER TABLE `periodos_academicos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `periodo_lectivo_id` (`periodo_lectivo_id`);

--
-- Indices de la tabla `periodos_lectivos`
--
ALTER TABLE `periodos_lectivos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indices de la tabla `personas`
--
ALTER TABLE `personas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dni` (`dni`),
  ADD KEY `tipo_documento_id` (`tipo_documento_id`),
  ADD KEY `nacionalidad_id` (`nacionalidad_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indices de la tabla `roles_permisos`
--
ALTER TABLE `roles_permisos`
  ADD PRIMARY KEY (`rol_id`,`permiso_id`),
  ADD KEY `idx_permiso` (`permiso_id`);

--
-- Indices de la tabla `tipos_evaluacion`
--
ALTER TABLE `tipos_evaluacion`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_periodo_macro_nombre` (`periodo_lectivo_id`,`macro_categoria`,`nombre`);

--
-- Indices de la tabla `tipo_documento`
--
ALTER TABLE `tipo_documento`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `descripcion` (`descripcion`);

--
-- Indices de la tabla `unidad_educativa`
--
ALTER TABLE `unidad_educativa`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indices de la tabla `usuarios_roles`
--
ALTER TABLE `usuarios_roles`
  ADD PRIMARY KEY (`usuario_id`,`rol_id`),
  ADD KEY `idx_rol` (`rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `asignaturas`
--
ALTER TABLE `asignaturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `aulas_periodo`
--
ALTER TABLE `aulas_periodo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `instituciones`
--
ALTER TABLE `instituciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `insumos_evaluacion`
--
ALTER TABLE `insumos_evaluacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=217;

--
-- AUTO_INCREMENT de la tabla `malla_curricular`
--
ALTER TABLE `malla_curricular`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `matriculas`
--
ALTER TABLE `matriculas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=644;

--
-- AUTO_INCREMENT de la tabla `nacionalidades`
--
ALTER TABLE `nacionalidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `paralelos`
--
ALTER TABLE `paralelos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `periodos_academicos`
--
ALTER TABLE `periodos_academicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `periodos_lectivos`
--
ALTER TABLE `periodos_lectivos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `personas`
--
ALTER TABLE `personas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tipos_evaluacion`
--
ALTER TABLE `tipos_evaluacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tipo_documento`
--
ALTER TABLE `tipo_documento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `unidad_educativa`
--
ALTER TABLE `unidad_educativa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD CONSTRAINT `alumnos_ibfk_1` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`id`);

--
-- Filtros para la tabla `aulas_periodo`
--
ALTER TABLE `aulas_periodo`
  ADD CONSTRAINT `aulas_periodo_ibfk_1` FOREIGN KEY (`periodo_lectivo_id`) REFERENCES `periodos_lectivos` (`id`),
  ADD CONSTRAINT `aulas_periodo_ibfk_2` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`),
  ADD CONSTRAINT `aulas_periodo_ibfk_3` FOREIGN KEY (`paralelo_id`) REFERENCES `paralelos` (`id`);

--
-- Filtros para la tabla `insumos_evaluacion`
--
ALTER TABLE `insumos_evaluacion`
  ADD CONSTRAINT `insumos_evaluacion_ibfk_1` FOREIGN KEY (`periodo_academico_id`) REFERENCES `periodos_academicos` (`id`),
  ADD CONSTRAINT `insumos_evaluacion_ibfk_2` FOREIGN KEY (`tipo_evaluacion_id`) REFERENCES `tipos_evaluacion` (`id`),
  ADD CONSTRAINT `insumos_evaluacion_ibfk_3` FOREIGN KEY (`malla_curricular_id`) REFERENCES `malla_curricular` (`id`);

--
-- Filtros para la tabla `malla_curricular`
--
ALTER TABLE `malla_curricular`
  ADD CONSTRAINT `malla_curricular_ibfk_1` FOREIGN KEY (`periodo_lectivo_id`) REFERENCES `periodos_lectivos` (`id`),
  ADD CONSTRAINT `malla_curricular_ibfk_2` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`),
  ADD CONSTRAINT `malla_curricular_ibfk_3` FOREIGN KEY (`asignatura_id`) REFERENCES `asignaturas` (`id`);

--
-- Filtros para la tabla `matriculas`
--
ALTER TABLE `matriculas`
  ADD CONSTRAINT `matriculas_ibfk_1` FOREIGN KEY (`aula_periodo_id`) REFERENCES `aulas_periodo` (`id`),
  ADD CONSTRAINT `matriculas_ibfk_2` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos` (`id`);

--
-- Filtros para la tabla `menus`
--
ALTER TABLE `menus`
  ADD CONSTRAINT `menus_ibfk_1` FOREIGN KEY (`permiso_slug`) REFERENCES `permisos` (`slug`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `menus_ibfk_2` FOREIGN KEY (`padre_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `periodos_academicos`
--
ALTER TABLE `periodos_academicos`
  ADD CONSTRAINT `periodos_academicos_ibfk_1` FOREIGN KEY (`periodo_lectivo_id`) REFERENCES `periodos_lectivos` (`id`);

--
-- Filtros para la tabla `personas`
--
ALTER TABLE `personas`
  ADD CONSTRAINT `personas_ibfk_1` FOREIGN KEY (`tipo_documento_id`) REFERENCES `tipo_documento` (`id`),
  ADD CONSTRAINT `personas_ibfk_2` FOREIGN KEY (`nacionalidad_id`) REFERENCES `nacionalidades` (`id`);

--
-- Filtros para la tabla `roles_permisos`
--
ALTER TABLE `roles_permisos`
  ADD CONSTRAINT `fk_roles_permisos_permiso` FOREIGN KEY (`permiso_id`) REFERENCES `permisos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_roles_permisos_rol` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tipos_evaluacion`
--
ALTER TABLE `tipos_evaluacion`
  ADD CONSTRAINT `tipos_evaluacion_ibfk_1` FOREIGN KEY (`periodo_lectivo_id`) REFERENCES `periodos_lectivos` (`id`);

--
-- Filtros para la tabla `usuarios_roles`
--
ALTER TABLE `usuarios_roles`
  ADD CONSTRAINT `fk_usuarios_roles_rol` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuarios_roles_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
