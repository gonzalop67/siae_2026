-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-07-2026 a las 04:34:08
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
(1, 2, 'MAT-2026-0001', 'O+', NULL, 0, NULL, NULL, 'Alumno base registrado automáticamente por el sistema de semillas.', 1, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(2, 3, 'MAT-2026-0002', 'O+', NULL, 0, NULL, NULL, 'Alumno base registrado automáticamente por el sistema de semillas.', 1, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(3, 4, 'MAT-2026-0003', 'O+', NULL, 0, NULL, NULL, 'Alumno base registrado automáticamente por el sistema de semillas.', 1, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(4, 5, 'MAT-2026-0004', 'O+', NULL, 0, NULL, NULL, 'Alumno base registrado automáticamente por el sistema de semillas.', 1, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(5, 6, 'MAT-2026-0005', 'O+', NULL, 0, NULL, NULL, 'Alumno base registrado automáticamente por el sistema de semillas.', 1, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(6, 7, 'MAT-2026-0006', 'O+', NULL, 0, NULL, NULL, 'Alumno base registrado automáticamente por el sistema de semillas.', 1, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(7, 8, 'MAT-2026-0007', 'O+', NULL, 0, NULL, NULL, 'Alumno base registrado automáticamente por el sistema de semillas.', 1, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(8, 9, 'MAT-2026-0008', 'O+', NULL, 0, NULL, NULL, 'Alumno base registrado automáticamente por el sistema de semillas.', 1, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(9, 10, 'MAT-2026-0009', 'O+', NULL, 0, NULL, NULL, 'Alumno base registrado automáticamente por el sistema de semillas.', 1, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(10, 11, 'MAT-2026-0010', 'O+', NULL, 0, NULL, NULL, 'Alumno base registrado automáticamente por el sistema de semillas.', 1, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL);

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
(1, 'Matemáticas', 'MAT-01', 1, '2026-07-10 11:57:19', NULL),
(2, 'Lengua y Literatura', 'LEN-01', 1, '2026-07-10 11:57:19', NULL),
(3, 'Ciencias Naturales', 'CNA-01', 1, '2026-07-10 11:57:19', NULL),
(4, 'Estudios Sociales', 'ESS-01', 1, '2026-07-10 11:57:19', NULL),
(5, 'Inglés', 'ING-01', 1, '2026-07-10 11:57:19', NULL),
(6, 'Educación Física', 'EFI-01', 1, '2026-07-10 11:57:19', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aulas_periodo`
--

CREATE TABLE `aulas_periodo` (
  `id` int(11) NOT NULL,
  `periodo_lectivo_id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `paralelo_id` int(11) NOT NULL,
  `docente_id` int(11) DEFAULT NULL,
  `jornada` enum('Matutina','Vespertina','Nocturna') NOT NULL DEFAULT 'Matutina',
  `cupo_maximo` tinyint(4) NOT NULL DEFAULT 40
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `aulas_periodo`
--

INSERT INTO `aulas_periodo` (`id`, `periodo_lectivo_id`, `curso_id`, `paralelo_id`, `docente_id`, `jornada`, `cupo_maximo`) VALUES
(1, 1, 1, 1, 1, 'Matutina', 40),
(2, 1, 1, 2, 2, 'Matutina', 40),
(3, 1, 2, 1, 3, 'Matutina', 40),
(4, 1, 2, 2, 4, 'Matutina', 40),
(5, 1, 3, 1, 5, 'Matutina', 40),
(6, 1, 3, 2, 1, 'Matutina', 40),
(7, 1, 4, 1, 2, 'Matutina', 40),
(8, 1, 4, 2, 3, 'Matutina', 40),
(9, 1, 5, 1, 4, 'Matutina', 40),
(10, 1, 5, 2, 5, 'Matutina', 40),
(11, 1, 6, 1, 1, 'Matutina', 40),
(12, 1, 6, 2, 2, 'Matutina', 40);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `id` int(11) NOT NULL,
  `subnivel_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `seccion` enum('Matutina','Vespertina','Nocturna') NOT NULL DEFAULT 'Matutina',
  `orden` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`id`, `subnivel_id`, `nombre`, `seccion`, `orden`) VALUES
(1, 4, 'Octavo Año de EGB', 'Matutina', 1),
(2, 4, 'Noveno Año de EGB', 'Matutina', 2),
(3, 4, 'Décimo Año de EGB', 'Matutina', 3),
(4, 5, 'Primer Año de Bachillerato', 'Matutina', 1),
(5, 5, 'Segundo Año de Bachillerato', 'Matutina', 2),
(6, 5, 'Tercer Año de Bachillerato', 'Matutina', 3),
(7, 8, 'Primero de primaria', 'Matutina', 1);

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
(1, 1, 2, 1, 'Deber 1: Investigación y Conceptualización', '2026-05-15', 'Actividad formativa autónoma obligatoria correspondiente al bloque inicial.', '2026-07-10 11:57:19', NULL),
(2, 1, 2, 1, 'Lección Escrita N°1', '2026-05-15', 'Evaluación continua de control de destrezas adquiridas en clase.', '2026-07-10 11:57:19', NULL),
(3, 1, 3, 1, 'Evaluación de Base Estructurada T1', '2026-05-15', 'Examen sumativo obligatorio que valida los logros de aprendizaje del trimestre.', '2026-07-10 11:57:19', NULL),
(4, 1, 2, 7, 'Deber 1: Investigación y Conceptualización', '2026-05-15', 'Actividad formativa autónoma obligatoria correspondiente al bloque inicial.', '2026-07-10 11:57:19', NULL),
(5, 1, 2, 7, 'Lección Escrita N°1', '2026-05-15', 'Evaluación continua de control de destrezas adquiridas en clase.', '2026-07-10 11:57:19', NULL),
(6, 1, 3, 7, 'Evaluación de Base Estructurada T1', '2026-05-15', 'Examen sumativo obligatorio que valida los logros de aprendizaje del trimestre.', '2026-07-10 11:57:19', NULL),
(7, 1, 2, 2, 'Deber 1: Investigación y Conceptualización', '2026-05-15', 'Actividad formativa autónoma obligatoria correspondiente al bloque inicial.', '2026-07-10 11:57:19', NULL),
(8, 1, 2, 2, 'Lección Escrita N°1', '2026-05-15', 'Evaluación continua de control de destrezas adquiridas en clase.', '2026-07-10 11:57:19', NULL),
(9, 1, 3, 2, 'Evaluación de Base Estructurada T1', '2026-05-15', 'Examen sumativo obligatorio que valida los logros de aprendizaje del trimestre.', '2026-07-10 11:57:19', NULL),
(10, 1, 2, 8, 'Deber 1: Investigación y Conceptualización', '2026-05-15', 'Actividad formativa autónoma obligatoria correspondiente al bloque inicial.', '2026-07-10 11:57:19', NULL),
(11, 1, 2, 8, 'Lección Escrita N°1', '2026-05-15', 'Evaluación continua de control de destrezas adquiridas en clase.', '2026-07-10 11:57:19', NULL),
(12, 1, 3, 8, 'Evaluación de Base Estructurada T1', '2026-05-15', 'Examen sumativo obligatorio que valida los logros de aprendizaje del trimestre.', '2026-07-10 11:57:19', NULL),
(13, 1, 2, 3, 'Deber 1: Investigación y Conceptualización', '2026-05-15', 'Actividad formativa autónoma obligatoria correspondiente al bloque inicial.', '2026-07-10 11:57:19', NULL),
(14, 1, 2, 3, 'Lección Escrita N°1', '2026-05-15', 'Evaluación continua de control de destrezas adquiridas en clase.', '2026-07-10 11:57:19', NULL),
(15, 1, 3, 3, 'Evaluación de Base Estructurada T1', '2026-05-15', 'Examen sumativo obligatorio que valida los logros de aprendizaje del trimestre.', '2026-07-10 11:57:19', NULL),
(16, 1, 2, 4, 'Deber 1: Investigación y Conceptualización', '2026-05-15', 'Actividad formativa autónoma obligatoria correspondiente al bloque inicial.', '2026-07-10 11:57:19', NULL),
(17, 1, 2, 4, 'Lección Escrita N°1', '2026-05-15', 'Evaluación continua de control de destrezas adquiridas en clase.', '2026-07-10 11:57:19', NULL),
(18, 1, 3, 4, 'Evaluación de Base Estructurada T1', '2026-05-15', 'Examen sumativo obligatorio que valida los logros de aprendizaje del trimestre.', '2026-07-10 11:57:19', NULL),
(19, 1, 2, 5, 'Deber 1: Investigación y Conceptualización', '2026-05-15', 'Actividad formativa autónoma obligatoria correspondiente al bloque inicial.', '2026-07-10 11:57:19', NULL),
(20, 1, 2, 5, 'Lección Escrita N°1', '2026-05-15', 'Evaluación continua de control de destrezas adquiridas en clase.', '2026-07-10 11:57:19', NULL),
(21, 1, 3, 5, 'Evaluación de Base Estructurada T1', '2026-05-15', 'Examen sumativo obligatorio que valida los logros de aprendizaje del trimestre.', '2026-07-10 11:57:19', NULL),
(22, 1, 2, 9, 'Deber 1: Investigación y Conceptualización', '2026-05-15', 'Actividad formativa autónoma obligatoria correspondiente al bloque inicial.', '2026-07-10 11:57:19', NULL),
(23, 1, 2, 9, 'Lección Escrita N°1', '2026-05-15', 'Evaluación continua de control de destrezas adquiridas en clase.', '2026-07-10 11:57:19', NULL),
(24, 1, 3, 9, 'Evaluación de Base Estructurada T1', '2026-05-15', 'Examen sumativo obligatorio que valida los logros de aprendizaje del trimestre.', '2026-07-10 11:57:19', NULL),
(25, 1, 2, 6, 'Deber 1: Investigación y Conceptualización', '2026-05-15', 'Actividad formativa autónoma obligatoria correspondiente al bloque inicial.', '2026-07-10 11:57:19', NULL),
(26, 1, 2, 6, 'Lección Escrita N°1', '2026-05-15', 'Evaluación continua de control de destrezas adquiridas en clase.', '2026-07-10 11:57:19', NULL),
(27, 1, 3, 6, 'Evaluación de Base Estructurada T1', '2026-05-15', 'Examen sumativo obligatorio que valida los logros de aprendizaje del trimestre.', '2026-07-10 11:57:19', NULL),
(28, 1, 2, 10, 'Deber 1: Investigación y Conceptualización', '2026-05-15', 'Actividad formativa autónoma obligatoria correspondiente al bloque inicial.', '2026-07-10 11:57:19', NULL),
(29, 1, 2, 10, 'Lección Escrita N°1', '2026-05-15', 'Evaluación continua de control de destrezas adquiridas en clase.', '2026-07-10 11:57:19', NULL),
(30, 1, 3, 10, 'Evaluación de Base Estructurada T1', '2026-05-15', 'Examen sumativo obligatorio que valida los logros de aprendizaje del trimestre.', '2026-07-10 11:57:19', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `malla_curricular`
--

CREATE TABLE `malla_curricular` (
  `id` int(11) NOT NULL,
  `periodo_lectivo_id` int(11) NOT NULL,
  `subnivel_id` int(11) DEFAULT NULL,
  `curso_id` int(11) DEFAULT NULL,
  `asignatura_id` int(11) NOT NULL,
  `horas_semanales` tinyint(4) NOT NULL DEFAULT 4
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `malla_curricular`
--

INSERT INTO `malla_curricular` (`id`, `periodo_lectivo_id`, `subnivel_id`, `curso_id`, `asignatura_id`, `horas_semanales`) VALUES
(1, 1, 4, NULL, 1, 6),
(2, 1, 4, NULL, 2, 6),
(3, 1, 4, NULL, 3, 4),
(4, 1, 4, NULL, 4, 4),
(5, 1, 4, NULL, 5, 5),
(6, 1, 4, NULL, 6, 5),
(7, 1, NULL, 6, 1, 4),
(8, 1, NULL, 6, 2, 4),
(9, 1, NULL, 6, 5, 3),
(10, 1, NULL, 6, 6, 2);

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
(1, 1, 1, '2026-07-10', 'REG-2026-0001', 'Matriculado', '2026-07-10 11:57:19'),
(2, 1, 2, '2026-07-10', 'REG-2026-0002', 'Matriculado', '2026-07-10 11:57:19'),
(3, 1, 3, '2026-07-10', 'REG-2026-0003', 'Matriculado', '2026-07-10 11:57:19'),
(4, 1, 4, '2026-07-10', 'REG-2026-0004', 'Matriculado', '2026-07-10 11:57:19'),
(5, 1, 5, '2026-07-10', 'REG-2026-0005', 'Matriculado', '2026-07-10 11:57:19'),
(6, 1, 6, '2026-07-10', 'REG-2026-0006', 'Matriculado', '2026-07-10 11:57:19'),
(7, 1, 7, '2026-07-10', 'REG-2026-0007', 'Matriculado', '2026-07-10 11:57:19'),
(8, 1, 8, '2026-07-10', 'REG-2026-0008', 'Matriculado', '2026-07-10 11:57:19'),
(9, 1, 9, '2026-07-10', 'REG-2026-0009', 'Matriculado', '2026-07-10 11:57:19'),
(10, 1, 10, '2026-07-10', 'REG-2026-0010', 'Matriculado', '2026-07-10 11:57:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menus`
--

CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `url` varchar(100) NOT NULL,
  `icono` varchar(50) DEFAULT NULL,
  `permiso_slug` varchar(100) DEFAULT NULL,
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
(1, 'Dashboard', 'admin/dashboard', 'mdi mdi-airplay', 'admin-dashboard', NULL, 1, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(2, 'Administración', '#', 'mdi mdi-layers', 'ver-modulos-del-administrador', NULL, 2, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(3, 'Académico', '#', 'mdi mdi-school', 'ver-modulos-academico', NULL, 3, '2026-07-10 11:57:19', '2026-07-10 16:45:33', NULL),
(4, 'Permisos', 'permissions', NULL, 'listar-permisos', 2, 1, '2026-07-10 11:57:19', '2026-07-10 15:14:42', NULL),
(5, 'Roles', 'roles', NULL, 'listar-roles', 2, 2, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(6, 'Usuarios', 'usuarios', NULL, 'listar-usuarios', 2, 3, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(7, 'Menús', 'menus', NULL, 'listar-menus', 2, 4, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(8, 'Niveles', 'niveles', NULL, 'listar-niveles-academicos', 3, 1, '2026-07-10 15:56:46', '2026-07-10 16:45:44', NULL),
(9, 'Subniveles', 'subniveles', NULL, 'listar-subniveles-academicos', 3, 2, '2026-07-10 17:06:36', '2026-07-10 17:06:36', NULL),
(10, 'Cursos', 'cursos', NULL, 'listar-cursos', 3, 3, '2026-07-13 16:28:17', '2026-07-13 16:28:17', NULL),
(11, 'Asignaturas', 'asignaturas', NULL, 'listar-asignaturas', 3, 4, '2026-07-16 00:41:34', '2026-07-16 00:41:34', NULL);

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
(395, '2026_06_18_141434_create_roles_table.php', 1, '2026-07-10 11:57:19'),
(396, '2026_06_24_064702_create_permisos_table.php', 1, '2026-07-10 11:57:19'),
(397, '2026_06_27_094820_create_tipo_documento_table.php', 1, '2026-07-10 11:57:19'),
(398, '2026_06_27_095156_create_nacionalidades_table.php', 1, '2026-07-10 11:57:19'),
(399, '2026_06_27_095627_create_personas_table.php', 1, '2026-07-10 11:57:19'),
(400, '2026_07_03_093049_create_menus_table.php', 1, '2026-07-10 11:57:19'),
(401, '2026_07_06_062730_create_alumnos_table.php', 1, '2026-07-10 11:57:19'),
(402, '2026_07_09_014813_create_niveles_educativos_table.php', 1, '2026-07-10 11:57:19'),
(403, '2026_07_09_015110_create_subniveles_educativos_table.php', 1, '2026-07-10 11:57:19'),
(404, '2026_07_09_034250_create_usuarios_table.php', 1, '2026-07-10 11:57:19'),
(405, '2026_07_09_035101_create_usuarios_roles_table.php', 1, '2026-07-10 11:57:19'),
(406, '2026_07_09_040627_create_roles_permisos_table.php', 1, '2026-07-10 11:57:19'),
(407, '2026_07_09_041526_create_unidad_educativa_table.php', 1, '2026-07-10 11:57:19'),
(408, '2026_07_09_052958_create_cursos_table.php', 1, '2026-07-10 11:57:19'),
(409, '2026_07_09_065713_create_periodos_lectivos_table.php', 1, '2026-07-10 11:57:19'),
(410, '2026_07_09_074406_create_paralelos_table.php', 1, '2026-07-10 11:57:19'),
(411, '2026_07_09_075001_create_asignaturas_table.php', 1, '2026-07-10 11:57:19'),
(412, '2026_07_09_085132_create_malla_curricular_table.php', 1, '2026-07-10 11:57:19'),
(413, '2026_07_09_222943_create_periodos_academicos_table.php', 1, '2026-07-10 11:57:19'),
(414, '2026_07_09_231055_create_tipos_evaluacion_table.php', 1, '2026-07-10 11:57:19'),
(415, '2026_07_09_245201_create_aulas_periodo_table.php', 1, '2026-07-10 11:57:19'),
(416, '2026_07_09_253425_create_matriculas_table.php', 1, '2026-07-10 11:57:19'),
(417, '2026_07_09_261424_create_insumos_evaluacion_table.php', 1, '2026-07-10 11:57:19');

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
(1, 'Ecuatoriana', '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(2, 'Colombiana', '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(3, 'Venezolana', '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(4, 'Haitiana', '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(5, 'Peruana', '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `niveles_educativos`
--

CREATE TABLE `niveles_educativos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `niveles_educativos`
--

INSERT INTO `niveles_educativos` (`id`, `nombre`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Educación Inicial', '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(2, 'Educación General Básica', '2026-07-10 11:57:19', '2026-07-13 15:56:58', NULL),
(3, 'Bachillerato', '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL);

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
(1, 'Ciclo Sierra 2025-2026', '2025-09-01', '2026-06-30', 1, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id`, `nombre`, `slug`, `descripcion`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Crear Usuario', 'crear-usuario', 'Puede insertar nuevos usuarios.', '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(2, 'Actualizar Usuario', 'actualizar-usuario', 'Puede actualizar usuarios.', '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(3, 'Eliminar Usuario', 'eliminar-usuario', 'Puede eliminar usuarios.', '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(4, 'Listar Usuarios', 'listar-usuarios', 'Puede ver el listado de usuarios.', '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(5, 'Crear Rol', 'crear-rol', 'Puede insertar nuevos roles.', '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(6, 'Actualizar Rol', 'actualizar-rol', 'Puede actualizar roles.', '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(7, 'Eliminar Rol', 'eliminar-rol', 'Puede eliminar roles.', '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(8, 'Listar Roles', 'listar-roles', 'Puede ver el listado de roles.', '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(9, 'Crear Permiso', 'crear-permiso', 'Puede crear nuevos permisos.', '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(10, 'Actualizar Permiso', 'actualizar-permiso', 'Puede actualizar permisos.', '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(11, 'Eliminar Permiso', 'eliminar-permiso', 'Puede eliminar permisos.', '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(12, 'Listar Permisos', 'listar-permisos', 'Puede ver el listado de permisos.', '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(13, 'Ver Módulos del Administrador', 'ver-modulos-del-administrador', 'Sólo el rol con este permiso tendrá acceso a este menú.', '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(14, 'Listar Menús', 'listar-menus', 'Puede ver el listado de menús.', '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(15, 'Admin Dashboard', 'admin-dashboard', 'Permite ver el dashboard del panel del Administrador.', '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(16, 'Ver Módulos Académico', 'ver-modulos-academico', 'Permite ver el menú de los módulos de administración académica.', '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(17, 'Listar Niveles Académicos', 'listar-niveles-academicos', 'Permite ver el menú para el crud de niveles académicos.', '2026-07-10 15:56:11', '2026-07-10 15:56:11', NULL),
(18, 'Listar Subniveles Académicos', 'listar-subniveles-academicos', 'Permite ver el listado de los subniveles académicos.', '2026-07-10 17:05:45', '2026-07-10 17:05:45', NULL),
(19, 'Listar Cursos', 'listar-cursos', 'Permite ver el listado de cursos.', '2026-07-13 16:27:35', '2026-07-13 16:27:35', NULL),
(20, 'Listar Asignaturas', 'listar-asignaturas', 'Permite ver el listado de asignaturas', '2026-07-16 00:40:11', '2026-07-16 00:40:11', NULL);

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
(1, 1, 1, '1709290207', 'Gonzalo', 'Nicolás', 'Peñaherrera', 'Escobar', 'Ing. Gonzalo Peñaherrera', 'Peñaherrera Escobar Gonzalo Nicolás', NULL, NULL, 'Masculino', NULL, NULL, NULL, NULL, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(2, 1, 1, '1440268207', 'Verónica', 'Blanca', 'Vargas', 'Morales', 'Lic. Verónica Vargas', 'Vargas Morales Verónica Blanca', NULL, NULL, 'Femenino', NULL, NULL, NULL, NULL, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(3, 1, 1, '1740038482', 'Elizabeth', 'Tatiana', 'Pinto', 'Chávez', 'MSc. Elizabeth Pinto', 'Pinto Chávez Elizabeth Tatiana', NULL, NULL, 'Femenino', NULL, NULL, NULL, NULL, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(4, 1, 1, '0655298685', 'Daniel', 'David', 'Rodríguez', 'Salazar', 'MSc. Daniel Rodríguez', 'Rodríguez Salazar Daniel David', NULL, NULL, 'Masculino', NULL, NULL, NULL, NULL, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(5, 1, 1, '2435645201', 'Lorena', 'Sofía', 'Pinto', 'Ramos', 'Dr. Lorena Pinto', 'Pinto Ramos Lorena Sofía', NULL, NULL, 'Femenino', NULL, NULL, NULL, NULL, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(6, 1, 1, '0951555622', 'Fernando', 'Ricardo', 'Rodríguez', 'Moreno', 'Dr. Fernando Rodríguez', 'Rodríguez Moreno Fernando Ricardo', NULL, NULL, 'Masculino', NULL, NULL, NULL, NULL, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(7, 1, 1, '1413950955', 'Daniela', 'Elizabeth', 'Rivera', 'Pinto', 'Dr. Daniela Rivera', 'Rivera Pinto Daniela Elizabeth', NULL, NULL, 'Femenino', NULL, NULL, NULL, NULL, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(8, 1, 1, '0908053317', 'Patricia', 'Sandra', 'Herrera', 'Morales', 'Dr. Patricia Herrera', 'Herrera Morales Patricia Sandra', NULL, NULL, 'Femenino', NULL, NULL, NULL, NULL, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(9, 1, 1, '1530497856', 'Anthony', 'Anthony', 'Espinoza', 'Muñoz', 'MSc. Anthony Espinoza', 'Espinoza Muñoz Anthony Anthony', NULL, NULL, 'Masculino', NULL, NULL, NULL, NULL, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(10, 1, 1, '1326747498', 'Camila', 'Camila', 'Guerrero', 'Rivera', 'Dr. Camila Guerrero', 'Guerrero Rivera Camila Camila', NULL, NULL, 'Femenino', NULL, NULL, NULL, NULL, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(11, 1, 1, '1415484714', 'Andrés', 'Roberto', 'Castro', 'Salazar', 'MSc. Andrés Castro', 'Castro Salazar Andrés Roberto', NULL, NULL, 'Masculino', NULL, NULL, NULL, NULL, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(12, 1, 1, '1016829713', 'Estefanía', 'Elena', 'Herrera', 'Morales', 'MSc. Estefanía Herrera', 'Herrera Morales Estefanía Elena', NULL, NULL, 'Femenino', NULL, NULL, NULL, NULL, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(13, 1, 1, '2146803586', 'Manuel', 'Santiago', 'Flores', 'Ramírez', 'Dr. Manuel Flores', 'Flores Ramírez Manuel Santiago', NULL, NULL, 'Masculino', NULL, NULL, NULL, NULL, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(14, 1, 1, '0848370896', 'Francisco', 'Ricardo', 'Gómez', 'Reyes', 'Dr. Francisco Gómez', 'Gómez Reyes Francisco Ricardo', NULL, NULL, 'Masculino', NULL, NULL, NULL, NULL, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(15, 1, 1, '1108770007', 'Aracely', 'Andrea', 'Velasco', 'Velasco', 'Dr. Aracely Velasco', 'Velasco Velasco Aracely Andrea', NULL, NULL, 'Femenino', NULL, NULL, NULL, NULL, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(16, 1, 1, '2423923156', 'Mateo', 'Fernando', 'Castillo', 'Toapanta', 'MSc. Mateo Castillo', 'Castillo Toapanta Mateo Fernando', NULL, NULL, 'Masculino', NULL, NULL, NULL, NULL, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(17, 1, 1, '0200324333', 'Jessica', 'Sandra', 'Rivera', 'Quishpe', 'MSc. Jessica Rivera', 'Rivera Quishpe Jessica Sandra', NULL, NULL, 'Femenino', NULL, NULL, NULL, NULL, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(18, 1, 1, '2208639480', 'Anthony', 'Franklin', 'Jiménez', 'Muñoz', 'Dr. Anthony Jiménez', 'Jiménez Muñoz Anthony Franklin', NULL, NULL, 'Masculino', NULL, NULL, NULL, NULL, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(19, 1, 1, '2005215252', 'Sofía', 'Verónica', 'Gómez', 'Muñoz', 'Lic. Sofía Gómez', 'Gómez Muñoz Sofía Verónica', NULL, NULL, 'Femenino', NULL, NULL, NULL, NULL, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(20, 1, 1, '1225209111', 'Mónica', 'Elizabeth', 'Peña', 'Salazar', 'MSc. Mónica Peña', 'Peña Salazar Mónica Elizabeth', NULL, NULL, 'Femenino', NULL, NULL, NULL, NULL, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(21, 1, 1, '0602632564', 'Alex', 'Alejandro', 'Gutiérrez', 'Muñoz', 'Lic. Alex Gutiérrez', 'Gutiérrez Muñoz Alex Alejandro', NULL, NULL, 'Masculino', NULL, NULL, NULL, NULL, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL);

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
(1, 'Administrador', 'administrador', 'Acceso total a todos los módulos del sistema administrativo.', '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(2, 'Autoridad', 'autoridad', 'Acceso a reportes de gestión educativa.', '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(3, 'Coordinador', 'coordinador', 'Gestión y supervisión de ofertas educativas y asignaciones.', '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(4, 'Docente', 'docente', 'Registro de calificaciones, asistencias y rúbricas.', '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(5, 'Estudiante', 'estudiante', 'Consulta de historial académico y perfiles.', '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(6, 'Secretaría', 'secretaria', 'Acceso a Matriculación y Reportes.', '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(7, 'Tutor', 'tutor', 'Acceso a reportes de calificaciones y comportamiento.', '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL);

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
(1, 15),
(1, 16),
(1, 17),
(1, 18),
(1, 19),
(1, 20);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subniveles_educativos`
--

CREATE TABLE `subniveles_educativos` (
  `id` int(11) NOT NULL,
  `nivel_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `orden` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `subniveles_educativos`
--

INSERT INTO `subniveles_educativos` (`id`, `nivel_id`, `nombre`, `orden`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, 'Preparatoria', 1, '2026-07-10 11:57:19', '2026-07-12 20:37:55', '2026-07-12 15:28:42'),
(2, 2, 'Básica Elemental (2.º, 3.º y 4.º Grado)', 3, '2026-07-10 11:57:19', '2026-07-13 13:58:36', NULL),
(3, 2, 'Básica Media (5.º, 6.º y 7.º Grado)', 4, '2026-07-10 11:57:19', '2026-07-13 14:20:34', NULL),
(4, 2, 'Básica Superior (8.º, 9.º y 10.º Grado)', 5, '2026-07-10 11:57:19', '2026-07-13 14:20:53', NULL),
(5, 3, 'Bachillerato en Ciencias', 1, '2026-07-10 11:57:19', '2026-07-13 14:21:41', NULL),
(6, 1, 'Inicial 1 (No obligatorio)', 1, '2026-07-12 20:44:05', '2026-07-14 04:59:56', NULL),
(7, 1, 'Inicial 2 (Obligatorio)', 2, '2026-07-13 08:33:45', '2026-07-13 13:47:24', NULL),
(8, 2, 'Preparatoria (1.º Grado)', 1, '2026-07-13 13:32:24', '2026-07-13 13:35:51', NULL),
(9, 3, 'Bachillerato Técnico', 2, '2026-07-13 14:45:30', '2026-07-13 14:45:30', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_evaluacion`
--

CREATE TABLE `tipos_evaluacion` (
  `id` int(11) NOT NULL,
  `periodo_lectivo_id` int(11) NOT NULL,
  `macro_categoria` enum('formativa','sumativa') NOT NULL,
  `parcial` enum('parcial_1','parcial_2','ninguno') NOT NULL DEFAULT 'ninguno',
  `nombre` varchar(100) NOT NULL,
  `ponderacion_macro` decimal(5,2) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos_evaluacion`
--

INSERT INTO `tipos_evaluacion` (`id`, `periodo_lectivo_id`, `macro_categoria`, `parcial`, `nombre`, `ponderacion_macro`, `descripcion`) VALUES
(1, 1, 'formativa', 'ninguno', 'Actividades Individuales', 70.00, 'Lecciones, pruebas, tareas escritas o trabajos prácticos realizados de forma autónoma.'),
(2, 1, 'formativa', 'ninguno', 'Actividades Grupales', 70.00, 'Proyectos en equipo, debates, exposiciones o talleres prácticos en clase.'),
(3, 1, 'sumativa', 'ninguno', 'Evaluación de Periodo Académico', 30.00, 'Examen de base estructurada que mide los logros de aprendizaje del trimestre/quimestre.'),
(4, 1, 'sumativa', 'ninguno', 'Proyecto Interdisciplinar', 30.00, 'Evidencia final de la aplicación integrada de saberes de múltiples asignaturas.');

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
(1, 'Cédula de Identidad', '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(2, 'Pasaporte', '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(3, 'Carnet de refugiado', '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(4, 'Cédula colombiana', '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(5, 'Cédula venezolana', '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidad_educativa`
--

CREATE TABLE `unidad_educativa` (
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
(1, 1, 'Administrador', 'gonzalop67@gmail.com', 'eJCYkBmXtXugXg==', '0', NULL, NULL, '992919889.png', 1, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(2, 2, 'verónica.vargas2', 'verónica.vargas2@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(3, 3, 'elizabeth.pinto3', 'elizabeth.pinto3@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(4, 4, 'daniel.rodríguez4', 'daniel.rodríguez4@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(5, 5, 'lorena.pinto5', 'lorena.pinto5@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(6, 6, 'fernando.rodríguez6', 'fernando.rodríguez6@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(7, 7, 'daniela.rivera7', 'daniela.rivera7@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(8, 8, 'patricia.herrera8', 'patricia.herrera8@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(9, 9, 'anthony.espinoza9', 'anthony.espinoza9@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(10, 10, 'camila.guerrero10', 'camila.guerrero10@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(11, 11, 'andrés.castro11', 'andrés.castro11@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(12, 12, 'estefanía.herrera12', 'estefanía.herrera12@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(13, 13, 'manuel.flores13', 'manuel.flores13@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(14, 14, 'francisco.gómez14', 'francisco.gómez14@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(15, 15, 'aracely.velasco15', 'aracely.velasco15@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(16, 16, 'mateo.castillo16', 'mateo.castillo16@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(17, 17, 'jessica.rivera17', 'jessica.rivera17@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(18, 18, 'anthony.jiménez18', 'anthony.jiménez18@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(19, 19, 'sofía.gómez19', 'sofía.gómez19@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(20, 20, 'mónica.peña20', 'mónica.peña20@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL),
(21, 21, 'alex.gutiérrez21', 'alex.gutiérrez21@siae.edu.ec', 'W6/NwjrR5Cy0R/CdCw==', '0', NULL, NULL, 'default_avatar.png', 1, '2026-07-10 11:57:19', '2026-07-10 11:57:19', NULL);

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
(1, 1, '2026-07-10 11:57:19'),
(2, 4, '2026-07-10 11:57:19'),
(3, 4, '2026-07-10 11:57:19'),
(4, 4, '2026-07-10 11:57:19'),
(5, 4, '2026-07-10 11:57:19'),
(6, 4, '2026-07-10 11:57:19'),
(7, 4, '2026-07-10 11:57:19'),
(8, 4, '2026-07-10 11:57:19'),
(9, 4, '2026-07-10 11:57:19'),
(10, 4, '2026-07-10 11:57:19'),
(11, 4, '2026-07-10 11:57:19'),
(12, 4, '2026-07-10 11:57:19'),
(13, 4, '2026-07-10 11:57:19'),
(14, 4, '2026-07-10 11:57:19'),
(15, 4, '2026-07-10 11:57:19'),
(16, 4, '2026-07-10 11:57:19'),
(17, 4, '2026-07-10 11:57:19'),
(18, 4, '2026-07-10 11:57:19'),
(19, 4, '2026-07-10 11:57:19'),
(20, 4, '2026-07-10 11:57:19'),
(21, 4, '2026-07-10 11:57:19');

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
  ADD KEY `fk_aulas_curso` (`curso_id`),
  ADD KEY `fk_aulas_paralelo` (`paralelo_id`),
  ADD KEY `fk_aulas_tutor` (`docente_id`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cursos_subnivel` (`subnivel_id`);

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
  ADD UNIQUE KEY `idx_malla_subnivel_materia` (`periodo_lectivo_id`,`subnivel_id`,`asignatura_id`),
  ADD UNIQUE KEY `idx_malla_curso_materia` (`periodo_lectivo_id`,`curso_id`,`asignatura_id`),
  ADD KEY `fk_malla_subnivel` (`subnivel_id`),
  ADD KEY `fk_malla_curso` (`curso_id`),
  ADD KEY `fk_malla_asignatura` (`asignatura_id`);

--
-- Indices de la tabla `matriculas`
--
ALTER TABLE `matriculas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_alumno_aula_periodo` (`alumno_id`,`aula_periodo_id`),
  ADD KEY `fk_matricula_aula` (`aula_periodo_id`);

--
-- Indices de la tabla `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_menus_permiso` (`permiso_slug`),
  ADD KEY `fk_menus_padre` (`padre_id`);

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
-- Indices de la tabla `niveles_educativos`
--
ALTER TABLE `niveles_educativos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `paralelos`
--
ALTER TABLE `paralelos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

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
-- Indices de la tabla `subniveles_educativos`
--
ALTER TABLE `subniveles_educativos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_subniveles_nivel` (`nivel_id`);

--
-- Indices de la tabla `tipos_evaluacion`
--
ALTER TABLE `tipos_evaluacion`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_periodo_macro_parcial_nombre` (`periodo_lectivo_id`,`macro_categoria`,`parcial`,`nombre`);

--
-- Indices de la tabla `tipo_documento`
--
ALTER TABLE `tipo_documento`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `descripcion` (`descripcion`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `persona_id` (`persona_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `insumos_evaluacion`
--
ALTER TABLE `insumos_evaluacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `malla_curricular`
--
ALTER TABLE `malla_curricular`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `matriculas`
--
ALTER TABLE `matriculas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=418;

--
-- AUTO_INCREMENT de la tabla `nacionalidades`
--
ALTER TABLE `nacionalidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `niveles_educativos`
--
ALTER TABLE `niveles_educativos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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
-- AUTO_INCREMENT de la tabla `subniveles_educativos`
--
ALTER TABLE `subniveles_educativos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
  ADD CONSTRAINT `fk_aulas_curso` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_aulas_paralelo` FOREIGN KEY (`paralelo_id`) REFERENCES `paralelos` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_aulas_periodo_lectivo` FOREIGN KEY (`periodo_lectivo_id`) REFERENCES `periodos_lectivos` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_aulas_tutor` FOREIGN KEY (`docente_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD CONSTRAINT `fk_cursos_subnivel` FOREIGN KEY (`subnivel_id`) REFERENCES `subniveles_educativos` (`id`) ON UPDATE CASCADE;

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
  ADD CONSTRAINT `fk_malla_asignatura` FOREIGN KEY (`asignatura_id`) REFERENCES `asignaturas` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_malla_curso` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_malla_periodo` FOREIGN KEY (`periodo_lectivo_id`) REFERENCES `periodos_lectivos` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_malla_subnivel` FOREIGN KEY (`subnivel_id`) REFERENCES `subniveles_educativos` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `matriculas`
--
ALTER TABLE `matriculas`
  ADD CONSTRAINT `fk_matricula_alumno` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_matricula_aula` FOREIGN KEY (`aula_periodo_id`) REFERENCES `aulas_periodo` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `menus`
--
ALTER TABLE `menus`
  ADD CONSTRAINT `fk_menus_padre` FOREIGN KEY (`padre_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_menus_permiso` FOREIGN KEY (`permiso_slug`) REFERENCES `permisos` (`slug`) ON DELETE SET NULL ON UPDATE CASCADE;

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
-- Filtros para la tabla `subniveles_educativos`
--
ALTER TABLE `subniveles_educativos`
  ADD CONSTRAINT `fk_subniveles_nivel` FOREIGN KEY (`nivel_id`) REFERENCES `niveles_educativos` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `tipos_evaluacion`
--
ALTER TABLE `tipos_evaluacion`
  ADD CONSTRAINT `fk_tipos_eval_periodo` FOREIGN KEY (`periodo_lectivo_id`) REFERENCES `periodos_lectivos` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`id`);

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
