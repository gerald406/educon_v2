-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-11-2025 a las 23:03:19
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
-- Base de datos: `laravel_educon`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `academic_activities`
--

CREATE TABLE `academic_activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `teacher_assignment_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `activity_type` enum('practice','project','research','presentation','exam','workshop','laboratory') NOT NULL,
  `assigned_date` datetime NOT NULL,
  `due_date` datetime NOT NULL,
  `weight` decimal(5,2) NOT NULL DEFAULT 0.00,
  `activity_file_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `academic_periods`
--

CREATE TABLE `academic_periods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `institution_id` bigint(20) UNSIGNED NOT NULL,
  `academic_year_id` bigint(20) UNSIGNED DEFAULT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `enrollment_start_date` date NOT NULL,
  `enrollment_end_date` date NOT NULL,
  `classes_start_date` date NOT NULL,
  `classes_end_date` date NOT NULL,
  `grade_entry_start_date` datetime DEFAULT NULL,
  `grade_entry_end_date` datetime DEFAULT NULL,
  `status` enum('planned','active','closed') NOT NULL DEFAULT 'planned',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `academic_periods`
--

INSERT INTO `academic_periods` (`id`, `institution_id`, `academic_year_id`, `code`, `name`, `start_date`, `end_date`, `enrollment_start_date`, `enrollment_end_date`, `classes_start_date`, `classes_end_date`, `grade_entry_start_date`, `grade_entry_end_date`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 2, '2025-II', 'Periodo Académico 2025 II', '2025-08-31', '2025-12-31', '2025-08-24', '2025-09-05', '2025-09-01', '2025-12-19', '2025-12-29 09:50:00', '2025-12-31 09:50:00', 'active', '2025-11-26 19:50:59', '2025-11-26 19:50:59', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `academic_records`
--

CREATE TABLE `academic_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `didactic_unit_id` bigint(20) UNSIGNED NOT NULL,
  `academic_period_id` bigint(20) UNSIGNED NOT NULL,
  `final_grade` decimal(4,2) NOT NULL,
  `credits_earned` int(11) NOT NULL,
  `course_status` enum('approved','failed','withdrawn','nsp') NOT NULL,
  `times_taken` int(11) NOT NULL DEFAULT 1,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `academic_years`
--

CREATE TABLE `academic_years` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `institution_id` bigint(20) UNSIGNED NOT NULL,
  `year` year(4) NOT NULL,
  `name` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('planned','active','closed') NOT NULL DEFAULT 'planned',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `academic_years`
--

INSERT INTO `academic_years` (`id`, `institution_id`, `year`, `name`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, '2024', 'Año Académico 2024', '2024-01-01', '2024-12-31', 'closed', '2025-11-26 18:45:39', '2025-11-26 18:45:39'),
(2, 1, '2025', 'Año Académico 2025', '2025-01-01', '2025-12-31', 'active', '2025-11-26 18:45:39', '2025-11-26 18:45:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `activity_submissions`
--

CREATE TABLE `activity_submissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `academic_activity_id` bigint(20) UNSIGNED NOT NULL,
  `registration_id` bigint(20) UNSIGNED NOT NULL,
  `submission_date` timestamp NOT NULL DEFAULT '2025-11-26 18:44:46',
  `submission_file_url` varchar(255) DEFAULT NULL,
  `student_comments` text DEFAULT NULL,
  `teacher_comments` text DEFAULT NULL,
  `grade` decimal(4,2) DEFAULT NULL,
  `review_date` timestamp NULL DEFAULT NULL,
  `reviewed_by_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('submitted','reviewed') NOT NULL DEFAULT 'submitted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admission_modalities`
--

CREATE TABLE `admission_modalities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `type` enum('ordinario','extraordinario') NOT NULL,
  `requirements` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `admission_modalities`
--

INSERT INTO `admission_modalities` (`id`, `name`, `type`, `requirements`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Examen Ordinario', 'ordinario', NULL, 1, '2025-11-26 18:45:43', '2025-11-26 18:45:43'),
(2, 'CEPRE JAE', 'extraordinario', NULL, 1, '2025-11-26 18:45:43', '2025-11-26 18:45:43'),
(3, 'Primeros Puestos', 'extraordinario', NULL, 1, '2025-11-26 18:45:43', '2025-11-26 18:45:43'),
(4, 'Deportistas Calificados', 'extraordinario', NULL, 1, '2025-11-26 18:45:43', '2025-11-26 18:45:43'),
(5, 'Ley N° 29248 Servicio Militar Obligatorio', 'extraordinario', NULL, 1, '2025-11-26 18:45:43', '2025-11-26 18:45:43'),
(6, 'Ley N° 29973 y 29643 Persona con Discapacidad', 'extraordinario', NULL, 1, '2025-11-26 18:45:43', '2025-11-26 18:45:43'),
(7, 'Ley N° 28592 P.I.R. (Plan Integral de Reparaciones)', 'extraordinario', NULL, 1, '2025-11-26 18:45:43', '2025-11-26 18:45:43'),
(8, 'Ley N° 28131 Artistas Calificados', 'extraordinario', NULL, 1, '2025-11-26 18:45:43', '2025-11-26 18:45:43'),
(9, 'Ley N° 30490 y 29600 Persona Adulta Mayor y Reinserción Escolar', 'extraordinario', NULL, 1, '2025-11-26 18:45:43', '2025-11-26 18:45:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admission_offerings`
--

CREATE TABLE `admission_offerings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `academic_period_id` bigint(20) UNSIGNED NOT NULL,
  `career_id` bigint(20) UNSIGNED NOT NULL,
  `shift_id` bigint(20) UNSIGNED NOT NULL,
  `vacancies` int(10) UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `admission_offerings`
--

INSERT INTO `admission_offerings` (`id`, `academic_period_id`, `career_id`, `shift_id`, `vacancies`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 40, 1, '2025-11-26 19:52:56', '2025-11-26 19:52:56'),
(2, 1, 1, 3, 40, 1, '2025-11-26 19:53:07', '2025-11-26 19:53:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `announcements`
--

CREATE TABLE `announcements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `announcement_type` enum('news','announcement','event','notice','urgent') NOT NULL,
  `target_audience` enum('all','students','teachers') NOT NULL DEFAULT 'all',
  `publish_date` datetime NOT NULL,
  `expiration_date` datetime DEFAULT NULL,
  `attachment_url` varchar(255) DEFAULT NULL,
  `published_by_user_id` bigint(20) UNSIGNED NOT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('draft','published','archived') NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `applicants`
--

CREATE TABLE `applicants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `gender` enum('masculino','femenino') NOT NULL,
  `birthday` date NOT NULL,
  `ubigeo_birth_id` varchar(10) DEFAULT NULL,
  `photo_url` varchar(255) DEFAULT NULL,
  `origin_school_id` bigint(20) UNSIGNED DEFAULT NULL,
  `school_graduation_year` year(4) DEFAULT NULL,
  `admission_offering_id` bigint(20) UNSIGNED DEFAULT NULL,
  `admission_modality_id` bigint(20) UNSIGNED DEFAULT NULL,
  `financial_entity_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payment_operation_code` varchar(50) DEFAULT NULL,
  `code` varchar(20) DEFAULT NULL,
  `exam_score` decimal(5,2) DEFAULT NULL,
  `merit_position` int(11) DEFAULT NULL,
  `application_status` enum('registrado','evaluado','aprobado','sin_vacante','cancelado') NOT NULL DEFAULT 'registrado',
  `registration_step` int(11) NOT NULL DEFAULT 1,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `applicants`
--

INSERT INTO `applicants` (`id`, `user_id`, `phone`, `address`, `gender`, `birthday`, `ubigeo_birth_id`, `photo_url`, `origin_school_id`, `school_graduation_year`, `admission_offering_id`, `admission_modality_id`, `financial_entity_id`, `payment_operation_code`, `code`, `exam_score`, `merit_position`, `application_status`, `registration_step`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 62, '987654321', 'Jr puno 123', 'masculino', '1988-07-28', '210101', 'applicants/9eOxQ1CLPSjX0UxHV7MBQzdRgaMUlFICKu0CVeOn.png', 2394, '2020', 1, 1, 1, '123', '45537302', 15.00, NULL, 'aprobado', 5, NULL, '2025-11-26 19:54:57', '2025-11-26 20:07:04', NULL),
(2, 63, '91234568', 'Jr altiplano 44', 'femenino', '1986-12-13', '210103', 'applicants/vXVlDcS6uYdsc2qRJdvlJOvqKSoXi9us2g0V0Rln.png', 142, '2021', 1, 1, 1, '2452', '43854482', 16.00, NULL, 'aprobado', 5, NULL, '2025-11-26 20:32:39', '2025-11-26 21:55:28', NULL),
(3, 64, '912121212', 'je puno 12344', 'masculino', '1990-12-13', '210101', 'applicants/NBe1UCz6jZ83lT5oF0Sy2uX00MgOxQIK2yVYQBx3.png', 867, '2020', 1, 1, 1, '235246423', '45899134', 20.00, NULL, 'aprobado', 5, NULL, '2025-11-28 21:56:32', '2025-11-28 22:01:12', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `attendances`
--

CREATE TABLE `attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `registration_id` bigint(20) UNSIGNED NOT NULL,
  `schedule_id` bigint(20) UNSIGNED NOT NULL,
  `registered_by_user_id` bigint(20) UNSIGNED NOT NULL,
  `class_date` date NOT NULL,
  `attendance_type` enum('present','absent','late','justified') NOT NULL,
  `late_minutes` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-bae7cfd54a80a4f1eb1317cc11a2ebe7', 'i:1;', 1764450717),
('laravel-cache-bae7cfd54a80a4f1eb1317cc11a2ebe7:timer', 'i:1764450717;', 1764450717),
('laravel-cache-spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:37:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:21:\"gestionar-institucion\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:7;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:23:\"gestionar-configuracion\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:7;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:30:\"gestionar-estructura-academica\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:7;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:24:\"gestionar-prerrequisitos\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:3;i:1;i:7;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:18:\"gestionar-docentes\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:7;i:1;i:8;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:21:\"gestionar-estudiantes\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:4;i:1;i:7;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:18:\"gestionar-periodos\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:4;i:1;i:7;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:25:\"gestionar-carga-academica\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:4;i:1;i:7;i:2;i:8;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:18:\"gestionar-horarios\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:3;i:1;i:7;i:2;i:8;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:15:\"aprobar-silabos\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:3;i:1;i:7;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:15:\"registrar-notas\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:2;i:1;i:3;i:2;i:7;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:20:\"registrar-asistencia\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:2;i:1;i:3;i:2;i:7;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:12:\"subir-silabo\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:2;i:1;i:3;i:2;i:7;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:12:\"matricularse\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:7;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:20:\"entregar-actividades\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:7;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:19:\"ver-mis-asistencias\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:7;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:15:\"registrar-pagos\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:5;i:1;i:7;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:23:\"gestionar-certificacion\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:4;i:1;i:7;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:20:\"gestionar-biblioteca\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:7;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:19:\"registrar-prestamos\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:7;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:18:\"gestionar-admision\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:4;i:1;i:7;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:18:\"gestionar-anuncios\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:4;i:1;i:7;i:2;i:8;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:24:\"gestionar-cuadro-meritos\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:4;i:1;i:7;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:16:\"revisar-entregas\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:2;i:1;i:3;i:2;i:7;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:22:\"ver-reporte-asistencia\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:2;i:1;i:3;i:2;i:7;i:3;i:8;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:20:\"descargar-acta-final\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:2;i:1;i:3;i:2;i:7;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:34:\"ver-reporte-acumulativo-asistencia\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:2;i:1;i:3;i:2;i:7;i:3;i:8;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:23:\"gestionar-sesiones-caja\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:5;i:1;i:7;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:22:\"gestionar-correlativos\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:7;}}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:19:\"anular-comprobantes\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:7;}}i:30;a:4:{s:1:\"a\";i:31;s:1:\"b\";s:18:\"registrar-tramites\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:5;i:1;i:7;}}i:31;a:4:{s:1:\"a\";i:32;s:1:\"b\";s:21:\"gestionar-actividades\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:2;i:1;i:3;i:2;i:7;i:3;i:8;}}i:32;a:4:{s:1:\"a\";i:33;s:1:\"b\";s:28:\"gestionar-reservas-matricula\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:4;i:1;i:7;}}i:33;a:4:{s:1:\"a\";i:34;s:1:\"b\";s:27:\"gestionar-reincorporaciones\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:4;i:1;i:7;}}i:34;a:4:{s:1:\"a\";i:35;s:1:\"b\";s:27:\"gestionar-matricula-regular\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:4;i:1;i:7;}}i:35;a:4:{s:1:\"a\";i:36;s:1:\"b\";s:15:\"gestionar-roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:7;}}i:36;a:4:{s:1:\"a\";i:37;s:1:\"b\";s:18:\"gestionar-usuarios\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:7;}}}s:5:\"roles\";a:7:{i:0;a:3:{s:1:\"a\";i:7;s:1:\"b\";s:13:\"Administrador\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:11:\"Coordinador\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:8;s:1:\"b\";s:13:\"Asistente JUA\";s:1:\"c\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";i:4;s:1:\"b\";s:20:\"Secretario Academico\";s:1:\"c\";s:3:\"web\";}i:4;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:7:\"Docente\";s:1:\"c\";s:3:\"web\";}i:5;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:10:\"Estudiante\";s:1:\"c\";s:3:\"web\";}i:6;a:3:{s:1:\"a\";i:5;s:1:\"b\";s:9:\"Tesoreria\";s:1:\"c\";s:3:\"web\";}}}', 1764535834);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `careers`
--

CREATE TABLE `careers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `institution_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(10) NOT NULL,
  `name` varchar(150) NOT NULL,
  `duration_semesters` int(11) NOT NULL DEFAULT 6,
  `degree_awarded` varchar(200) DEFAULT NULL,
  `authorization_resolution` varchar(50) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `careers`
--

INSERT INTO `careers` (`id`, `institution_id`, `code`, `name`, `duration_semesters`, `degree_awarded`, `authorization_resolution`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'APSTI', 'Administración de Plataformas y Servicios de Tecnologías de Información', 6, 'Profesional Técnico en Administración de Plataformas y Servicios de TI', 'R.D. 001-2021', 'active', '2025-11-26 18:45:43', '2025-11-26 18:45:43', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cash_sessions`
--

CREATE TABLE `cash_sessions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `opening_time` datetime NOT NULL,
  `opening_balance` decimal(10,2) NOT NULL,
  `closing_time` datetime DEFAULT NULL,
  `closing_balance_cash` decimal(10,2) DEFAULT NULL,
  `calculated_cash` decimal(10,2) DEFAULT NULL,
  `total_other_methods` decimal(10,2) DEFAULT NULL,
  `difference` decimal(10,2) DEFAULT NULL,
  `status` enum('open','closed') NOT NULL DEFAULT 'open',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cash_sessions`
--

INSERT INTO `cash_sessions` (`id`, `user_id`, `opening_time`, `opening_balance`, `closing_time`, `closing_balance_cash`, `calculated_cash`, `total_other_methods`, `difference`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, '2025-11-26 15:19:45', 0.00, NULL, NULL, NULL, NULL, NULL, 'open', NULL, '2025-11-26 20:19:45', '2025-11-26 20:19:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `certificates`
--

CREATE TABLE `certificates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `certificate_type` enum('modular','grades','studies','graduation') NOT NULL,
  `module_id` bigint(20) UNSIGNED DEFAULT NULL,
  `code` varchar(50) NOT NULL,
  `issue_date` date NOT NULL,
  `document_url` varchar(255) DEFAULT NULL,
  `issued_by_user_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('valid','cancelled','expired') NOT NULL DEFAULT 'valid',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `classroom_resources`
--

CREATE TABLE `classroom_resources` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `classroom_code` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `building` varchar(100) DEFAULT NULL,
  `floor` varchar(10) DEFAULT NULL,
  `capacity` int(11) NOT NULL,
  `has_projector` tinyint(1) NOT NULL DEFAULT 0,
  `has_computers` tinyint(1) NOT NULL DEFAULT 0,
  `computer_count` int(11) NOT NULL DEFAULT 0,
  `has_air_conditioning` tinyint(1) NOT NULL DEFAULT 0,
  `location` text DEFAULT NULL,
  `status` enum('available','maintenance','unavailable') NOT NULL DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `classroom_resources`
--

INSERT INTO `classroom_resources` (`id`, `classroom_code`, `name`, `building`, `floor`, `capacity`, `has_projector`, `has_computers`, `computer_count`, `has_air_conditioning`, `location`, `status`, `created_at`, `updated_at`) VALUES
(1, 'LAB-143', 'Laboratorio de Cómputo', 'Pabellón C', '2', 30, 1, 1, 30, 1, NULL, 'available', '2025-11-26 18:45:43', '2025-11-26 18:45:43'),
(2, 'A-144', 'Aula Común', 'Pabellón B', '3', 40, 1, 0, 0, 0, NULL, 'available', '2025-11-26 18:45:43', '2025-11-26 18:45:43'),
(3, 'LAB-282', 'Laboratorio de Cómputo', 'Pabellón B', '1', 30, 1, 1, 30, 0, NULL, 'available', '2025-11-26 18:45:43', '2025-11-26 18:45:43'),
(4, 'A-229', 'Laboratorio de Cómputo', 'Pabellón A', '2', 40, 1, 1, 30, 0, NULL, 'available', '2025-11-26 18:45:43', '2025-11-26 18:45:43'),
(5, 'A-296', 'Laboratorio de Cómputo', 'Pabellón B', '1', 30, 1, 1, 30, 0, NULL, 'available', '2025-11-26 18:45:43', '2025-11-26 18:45:43'),
(6, 'B-198', 'Laboratorio de Cómputo', 'Pabellón B', '3', 40, 1, 1, 30, 0, NULL, 'available', '2025-11-26 18:45:43', '2025-11-26 18:45:43'),
(7, 'B-103', 'Aula Común', 'Pabellón B', '1', 30, 1, 0, 0, 1, NULL, 'available', '2025-11-26 18:45:43', '2025-11-26 18:45:43'),
(8, 'B-140', 'Aula Común', 'Pabellón A', '3', 40, 1, 0, 0, 0, NULL, 'available', '2025-11-26 18:45:43', '2025-11-26 18:45:43'),
(9, 'C-111', 'Laboratorio de Cómputo', 'Pabellón B', '1', 30, 1, 1, 30, 0, NULL, 'available', '2025-11-26 18:45:43', '2025-11-26 18:45:43'),
(10, 'A-141', 'Aula Común', 'Pabellón C', '1', 40, 1, 0, 0, 0, NULL, 'available', '2025-11-26 18:45:43', '2025-11-26 18:45:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `credit_notes`
--

CREATE TABLE `credit_notes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `voucher_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `cash_session_id` bigint(20) UNSIGNED NOT NULL,
  `reason` text NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `didactic_units`
--

CREATE TABLE `didactic_units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `module_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(200) NOT NULL,
  `semester` int(11) NOT NULL,
  `weekly_hours` int(11) NOT NULL,
  `total_hours` int(11) NOT NULL,
  `credits` int(11) NOT NULL,
  `unit_type` enum('career','transversal') NOT NULL,
  `description` text DEFAULT NULL,
  `specific_competencies` text DEFAULT NULL,
  `semester_order` int(11) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `didactic_units`
--

INSERT INTO `didactic_units` (`id`, `module_id`, `code`, `name`, `semester`, `weekly_hours`, `total_hours`, `credits`, `unit_type`, `description`, `specific_competencies`, `semester_order`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'MEI-I', 'Mantenimiento de equipos informáticos', 1, 8, 128, 5, 'career', NULL, NULL, 1, 'active', '2025-11-26 18:45:43', '2025-11-26 18:45:43', NULL),
(2, 1, 'ISO-I', 'Instalación de Sistemas operativos', 1, 5, 80, 3, 'career', NULL, NULL, 2, 'active', '2025-11-26 18:45:43', '2025-11-26 18:45:43', NULL),
(3, 1, 'ACPD-I', 'Administración de Centros de procesamiento de datos', 1, 4, 64, 3, 'career', NULL, NULL, 3, 'active', '2025-11-26 18:45:43', '2025-11-26 18:45:43', NULL),
(4, 1, 'DRC-I', 'Diseño de redes de comunicación', 1, 6, 96, 4, 'career', NULL, NULL, 4, 'active', '2025-11-26 18:45:43', '2025-11-26 18:45:43', NULL),
(5, 1, 'CE-I', 'Comunicación efectiva', 1, 4, 64, 3, 'transversal', NULL, NULL, 5, 'active', '2025-11-26 18:45:43', '2025-11-26 18:45:43', NULL),
(6, 1, 'OE-I', 'Ofimática empresarial', 1, 3, 48, 2, 'transversal', NULL, NULL, 6, 'active', '2025-11-26 18:45:43', '2025-11-26 18:45:43', NULL),
(7, 1, 'CE2-I', 'Comportamiento ético', 1, 3, 48, 2, 'transversal', NULL, NULL, 7, 'active', '2025-11-26 18:45:43', '2025-11-26 18:45:43', NULL),
(8, 1, 'SI-I', 'Seguridad informática', 1, 5, 80, 3, 'career', NULL, NULL, 8, 'active', '2025-11-26 18:45:43', '2025-11-26 18:45:43', NULL),
(9, 1, 'ADS-I', 'Análisis y diseño de sistemas', 1, 5, 80, 3, 'career', NULL, NULL, 9, 'active', '2025-11-26 18:45:43', '2025-11-26 18:45:43', NULL),
(10, 1, 'REC-II', 'Reparación de equipos de cómputo', 2, 8, 128, 5, 'career', NULL, NULL, 1, 'active', '2025-11-26 18:45:43', '2025-11-26 18:45:43', NULL),
(11, 1, 'ICRC-II', 'Instalación y configuración de redes de comunicación', 2, 7, 112, 4, 'career', NULL, NULL, 2, 'active', '2025-11-26 18:45:43', '2025-11-26 18:45:43', NULL),
(12, 1, 'ARC-II', 'Administración de redes de comunicación', 2, 5, 80, 3, 'career', NULL, NULL, 3, 'active', '2025-11-26 18:45:43', '2025-11-26 18:45:43', NULL),
(13, 2, 'PSE-III', 'Programación de software empresarial', 3, 8, 128, 5, 'career', NULL, NULL, 1, 'active', '2025-11-26 18:45:43', '2025-11-26 18:45:43', NULL),
(14, 2, 'ICO-III', 'Inglés para la comunicación oral', 3, 3, 48, 2, 'transversal', NULL, NULL, 3, 'active', '2025-11-26 18:45:43', '2025-11-26 18:45:43', NULL),
(15, 3, 'AW-V', 'Arquitectura web', 5, 9, 144, 5, 'career', NULL, NULL, 1, 'active', '2025-11-26 18:45:43', '2025-11-26 18:45:43', NULL),
(16, 3, 'PT-VI', 'Proyecto de Tesis', 6, 8, 128, 5, 'career', NULL, NULL, 1, 'active', '2025-11-26 18:45:43', '2025-11-26 18:45:43', NULL),
(17, 3, 'PP-VI', 'Prácticas Pre-profesionales', 6, 12, 192, 8, 'career', NULL, NULL, 2, 'active', '2025-11-26 18:45:43', '2025-11-26 18:45:43', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `enrollments`
--

CREATE TABLE `enrollments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `academic_period_id` bigint(20) UNSIGNED NOT NULL,
  `enrollment_date` timestamp NOT NULL DEFAULT '2025-11-26 18:44:19',
  `semester_enrolled` int(11) NOT NULL,
  `enrollment_type` enum('first_time','continuing','restart','reincorporation') NOT NULL DEFAULT 'continuing',
  `amount_paid` decimal(8,2) NOT NULL DEFAULT 0.00,
  `payment_status` enum('pending','partial','paid') NOT NULL DEFAULT 'pending',
  `status` enum('active','cancelled','frozen') NOT NULL DEFAULT 'active',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `enrollments`
--

INSERT INTO `enrollments` (`id`, `student_id`, `academic_period_id`, `enrollment_date`, `semester_enrolled`, `enrollment_type`, `amount_paid`, `payment_status`, `status`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 5, 1, '2025-11-29 02:40:30', 1, 'reincorporation', 120.00, 'paid', 'active', 'Reincorporación. Voucher: R25-6. ', '2025-11-26 21:55:28', '2025-11-29 02:40:30', NULL),
(3, 6, 1, '2025-11-26 18:44:19', 1, 'first_time', 120.00, 'paid', 'active', 'Matrícula automática (Ingresante). Voucher: R25-5', '2025-11-28 22:01:12', '2025-11-28 22:01:12', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `enrollment_reserves`
--

CREATE TABLE `enrollment_reserves` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `academic_period_id` bigint(20) UNSIGNED NOT NULL,
  `resolution_code` varchar(50) NOT NULL,
  `reason` text NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `supporting_document_url` varchar(255) DEFAULT NULL,
  `status` enum('active','expired','cancelled') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `enrollment_reserves`
--

INSERT INTO `enrollment_reserves` (`id`, `student_id`, `academic_period_id`, `resolution_code`, `reason`, `start_date`, `end_date`, `supporting_document_url`, `status`, `created_at`, `updated_at`) VALUES
(1, 5, 1, 'RD 1245', 'dffhserhshfths', '2025-11-28', '2025-12-31', NULL, 'expired', '2025-11-29 01:34:38', '2025-11-29 02:40:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evaluation_types`
--

CREATE TABLE `evaluation_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `weight_percentage` decimal(5,2) NOT NULL,
  `is_droppable` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `evaluation_types`
--

INSERT INTO `evaluation_types` (`id`, `name`, `description`, `weight_percentage`, `is_droppable`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Evaluación Parcial', NULL, 30.00, 1, 1, 'active', '2025-11-26 18:45:42', '2025-11-26 18:45:42'),
(2, 'Evaluación Final', NULL, 40.00, 1, 3, 'active', '2025-11-26 18:45:42', '2025-11-26 18:45:42'),
(3, 'Evaluación Continua', NULL, 30.00, 1, 2, 'active', '2025-11-26 18:45:42', '2025-11-26 18:45:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `financial_entities`
--

CREATE TABLE `financial_entities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(20) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `financial_entities`
--

INSERT INTO `financial_entities` (`id`, `name`, `code`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Caja JAE (Institucional)', 'JAE', 1, '2025-11-26 18:45:43', '2025-11-26 18:45:43'),
(2, 'Banco de la Nación', 'BN', 1, '2025-11-26 18:45:43', '2025-11-26 18:45:43'),
(3, 'Caja Arequipa', 'CA', 1, '2025-11-26 18:45:43', '2025-11-26 18:45:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grades`
--

CREATE TABLE `grades` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `registration_id` bigint(20) UNSIGNED NOT NULL,
  `evaluation_type_id` bigint(20) UNSIGNED NOT NULL,
  `registered_by_user_id` bigint(20) UNSIGNED NOT NULL,
  `grade` decimal(4,2) NOT NULL,
  `evaluation_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `graduation_processes`
--

CREATE TABLE `graduation_processes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `process_type` enum('thesis','project','sufficiency_exam') NOT NULL,
  `title` varchar(500) NOT NULL,
  `abstract` text DEFAULT NULL,
  `advisor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `jury_president_id` bigint(20) UNSIGNED DEFAULT NULL,
  `jury_secretary_id` bigint(20) UNSIGNED DEFAULT NULL,
  `jury_member_id` bigint(20) UNSIGNED DEFAULT NULL,
  `proposal_date` date DEFAULT NULL,
  `approval_date` date DEFAULT NULL,
  `defense_date` date DEFAULT NULL,
  `final_grade` decimal(4,2) DEFAULT NULL,
  `document_url` varchar(255) DEFAULT NULL,
  `status` enum('proposal','in_development','review','defended','approved','rejected') NOT NULL DEFAULT 'proposal',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `institutions`
--

CREATE TABLE `institutions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(10) NOT NULL,
  `name` varchar(200) NOT NULL,
  `tax_id` varchar(11) NOT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `logo_url` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `institutions`
--

INSERT INTO `institutions` (`id`, `code`, `name`, `tax_id`, `address`, `phone`, `email`, `website`, `logo_url`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'IESTP001', 'IESTP Educon (Sede Principal)', '20123456789', 'Av. Principal 123, Lima', NULL, NULL, NULL, NULL, 'active', '2025-11-26 18:45:39', '2025-11-26 18:45:39', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `internships`
--

CREATE TABLE `internships` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `company_name` varchar(200) NOT NULL,
  `company_ruc` varchar(11) DEFAULT NULL,
  `company_address` text DEFAULT NULL,
  `supervisor_name` varchar(200) NOT NULL,
  `supervisor_position` varchar(100) DEFAULT NULL,
  `supervisor_email` varchar(100) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_hours` int(11) NOT NULL,
  `evaluation_score` decimal(4,2) DEFAULT NULL,
  `evaluation_file_url` varchar(255) DEFAULT NULL,
  `certificate_url` varchar(255) DEFAULT NULL,
  `status` enum('planned','in_progress','completed','cancelled') NOT NULL DEFAULT 'planned',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `library_loans`
--

CREATE TABLE `library_loans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `library_resource_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `loan_date` datetime NOT NULL,
  `due_date` date NOT NULL,
  `return_date` datetime DEFAULT NULL,
  `status` enum('active','returned','overdue','lost') NOT NULL DEFAULT 'active',
  `fine_amount` decimal(6,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `library_resources`
--

CREATE TABLE `library_resources` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(50) NOT NULL,
  `title` varchar(300) NOT NULL,
  `author` varchar(200) DEFAULT NULL,
  `institution_id` bigint(20) UNSIGNED NOT NULL,
  `career_id` bigint(20) UNSIGNED DEFAULT NULL,
  `resource_type` enum('book','magazine','thesis','manual','digital','audiovisual') NOT NULL,
  `publisher` varchar(200) DEFAULT NULL,
  `publication_year` year(4) DEFAULT NULL,
  `isbn` varchar(20) DEFAULT NULL,
  `copies_available` int(11) NOT NULL DEFAULT 1,
  `physical_location` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `cover_image_url` varchar(255) DEFAULT NULL,
  `digital_file_url` varchar(255) DEFAULT NULL,
  `status` enum('available','borrowed','reserved','maintenance','lost') NOT NULL DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `locations`
--

CREATE TABLE `locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `iddist` varchar(10) NOT NULL,
  `nombdep` varchar(100) NOT NULL,
  `nombprov` varchar(100) NOT NULL,
  `nombdist` varchar(100) NOT NULL,
  `nom_capital` varchar(100) DEFAULT NULL,
  `cod_reg_nat` varchar(10) DEFAULT NULL,
  `region_natural` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `locations`
--

INSERT INTO `locations` (`id`, `iddist`, `nombdep`, `nombprov`, `nombdist`, `nom_capital`, `cod_reg_nat`, `region_natural`, `created_at`, `updated_at`) VALUES
(1, '010101', 'AMAZONAS', 'CHACHAPOYAS', 'CHACHAPOYAS', 'CHACHAPOYAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, '010102', 'AMAZONAS', 'CHACHAPOYAS', 'ASUNCION', 'ASUNCION', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, '010103', 'AMAZONAS', 'CHACHAPOYAS', 'BALSAS', 'BALSAS', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, '010104', 'AMAZONAS', 'CHACHAPOYAS', 'CHETO', 'CHETO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, '010105', 'AMAZONAS', 'CHACHAPOYAS', 'CHILIQUIN', 'CHILIQUIN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, '010106', 'AMAZONAS', 'CHACHAPOYAS', 'CHUQUIBAMBA', 'CHUQUIBAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, '010107', 'AMAZONAS', 'CHACHAPOYAS', 'GRANADA', 'GRANADA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, '010108', 'AMAZONAS', 'CHACHAPOYAS', 'HUANCAS', 'HUANCAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, '010109', 'AMAZONAS', 'CHACHAPOYAS', 'LA JALCA', 'LA JALCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, '010110', 'AMAZONAS', 'CHACHAPOYAS', 'LEIMEBAMBA', 'LEIMEBAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, '010111', 'AMAZONAS', 'CHACHAPOYAS', 'LEVANTO', 'LEVANTO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, '010112', 'AMAZONAS', 'CHACHAPOYAS', 'MAGDALENA', 'MAGDALENA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13, '010113', 'AMAZONAS', 'CHACHAPOYAS', 'MARISCAL CASTILLA', 'DURAZNOPAMPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, '010114', 'AMAZONAS', 'CHACHAPOYAS', 'MOLINOPAMPA', 'MOLINOPAMPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, '010115', 'AMAZONAS', 'CHACHAPOYAS', 'MONTEVIDEO', 'MONTEVIDEO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, '010116', 'AMAZONAS', 'CHACHAPOYAS', 'OLLEROS', 'OLLEROS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, '010117', 'AMAZONAS', 'CHACHAPOYAS', 'QUINJALCA', 'QUINJALCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(18, '010118', 'AMAZONAS', 'CHACHAPOYAS', 'SAN FRANCISCO DE DAGUAS', 'DAGUAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(19, '010119', 'AMAZONAS', 'CHACHAPOYAS', 'SAN ISIDRO DE MAINO', 'MAINO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, '010120', 'AMAZONAS', 'CHACHAPOYAS', 'SOLOCO', 'SOLOCO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, '010121', 'AMAZONAS', 'CHACHAPOYAS', 'SONCHE', 'SAN JUAN DE SONCHE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, '010201', 'AMAZONAS', 'BAGUA', 'BAGUA', 'BAGUA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, '010202', 'AMAZONAS', 'BAGUA', 'ARAMANGO', 'ARAMANGO', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(24, '010203', 'AMAZONAS', 'BAGUA', 'COPALLIN', 'COPALLIN', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(25, '010204', 'AMAZONAS', 'BAGUA', 'EL PARCO', 'EL PARCO', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(26, '010205', 'AMAZONAS', 'BAGUA', 'IMAZA', 'CHIRIACO', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(27, '010206', 'AMAZONAS', 'BAGUA', 'LA PECA', 'LA PECA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(28, '010301', 'AMAZONAS', 'BONGARA', 'JUMBILLA', 'JUMBILLA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(29, '010302', 'AMAZONAS', 'BONGARA', 'CHISQUILLA', 'CHISQUILLA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30, '010303', 'AMAZONAS', 'BONGARA', 'CHURUJA', 'CHURUJA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(31, '010304', 'AMAZONAS', 'BONGARA', 'COROSHA', 'COROSHA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(32, '010305', 'AMAZONAS', 'BONGARA', 'CUISPES', 'CUISPES', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(33, '010306', 'AMAZONAS', 'BONGARA', 'FLORIDA', 'FLORIDA (POMACOCHAS)', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(34, '010307', 'AMAZONAS', 'BONGARA', 'JAZAN', 'PEDRO RUIZ GALLO', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(35, '010308', 'AMAZONAS', 'BONGARA', 'RECTA', 'RECTA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(36, '010309', 'AMAZONAS', 'BONGARA', 'SAN CARLOS', 'SAN CARLOS', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(37, '010310', 'AMAZONAS', 'BONGARA', 'SHIPASBAMBA', 'SHIPASBAMBA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(38, '010311', 'AMAZONAS', 'BONGARA', 'VALERA', 'VALERA (SAN PABLO)', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(39, '010312', 'AMAZONAS', 'BONGARA', 'YAMBRASBAMBA', 'YAMBRASBAMBA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(40, '010401', 'AMAZONAS', 'CONDORCANQUI', 'NIEVA', 'SANTA MARIA DE NIEVA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(41, '010402', 'AMAZONAS', 'CONDORCANQUI', 'EL CENEPA', 'HUAMPAMI', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(42, '010403', 'AMAZONAS', 'CONDORCANQUI', 'RIO SANTIAGO', 'PUERTO GALILEA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(43, '010501', 'AMAZONAS', 'LUYA', 'LAMUD', 'LAMUD', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(44, '010502', 'AMAZONAS', 'LUYA', 'CAMPORREDONDO', 'CAMPORREDONDO', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(45, '010503', 'AMAZONAS', 'LUYA', 'COCABAMBA', 'COCABAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(46, '010504', 'AMAZONAS', 'LUYA', 'COLCAMAR', 'COLCAMAR', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(47, '010505', 'AMAZONAS', 'LUYA', 'CONILA', 'COHECHAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(48, '010506', 'AMAZONAS', 'LUYA', 'INGUILPATA', 'INGUILPATA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(49, '010507', 'AMAZONAS', 'LUYA', 'LONGUITA', 'LONGUITA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(50, '010508', 'AMAZONAS', 'LUYA', 'LONYA CHICO', 'LONYA CHICO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(51, '010509', 'AMAZONAS', 'LUYA', 'LUYA', 'LUYA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(52, '010510', 'AMAZONAS', 'LUYA', 'LUYA VIEJO', 'LUYA VIEJO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(53, '010511', 'AMAZONAS', 'LUYA', 'MARIA', 'MARIA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(54, '010512', 'AMAZONAS', 'LUYA', 'OCALLI', 'OCALLI', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(55, '010513', 'AMAZONAS', 'LUYA', 'OCUMAL', 'COLLONCE', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(56, '010514', 'AMAZONAS', 'LUYA', 'PISUQUIA', 'YOMBLON', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(57, '010515', 'AMAZONAS', 'LUYA', 'PROVIDENCIA', 'PROVIDENCIA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(58, '010516', 'AMAZONAS', 'LUYA', 'SAN CRISTOBAL', 'OLTO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(59, '010517', 'AMAZONAS', 'LUYA', 'SAN FRANCISCO DEL YESO', 'SAN FRANCISCO DEL YESO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(60, '010518', 'AMAZONAS', 'LUYA', 'SAN JERONIMO', 'PACLAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(61, '010519', 'AMAZONAS', 'LUYA', 'SAN JUAN DE LOPECANCHA', 'SAN JUAN DE LOPECANCHA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(62, '010520', 'AMAZONAS', 'LUYA', 'SANTA CATALINA', 'SANTA CATALINA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(63, '010521', 'AMAZONAS', 'LUYA', 'SANTO TOMAS', 'SANTO TOMAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(64, '010522', 'AMAZONAS', 'LUYA', 'TINGO', 'TINGO', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(65, '010523', 'AMAZONAS', 'LUYA', 'TRITA', 'TRITA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(66, '010601', 'AMAZONAS', 'RODRIGUEZ DE MENDOZA', 'SAN NICOLAS', 'MENDOZA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(67, '010602', 'AMAZONAS', 'RODRIGUEZ DE MENDOZA', 'CHIRIMOTO', 'CHIRIMOTO', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(68, '010603', 'AMAZONAS', 'RODRIGUEZ DE MENDOZA', 'COCHAMAL', 'COCHAMAL', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(69, '010604', 'AMAZONAS', 'RODRIGUEZ DE MENDOZA', 'HUAMBO', 'HUAMBO', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(70, '010605', 'AMAZONAS', 'RODRIGUEZ DE MENDOZA', 'LIMABAMBA', 'LIMABAMBA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(71, '010606', 'AMAZONAS', 'RODRIGUEZ DE MENDOZA', 'LONGAR', 'LONGAR', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(72, '010607', 'AMAZONAS', 'RODRIGUEZ DE MENDOZA', 'MARISCAL BENAVIDES', 'MARISCAL BENAVIDES', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(73, '010608', 'AMAZONAS', 'RODRIGUEZ DE MENDOZA', 'MILPUC', 'MILPUC', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(74, '010609', 'AMAZONAS', 'RODRIGUEZ DE MENDOZA', 'OMIA', 'OMIA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(75, '010610', 'AMAZONAS', 'RODRIGUEZ DE MENDOZA', 'SANTA ROSA', 'SANTA ROSA DE HUAYABAMBA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(76, '010611', 'AMAZONAS', 'RODRIGUEZ DE MENDOZA', 'TOTORA', 'TOTORA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(77, '010612', 'AMAZONAS', 'RODRIGUEZ DE MENDOZA', 'VISTA ALEGRE', 'VISTA ALEGRE', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(78, '010701', 'AMAZONAS', 'UTCUBAMBA', 'BAGUA GRANDE', 'BAGUA GRANDE', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(79, '010702', 'AMAZONAS', 'UTCUBAMBA', 'CAJARURO', 'CAJARURO', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(80, '010703', 'AMAZONAS', 'UTCUBAMBA', 'CUMBA', 'CUMBA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(81, '010704', 'AMAZONAS', 'UTCUBAMBA', 'EL MILAGRO', 'EL MILAGRO', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(82, '010705', 'AMAZONAS', 'UTCUBAMBA', 'JAMALCA', 'JAMALCA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(83, '010706', 'AMAZONAS', 'UTCUBAMBA', 'LONYA GRANDE', 'LONYA GRANDE', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(84, '010707', 'AMAZONAS', 'UTCUBAMBA', 'YAMON', 'YAMON', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(85, '020101', 'ANCASH', 'HUARAZ', 'HUARAZ', 'HUARAZ', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(86, '020102', 'ANCASH', 'HUARAZ', 'COCHABAMBA', 'COCHABAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(87, '020103', 'ANCASH', 'HUARAZ', 'COLCABAMBA', 'COLCABAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(88, '020104', 'ANCASH', 'HUARAZ', 'HUANCHAY', 'HUANCHAY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(89, '020105', 'ANCASH', 'HUARAZ', 'INDEPENDENCIA', 'CENTENARIO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(90, '020106', 'ANCASH', 'HUARAZ', 'JANGAS', 'JANGAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(91, '020107', 'ANCASH', 'HUARAZ', 'LA LIBERTAD', 'CAJAMARQUILLA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(92, '020108', 'ANCASH', 'HUARAZ', 'OLLEROS', 'OLLEROS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(93, '020109', 'ANCASH', 'HUARAZ', 'PAMPAS GRANDE', 'PAMPAS GRANDE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(94, '020110', 'ANCASH', 'HUARAZ', 'PARIACOTO', 'PARIACOTO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(95, '020111', 'ANCASH', 'HUARAZ', 'PIRA', 'PIRA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(96, '020112', 'ANCASH', 'HUARAZ', 'TARICA', 'TARICA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(97, '020201', 'ANCASH', 'AIJA', 'AIJA', 'AIJA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(98, '020202', 'ANCASH', 'AIJA', 'CORIS', 'CORIS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(99, '020203', 'ANCASH', 'AIJA', 'HUACLLAN', 'HUACLLAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(100, '020204', 'ANCASH', 'AIJA', 'LA MERCED', 'LA MERCED', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(101, '020205', 'ANCASH', 'AIJA', 'SUCCHA', 'SUCCHA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(102, '020301', 'ANCASH', 'ANTONIO RAYMONDI', 'LLAMELLIN', 'LLAMELLIN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(103, '020302', 'ANCASH', 'ANTONIO RAYMONDI', 'ACZO', 'ACZO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(104, '020303', 'ANCASH', 'ANTONIO RAYMONDI', 'CHACCHO', 'CHACCHO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(105, '020304', 'ANCASH', 'ANTONIO RAYMONDI', 'CHINGAS', 'CHINGAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(106, '020305', 'ANCASH', 'ANTONIO RAYMONDI', 'MIRGAS', 'MIRGAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(107, '020306', 'ANCASH', 'ANTONIO RAYMONDI', 'SAN JUAN DE RONTOY', 'SAN JUAN DE RONTOY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(108, '020401', 'ANCASH', 'ASUNCION', 'CHACAS', 'CHACAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(109, '020402', 'ANCASH', 'ASUNCION', 'ACOCHACA', 'ACOCHACA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(110, '020501', 'ANCASH', 'BOLOGNESI', 'CHIQUIAN', 'CHIQUIAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(111, '020502', 'ANCASH', 'BOLOGNESI', 'ABELARDO PARDO LEZAMETA', 'LLACLLA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(112, '020503', 'ANCASH', 'BOLOGNESI', 'ANTONIO RAYMONDI', 'RAQUIA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(113, '020504', 'ANCASH', 'BOLOGNESI', 'AQUIA', 'AQUIA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(114, '020505', 'ANCASH', 'BOLOGNESI', 'CAJACAY', 'CAJACAY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(115, '020506', 'ANCASH', 'BOLOGNESI', 'CANIS', 'CANIS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(116, '020507', 'ANCASH', 'BOLOGNESI', 'COLQUIOC', 'CHASQUITAMBO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(117, '020508', 'ANCASH', 'BOLOGNESI', 'HUALLANCA', 'HUALLANCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(118, '020509', 'ANCASH', 'BOLOGNESI', 'HUASTA', 'HUASTA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(119, '020510', 'ANCASH', 'BOLOGNESI', 'HUAYLLACAYAN', 'HUAYLLACAYAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(120, '020511', 'ANCASH', 'BOLOGNESI', 'LA PRIMAVERA', 'GORGORILLO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(121, '020512', 'ANCASH', 'BOLOGNESI', 'MANGAS', 'MANGAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(122, '020513', 'ANCASH', 'BOLOGNESI', 'PACLLON', 'PACLLON', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(123, '020514', 'ANCASH', 'BOLOGNESI', 'SAN MIGUEL DE CORPANQUI', 'CORPANQUI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(124, '020515', 'ANCASH', 'BOLOGNESI', 'TICLLOS', 'TICLLOS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(125, '020601', 'ANCASH', 'CARHUAZ', 'CARHUAZ', 'CARHUAZ', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(126, '020602', 'ANCASH', 'CARHUAZ', 'ACOPAMPA', 'ACOPAMPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(127, '020603', 'ANCASH', 'CARHUAZ', 'AMASHCA', 'AMASHCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(128, '020604', 'ANCASH', 'CARHUAZ', 'ANTA', 'ANTA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(129, '020605', 'ANCASH', 'CARHUAZ', 'ATAQUERO', 'CARHUAC', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(130, '020606', 'ANCASH', 'CARHUAZ', 'MARCARA', 'MARCARA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(131, '020607', 'ANCASH', 'CARHUAZ', 'PARIAHUANCA', 'PARIAHUANCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(132, '020608', 'ANCASH', 'CARHUAZ', 'SAN MIGUEL DE ACO', 'ACO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(133, '020609', 'ANCASH', 'CARHUAZ', 'SHILLA', 'SHILLA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(134, '020610', 'ANCASH', 'CARHUAZ', 'TINCO', 'TINCO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, '020611', 'ANCASH', 'CARHUAZ', 'YUNGAR', 'YUNGAR', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(136, '020701', 'ANCASH', 'CARLOS FERMIN FITZCARRALD', 'SAN LUIS', 'SAN LUIS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(137, '020702', 'ANCASH', 'CARLOS FERMIN FITZCARRALD', 'SAN NICOLAS', 'SAN NICOLAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(138, '020703', 'ANCASH', 'CARLOS FERMIN FITZCARRALD', 'YAUYA', 'YAUYA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(139, '020801', 'ANCASH', 'CASMA', 'CASMA', 'CASMA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(140, '020802', 'ANCASH', 'CASMA', 'BUENA VISTA ALTA', 'BUENA VISTA ALTA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(141, '020803', 'ANCASH', 'CASMA', 'COMANDANTE NOEL', 'PUERTO CASMA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(142, '020804', 'ANCASH', 'CASMA', 'YAUTAN', 'YAUTAN', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, '020901', 'ANCASH', 'CORONGO', 'CORONGO', 'CORONGO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(144, '020902', 'ANCASH', 'CORONGO', 'ACO', 'ACO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(145, '020903', 'ANCASH', 'CORONGO', 'BAMBAS', 'BAMBAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(146, '020904', 'ANCASH', 'CORONGO', 'CUSCA', 'CUSCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(147, '020905', 'ANCASH', 'CORONGO', 'LA PAMPA', 'LA PAMPA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(148, '020906', 'ANCASH', 'CORONGO', 'YANAC', 'YANAC', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(149, '020907', 'ANCASH', 'CORONGO', 'YUPAN', 'YUPAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(150, '021001', 'ANCASH', 'HUARI', 'HUARI', 'HUARI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(151, '021002', 'ANCASH', 'HUARI', 'ANRA', 'ANRA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(152, '021003', 'ANCASH', 'HUARI', 'CAJAY', 'CAJAY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(153, '021004', 'ANCASH', 'HUARI', 'CHAVIN DE HUANTAR', 'CHAVIN DE HUANTAR', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(154, '021005', 'ANCASH', 'HUARI', 'HUACACHI', 'HUACACHI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(155, '021006', 'ANCASH', 'HUARI', 'HUACCHIS', 'HUACCHIS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(156, '021007', 'ANCASH', 'HUARI', 'HUACHIS', 'HUACHIS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(157, '021008', 'ANCASH', 'HUARI', 'HUANTAR', 'HUANTAR', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(158, '021009', 'ANCASH', 'HUARI', 'MASIN', 'MASIN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(159, '021010', 'ANCASH', 'HUARI', 'PAUCAS', 'PAUCAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(160, '021011', 'ANCASH', 'HUARI', 'PONTO', 'PONTO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(161, '021012', 'ANCASH', 'HUARI', 'RAHUAPAMPA', 'RAHUAPAMPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(162, '021013', 'ANCASH', 'HUARI', 'RAPAYAN', 'RAPAYAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(163, '021014', 'ANCASH', 'HUARI', 'SAN MARCOS', 'SAN MARCOS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(164, '021015', 'ANCASH', 'HUARI', 'SAN PEDRO DE CHANA', 'CHANA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(165, '021016', 'ANCASH', 'HUARI', 'UCO', 'UCO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(166, '021101', 'ANCASH', 'HUARMEY', 'HUARMEY', 'HUARMEY', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(167, '021102', 'ANCASH', 'HUARMEY', 'COCHAPETI', 'COCHAPETI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(168, '021103', 'ANCASH', 'HUARMEY', 'CULEBRAS', 'LA CALETA CULEBRAS', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(169, '021104', 'ANCASH', 'HUARMEY', 'HUAYAN', 'HUAYAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(170, '021105', 'ANCASH', 'HUARMEY', 'MALVAS', 'MALVAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(171, '021201', 'ANCASH', 'HUAYLAS', 'CARAZ', 'CARAZ', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(172, '021202', 'ANCASH', 'HUAYLAS', 'HUALLANCA', 'HUALLANCA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(173, '021203', 'ANCASH', 'HUAYLAS', 'HUATA', 'HUATA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(174, '021204', 'ANCASH', 'HUAYLAS', 'HUAYLAS', 'HUAYLAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(175, '021205', 'ANCASH', 'HUAYLAS', 'MATO', 'SUCRE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(176, '021206', 'ANCASH', 'HUAYLAS', 'PAMPAROMAS', 'PAMPAROMAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(177, '021207', 'ANCASH', 'HUAYLAS', 'PUEBLO LIBRE', 'SAN JUAN /1', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(178, '021208', 'ANCASH', 'HUAYLAS', 'SANTA CRUZ', 'HUARIPAMPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(179, '021209', 'ANCASH', 'HUAYLAS', 'SANTO TORIBIO', 'SANTO TORIBIO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(180, '021210', 'ANCASH', 'HUAYLAS', 'YURACMARCA', 'YURACMARCA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(181, '021301', 'ANCASH', 'MARISCAL LUZURIAGA', 'PISCOBAMBA', 'PISCOBAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(182, '021302', 'ANCASH', 'MARISCAL LUZURIAGA', 'CASCA', 'CASCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(183, '021303', 'ANCASH', 'MARISCAL LUZURIAGA', 'ELEAZAR GUZMAN BARRON', 'PAMPACHACRA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(184, '021304', 'ANCASH', 'MARISCAL LUZURIAGA', 'FIDEL OLIVAS ESCUDERO', 'SANACHGAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(185, '021305', 'ANCASH', 'MARISCAL LUZURIAGA', 'LLAMA', 'LLAMA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(186, '021306', 'ANCASH', 'MARISCAL LUZURIAGA', 'LLUMPA', 'LLUMPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(187, '021307', 'ANCASH', 'MARISCAL LUZURIAGA', 'LUCMA', 'LUCMA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(188, '021308', 'ANCASH', 'MARISCAL LUZURIAGA', 'MUSGA', 'MUSGA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(189, '021401', 'ANCASH', 'OCROS', 'OCROS', 'OCROS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(190, '021402', 'ANCASH', 'OCROS', 'ACAS', 'ACAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(191, '021403', 'ANCASH', 'OCROS', 'CAJAMARQUILLA', 'CAJAMARQUILLA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(192, '021404', 'ANCASH', 'OCROS', 'CARHUAPAMPA', 'ACO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(193, '021405', 'ANCASH', 'OCROS', 'COCHAS', 'HUANCHAY', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(194, '021406', 'ANCASH', 'OCROS', 'CONGAS', 'CONGAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(195, '021407', 'ANCASH', 'OCROS', 'LLIPA', 'LLIPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(196, '021408', 'ANCASH', 'OCROS', 'SAN CRISTOBAL DE RAJAN', 'RAJAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(197, '021409', 'ANCASH', 'OCROS', 'SAN PEDRO', 'COPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(198, '021410', 'ANCASH', 'OCROS', 'SANTIAGO DE CHILCAS', 'SANTIAGO DE CHILCAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(199, '021501', 'ANCASH', 'PALLASCA', 'CABANA', 'CABANA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(200, '021502', 'ANCASH', 'PALLASCA', 'BOLOGNESI', 'BOLOGNESI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(201, '021503', 'ANCASH', 'PALLASCA', 'CONCHUCOS', 'CONCHUCOS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(202, '021504', 'ANCASH', 'PALLASCA', 'HUACASCHUQUE', 'HUACASCHUQUE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(203, '021505', 'ANCASH', 'PALLASCA', 'HUANDOVAL', 'HUANDOVAL', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(204, '021506', 'ANCASH', 'PALLASCA', 'LACABAMBA', 'LACABAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(205, '021507', 'ANCASH', 'PALLASCA', 'LLAPO', 'LLAPO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(206, '021508', 'ANCASH', 'PALLASCA', 'PALLASCA', 'PALLASCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(207, '021509', 'ANCASH', 'PALLASCA', 'PAMPAS', 'PAMPAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(208, '021510', 'ANCASH', 'PALLASCA', 'SANTA ROSA', 'SANTA ROSA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(209, '021511', 'ANCASH', 'PALLASCA', 'TAUCA', 'TAUCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(210, '021601', 'ANCASH', 'POMABAMBA', 'POMABAMBA', 'POMABAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(211, '021602', 'ANCASH', 'POMABAMBA', 'HUAYLLAN', 'HUAYLLAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(212, '021603', 'ANCASH', 'POMABAMBA', 'PAROBAMBA', 'PAROBAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(213, '021604', 'ANCASH', 'POMABAMBA', 'QUINUABAMBA', 'QUINUABAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(214, '021701', 'ANCASH', 'RECUAY', 'RECUAY', 'RECUAY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(215, '021702', 'ANCASH', 'RECUAY', 'CATAC', 'CATAC', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(216, '021703', 'ANCASH', 'RECUAY', 'COTAPARACO', 'COTAPARACO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(217, '021704', 'ANCASH', 'RECUAY', 'HUAYLLAPAMPA', 'HUAYLLAPAMPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(218, '021705', 'ANCASH', 'RECUAY', 'LLACLLIN', 'LLACLLIN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(219, '021706', 'ANCASH', 'RECUAY', 'MARCA', 'MARCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(220, '021707', 'ANCASH', 'RECUAY', 'PAMPAS CHICO', 'PAMPAS CHICO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(221, '021708', 'ANCASH', 'RECUAY', 'PARARIN', 'PARARIN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(222, '021709', 'ANCASH', 'RECUAY', 'TAPACOCHA', 'TAPACOCHA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(223, '021710', 'ANCASH', 'RECUAY', 'TICAPAMPA', 'TICAPAMPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(224, '021801', 'ANCASH', 'SANTA', 'CHIMBOTE', 'CHIMBOTE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(225, '021802', 'ANCASH', 'SANTA', 'CACERES DEL PERU', 'JIMBE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(226, '021803', 'ANCASH', 'SANTA', 'COISHCO', 'COISHCO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(227, '021804', 'ANCASH', 'SANTA', 'MACATE', 'MACATE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(228, '021805', 'ANCASH', 'SANTA', 'MORO', 'MORO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(229, '021806', 'ANCASH', 'SANTA', 'NEPEÑA', 'NEPEÑA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(230, '021807', 'ANCASH', 'SANTA', 'SAMANCO', 'SAMANCO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(231, '021808', 'ANCASH', 'SANTA', 'SANTA', 'SANTA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(232, '021809', 'ANCASH', 'SANTA', 'NUEVO CHIMBOTE', 'BUENOS AIRES', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(233, '021901', 'ANCASH', 'SIHUAS', 'SIHUAS', 'SIHUAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(234, '021902', 'ANCASH', 'SIHUAS', 'ACOBAMBA', 'ACOBAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(235, '021903', 'ANCASH', 'SIHUAS', 'ALFONSO UGARTE', 'ULLULLUCO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(236, '021904', 'ANCASH', 'SIHUAS', 'CASHAPAMPA', 'CASHAPAMPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(237, '021905', 'ANCASH', 'SIHUAS', 'CHINGALPO', 'CHINGALPO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(238, '021906', 'ANCASH', 'SIHUAS', 'HUAYLLABAMBA', 'HUAYLLABAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(239, '021907', 'ANCASH', 'SIHUAS', 'QUICHES', 'QUICHES', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(240, '021908', 'ANCASH', 'SIHUAS', 'RAGASH', 'RAGASH', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(241, '021909', 'ANCASH', 'SIHUAS', 'SAN JUAN', 'CHULLIN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(242, '021910', 'ANCASH', 'SIHUAS', 'SICSIBAMBA', 'UMBE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(243, '022001', 'ANCASH', 'YUNGAY', 'YUNGAY', 'YUNGAY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(244, '022002', 'ANCASH', 'YUNGAY', 'CASCAPARA', 'CASCAPARA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(245, '022003', 'ANCASH', 'YUNGAY', 'MANCOS', 'MANCOS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(246, '022004', 'ANCASH', 'YUNGAY', 'MATACOTO', 'MATACOTO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(247, '022005', 'ANCASH', 'YUNGAY', 'QUILLO', 'QUILLO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(248, '022006', 'ANCASH', 'YUNGAY', 'RANRAHIRCA', 'RANRAHIRCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(249, '022007', 'ANCASH', 'YUNGAY', 'SHUPLUY', 'SHUPLUY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(250, '022008', 'ANCASH', 'YUNGAY', 'YANAMA', 'YANAMA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(251, '030101', 'APURIMAC', 'ABANCAY', 'ABANCAY', 'ABANCAY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(252, '030102', 'APURIMAC', 'ABANCAY', 'CHACOCHE', 'CHACOCHE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(253, '030103', 'APURIMAC', 'ABANCAY', 'CIRCA', 'CIRCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(254, '030104', 'APURIMAC', 'ABANCAY', 'CURAHUASI', 'CURAHUASI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(255, '030105', 'APURIMAC', 'ABANCAY', 'HUANIPACA', 'HUANIPACA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(256, '030106', 'APURIMAC', 'ABANCAY', 'LAMBRAMA', 'LAMBRAMA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(257, '030107', 'APURIMAC', 'ABANCAY', 'PICHIRHUA', 'PICHIRHUA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(258, '030108', 'APURIMAC', 'ABANCAY', 'SAN PEDRO DE CACHORA', 'CACHORA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(259, '030109', 'APURIMAC', 'ABANCAY', 'TAMBURCO', 'TAMBURCO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(260, '030201', 'APURIMAC', 'ANDAHUAYLAS', 'ANDAHUAYLAS', 'ANDAHUAYLAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(261, '030202', 'APURIMAC', 'ANDAHUAYLAS', 'ANDARAPA', 'ANDARAPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(262, '030203', 'APURIMAC', 'ANDAHUAYLAS', 'CHIARA', 'CHIARA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(263, '030204', 'APURIMAC', 'ANDAHUAYLAS', 'HUANCARAMA', 'HUANCARAMA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(264, '030205', 'APURIMAC', 'ANDAHUAYLAS', 'HUANCARAY', 'HUANCARAY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(265, '030206', 'APURIMAC', 'ANDAHUAYLAS', 'HUAYANA', 'HUAYANA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(266, '030207', 'APURIMAC', 'ANDAHUAYLAS', 'KISHUARA', 'KISHUARA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(267, '030208', 'APURIMAC', 'ANDAHUAYLAS', 'PACOBAMBA', 'PACOBAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(268, '030209', 'APURIMAC', 'ANDAHUAYLAS', 'PACUCHA', 'PACUCHA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(269, '030210', 'APURIMAC', 'ANDAHUAYLAS', 'PAMPACHIRI', 'PAMPACHIRI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(270, '030211', 'APURIMAC', 'ANDAHUAYLAS', 'POMACOCHA', 'POMACOCHA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(271, '030212', 'APURIMAC', 'ANDAHUAYLAS', 'SAN ANTONIO DE CACHI', 'SAN ANTONIO DE CACHI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(272, '030213', 'APURIMAC', 'ANDAHUAYLAS', 'SAN JERONIMO', 'SAN JERONIMO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(273, '030214', 'APURIMAC', 'ANDAHUAYLAS', 'SAN MIGUEL DE CHACCRAMPA', 'CHACCRAMPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(274, '030215', 'APURIMAC', 'ANDAHUAYLAS', 'SANTA MARIA DE CHICMO', 'SANTA MARIA DE CHICMO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(275, '030216', 'APURIMAC', 'ANDAHUAYLAS', 'TALAVERA', 'TALAVERA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(276, '030217', 'APURIMAC', 'ANDAHUAYLAS', 'TUMAY HUARACA', 'UMAMARCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(277, '030218', 'APURIMAC', 'ANDAHUAYLAS', 'TURPO', 'TURPO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(278, '030219', 'APURIMAC', 'ANDAHUAYLAS', 'KAQUIABAMBA', 'KAQUIABAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(279, '030220', 'APURIMAC', 'ANDAHUAYLAS', 'JOSE MARIA ARGUEDAS', 'HUANCABAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(280, '030301', 'APURIMAC', 'ANTABAMBA', 'ANTABAMBA', 'ANTABAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(281, '030302', 'APURIMAC', 'ANTABAMBA', 'EL ORO', 'AYAHUAY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(282, '030303', 'APURIMAC', 'ANTABAMBA', 'HUAQUIRCA', 'HUAQUIRCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(283, '030304', 'APURIMAC', 'ANTABAMBA', 'JUAN ESPINOZA MEDRANO', 'MOLLEBAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(284, '030305', 'APURIMAC', 'ANTABAMBA', 'OROPESA', 'OROPESA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(285, '030306', 'APURIMAC', 'ANTABAMBA', 'PACHACONAS', 'PACHACONAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(286, '030307', 'APURIMAC', 'ANTABAMBA', 'SABAINO', 'SABAINO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(287, '030401', 'APURIMAC', 'AYMARAES', 'CHALHUANCA', 'CHALHUANCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(288, '030402', 'APURIMAC', 'AYMARAES', 'CAPAYA', 'CAPAYA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(289, '030403', 'APURIMAC', 'AYMARAES', 'CARAYBAMBA', 'CARAYBAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(290, '030404', 'APURIMAC', 'AYMARAES', 'CHAPIMARCA', 'CHAPIMARCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(291, '030405', 'APURIMAC', 'AYMARAES', 'COLCABAMBA', 'COLCABAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(292, '030406', 'APURIMAC', 'AYMARAES', 'COTARUSE', 'COTARUSE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(293, '030407', 'APURIMAC', 'AYMARAES', 'IHUAYLLO', 'IHUAYLLO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(294, '030408', 'APURIMAC', 'AYMARAES', 'JUSTO APU SAHUARAURA', 'PICHIHUA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(295, '030409', 'APURIMAC', 'AYMARAES', 'LUCRE', 'LUCRE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(296, '030410', 'APURIMAC', 'AYMARAES', 'POCOHUANCA', 'POCOHUANCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(297, '030411', 'APURIMAC', 'AYMARAES', 'SAN JUAN DE CHACÑA', 'SAN JUAN DE CHACÑA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(298, '030412', 'APURIMAC', 'AYMARAES', 'SAÑAYCA', 'SAÑAYCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(299, '030413', 'APURIMAC', 'AYMARAES', 'SORAYA', 'SORAYA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(300, '030414', 'APURIMAC', 'AYMARAES', 'TAPAIRIHUA', 'TAPAIRIHUA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(301, '030415', 'APURIMAC', 'AYMARAES', 'TINTAY', 'TINTAY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(302, '030416', 'APURIMAC', 'AYMARAES', 'TORAYA', 'TORAYA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(303, '030417', 'APURIMAC', 'AYMARAES', 'YANACA', 'YANACA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(304, '030501', 'APURIMAC', 'COTABAMBAS', 'TAMBOBAMBA', 'TAMBOBAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(305, '030502', 'APURIMAC', 'COTABAMBAS', 'COTABAMBAS', 'COTABAMBAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(306, '030503', 'APURIMAC', 'COTABAMBAS', 'COYLLURQUI', 'COYLLURQUI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(307, '030504', 'APURIMAC', 'COTABAMBAS', 'HAQUIRA', 'HAQUIRA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(308, '030505', 'APURIMAC', 'COTABAMBAS', 'MARA', 'MARA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(309, '030506', 'APURIMAC', 'COTABAMBAS', 'CHALLHUAHUACHO', 'CHALLHUAHUACHO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(310, '030601', 'APURIMAC', 'CHINCHEROS', 'CHINCHEROS', 'CHINCHEROS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(311, '030602', 'APURIMAC', 'CHINCHEROS', 'ANCO_HUALLO', 'URIPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(312, '030603', 'APURIMAC', 'CHINCHEROS', 'COCHARCAS', 'COCHARCAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(313, '030604', 'APURIMAC', 'CHINCHEROS', 'HUACCANA', 'HUACCANA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(314, '030605', 'APURIMAC', 'CHINCHEROS', 'OCOBAMBA', 'OCOBAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(315, '030606', 'APURIMAC', 'CHINCHEROS', 'ONGOY', 'ONGOY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(316, '030607', 'APURIMAC', 'CHINCHEROS', 'URANMARCA', 'URANMARCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(317, '030608', 'APURIMAC', 'CHINCHEROS', 'RANRACANCHA', 'RANRACANCHA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(318, '030609', 'APURIMAC', 'CHINCHEROS', 'ROCCHACC', 'ROCCHACC', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(319, '030610', 'APURIMAC', 'CHINCHEROS', 'EL PORVENIR', 'SAN PEDRO DE HUAMBURQUE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(320, '030611', 'APURIMAC', 'CHINCHEROS', 'LOS CHANKAS', 'RIO BLANCO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(321, '030612', 'APURIMAC', 'CHINCHEROS', 'AHUAYRO', 'AHUAYRO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(322, '030701', 'APURIMAC', 'GRAU', 'CHUQUIBAMBILLA', 'CHUQUIBAMBILLA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(323, '030702', 'APURIMAC', 'GRAU', 'CURPAHUASI', 'CURPAHUASI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(324, '030703', 'APURIMAC', 'GRAU', 'GAMARRA', 'PALPACACHI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(325, '030704', 'APURIMAC', 'GRAU', 'HUAYLLATI', 'HUAYLLATI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(326, '030705', 'APURIMAC', 'GRAU', 'MAMARA', 'MAMARA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(327, '030706', 'APURIMAC', 'GRAU', 'MICAELA BASTIDAS', 'AYRIHUANCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(328, '030707', 'APURIMAC', 'GRAU', 'PATAYPAMPA', 'PATAYPAMPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(329, '030708', 'APURIMAC', 'GRAU', 'PROGRESO', 'PROGRESO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(330, '030709', 'APURIMAC', 'GRAU', 'SAN ANTONIO', 'SAN ANTONIO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(331, '030710', 'APURIMAC', 'GRAU', 'SANTA ROSA', 'SANTA ROSA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(332, '030711', 'APURIMAC', 'GRAU', 'TURPAY', 'TURPAY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(333, '030712', 'APURIMAC', 'GRAU', 'VILCABAMBA', 'VILCABAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(334, '030713', 'APURIMAC', 'GRAU', 'VIRUNDO', 'SAN JUAN DE VIRUNDO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(335, '030714', 'APURIMAC', 'GRAU', 'CURASCO', 'CURASCO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(336, '040101', 'AREQUIPA', 'AREQUIPA', 'AREQUIPA', 'AREQUIPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(337, '040102', 'AREQUIPA', 'AREQUIPA', 'ALTO SELVA ALEGRE', 'SELVA ALEGRE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(338, '040103', 'AREQUIPA', 'AREQUIPA', 'CAYMA', 'CAYMA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(339, '040104', 'AREQUIPA', 'AREQUIPA', 'CERRO COLORADO', 'LA LIBERTAD', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(340, '040105', 'AREQUIPA', 'AREQUIPA', 'CHARACATO', 'CHARACATO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(341, '040106', 'AREQUIPA', 'AREQUIPA', 'CHIGUATA', 'CHIGUATA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(342, '040107', 'AREQUIPA', 'AREQUIPA', 'JACOBO HUNTER', 'JACOBO HUNTER', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(343, '040108', 'AREQUIPA', 'AREQUIPA', 'LA JOYA', 'LA JOYA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(344, '040109', 'AREQUIPA', 'AREQUIPA', 'MARIANO MELGAR', 'MARIANO MELGAR', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(345, '040110', 'AREQUIPA', 'AREQUIPA', 'MIRAFLORES', 'MIRAFLORES', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(346, '040111', 'AREQUIPA', 'AREQUIPA', 'MOLLEBAYA', 'MOLLEBAYA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(347, '040112', 'AREQUIPA', 'AREQUIPA', 'PAUCARPATA', 'PAUCARPATA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(348, '040113', 'AREQUIPA', 'AREQUIPA', 'POCSI', 'POCSI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(349, '040114', 'AREQUIPA', 'AREQUIPA', 'POLOBAYA', 'POLOBAYA GRANDE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(350, '040115', 'AREQUIPA', 'AREQUIPA', 'QUEQUEÑA', 'QUEQUEÑA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(351, '040116', 'AREQUIPA', 'AREQUIPA', 'SABANDIA', 'SABANDIA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(352, '040117', 'AREQUIPA', 'AREQUIPA', 'SACHACA', 'SACHACA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(353, '040118', 'AREQUIPA', 'AREQUIPA', 'SAN JUAN DE SIGUAS', 'TAMBILLO /2', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(354, '040119', 'AREQUIPA', 'AREQUIPA', 'SAN JUAN DE TARUCANI', 'TARUCANI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(355, '040120', 'AREQUIPA', 'AREQUIPA', 'SANTA ISABEL DE SIGUAS', 'SANTA ISABEL DE SIGUAS', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(356, '040121', 'AREQUIPA', 'AREQUIPA', 'SANTA RITA DE SIGUAS', 'SANTA RITA DE SIGUAS', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(357, '040122', 'AREQUIPA', 'AREQUIPA', 'SOCABAYA', 'SOCABAYA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(358, '040123', 'AREQUIPA', 'AREQUIPA', 'TIABAYA', 'TIABAYA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(359, '040124', 'AREQUIPA', 'AREQUIPA', 'UCHUMAYO', 'UCHUMAYO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(360, '040125', 'AREQUIPA', 'AREQUIPA', 'VITOR', 'VITOR', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(361, '040126', 'AREQUIPA', 'AREQUIPA', 'YANAHUARA', 'YANAHUARA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(362, '040127', 'AREQUIPA', 'AREQUIPA', 'YARABAMBA', 'YARABAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(363, '040128', 'AREQUIPA', 'AREQUIPA', 'YURA', 'YURA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(364, '040129', 'AREQUIPA', 'AREQUIPA', 'JOSE LUIS BUSTAMANTE Y RIVERO', 'CIUDAD SATELITE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(365, '040201', 'AREQUIPA', 'CAMANA', 'CAMANA', 'CAMANA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(366, '040202', 'AREQUIPA', 'CAMANA', 'JOSE MARIA QUIMPER', 'EL CARDO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(367, '040203', 'AREQUIPA', 'CAMANA', 'MARIANO NICOLAS VALCARCEL', 'URASQUI', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(368, '040204', 'AREQUIPA', 'CAMANA', 'MARISCAL CACERES', 'SAN JOSE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(369, '040205', 'AREQUIPA', 'CAMANA', 'NICOLAS DE PIEROLA', 'SAN GREGORIO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(370, '040206', 'AREQUIPA', 'CAMANA', 'OCOÑA', 'OCOÑA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(371, '040207', 'AREQUIPA', 'CAMANA', 'QUILCA', 'QUILCA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(372, '040208', 'AREQUIPA', 'CAMANA', 'SAMUEL PASTOR', 'LA PAMPA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(373, '040301', 'AREQUIPA', 'CARAVELI', 'CARAVELI', 'CARAVELI', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(374, '040302', 'AREQUIPA', 'CARAVELI', 'ACARI', 'ACARI', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(375, '040303', 'AREQUIPA', 'CARAVELI', 'ATICO', 'ATICO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(376, '040304', 'AREQUIPA', 'CARAVELI', 'ATIQUIPA', 'ATIQUIPA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(377, '040305', 'AREQUIPA', 'CARAVELI', 'BELLA UNION', 'BELLA UNION', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(378, '040306', 'AREQUIPA', 'CARAVELI', 'CAHUACHO', 'CAHUACHO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(379, '040307', 'AREQUIPA', 'CARAVELI', 'CHALA', 'CHALA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(380, '040308', 'AREQUIPA', 'CARAVELI', 'CHAPARRA', 'ACHANIZO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(381, '040309', 'AREQUIPA', 'CARAVELI', 'HUANUHUANU', 'TOCOTA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(382, '040310', 'AREQUIPA', 'CARAVELI', 'JAQUI', 'JAQUI', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(383, '040311', 'AREQUIPA', 'CARAVELI', 'LOMAS', 'LOMAS', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(384, '040312', 'AREQUIPA', 'CARAVELI', 'QUICACHA', 'QUICACHA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(385, '040313', 'AREQUIPA', 'CARAVELI', 'YAUCA', 'YAUCA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(386, '040401', 'AREQUIPA', 'CASTILLA', 'APLAO', 'APLAO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(387, '040402', 'AREQUIPA', 'CASTILLA', 'ANDAGUA', 'ANDAGUA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(388, '040403', 'AREQUIPA', 'CASTILLA', 'AYO', 'AYO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(389, '040404', 'AREQUIPA', 'CASTILLA', 'CHACHAS', 'CHACHAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(390, '040405', 'AREQUIPA', 'CASTILLA', 'CHILCAYMARCA', 'CHILCAYMARCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(391, '040406', 'AREQUIPA', 'CASTILLA', 'CHOCO', 'CHOCO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(392, '040407', 'AREQUIPA', 'CASTILLA', 'HUANCARQUI', 'HUANCARQUI', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(393, '040408', 'AREQUIPA', 'CASTILLA', 'MACHAGUAY', 'MACHAGUAY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(394, '040409', 'AREQUIPA', 'CASTILLA', 'ORCOPAMPA', 'ORCOPAMPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(395, '040410', 'AREQUIPA', 'CASTILLA', 'PAMPACOLCA', 'PAMPACOLCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(396, '040411', 'AREQUIPA', 'CASTILLA', 'TIPAN', 'TIPAN', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(397, '040412', 'AREQUIPA', 'CASTILLA', 'UÑON', 'UÑON', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(398, '040413', 'AREQUIPA', 'CASTILLA', 'URACA', 'CORIRE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(399, '040414', 'AREQUIPA', 'CASTILLA', 'VIRACO', 'VIRACO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `locations` (`id`, `iddist`, `nombdep`, `nombprov`, `nombdist`, `nom_capital`, `cod_reg_nat`, `region_natural`, `created_at`, `updated_at`) VALUES
(400, '040501', 'AREQUIPA', 'CAYLLOMA', 'CHIVAY', 'CHIVAY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(401, '040502', 'AREQUIPA', 'CAYLLOMA', 'ACHOMA', 'ACHOMA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(402, '040503', 'AREQUIPA', 'CAYLLOMA', 'CABANACONDE', 'CABANACONDE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(403, '040504', 'AREQUIPA', 'CAYLLOMA', 'CALLALLI', 'CALLALLI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(404, '040505', 'AREQUIPA', 'CAYLLOMA', 'CAYLLOMA', 'CAYLLOMA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(405, '040506', 'AREQUIPA', 'CAYLLOMA', 'COPORAQUE', 'COPORAQUE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(406, '040507', 'AREQUIPA', 'CAYLLOMA', 'HUAMBO', 'HUAMBO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(407, '040508', 'AREQUIPA', 'CAYLLOMA', 'HUANCA', 'HUANCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(408, '040509', 'AREQUIPA', 'CAYLLOMA', 'ICHUPAMPA', 'ICHUPAMPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(409, '040510', 'AREQUIPA', 'CAYLLOMA', 'LARI', 'LARI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(410, '040511', 'AREQUIPA', 'CAYLLOMA', 'LLUTA', 'LLUTA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(411, '040512', 'AREQUIPA', 'CAYLLOMA', 'MACA', 'MACA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(412, '040513', 'AREQUIPA', 'CAYLLOMA', 'MADRIGAL', 'MADRIGAL', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(413, '040514', 'AREQUIPA', 'CAYLLOMA', 'SAN ANTONIO DE CHUCA', 'IMATA /3', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(414, '040515', 'AREQUIPA', 'CAYLLOMA', 'SIBAYO', 'SIBAYO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(415, '040516', 'AREQUIPA', 'CAYLLOMA', 'TAPAY', 'TAPAY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(416, '040517', 'AREQUIPA', 'CAYLLOMA', 'TISCO', 'TISCO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(417, '040518', 'AREQUIPA', 'CAYLLOMA', 'TUTI', 'TUTI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(418, '040519', 'AREQUIPA', 'CAYLLOMA', 'YANQUE', 'YANQUE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(419, '040520', 'AREQUIPA', 'CAYLLOMA', 'MAJES', 'EL PEDREGAL', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(420, '040601', 'AREQUIPA', 'CONDESUYOS', 'CHUQUIBAMBA', 'CHUQUIBAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(421, '040602', 'AREQUIPA', 'CONDESUYOS', 'ANDARAY', 'ANDARAY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(422, '040603', 'AREQUIPA', 'CONDESUYOS', 'CAYARANI', 'CAYARANI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(423, '040604', 'AREQUIPA', 'CONDESUYOS', 'CHICHAS', 'CHICHAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(424, '040605', 'AREQUIPA', 'CONDESUYOS', 'IRAY', 'IRAY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(425, '040606', 'AREQUIPA', 'CONDESUYOS', 'RIO GRANDE', 'IQUIPI', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(426, '040607', 'AREQUIPA', 'CONDESUYOS', 'SALAMANCA', 'SALAMANCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(427, '040608', 'AREQUIPA', 'CONDESUYOS', 'YANAQUIHUA', 'YANAQUIHUA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(428, '040701', 'AREQUIPA', 'ISLAY', 'MOLLENDO', 'MOLLENDO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(429, '040702', 'AREQUIPA', 'ISLAY', 'COCACHACRA', 'COCACHACRA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(430, '040703', 'AREQUIPA', 'ISLAY', 'DEAN VALDIVIA', 'LA CURVA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(431, '040704', 'AREQUIPA', 'ISLAY', 'ISLAY', 'ISLAY (MATARANI)', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(432, '040705', 'AREQUIPA', 'ISLAY', 'MEJIA', 'MEJIA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(433, '040706', 'AREQUIPA', 'ISLAY', 'PUNTA DE BOMBON', 'PUNTA DE BOMBON', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(434, '040801', 'AREQUIPA', 'LA UNION', 'COTAHUASI', 'COTAHUASI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(435, '040802', 'AREQUIPA', 'LA UNION', 'ALCA', 'ALCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(436, '040803', 'AREQUIPA', 'LA UNION', 'CHARCANA', 'CHARCANA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(437, '040804', 'AREQUIPA', 'LA UNION', 'HUAYNACOTAS', 'TAURISMA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(438, '040805', 'AREQUIPA', 'LA UNION', 'PAMPAMARCA', 'MUNGUI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(439, '040806', 'AREQUIPA', 'LA UNION', 'PUYCA', 'PUYCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(440, '040807', 'AREQUIPA', 'LA UNION', 'QUECHUALLA', 'VELINGA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(441, '040808', 'AREQUIPA', 'LA UNION', 'SAYLA', 'SAYLA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(442, '040809', 'AREQUIPA', 'LA UNION', 'TAURIA', 'TAURIA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(443, '040810', 'AREQUIPA', 'LA UNION', 'TOMEPAMPA', 'TOMEPAMPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(444, '040811', 'AREQUIPA', 'LA UNION', 'TORO', 'TORO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(445, '050101', 'AYACUCHO', 'HUAMANGA', 'AYACUCHO', 'AYACUCHO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(446, '050102', 'AYACUCHO', 'HUAMANGA', 'ACOCRO', 'ACOCRO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(447, '050103', 'AYACUCHO', 'HUAMANGA', 'ACOS VINCHOS', 'ACOS VINCHOS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(448, '050104', 'AYACUCHO', 'HUAMANGA', 'CARMEN ALTO', 'CARMEN ALTO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(449, '050105', 'AYACUCHO', 'HUAMANGA', 'CHIARA', 'CHIARA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(450, '050106', 'AYACUCHO', 'HUAMANGA', 'OCROS', 'OCROS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(451, '050107', 'AYACUCHO', 'HUAMANGA', 'PACAYCASA', 'PACAYCASA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(452, '050108', 'AYACUCHO', 'HUAMANGA', 'QUINUA', 'QUINUA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(453, '050109', 'AYACUCHO', 'HUAMANGA', 'SAN JOSE DE TICLLAS', 'TICLLAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(454, '050110', 'AYACUCHO', 'HUAMANGA', 'SAN JUAN BAUTISTA', 'SAN JUAN BAUTISTA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(455, '050111', 'AYACUCHO', 'HUAMANGA', 'SANTIAGO DE PISCHA', 'SAN PEDRO DE CACHI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(456, '050112', 'AYACUCHO', 'HUAMANGA', 'SOCOS', 'SOCOS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(457, '050113', 'AYACUCHO', 'HUAMANGA', 'TAMBILLO', 'TAMBILLO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(458, '050114', 'AYACUCHO', 'HUAMANGA', 'VINCHOS', 'VINCHOS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(459, '050115', 'AYACUCHO', 'HUAMANGA', 'JESUS NAZARENO', 'LAS NAZARENAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(460, '050116', 'AYACUCHO', 'HUAMANGA', 'ANDRES AVELINO CACERES DORREGARAY', 'JARDÍN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(461, '050201', 'AYACUCHO', 'CANGALLO', 'CANGALLO', 'CANGALLO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(462, '050202', 'AYACUCHO', 'CANGALLO', 'CHUSCHI', 'CHUSCHI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(463, '050203', 'AYACUCHO', 'CANGALLO', 'LOS MOROCHUCOS', 'PAMPA-CANGALLO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(464, '050204', 'AYACUCHO', 'CANGALLO', 'MARIA PARADO DE BELLIDO', 'POMABAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(465, '050205', 'AYACUCHO', 'CANGALLO', 'PARAS', 'PARAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(466, '050206', 'AYACUCHO', 'CANGALLO', 'TOTOS', 'TOTOS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(467, '050301', 'AYACUCHO', 'HUANCA SANCOS', 'SANCOS', 'HUANCA SANCOS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(468, '050302', 'AYACUCHO', 'HUANCA SANCOS', 'CARAPO', 'CARAPO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(469, '050303', 'AYACUCHO', 'HUANCA SANCOS', 'SACSAMARCA', 'SACSAMARCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(470, '050304', 'AYACUCHO', 'HUANCA SANCOS', 'SANTIAGO DE LUCANAMARCA', 'SANTIAGO DE LUCANAMARCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(471, '050401', 'AYACUCHO', 'HUANTA', 'HUANTA', 'HUANTA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(472, '050402', 'AYACUCHO', 'HUANTA', 'AYAHUANCO', 'VIRACOCHAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(473, '050403', 'AYACUCHO', 'HUANTA', 'HUAMANGUILLA', 'HUAMANGUILLA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(474, '050404', 'AYACUCHO', 'HUANTA', 'IGUAIN', 'MACACHACRA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(475, '050405', 'AYACUCHO', 'HUANTA', 'LURICOCHA', 'LURICOCHA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(476, '050406', 'AYACUCHO', 'HUANTA', 'SANTILLANA', 'SAN JOSE DE SECCE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(477, '050407', 'AYACUCHO', 'HUANTA', 'SIVIA', 'SIVIA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(478, '050408', 'AYACUCHO', 'HUANTA', 'LLOCHEGUA', 'LLOCHEGUA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(479, '050409', 'AYACUCHO', 'HUANTA', 'CANAYRE', 'CANAYRE', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(480, '050410', 'AYACUCHO', 'HUANTA', 'UCHURACCAY', 'HUAYNACANCHA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(481, '050411', 'AYACUCHO', 'HUANTA', 'PUCACOLPA', 'HUALLHUA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(482, '050412', 'AYACUCHO', 'HUANTA', 'CHACA', 'CHACA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(483, '050413', 'AYACUCHO', 'HUANTA', 'PUTIS', 'RODEO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(484, '050501', 'AYACUCHO', 'LA MAR', 'SAN MIGUEL', 'SAN MIGUEL', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(485, '050502', 'AYACUCHO', 'LA MAR', 'ANCO', 'CHIQUINTIRCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(486, '050503', 'AYACUCHO', 'LA MAR', 'AYNA', 'SAN FRANCISCO', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(487, '050504', 'AYACUCHO', 'LA MAR', 'CHILCAS', 'CHILCAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(488, '050505', 'AYACUCHO', 'LA MAR', 'CHUNGUI', 'CHUNGUI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(489, '050506', 'AYACUCHO', 'LA MAR', 'LUIS CARRANZA', 'PAMPAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(490, '050507', 'AYACUCHO', 'LA MAR', 'SANTA ROSA', 'SANTA ROSA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(491, '050508', 'AYACUCHO', 'LA MAR', 'TAMBO', 'TAMBO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(492, '050509', 'AYACUCHO', 'LA MAR', 'SAMUGARI', 'PALMAPAMPA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(493, '050510', 'AYACUCHO', 'LA MAR', 'ANCHIHUAY', 'ANCHIHUAY', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(494, '050511', 'AYACUCHO', 'LA MAR', 'ORONCCOY', 'ORONCCOY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(495, '050512', 'AYACUCHO', 'LA MAR', 'UNION PROGRESO', 'SAN ANTONIO', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(496, '050513', 'AYACUCHO', 'LA MAR', 'RIO MAGDALENA', 'MONTERRICO', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(497, '050514', 'AYACUCHO', 'LA MAR', 'NINABAMBA', 'NINABAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(498, '050515', 'AYACUCHO', 'LA MAR', 'PATIBAMBA', 'PATIBAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(499, '050601', 'AYACUCHO', 'LUCANAS', 'PUQUIO', 'PUQUIO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(500, '050602', 'AYACUCHO', 'LUCANAS', 'AUCARA', 'AUCARA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(501, '050603', 'AYACUCHO', 'LUCANAS', 'CABANA', 'CABANA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(502, '050604', 'AYACUCHO', 'LUCANAS', 'CARMEN SALCEDO', 'ANDAMARCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(503, '050605', 'AYACUCHO', 'LUCANAS', 'CHAVIÑA', 'CHAVIÑA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(504, '050606', 'AYACUCHO', 'LUCANAS', 'CHIPAO', 'CHIPAO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(505, '050607', 'AYACUCHO', 'LUCANAS', 'HUAC-HUAS', 'HUAC-HUAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(506, '050608', 'AYACUCHO', 'LUCANAS', 'LARAMATE', 'LARAMATE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(507, '050609', 'AYACUCHO', 'LUCANAS', 'LEONCIO PRADO', 'TAMBO QUEMADO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(508, '050610', 'AYACUCHO', 'LUCANAS', 'LLAUTA', 'LLAUTA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(509, '050611', 'AYACUCHO', 'LUCANAS', 'LUCANAS', 'LUCANAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(510, '050612', 'AYACUCHO', 'LUCANAS', 'OCAÑA', 'OCAÑA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(511, '050613', 'AYACUCHO', 'LUCANAS', 'OTOCA', 'OTOCA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(512, '050614', 'AYACUCHO', 'LUCANAS', 'SAISA', 'SAISA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(513, '050615', 'AYACUCHO', 'LUCANAS', 'SAN CRISTOBAL', 'SAN CRISTOBAL', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(514, '050616', 'AYACUCHO', 'LUCANAS', 'SAN JUAN', 'SAN JUAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(515, '050617', 'AYACUCHO', 'LUCANAS', 'SAN PEDRO', 'SAN PEDRO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(516, '050618', 'AYACUCHO', 'LUCANAS', 'SAN PEDRO DE PALCO', 'SAN PEDRO DE PALCO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(517, '050619', 'AYACUCHO', 'LUCANAS', 'SANCOS', 'SANCOS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(518, '050620', 'AYACUCHO', 'LUCANAS', 'SANTA ANA DE HUAYCAHUACHO', 'SANTA ANA DE HUAYCAHUACHO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(519, '050621', 'AYACUCHO', 'LUCANAS', 'SANTA LUCIA', 'SANTA LUCIA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(520, '050701', 'AYACUCHO', 'PARINACOCHAS', 'CORACORA', 'CORACORA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(521, '050702', 'AYACUCHO', 'PARINACOCHAS', 'CHUMPI', 'CHUMPI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(522, '050703', 'AYACUCHO', 'PARINACOCHAS', 'CORONEL CASTAÑEDA', 'ANISO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(523, '050704', 'AYACUCHO', 'PARINACOCHAS', 'PACAPAUSA', 'PACAPAUSA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(524, '050705', 'AYACUCHO', 'PARINACOCHAS', 'PULLO', 'PULLO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(525, '050706', 'AYACUCHO', 'PARINACOCHAS', 'PUYUSCA', 'INCUYO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(526, '050707', 'AYACUCHO', 'PARINACOCHAS', 'SAN FRANCISCO DE RIVACAYCO', 'SAN FRANCISCO DE RIVACAYCO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(527, '050708', 'AYACUCHO', 'PARINACOCHAS', 'UPAHUACHO', 'UPAHUACHO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(528, '050801', 'AYACUCHO', 'PAUCAR DEL SARA SARA', 'PAUSA', 'PAUSA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(529, '050802', 'AYACUCHO', 'PAUCAR DEL SARA SARA', 'COLTA', 'COLTA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(530, '050803', 'AYACUCHO', 'PAUCAR DEL SARA SARA', 'CORCULLA', 'CORCULLA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(531, '050804', 'AYACUCHO', 'PAUCAR DEL SARA SARA', 'LAMPA', 'LAMPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(532, '050805', 'AYACUCHO', 'PAUCAR DEL SARA SARA', 'MARCABAMBA', 'MARCABAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(533, '050806', 'AYACUCHO', 'PAUCAR DEL SARA SARA', 'OYOLO', 'OYOLO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(534, '050807', 'AYACUCHO', 'PAUCAR DEL SARA SARA', 'PARARCA', 'PARARCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(535, '050808', 'AYACUCHO', 'PAUCAR DEL SARA SARA', 'SAN JAVIER DE ALPABAMBA', 'SAN JAVIER DE ALPABAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(536, '050809', 'AYACUCHO', 'PAUCAR DEL SARA SARA', 'SAN JOSE DE USHUA', 'SAN JOSE DE USHUA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(537, '050810', 'AYACUCHO', 'PAUCAR DEL SARA SARA', 'SARA SARA', 'QUILCATA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(538, '050901', 'AYACUCHO', 'SUCRE', 'QUEROBAMBA', 'QUEROBAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(539, '050902', 'AYACUCHO', 'SUCRE', 'BELEN', 'BELEN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(540, '050903', 'AYACUCHO', 'SUCRE', 'CHALCOS', 'CHALCOS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(541, '050904', 'AYACUCHO', 'SUCRE', 'CHILCAYOC', 'CHILCAYOC', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(542, '050905', 'AYACUCHO', 'SUCRE', 'HUACAÑA', 'HUACAÑA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(543, '050906', 'AYACUCHO', 'SUCRE', 'MORCOLLA', 'MORCOLLA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(544, '050907', 'AYACUCHO', 'SUCRE', 'PAICO', 'PAICO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(545, '050908', 'AYACUCHO', 'SUCRE', 'SAN PEDRO DE LARCAY', 'SAN PEDRO DE LARCAY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(546, '050909', 'AYACUCHO', 'SUCRE', 'SAN SALVADOR DE QUIJE', 'SAN SALVADOR DE QUIJE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(547, '050910', 'AYACUCHO', 'SUCRE', 'SANTIAGO DE PAUCARAY', 'SANTIAGO DE PAUCARAY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(548, '050911', 'AYACUCHO', 'SUCRE', 'SORAS', 'SORAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(549, '051001', 'AYACUCHO', 'VICTOR FAJARDO', 'HUANCAPI', 'HUANCAPI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(550, '051002', 'AYACUCHO', 'VICTOR FAJARDO', 'ALCAMENCA', 'ALCAMENCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(551, '051003', 'AYACUCHO', 'VICTOR FAJARDO', 'APONGO', 'APONGO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(552, '051004', 'AYACUCHO', 'VICTOR FAJARDO', 'ASQUIPATA', 'ASQUIPATA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(553, '051005', 'AYACUCHO', 'VICTOR FAJARDO', 'CANARIA', 'CANARIA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(554, '051006', 'AYACUCHO', 'VICTOR FAJARDO', 'CAYARA', 'CAYARA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(555, '051007', 'AYACUCHO', 'VICTOR FAJARDO', 'COLCA', 'COLCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(556, '051008', 'AYACUCHO', 'VICTOR FAJARDO', 'HUAMANQUIQUIA', 'HUAMANQUIQUIA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(557, '051009', 'AYACUCHO', 'VICTOR FAJARDO', 'HUANCARAYLLA', 'HUANCARAYLLA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(558, '051010', 'AYACUCHO', 'VICTOR FAJARDO', 'HUALLA', 'SAN PEDRO DE HUAYA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(559, '051011', 'AYACUCHO', 'VICTOR FAJARDO', 'SARHUA', 'SARHUA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(560, '051012', 'AYACUCHO', 'VICTOR FAJARDO', 'VILCANCHOS', 'VILCANCHOS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(561, '051101', 'AYACUCHO', 'VILCAS HUAMAN', 'VILCAS HUAMAN', 'VILCAS HUAMAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(562, '051102', 'AYACUCHO', 'VILCAS HUAMAN', 'ACCOMARCA', 'ACCOMARCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(563, '051103', 'AYACUCHO', 'VILCAS HUAMAN', 'CARHUANCA', 'CARHUANCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(564, '051104', 'AYACUCHO', 'VILCAS HUAMAN', 'CONCEPCION', 'CONCEPCION', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(565, '051105', 'AYACUCHO', 'VILCAS HUAMAN', 'HUAMBALPA', 'HUAMBALPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(566, '051106', 'AYACUCHO', 'VILCAS HUAMAN', 'INDEPENDENCIA', 'NUEVO PACCHA HUALLHUA /4', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(567, '051107', 'AYACUCHO', 'VILCAS HUAMAN', 'SAURAMA', 'SAURAMA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(568, '051108', 'AYACUCHO', 'VILCAS HUAMAN', 'VISCHONGO', 'VISCHONGO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(569, '060101', 'CAJAMARCA', 'CAJAMARCA', 'CAJAMARCA', 'CAJAMARCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(570, '060102', 'CAJAMARCA', 'CAJAMARCA', 'ASUNCION', 'ASUNCION', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(571, '060103', 'CAJAMARCA', 'CAJAMARCA', 'CHETILLA', 'CHETILLA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(572, '060104', 'CAJAMARCA', 'CAJAMARCA', 'COSPAN', 'COSPAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(573, '060105', 'CAJAMARCA', 'CAJAMARCA', 'ENCAÑADA', 'ENCAÑADA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(574, '060106', 'CAJAMARCA', 'CAJAMARCA', 'JESUS', 'JESUS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(575, '060107', 'CAJAMARCA', 'CAJAMARCA', 'LLACANORA', 'LLACANORA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(576, '060108', 'CAJAMARCA', 'CAJAMARCA', 'LOS BAÑOS DEL INCA', 'LOS BAÑOS DEL INCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(577, '060109', 'CAJAMARCA', 'CAJAMARCA', 'MAGDALENA', 'MAGDALENA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(578, '060110', 'CAJAMARCA', 'CAJAMARCA', 'MATARA', 'MATARA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(579, '060111', 'CAJAMARCA', 'CAJAMARCA', 'NAMORA', 'NAMORA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(580, '060112', 'CAJAMARCA', 'CAJAMARCA', 'SAN JUAN', 'SAN JUAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(581, '060201', 'CAJAMARCA', 'CAJABAMBA', 'CAJABAMBA', 'CAJABAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(582, '060202', 'CAJAMARCA', 'CAJABAMBA', 'CACHACHI', 'CACHACHI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(583, '060203', 'CAJAMARCA', 'CAJABAMBA', 'CONDEBAMBA', 'CAUDAY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(584, '060204', 'CAJAMARCA', 'CAJABAMBA', 'SITACOCHA', 'LLUCHUBAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(585, '060301', 'CAJAMARCA', 'CELENDIN', 'CELENDIN', 'CELENDIN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(586, '060302', 'CAJAMARCA', 'CELENDIN', 'CHUMUCH', 'CHUMUCH', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(587, '060303', 'CAJAMARCA', 'CELENDIN', 'CORTEGANA', 'CHIMUCH (CORTEGANA)', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(588, '060304', 'CAJAMARCA', 'CELENDIN', 'HUASMIN', 'HUASMIN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(589, '060305', 'CAJAMARCA', 'CELENDIN', 'JORGE CHAVEZ', 'LUCMAPAMPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(590, '060306', 'CAJAMARCA', 'CELENDIN', 'JOSE GALVEZ', 'HUACAPAMPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(591, '060307', 'CAJAMARCA', 'CELENDIN', 'MIGUEL IGLESIAS', 'CHALAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(592, '060308', 'CAJAMARCA', 'CELENDIN', 'OXAMARCA', 'OXAMARCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(593, '060309', 'CAJAMARCA', 'CELENDIN', 'SOROCHUCO', 'SOROCHUCO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(594, '060310', 'CAJAMARCA', 'CELENDIN', 'SUCRE', 'SUCRE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(595, '060311', 'CAJAMARCA', 'CELENDIN', 'UTCO', 'UTCO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(596, '060312', 'CAJAMARCA', 'CELENDIN', 'LA LIBERTAD DE PALLAN', 'LA LIBERTAD DE PALLAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(597, '060401', 'CAJAMARCA', 'CHOTA', 'CHOTA', 'CHOTA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(598, '060402', 'CAJAMARCA', 'CHOTA', 'ANGUIA', 'ANGUIA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(599, '060403', 'CAJAMARCA', 'CHOTA', 'CHADIN', 'CHADIN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(600, '060404', 'CAJAMARCA', 'CHOTA', 'CHIGUIRIP', 'CHIGUIRIP', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(601, '060405', 'CAJAMARCA', 'CHOTA', 'CHIMBAN', 'CHIMBAN', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(602, '060406', 'CAJAMARCA', 'CHOTA', 'CHOROPAMPA', 'CHOROPAMPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(603, '060407', 'CAJAMARCA', 'CHOTA', 'COCHABAMBA', 'COCHABAMBA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(604, '060408', 'CAJAMARCA', 'CHOTA', 'CONCHAN', 'CONCHAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(605, '060409', 'CAJAMARCA', 'CHOTA', 'HUAMBOS', 'HUAMBOS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(606, '060410', 'CAJAMARCA', 'CHOTA', 'LAJAS', 'LAJAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(607, '060411', 'CAJAMARCA', 'CHOTA', 'LLAMA', 'LLAMA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(608, '060412', 'CAJAMARCA', 'CHOTA', 'MIRACOSTA', 'MIRACOSTA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(609, '060413', 'CAJAMARCA', 'CHOTA', 'PACCHA', 'PACCHA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(610, '060414', 'CAJAMARCA', 'CHOTA', 'PION', 'PION', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(611, '060415', 'CAJAMARCA', 'CHOTA', 'QUEROCOTO', 'QUEROCOTO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(612, '060416', 'CAJAMARCA', 'CHOTA', 'SAN JUAN DE LICUPIS', 'LICUPIS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(613, '060417', 'CAJAMARCA', 'CHOTA', 'TACABAMBA', 'TACABAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(614, '060418', 'CAJAMARCA', 'CHOTA', 'TOCMOCHE', 'TOCMOCHE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(615, '060419', 'CAJAMARCA', 'CHOTA', 'CHALAMARCA', 'CHALAMARCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(616, '060501', 'CAJAMARCA', 'CONTUMAZA', 'CONTUMAZA', 'CONTUMAZA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(617, '060502', 'CAJAMARCA', 'CONTUMAZA', 'CHILETE', 'CHILETE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(618, '060503', 'CAJAMARCA', 'CONTUMAZA', 'CUPISNIQUE', 'TRINIDAD', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(619, '060504', 'CAJAMARCA', 'CONTUMAZA', 'GUZMANGO', 'GUZMANGO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(620, '060505', 'CAJAMARCA', 'CONTUMAZA', 'SAN BENITO', 'SAN BENITO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(621, '060506', 'CAJAMARCA', 'CONTUMAZA', 'SANTA CRUZ DE TOLED', 'SANTA CRUZ DE TOLED', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(622, '060507', 'CAJAMARCA', 'CONTUMAZA', 'TANTARICA', 'CATAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(623, '060508', 'CAJAMARCA', 'CONTUMAZA', 'YONAN', 'TEMBLADERA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(624, '060601', 'CAJAMARCA', 'CUTERVO', 'CUTERVO', 'CUTERVO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(625, '060602', 'CAJAMARCA', 'CUTERVO', 'CALLAYUC', 'CALLAYUC', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(626, '060603', 'CAJAMARCA', 'CUTERVO', 'CHOROS', 'CHOROS', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(627, '060604', 'CAJAMARCA', 'CUTERVO', 'CUJILLO', 'CUJILLO', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(628, '060605', 'CAJAMARCA', 'CUTERVO', 'LA RAMADA', 'LA RAMADA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(629, '060606', 'CAJAMARCA', 'CUTERVO', 'PIMPINGOS', 'PIMPINGOS', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(630, '060607', 'CAJAMARCA', 'CUTERVO', 'QUEROCOTILLO', 'QUEROCOTILLO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(631, '060608', 'CAJAMARCA', 'CUTERVO', 'SAN ANDRES DE CUTERVO', 'SAN ANDRES DE CUTERVO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(632, '060609', 'CAJAMARCA', 'CUTERVO', 'SAN JUAN DE CUTERVO', 'SAN JUAN DE CUTERVO', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(633, '060610', 'CAJAMARCA', 'CUTERVO', 'SAN LUIS DE LUCMA', 'SAN LUIS DE LUCMA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(634, '060611', 'CAJAMARCA', 'CUTERVO', 'SANTA CRUZ', 'SANTA CRUZ', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(635, '060612', 'CAJAMARCA', 'CUTERVO', 'SANTO DOMINGO DE LA CAPILLA', 'SANTO DOMINGO DE LA CAPILLA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(636, '060613', 'CAJAMARCA', 'CUTERVO', 'SANTO TOMAS', 'SANTO TOMAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(637, '060614', 'CAJAMARCA', 'CUTERVO', 'SOCOTA', 'SOCOTA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(638, '060615', 'CAJAMARCA', 'CUTERVO', 'TORIBIO CASANOVA', 'LA SACILIA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(639, '060701', 'CAJAMARCA', 'HUALGAYOC', 'BAMBAMARCA', 'BAMBAMARCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(640, '060702', 'CAJAMARCA', 'HUALGAYOC', 'CHUGUR', 'CHUGUR', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(641, '060703', 'CAJAMARCA', 'HUALGAYOC', 'HUALGAYOC', 'HUALGAYOC', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(642, '060801', 'CAJAMARCA', 'JAEN', 'JAEN', 'JAEN', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(643, '060802', 'CAJAMARCA', 'JAEN', 'BELLAVISTA', 'BELLAVISTA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(644, '060803', 'CAJAMARCA', 'JAEN', 'CHONTALI', 'CHONTALI', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(645, '060804', 'CAJAMARCA', 'JAEN', 'COLASAY', 'COLASAY', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(646, '060805', 'CAJAMARCA', 'JAEN', 'HUABAL', 'HUABAL', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(647, '060806', 'CAJAMARCA', 'JAEN', 'LAS PIRIAS', 'LAS PIRIAS', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(648, '060807', 'CAJAMARCA', 'JAEN', 'POMAHUACA', 'POMAHUACA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(649, '060808', 'CAJAMARCA', 'JAEN', 'PUCARA', 'PUCARA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(650, '060809', 'CAJAMARCA', 'JAEN', 'SALLIQUE', 'SALLIQUE', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(651, '060810', 'CAJAMARCA', 'JAEN', 'SAN FELIPE', 'SAN FELIPE', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(652, '060811', 'CAJAMARCA', 'JAEN', 'SAN JOSE DEL ALTO', 'SAN JOSE DEL ALTO', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(653, '060812', 'CAJAMARCA', 'JAEN', 'SANTA ROSA', 'SANTA ROSA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(654, '060901', 'CAJAMARCA', 'SAN IGNACIO', 'SAN IGNACIO', 'SAN IGNACIO', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(655, '060902', 'CAJAMARCA', 'SAN IGNACIO', 'CHIRINOS', 'CHIRINOS', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(656, '060903', 'CAJAMARCA', 'SAN IGNACIO', 'HUARANGO', 'HUARANGO', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(657, '060904', 'CAJAMARCA', 'SAN IGNACIO', 'LA COIPA', 'LA COIPA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(658, '060905', 'CAJAMARCA', 'SAN IGNACIO', 'NAMBALLE', 'NAMBALLE', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(659, '060906', 'CAJAMARCA', 'SAN IGNACIO', 'SAN JOSE DE LOURDES', 'SAN JOSE DE LOURDES', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(660, '060907', 'CAJAMARCA', 'SAN IGNACIO', 'TABACONAS', 'TABACONAS', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(661, '061001', 'CAJAMARCA', 'SAN MARCOS', 'PEDRO GALVEZ', 'SAN MARCOS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(662, '061002', 'CAJAMARCA', 'SAN MARCOS', 'CHANCAY', 'CHANCAY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(663, '061003', 'CAJAMARCA', 'SAN MARCOS', 'EDUARDO VILLANUEVA', 'LA GRAMA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(664, '061004', 'CAJAMARCA', 'SAN MARCOS', 'GREGORIO PITA', 'PAUCAMARCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(665, '061005', 'CAJAMARCA', 'SAN MARCOS', 'ICHOCAN', 'ICHOCAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(666, '061006', 'CAJAMARCA', 'SAN MARCOS', 'JOSE MANUEL QUIROZ', 'SHIRAC', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(667, '061007', 'CAJAMARCA', 'SAN MARCOS', 'JOSE SABOGAL', 'VENECIA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(668, '061101', 'CAJAMARCA', 'SAN MIGUEL', 'SAN MIGUEL', 'SAN MIGUEL DE PALLAQUES', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(669, '061102', 'CAJAMARCA', 'SAN MIGUEL', 'BOLIVAR', 'BOLIVAR', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(670, '061103', 'CAJAMARCA', 'SAN MIGUEL', 'CALQUIS', 'CALQUIS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(671, '061104', 'CAJAMARCA', 'SAN MIGUEL', 'CATILLUC', 'CATILLUC', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(672, '061105', 'CAJAMARCA', 'SAN MIGUEL', 'EL PRADO', 'EL PRADO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(673, '061106', 'CAJAMARCA', 'SAN MIGUEL', 'LA FLORIDA', 'LA FLORIDA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(674, '061107', 'CAJAMARCA', 'SAN MIGUEL', 'LLAPA', 'LLAPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(675, '061108', 'CAJAMARCA', 'SAN MIGUEL', 'NANCHOC', 'NANCHOC', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(676, '061109', 'CAJAMARCA', 'SAN MIGUEL', 'NIEPOS', 'NIEPOS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(677, '061110', 'CAJAMARCA', 'SAN MIGUEL', 'SAN GREGORIO', 'SAN GREGORIO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(678, '061111', 'CAJAMARCA', 'SAN MIGUEL', 'SAN SILVESTRE DE COCHAN', 'SAN SILVESTRE DE COCHAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(679, '061112', 'CAJAMARCA', 'SAN MIGUEL', 'TONGOD', 'TONGOD', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(680, '061113', 'CAJAMARCA', 'SAN MIGUEL', 'UNION AGUA BLANCA', 'AGUA BLANCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(681, '061201', 'CAJAMARCA', 'SAN PABLO', 'SAN PABLO', 'SAN PABLO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(682, '061202', 'CAJAMARCA', 'SAN PABLO', 'SAN BERNARDINO', 'SAN BERNARDINO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(683, '061203', 'CAJAMARCA', 'SAN PABLO', 'SAN LUIS', 'SAN LUIS GRANDE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(684, '061204', 'CAJAMARCA', 'SAN PABLO', 'TUMBADEN', 'TUMBADEN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(685, '061301', 'CAJAMARCA', 'SANTA CRUZ', 'SANTA CRUZ', 'SANTA CRUZ DE SUCCHABAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(686, '061302', 'CAJAMARCA', 'SANTA CRUZ', 'ANDABAMBA', 'ANDABAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(687, '061303', 'CAJAMARCA', 'SANTA CRUZ', 'CATACHE', 'CATACHE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(688, '061304', 'CAJAMARCA', 'SANTA CRUZ', 'CHANCAYBAÑOS', 'CHANCAYBAÑOS', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(689, '061305', 'CAJAMARCA', 'SANTA CRUZ', 'LA ESPERANZA', 'LA ESPERANZA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(690, '061306', 'CAJAMARCA', 'SANTA CRUZ', 'NINABAMBA', 'NINABAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(691, '061307', 'CAJAMARCA', 'SANTA CRUZ', 'PULAN', 'PULAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(692, '061308', 'CAJAMARCA', 'SANTA CRUZ', 'SAUCEPAMPA', 'SAUCEPAMPA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(693, '061309', 'CAJAMARCA', 'SANTA CRUZ', 'SEXI', 'SEXI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(694, '061310', 'CAJAMARCA', 'SANTA CRUZ', 'UTICYACU', 'UTICYACU', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(695, '061311', 'CAJAMARCA', 'SANTA CRUZ', 'YAUYUCAN', 'YAUYUCAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(696, '070101', 'CALLAO', 'CALLAO', 'CALLAO', 'CALLAO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(697, '070102', 'CALLAO', 'CALLAO', 'BELLAVISTA', 'BELLAVISTA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(698, '070103', 'CALLAO', 'CALLAO', 'CARMEN DE LA LEGUA REYNOSO', 'CARMEN DE LA LEGUA REYNOSO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(699, '070104', 'CALLAO', 'CALLAO', 'LA PERLA', 'LA PERLA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(700, '070105', 'CALLAO', 'CALLAO', 'LA PUNTA', 'LA PUNTA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(701, '070106', 'CALLAO', 'CALLAO', 'VENTANILLA', 'VENTANILLA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(702, '070107', 'CALLAO', 'CALLAO', 'MI PERU', 'MI PERU', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(703, '080101', 'CUSCO', 'CUSCO', 'CUSCO', 'CUSCO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(704, '080102', 'CUSCO', 'CUSCO', 'CCORCA', 'CCORCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(705, '080103', 'CUSCO', 'CUSCO', 'POROY', 'POROY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(706, '080104', 'CUSCO', 'CUSCO', 'SAN JERONIMO', 'SAN JERONIMO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(707, '080105', 'CUSCO', 'CUSCO', 'SAN SEBASTIAN', 'SAN SEBASTIAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(708, '080106', 'CUSCO', 'CUSCO', 'SANTIAGO', 'SANTIAGO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(709, '080107', 'CUSCO', 'CUSCO', 'SAYLLA', 'SAYLLA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(710, '080108', 'CUSCO', 'CUSCO', 'WANCHAQ', 'WANCHAQ', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(711, '080201', 'CUSCO', 'ACOMAYO', 'ACOMAYO', 'ACOMAYO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(712, '080202', 'CUSCO', 'ACOMAYO', 'ACOPIA', 'ACOPIA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(713, '080203', 'CUSCO', 'ACOMAYO', 'ACOS', 'ACOS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(714, '080204', 'CUSCO', 'ACOMAYO', 'MOSOC LLACTA', 'MOSOC LLACTA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(715, '080205', 'CUSCO', 'ACOMAYO', 'POMACANCHI', 'POMACANCHI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(716, '080206', 'CUSCO', 'ACOMAYO', 'RONDOCAN', 'RONDOCAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(717, '080207', 'CUSCO', 'ACOMAYO', 'SANGARARA', 'SANGARARA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(718, '080301', 'CUSCO', 'ANTA', 'ANTA', 'ANTA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(719, '080302', 'CUSCO', 'ANTA', 'ANCAHUASI', 'ANCAHUASI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(720, '080303', 'CUSCO', 'ANTA', 'CACHIMAYO', 'CACHIMAYO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(721, '080304', 'CUSCO', 'ANTA', 'CHINCHAYPUJIO', 'CHINCHAYPUJIO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(722, '080305', 'CUSCO', 'ANTA', 'HUAROCONDO', 'HUAROCONDO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(723, '080306', 'CUSCO', 'ANTA', 'LIMATAMBO', 'LIMATAMBO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(724, '080307', 'CUSCO', 'ANTA', 'MOLLEPATA', 'MOLLEPATA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(725, '080308', 'CUSCO', 'ANTA', 'PUCYURA', 'PUCYURA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(726, '080309', 'CUSCO', 'ANTA', 'ZURITE', 'ZURITE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(727, '080401', 'CUSCO', 'CALCA', 'CALCA', 'CALCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(728, '080402', 'CUSCO', 'CALCA', 'COYA', 'COYA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(729, '080403', 'CUSCO', 'CALCA', 'LAMAY', 'LAMAY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(730, '080404', 'CUSCO', 'CALCA', 'LARES', 'LARES', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(731, '080405', 'CUSCO', 'CALCA', 'PISAC', 'PISAC', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(732, '080406', 'CUSCO', 'CALCA', 'SAN SALVADOR', 'SAN SALVADOR', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(733, '080407', 'CUSCO', 'CALCA', 'TARAY', 'TARAY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(734, '080408', 'CUSCO', 'CALCA', 'YANATILE', 'QUEBRADA HONDA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(735, '080501', 'CUSCO', 'CANAS', 'YANAOCA', 'YANAOCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(736, '080502', 'CUSCO', 'CANAS', 'CHECCA', 'CHECCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(737, '080503', 'CUSCO', 'CANAS', 'KUNTURKANKI', 'EL DESCANSO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(738, '080504', 'CUSCO', 'CANAS', 'LANGUI', 'LANGUI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(739, '080505', 'CUSCO', 'CANAS', 'LAYO', 'LAYO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(740, '080506', 'CUSCO', 'CANAS', 'PAMPAMARCA', 'PAMPAMARCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(741, '080507', 'CUSCO', 'CANAS', 'QUEHUE', 'QUEHUE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(742, '080508', 'CUSCO', 'CANAS', 'TUPAC AMARU', 'TUNGASUCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(743, '080601', 'CUSCO', 'CANCHIS', 'SICUANI', 'SICUANI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(744, '080602', 'CUSCO', 'CANCHIS', 'CHECACUPE', 'CHECACUPE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(745, '080603', 'CUSCO', 'CANCHIS', 'COMBAPATA', 'COMBAPATA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(746, '080604', 'CUSCO', 'CANCHIS', 'MARANGANI', 'MARANGANI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(747, '080605', 'CUSCO', 'CANCHIS', 'PITUMARCA', 'PITUMARCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(748, '080606', 'CUSCO', 'CANCHIS', 'SAN PABLO', 'SAN PABLO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(749, '080607', 'CUSCO', 'CANCHIS', 'SAN PEDRO', 'SAN PEDRO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(750, '080608', 'CUSCO', 'CANCHIS', 'TINTA', 'TINTA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(751, '080701', 'CUSCO', 'CHUMBIVILCAS', 'SANTO TOMAS', 'SANTO TOMAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(752, '080702', 'CUSCO', 'CHUMBIVILCAS', 'CAPACMARCA', 'CAPACMARCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(753, '080703', 'CUSCO', 'CHUMBIVILCAS', 'CHAMACA', 'CHAMACA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(754, '080704', 'CUSCO', 'CHUMBIVILCAS', 'COLQUEMARCA', 'COLQUEMARCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(755, '080705', 'CUSCO', 'CHUMBIVILCAS', 'LIVITACA', 'LIVITACA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(756, '080706', 'CUSCO', 'CHUMBIVILCAS', 'LLUSCO', 'LLUSCO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(757, '080707', 'CUSCO', 'CHUMBIVILCAS', 'QUIÑOTA', 'QUIÑOTA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(758, '080708', 'CUSCO', 'CHUMBIVILCAS', 'VELILLE', 'VELILLE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(759, '080801', 'CUSCO', 'ESPINAR', 'ESPINAR', 'YAURI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(760, '080802', 'CUSCO', 'ESPINAR', 'CONDOROMA', 'CONDOROMA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(761, '080803', 'CUSCO', 'ESPINAR', 'COPORAQUE', 'COPORAQUE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(762, '080804', 'CUSCO', 'ESPINAR', 'OCORURO', 'OCORURO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(763, '080805', 'CUSCO', 'ESPINAR', 'PALLPATA', 'HECTOR TEJADA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(764, '080806', 'CUSCO', 'ESPINAR', 'PICHIGUA', 'PICHIGUA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(765, '080807', 'CUSCO', 'ESPINAR', 'SUYCKUTAMBO', 'VIRGINIYOC /5', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(766, '080808', 'CUSCO', 'ESPINAR', 'ALTO PICHIGUA', 'ACCOCUNCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(767, '080901', 'CUSCO', 'LA CONVENCION', 'SANTA ANA', 'QUILLABAMBA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(768, '080902', 'CUSCO', 'LA CONVENCION', 'ECHARATE', 'ECHARATE', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(769, '080903', 'CUSCO', 'LA CONVENCION', 'HUAYOPATA', 'HUYRO /6', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(770, '080904', 'CUSCO', 'LA CONVENCION', 'MARANURA', 'MARANURA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(771, '080905', 'CUSCO', 'LA CONVENCION', 'OCOBAMBA', 'KQUELCCAYBAMBA /7', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(772, '080906', 'CUSCO', 'LA CONVENCION', 'QUELLOUNO', 'QUELLOUNO', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(773, '080907', 'CUSCO', 'LA CONVENCION', 'KIMBIRI', 'KIMBIRI', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(774, '080908', 'CUSCO', 'LA CONVENCION', 'SANTA TERESA', 'SANTA TERESA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(775, '080909', 'CUSCO', 'LA CONVENCION', 'VILCABAMBA', 'LUCMA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(776, '080910', 'CUSCO', 'LA CONVENCION', 'PICHARI', 'PICHARI', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(777, '080911', 'CUSCO', 'LA CONVENCION', 'INKAWASI', 'AMAYBAMBA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(778, '080912', 'CUSCO', 'LA CONVENCION', 'VILLA VIRGEN', 'VILLA VIRGEN', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(779, '080913', 'CUSCO', 'LA CONVENCION', 'VILLA KINTIARINA', 'VILLA KINTIARINA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(780, '080914', 'CUSCO', 'LA CONVENCION', 'MEGANTONI', 'CAMISEA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(781, '080915', 'CUSCO', 'LA CONVENCION', 'KUMPIRUSHIATO', 'KEPASHIATO', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(782, '080916', 'CUSCO', 'LA CONVENCION', 'CIELO PUNCO', 'CHIRUMPIARI', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(783, '080917', 'CUSCO', 'LA CONVENCION', 'MANITEA', 'TAWANTINSUYO', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(784, '080918', 'CUSCO', 'LA CONVENCION', 'UNION ASHANINKA', 'MANTARO', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(785, '081001', 'CUSCO', 'PARURO', 'PARURO', 'PARURO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(786, '081002', 'CUSCO', 'PARURO', 'ACCHA', 'ACCHA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(787, '081003', 'CUSCO', 'PARURO', 'CCAPI', 'CCAPI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(788, '081004', 'CUSCO', 'PARURO', 'COLCHA', 'COLCHA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(789, '081005', 'CUSCO', 'PARURO', 'HUANOQUITE', 'HUANOQUITE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(790, '081006', 'CUSCO', 'PARURO', 'OMACHA', 'OMACHA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(791, '081007', 'CUSCO', 'PARURO', 'PACCARITAMBO', 'PACCARITAMBO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(792, '081008', 'CUSCO', 'PARURO', 'PILLPINTO', 'PILLPINTO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(793, '081009', 'CUSCO', 'PARURO', 'YAURISQUE', 'YAURISQUE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `locations` (`id`, `iddist`, `nombdep`, `nombprov`, `nombdist`, `nom_capital`, `cod_reg_nat`, `region_natural`, `created_at`, `updated_at`) VALUES
(794, '081101', 'CUSCO', 'PAUCARTAMBO', 'PAUCARTAMBO', 'PAUCARTAMBO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(795, '081102', 'CUSCO', 'PAUCARTAMBO', 'CAICAY', 'CAICAY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(796, '081103', 'CUSCO', 'PAUCARTAMBO', 'CHALLABAMBA', 'CHALLABAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(797, '081104', 'CUSCO', 'PAUCARTAMBO', 'COLQUEPATA', 'COLQUEPATA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(798, '081105', 'CUSCO', 'PAUCARTAMBO', 'HUANCARANI', 'HUANCARANI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(799, '081106', 'CUSCO', 'PAUCARTAMBO', 'KOSÑIPATA', 'PILLCOPATA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(800, '081201', 'CUSCO', 'QUISPICANCHI', 'URCOS', 'URCOS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(801, '081202', 'CUSCO', 'QUISPICANCHI', 'ANDAHUAYLILLAS', 'ANDAHUAYLILLAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(802, '081203', 'CUSCO', 'QUISPICANCHI', 'CAMANTI', 'QUINCE MIL', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(803, '081204', 'CUSCO', 'QUISPICANCHI', 'CCARHUAYO', 'CCARHUAYO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(804, '081205', 'CUSCO', 'QUISPICANCHI', 'CCATCA', 'CCATCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(805, '081206', 'CUSCO', 'QUISPICANCHI', 'CUSIPATA', 'CUSIPATA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(806, '081207', 'CUSCO', 'QUISPICANCHI', 'HUARO', 'HUARO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(807, '081208', 'CUSCO', 'QUISPICANCHI', 'LUCRE', 'LUCRE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(808, '081209', 'CUSCO', 'QUISPICANCHI', 'MARCAPATA', 'MARCAPATA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(809, '081210', 'CUSCO', 'QUISPICANCHI', 'OCONGATE', 'OCONGATE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(810, '081211', 'CUSCO', 'QUISPICANCHI', 'OROPESA', 'OROPESA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(811, '081212', 'CUSCO', 'QUISPICANCHI', 'QUIQUIJANA', 'QUIQUIJANA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(812, '081301', 'CUSCO', 'URUBAMBA', 'URUBAMBA', 'URUBAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(813, '081302', 'CUSCO', 'URUBAMBA', 'CHINCHERO', 'CHINCHERO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(814, '081303', 'CUSCO', 'URUBAMBA', 'HUAYLLABAMBA', 'HUAYLLABAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(815, '081304', 'CUSCO', 'URUBAMBA', 'MACHUPICCHU', 'MACHUPICCHU', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(816, '081305', 'CUSCO', 'URUBAMBA', 'MARAS', 'MARAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(817, '081306', 'CUSCO', 'URUBAMBA', 'OLLANTAYTAMBO', 'OLLANTAYTAMBO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(818, '081307', 'CUSCO', 'URUBAMBA', 'YUCAY', 'YUCAY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(819, '090101', 'HUANCAVELICA', 'HUANCAVELICA', 'HUANCAVELICA', 'HUANCAVELICA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(820, '090102', 'HUANCAVELICA', 'HUANCAVELICA', 'ACOBAMBILLA', 'ACOBAMBILLA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(821, '090103', 'HUANCAVELICA', 'HUANCAVELICA', 'ACORIA', 'ACORIA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(822, '090104', 'HUANCAVELICA', 'HUANCAVELICA', 'CONAYCA', 'CONAYCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(823, '090105', 'HUANCAVELICA', 'HUANCAVELICA', 'CUENCA', 'CUENCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(824, '090106', 'HUANCAVELICA', 'HUANCAVELICA', 'HUACHOCOLPA', 'HUACHOCOLPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(825, '090107', 'HUANCAVELICA', 'HUANCAVELICA', 'HUAYLLAHUARA', 'HUAYLLAHUARA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(826, '090108', 'HUANCAVELICA', 'HUANCAVELICA', 'IZCUCHACA', 'IZCUCHACA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(827, '090109', 'HUANCAVELICA', 'HUANCAVELICA', 'LARIA', 'LARIA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(828, '090110', 'HUANCAVELICA', 'HUANCAVELICA', 'MANTA', 'MANTA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(829, '090111', 'HUANCAVELICA', 'HUANCAVELICA', 'MARISCAL CACERES', 'MARISCAL CACERES', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(830, '090112', 'HUANCAVELICA', 'HUANCAVELICA', 'MOYA', 'MOYA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(831, '090113', 'HUANCAVELICA', 'HUANCAVELICA', 'NUEVO OCCORO', 'OCCORO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(832, '090114', 'HUANCAVELICA', 'HUANCAVELICA', 'PALCA', 'PALCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(833, '090115', 'HUANCAVELICA', 'HUANCAVELICA', 'PILCHACA', 'PILCHACA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(834, '090116', 'HUANCAVELICA', 'HUANCAVELICA', 'VILCA', 'VILCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(835, '090117', 'HUANCAVELICA', 'HUANCAVELICA', 'YAULI', 'YAULI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(836, '090118', 'HUANCAVELICA', 'HUANCAVELICA', 'ASCENSION', 'ASCENSION', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(837, '090119', 'HUANCAVELICA', 'HUANCAVELICA', 'HUANDO', 'HUANDO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(838, '090201', 'HUANCAVELICA', 'ACOBAMBA', 'ACOBAMBA', 'ACOBAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(839, '090202', 'HUANCAVELICA', 'ACOBAMBA', 'ANDABAMBA', 'ANDABAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(840, '090203', 'HUANCAVELICA', 'ACOBAMBA', 'ANTA', 'ANTA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(841, '090204', 'HUANCAVELICA', 'ACOBAMBA', 'CAJA', 'CAJA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(842, '090205', 'HUANCAVELICA', 'ACOBAMBA', 'MARCAS', 'MARCAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(843, '090206', 'HUANCAVELICA', 'ACOBAMBA', 'PAUCARA', 'PAUCARA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(844, '090207', 'HUANCAVELICA', 'ACOBAMBA', 'POMACOCHA', 'POMACOCHA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(845, '090208', 'HUANCAVELICA', 'ACOBAMBA', 'ROSARIO', 'ROSARIO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(846, '090301', 'HUANCAVELICA', 'ANGARAES', 'LIRCAY', 'LIRCAY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(847, '090302', 'HUANCAVELICA', 'ANGARAES', 'ANCHONGA', 'ANCHONGA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(848, '090303', 'HUANCAVELICA', 'ANGARAES', 'CALLANMARCA', 'CALLANMARCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(849, '090304', 'HUANCAVELICA', 'ANGARAES', 'CCOCHACCASA', 'CCOCHACCASA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(850, '090305', 'HUANCAVELICA', 'ANGARAES', 'CHINCHO', 'CHINCHO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(851, '090306', 'HUANCAVELICA', 'ANGARAES', 'CONGALLA', 'CONGALLA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(852, '090307', 'HUANCAVELICA', 'ANGARAES', 'HUANCA-HUANCA', 'HUANCA-HUANCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(853, '090308', 'HUANCAVELICA', 'ANGARAES', 'HUAYLLAY GRANDE', 'HUAYLLAY GRANDE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(854, '090309', 'HUANCAVELICA', 'ANGARAES', 'JULCAMARCA', 'JULCAMARCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(855, '090310', 'HUANCAVELICA', 'ANGARAES', 'SAN ANTONIO DE ANTAPARCO', 'ANTAPARCO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(856, '090311', 'HUANCAVELICA', 'ANGARAES', 'SANTO TOMAS DE PATA', 'SANTO TOMAS DE PATA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(857, '090312', 'HUANCAVELICA', 'ANGARAES', 'SECCLLA', 'SECCLLA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(858, '090401', 'HUANCAVELICA', 'CASTROVIRREYNA', 'CASTROVIRREYNA', 'CASTROVIRREYNA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(859, '090402', 'HUANCAVELICA', 'CASTROVIRREYNA', 'ARMA', 'ARMA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(860, '090403', 'HUANCAVELICA', 'CASTROVIRREYNA', 'AURAHUA', 'AURAHUA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(861, '090404', 'HUANCAVELICA', 'CASTROVIRREYNA', 'CAPILLAS', 'CAPILLAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(862, '090405', 'HUANCAVELICA', 'CASTROVIRREYNA', 'CHUPAMARCA', 'CHUPAMARCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(863, '090406', 'HUANCAVELICA', 'CASTROVIRREYNA', 'COCAS', 'COCAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(864, '090407', 'HUANCAVELICA', 'CASTROVIRREYNA', 'HUACHOS', 'HUACHOS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(865, '090408', 'HUANCAVELICA', 'CASTROVIRREYNA', 'HUAMATAMBO', 'HUAMATAMBO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(866, '090409', 'HUANCAVELICA', 'CASTROVIRREYNA', 'MOLLEPAMPA', 'MOLLEPAMPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(867, '090410', 'HUANCAVELICA', 'CASTROVIRREYNA', 'SAN JUAN', 'SAN JUAN', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(868, '090411', 'HUANCAVELICA', 'CASTROVIRREYNA', 'SANTA ANA', 'SANTA ANA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(869, '090412', 'HUANCAVELICA', 'CASTROVIRREYNA', 'TANTARA', 'TANTARA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(870, '090413', 'HUANCAVELICA', 'CASTROVIRREYNA', 'TICRAPO', 'TICRAPO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(871, '090501', 'HUANCAVELICA', 'CHURCAMPA', 'CHURCAMPA', 'CHURCAMPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(872, '090502', 'HUANCAVELICA', 'CHURCAMPA', 'ANCO', 'LA ESMERALDA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(873, '090503', 'HUANCAVELICA', 'CHURCAMPA', 'CHINCHIHUASI', 'CHINCHIHUASI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(874, '090504', 'HUANCAVELICA', 'CHURCAMPA', 'EL CARMEN', 'PAUCARBAMBILLA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(875, '090505', 'HUANCAVELICA', 'CHURCAMPA', 'LA MERCED', 'LA MERCED', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(876, '090506', 'HUANCAVELICA', 'CHURCAMPA', 'LOCROJA', 'LOCROJA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(877, '090507', 'HUANCAVELICA', 'CHURCAMPA', 'PAUCARBAMBA', 'PAUCARBAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(878, '090508', 'HUANCAVELICA', 'CHURCAMPA', 'SAN MIGUEL DE MAYOCC', 'MAYOCC', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(879, '090509', 'HUANCAVELICA', 'CHURCAMPA', 'SAN PEDRO DE CORIS', 'SAN PEDRO DE CORIS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(880, '090510', 'HUANCAVELICA', 'CHURCAMPA', 'PACHAMARCA', 'PACHAMARCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(881, '090511', 'HUANCAVELICA', 'CHURCAMPA', 'COSME', 'SANTA CLARA DE COSME', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(882, '090601', 'HUANCAVELICA', 'HUAYTARA', 'HUAYTARA', 'HUAYTARA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(883, '090602', 'HUANCAVELICA', 'HUAYTARA', 'AYAVI', 'AYAVI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(884, '090603', 'HUANCAVELICA', 'HUAYTARA', 'CORDOVA', 'CORDOVA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(885, '090604', 'HUANCAVELICA', 'HUAYTARA', 'HUAYACUNDO ARMA', 'HUAYACUNDO ARMA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(886, '090605', 'HUANCAVELICA', 'HUAYTARA', 'LARAMARCA', 'LARAMARCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(887, '090606', 'HUANCAVELICA', 'HUAYTARA', 'OCOYO', 'OCOYO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(888, '090607', 'HUANCAVELICA', 'HUAYTARA', 'PILPICHACA', 'PILPICHACA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(889, '090608', 'HUANCAVELICA', 'HUAYTARA', 'QUERCO', 'QUERCO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(890, '090609', 'HUANCAVELICA', 'HUAYTARA', 'QUITO-ARMA', 'QUITO-ARMA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(891, '090610', 'HUANCAVELICA', 'HUAYTARA', 'SAN ANTONIO DE CUSICANCHA', 'CUSICANCHA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(892, '090611', 'HUANCAVELICA', 'HUAYTARA', 'SAN FRANCISCO DE SANGAYAICO', 'SAN FRANCISCO DE SANGAYAICO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(893, '090612', 'HUANCAVELICA', 'HUAYTARA', 'SAN ISIDRO', 'SAN JUAN DE HUIRPACANCHA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(894, '090613', 'HUANCAVELICA', 'HUAYTARA', 'SANTIAGO DE CHOCORVOS', 'SANTIAGO DE CHOCORVOS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(895, '090614', 'HUANCAVELICA', 'HUAYTARA', 'SANTIAGO DE QUIRAHUARA', 'SANTIAGO DE QUIRAHUARA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(896, '090615', 'HUANCAVELICA', 'HUAYTARA', 'SANTO DOMINGO DE CAPILLAS', 'SANTO DOMINGO DE CAPILLAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(897, '090616', 'HUANCAVELICA', 'HUAYTARA', 'TAMBO', 'TAMBO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(898, '090701', 'HUANCAVELICA', 'TAYACAJA', 'PAMPAS', 'PAMPAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(899, '090702', 'HUANCAVELICA', 'TAYACAJA', 'ACOSTAMBO', 'ACOSTAMBO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(900, '090703', 'HUANCAVELICA', 'TAYACAJA', 'ACRAQUIA', 'ACRAQUIA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(901, '090704', 'HUANCAVELICA', 'TAYACAJA', 'AHUAYCHA', 'AHUAYCHA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(902, '090705', 'HUANCAVELICA', 'TAYACAJA', 'COLCABAMBA', 'COLCABAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(903, '090706', 'HUANCAVELICA', 'TAYACAJA', 'DANIEL HERNANDEZ', 'MARISCAL CACERES', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(904, '090707', 'HUANCAVELICA', 'TAYACAJA', 'HUACHOCOLPA', 'HUACHOCOLPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(905, '090709', 'HUANCAVELICA', 'TAYACAJA', 'HUARIBAMBA', 'HUARIBAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(906, '090710', 'HUANCAVELICA', 'TAYACAJA', 'ÑAHUIMPUQUIO', 'ÑAHUIMPUQUIO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(907, '090711', 'HUANCAVELICA', 'TAYACAJA', 'PAZOS', 'PAZOS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(908, '090713', 'HUANCAVELICA', 'TAYACAJA', 'QUISHUAR', 'QUISHUAR', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(909, '090714', 'HUANCAVELICA', 'TAYACAJA', 'SALCABAMBA', 'SALCABAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(910, '090715', 'HUANCAVELICA', 'TAYACAJA', 'SALCAHUASI', 'SALCAHUASI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(911, '090716', 'HUANCAVELICA', 'TAYACAJA', 'SAN MARCOS DE ROCCHAC', 'SAN MARCOS DE ROCCHAC', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(912, '090717', 'HUANCAVELICA', 'TAYACAJA', 'SURCUBAMBA', 'SURCUBAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(913, '090718', 'HUANCAVELICA', 'TAYACAJA', 'TINTAY PUNCU', 'TINTAY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(914, '090719', 'HUANCAVELICA', 'TAYACAJA', 'QUICHUAS', 'QUICHUAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(915, '090720', 'HUANCAVELICA', 'TAYACAJA', 'ANDAYMARCA', 'ANDAYMARCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(916, '090721', 'HUANCAVELICA', 'TAYACAJA', 'ROBLE', 'PUERTO SAN ANTONIO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(917, '090722', 'HUANCAVELICA', 'TAYACAJA', 'PICHOS', 'PICHOS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(918, '090723', 'HUANCAVELICA', 'TAYACAJA', 'SANTIAGO DE TUCUMA', 'SANTIAGO DE TUCUMA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(919, '090724', 'HUANCAVELICA', 'TAYACAJA', 'LAMBRAS', 'SANTA MARIA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(920, '090725', 'HUANCAVELICA', 'TAYACAJA', 'COCHABAMBA', 'COCHABAMBA GRANDE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(921, '100101', 'HUANUCO', 'HUANUCO', 'HUANUCO', 'HUANUCO', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(922, '100102', 'HUANUCO', 'HUANUCO', 'AMARILIS', 'PAUCARBAMBA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(923, '100103', 'HUANUCO', 'HUANUCO', 'CHINCHAO', 'ACOMAYO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(924, '100104', 'HUANUCO', 'HUANUCO', 'CHURUBAMBA', 'CHURUBAMBA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(925, '100105', 'HUANUCO', 'HUANUCO', 'MARGOS', 'MARGOS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(926, '100106', 'HUANUCO', 'HUANUCO', 'QUISQUI (KICHKI)', 'HUANCAPALLAC', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(927, '100107', 'HUANUCO', 'HUANUCO', 'SAN FRANCISCO DE CAYRAN', 'CAYRAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(928, '100108', 'HUANUCO', 'HUANUCO', 'SAN PEDRO DE CHAULAN', 'CHAULAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(929, '100109', 'HUANUCO', 'HUANUCO', 'SANTA MARIA DEL VALLE', 'SANTA MARIA DEL VALLE', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(930, '100110', 'HUANUCO', 'HUANUCO', 'YARUMAYO', 'YARUMAYO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(931, '100111', 'HUANUCO', 'HUANUCO', 'PILLCO MARCA', 'CAYHUAYNA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(932, '100112', 'HUANUCO', 'HUANUCO', 'YACUS', 'YACUS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(933, '100113', 'HUANUCO', 'HUANUCO', 'SAN PABLO DE PILLAO', 'SAN PABLO DE PILLAO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(934, '100201', 'HUANUCO', 'AMBO', 'AMBO', 'AMBO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(935, '100202', 'HUANUCO', 'AMBO', 'CAYNA', 'CAYNA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(936, '100203', 'HUANUCO', 'AMBO', 'COLPAS', 'COLPAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(937, '100204', 'HUANUCO', 'AMBO', 'CONCHAMARCA', 'CONCHAMARCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(938, '100205', 'HUANUCO', 'AMBO', 'HUACAR', 'HUACAR', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(939, '100206', 'HUANUCO', 'AMBO', 'SAN FRANCISCO', 'MOSCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(940, '100207', 'HUANUCO', 'AMBO', 'SAN RAFAEL', 'SAN RAFAEL', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(941, '100208', 'HUANUCO', 'AMBO', 'TOMAY KICHWA', 'TOMAY KICHWA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(942, '100301', 'HUANUCO', 'DOS DE MAYO', 'LA UNION', 'LA UNION', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(943, '100307', 'HUANUCO', 'DOS DE MAYO', 'CHUQUIS', 'CHUQUIS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(944, '100311', 'HUANUCO', 'DOS DE MAYO', 'MARIAS', 'MARIAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(945, '100313', 'HUANUCO', 'DOS DE MAYO', 'PACHAS', 'PACHAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(946, '100316', 'HUANUCO', 'DOS DE MAYO', 'QUIVILLA', 'QUIVILLA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(947, '100317', 'HUANUCO', 'DOS DE MAYO', 'RIPAN', 'RIPAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(948, '100321', 'HUANUCO', 'DOS DE MAYO', 'SHUNQUI', 'SHUNQUI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(949, '100322', 'HUANUCO', 'DOS DE MAYO', 'SILLAPATA', 'SILLAPATA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(950, '100323', 'HUANUCO', 'DOS DE MAYO', 'YANAS', 'YANAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(951, '100401', 'HUANUCO', 'HUACAYBAMBA', 'HUACAYBAMBA', 'HUACAYBAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(952, '100402', 'HUANUCO', 'HUACAYBAMBA', 'CANCHABAMBA', 'CANCHABAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(953, '100403', 'HUANUCO', 'HUACAYBAMBA', 'COCHABAMBA', 'COCHABAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(954, '100404', 'HUANUCO', 'HUACAYBAMBA', 'PINRA', 'PINRA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(955, '100501', 'HUANUCO', 'HUAMALIES', 'LLATA', 'LLATA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(956, '100502', 'HUANUCO', 'HUAMALIES', 'ARANCAY', 'ARANCAY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(957, '100503', 'HUANUCO', 'HUAMALIES', 'CHAVIN DE PARIARCA', 'CHAVIN DE PARIARCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(958, '100504', 'HUANUCO', 'HUAMALIES', 'JACAS GRANDE', 'JACAS GRANDE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(959, '100505', 'HUANUCO', 'HUAMALIES', 'JIRCAN', 'JIRCAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(960, '100506', 'HUANUCO', 'HUAMALIES', 'MIRAFLORES', 'MIRAFLORES', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(961, '100507', 'HUANUCO', 'HUAMALIES', 'MONZON', 'MONZON', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(962, '100508', 'HUANUCO', 'HUAMALIES', 'PUNCHAO', 'PUNCHAO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(963, '100509', 'HUANUCO', 'HUAMALIES', 'PUÑOS', 'PUÑOS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(964, '100510', 'HUANUCO', 'HUAMALIES', 'SINGA', 'SINGA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(965, '100511', 'HUANUCO', 'HUAMALIES', 'TANTAMAYO', 'TANTAMAYO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(966, '100601', 'HUANUCO', 'LEONCIO PRADO', 'RUPA-RUPA', 'TINGO MARIA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(967, '100602', 'HUANUCO', 'LEONCIO PRADO', 'DANIEL ALOMIA ROBLES', 'DANIEL ALOMIA ROBLES (PUMAHUASI)', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(968, '100603', 'HUANUCO', 'LEONCIO PRADO', 'HERMILIO VALDIZAN', 'HERMILIO VALDIZAN', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(969, '100604', 'HUANUCO', 'LEONCIO PRADO', 'JOSE CRESPO Y CASTILLO', 'AUCAYACU', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(970, '100605', 'HUANUCO', 'LEONCIO PRADO', 'LUYANDO', 'NARANJILLO /8', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(971, '100606', 'HUANUCO', 'LEONCIO PRADO', 'MARIANO DAMASO BERAUN', 'LAS PALMAS', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(972, '100607', 'HUANUCO', 'LEONCIO PRADO', 'PUCAYACU', 'PUCAYACU', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(973, '100608', 'HUANUCO', 'LEONCIO PRADO', 'CASTILLO GRANDE', 'CASTILLO GRANDE', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(974, '100609', 'HUANUCO', 'LEONCIO PRADO', 'PUEBLO NUEVO', 'PUEBLO NUEVO', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(975, '100610', 'HUANUCO', 'LEONCIO PRADO', 'SANTO DOMINGO DE ANDA', 'PACAE', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(976, '100701', 'HUANUCO', 'MARAÑON', 'HUACRACHUCO', 'HUACRACHUCO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(977, '100702', 'HUANUCO', 'MARAÑON', 'CHOLON', 'SAN PEDRO DE CHONTA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(978, '100703', 'HUANUCO', 'MARAÑON', 'SAN BUENAVENTURA', 'SAN BUENAVENTURA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(979, '100704', 'HUANUCO', 'MARAÑON', 'LA MORADA', 'LA MORADA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(980, '100705', 'HUANUCO', 'MARAÑON', 'SANTA ROSA DE ALTO YANAJANCA', 'SANTA ROSA DE ALTO YANAJANCA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(981, '100801', 'HUANUCO', 'PACHITEA', 'PANAO', 'PANAO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(982, '100802', 'HUANUCO', 'PACHITEA', 'CHAGLLA', 'CHAGLLA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(983, '100803', 'HUANUCO', 'PACHITEA', 'MOLINO', 'MOLINO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(984, '100804', 'HUANUCO', 'PACHITEA', 'UMARI', 'UMARI (TAMBILLO)', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(985, '100901', 'HUANUCO', 'PUERTO INCA', 'PUERTO INCA', 'PUERTO INCA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(986, '100902', 'HUANUCO', 'PUERTO INCA', 'CODO DEL POZUZO', 'CODO DEL POZUZO', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(987, '100903', 'HUANUCO', 'PUERTO INCA', 'HONORIA', 'HONORIA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(988, '100904', 'HUANUCO', 'PUERTO INCA', 'TOURNAVISTA', 'TOURNAVISTA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(989, '100905', 'HUANUCO', 'PUERTO INCA', 'YUYAPICHIS', 'YUYAPICHIS', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(990, '101001', 'HUANUCO', 'LAURICOCHA', 'JESUS', 'JESUS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(991, '101002', 'HUANUCO', 'LAURICOCHA', 'BAÑOS', 'BAÑOS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(992, '101003', 'HUANUCO', 'LAURICOCHA', 'JIVIA', 'JIVIA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(993, '101004', 'HUANUCO', 'LAURICOCHA', 'QUEROPALCA', 'QUEROPALCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(994, '101005', 'HUANUCO', 'LAURICOCHA', 'RONDOS', 'RONDOS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(995, '101006', 'HUANUCO', 'LAURICOCHA', 'SAN FRANCISCO DE ASIS', 'HUARIN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(996, '101007', 'HUANUCO', 'LAURICOCHA', 'SAN MIGUEL DE CAURI', 'CAURI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(997, '101101', 'HUANUCO', 'YAROWILCA', 'CHAVINILLO', 'CHAVINILLO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(998, '101102', 'HUANUCO', 'YAROWILCA', 'CAHUAC', 'CAHUAC', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(999, '101103', 'HUANUCO', 'YAROWILCA', 'CHACABAMBA', 'CHACABAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1000, '101104', 'HUANUCO', 'YAROWILCA', 'APARICIO POMARES', 'CHUPAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1001, '101105', 'HUANUCO', 'YAROWILCA', 'JACAS CHICO', 'SAN CRISTOBAL DE JACAS CHICO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1002, '101106', 'HUANUCO', 'YAROWILCA', 'OBAS', 'OBAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1003, '101107', 'HUANUCO', 'YAROWILCA', 'PAMPAMARCA', 'PAMPAMARCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1004, '101108', 'HUANUCO', 'YAROWILCA', 'CHORAS', 'CHORAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1005, '110101', 'ICA', 'ICA', 'ICA', 'ICA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1006, '110102', 'ICA', 'ICA', 'LA TINGUIÑA', 'LA TINGUIÑA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1007, '110103', 'ICA', 'ICA', 'LOS AQUIJES', 'LOS AQUIJES', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1008, '110104', 'ICA', 'ICA', 'OCUCAJE', 'OCUCAJE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1009, '110105', 'ICA', 'ICA', 'PACHACUTEC', 'PAMPA DE TATE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1010, '110106', 'ICA', 'ICA', 'PARCONA', 'PARCONA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1011, '110107', 'ICA', 'ICA', 'PUEBLO NUEVO', 'PUEBLO NUEVO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1012, '110108', 'ICA', 'ICA', 'SALAS', 'GUADALUPE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1013, '110109', 'ICA', 'ICA', 'SAN JOSE DE LOS MOLINOS', 'SAN JOSE DE LOS MOLINOS', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1014, '110110', 'ICA', 'ICA', 'SAN JUAN BAUTISTA', 'SAN JUAN BAUTISTA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1015, '110111', 'ICA', 'ICA', 'SANTIAGO', 'SANTIAGO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1016, '110112', 'ICA', 'ICA', 'SUBTANJALLA', 'SUBTANJALLA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1017, '110113', 'ICA', 'ICA', 'TATE', 'TATE DE LA CAPILLA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1018, '110114', 'ICA', 'ICA', 'YAUCA DEL ROSARIO', 'PAMPAHUASI /9', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1019, '110201', 'ICA', 'CHINCHA', 'CHINCHA ALTA', 'CHINCHA ALTA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1020, '110202', 'ICA', 'CHINCHA', 'ALTO LARAN', 'ALTO LARAN', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1021, '110203', 'ICA', 'CHINCHA', 'CHAVIN', 'CHAVIN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1022, '110204', 'ICA', 'CHINCHA', 'CHINCHA BAJA', 'CHINCHA BAJA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1023, '110205', 'ICA', 'CHINCHA', 'EL CARMEN', 'EL CARMEN', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1024, '110206', 'ICA', 'CHINCHA', 'GROCIO PRADO', 'SAN PEDRO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1025, '110207', 'ICA', 'CHINCHA', 'PUEBLO NUEVO', 'PUEBLO NUEVO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1026, '110208', 'ICA', 'CHINCHA', 'SAN JUAN DE YANAC', 'SAN JUAN DE YANAC', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1027, '110209', 'ICA', 'CHINCHA', 'SAN PEDRO DE HUACARPANA', 'SAN PEDRO DE HUACARPANA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1028, '110210', 'ICA', 'CHINCHA', 'SUNAMPE', 'SUNAMPE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1029, '110211', 'ICA', 'CHINCHA', 'TAMBO DE MORA', 'TAMBO DE MORA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1030, '110301', 'ICA', 'NASCA', 'NASCA', 'NASCA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1031, '110302', 'ICA', 'NASCA', 'CHANGUILLO', 'CHANGUILLO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1032, '110303', 'ICA', 'NASCA', 'EL INGENIO', 'EL INGENIO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1033, '110304', 'ICA', 'NASCA', 'MARCONA', 'SAN JUAN', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1034, '110305', 'ICA', 'NASCA', 'VISTA ALEGRE', 'VISTA ALEGRE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1035, '110401', 'ICA', 'PALPA', 'PALPA', 'PALPA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1036, '110402', 'ICA', 'PALPA', 'LLIPATA', 'LLIPATA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1037, '110403', 'ICA', 'PALPA', 'RIO GRANDE', 'RIO GRANDE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1038, '110404', 'ICA', 'PALPA', 'SANTA CRUZ', 'SANTA CRUZ', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1039, '110405', 'ICA', 'PALPA', 'TIBILLO', 'TIBILLO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1040, '110501', 'ICA', 'PISCO', 'PISCO', 'PISCO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1041, '110502', 'ICA', 'PISCO', 'HUANCANO', 'HUANCANO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1042, '110503', 'ICA', 'PISCO', 'HUMAY', 'HUMAY', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1043, '110504', 'ICA', 'PISCO', 'INDEPENDENCIA', 'INDEPENDENCIA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1044, '110505', 'ICA', 'PISCO', 'PARACAS', 'PARACAS', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1045, '110506', 'ICA', 'PISCO', 'SAN ANDRES', 'SAN ANDRES', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1046, '110507', 'ICA', 'PISCO', 'SAN CLEMENTE', 'SAN CLEMENTE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1047, '110508', 'ICA', 'PISCO', 'TUPAC AMARU INCA', 'TUPAC AMARU', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1048, '120101', 'JUNIN', 'HUANCAYO', 'HUANCAYO', 'HUANCAYO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1049, '120104', 'JUNIN', 'HUANCAYO', 'CARHUACALLANGA', 'CARHUACALLANGA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1050, '120105', 'JUNIN', 'HUANCAYO', 'CHACAPAMPA', 'CHACAPAMPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1051, '120106', 'JUNIN', 'HUANCAYO', 'CHICCHE', 'CHICCHE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1052, '120107', 'JUNIN', 'HUANCAYO', 'CHILCA', 'CHILCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1053, '120108', 'JUNIN', 'HUANCAYO', 'CHONGOS ALTO', 'CHONGOS ALTO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1054, '120111', 'JUNIN', 'HUANCAYO', 'CHUPURO', 'CHUPURO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1055, '120112', 'JUNIN', 'HUANCAYO', 'COLCA', 'COLCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1056, '120113', 'JUNIN', 'HUANCAYO', 'CULLHUAS', 'CULLHUAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1057, '120114', 'JUNIN', 'HUANCAYO', 'EL TAMBO', 'EL TAMBO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1058, '120116', 'JUNIN', 'HUANCAYO', 'HUACRAPUQUIO', 'HUACRAPUQUIO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1059, '120117', 'JUNIN', 'HUANCAYO', 'HUALHUAS', 'HUALHUAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1060, '120119', 'JUNIN', 'HUANCAYO', 'HUANCAN', 'HUANCAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1061, '120120', 'JUNIN', 'HUANCAYO', 'HUASICANCHA', 'HUASICANCHA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1062, '120121', 'JUNIN', 'HUANCAYO', 'HUAYUCACHI', 'HUAYUCACHI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1063, '120122', 'JUNIN', 'HUANCAYO', 'INGENIO', 'INGENIO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1064, '120124', 'JUNIN', 'HUANCAYO', 'PARIAHUANCA', 'LAMPA /10', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1065, '120125', 'JUNIN', 'HUANCAYO', 'PILCOMAYO', 'PILCOMAYO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1066, '120126', 'JUNIN', 'HUANCAYO', 'PUCARA', 'PUCARA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1067, '120127', 'JUNIN', 'HUANCAYO', 'QUICHUAY', 'QUICHUAY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1068, '120128', 'JUNIN', 'HUANCAYO', 'QUILCAS', 'QUILCAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1069, '120129', 'JUNIN', 'HUANCAYO', 'SAN AGUSTIN', 'SAN AGUSTIN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1070, '120130', 'JUNIN', 'HUANCAYO', 'SAN JERONIMO DE TUNAN', 'SAN JERONIMO DE TUNAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1071, '120132', 'JUNIN', 'HUANCAYO', 'SAÑO', 'SAÑO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1072, '120133', 'JUNIN', 'HUANCAYO', 'SAPALLANGA', 'SAPALLANGA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1073, '120134', 'JUNIN', 'HUANCAYO', 'SICAYA', 'SICAYA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1074, '120135', 'JUNIN', 'HUANCAYO', 'SANTO DOMINGO DE ACOBAMBA', 'SANTO DOMINGO DE ACOBAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1075, '120136', 'JUNIN', 'HUANCAYO', 'VIQUES', 'VIQUES', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1076, '120201', 'JUNIN', 'CONCEPCION', 'CONCEPCION', 'CONCEPCION', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1077, '120202', 'JUNIN', 'CONCEPCION', 'ACO', 'ACO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1078, '120203', 'JUNIN', 'CONCEPCION', 'ANDAMARCA', 'ANDAMARCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1079, '120204', 'JUNIN', 'CONCEPCION', 'CHAMBARA', 'CHAMBARA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1080, '120205', 'JUNIN', 'CONCEPCION', 'COCHAS', 'COCHAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1081, '120206', 'JUNIN', 'CONCEPCION', 'COMAS', 'COMAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1082, '120207', 'JUNIN', 'CONCEPCION', 'HEROINAS TOLEDO', 'SAN ANTONIO DE OCOPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1083, '120208', 'JUNIN', 'CONCEPCION', 'MANZANARES', 'SAN MIGUEL', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1084, '120209', 'JUNIN', 'CONCEPCION', 'MARISCAL CASTILLA', 'MUCLLO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1085, '120210', 'JUNIN', 'CONCEPCION', 'MATAHUASI', 'MATAHUASI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1086, '120211', 'JUNIN', 'CONCEPCION', 'MITO', 'MITO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1087, '120212', 'JUNIN', 'CONCEPCION', 'NUEVE DE JULIO', 'SANTO DOMINGO DEL PRADO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1088, '120213', 'JUNIN', 'CONCEPCION', 'ORCOTUNA', 'ORCOTUNA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1089, '120214', 'JUNIN', 'CONCEPCION', 'SAN JOSE DE QUERO', 'SAN JOSE DE QUERO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1090, '120215', 'JUNIN', 'CONCEPCION', 'SANTA ROSA DE OCOPA', 'SANTA ROSA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1091, '120301', 'JUNIN', 'CHANCHAMAYO', 'CHANCHAMAYO', 'LA MERCED', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1092, '120302', 'JUNIN', 'CHANCHAMAYO', 'PERENE', 'PERENE', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1093, '120303', 'JUNIN', 'CHANCHAMAYO', 'PICHANAQUI', 'BAJO PICHANAQUI', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1094, '120304', 'JUNIN', 'CHANCHAMAYO', 'SAN LUIS DE SHUARO', 'SAN LUIS DE SHUARO', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1095, '120305', 'JUNIN', 'CHANCHAMAYO', 'SAN RAMON', 'SAN RAMON', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1096, '120306', 'JUNIN', 'CHANCHAMAYO', 'VITOC', 'PUCARA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1097, '120401', 'JUNIN', 'JAUJA', 'JAUJA', 'JAUJA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1098, '120402', 'JUNIN', 'JAUJA', 'ACOLLA', 'ACOLLA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1099, '120403', 'JUNIN', 'JAUJA', 'APATA', 'APATA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1100, '120404', 'JUNIN', 'JAUJA', 'ATAURA', 'ATAURA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1101, '120405', 'JUNIN', 'JAUJA', 'CANCHAYLLO', 'CANCHAYLLO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1102, '120406', 'JUNIN', 'JAUJA', 'CURICACA', 'EL ROSARIO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1103, '120407', 'JUNIN', 'JAUJA', 'EL MANTARO', 'PUCUCHO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1104, '120408', 'JUNIN', 'JAUJA', 'HUAMALI', 'HUAMALI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1105, '120409', 'JUNIN', 'JAUJA', 'HUARIPAMPA', 'HUARIPAMPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1106, '120410', 'JUNIN', 'JAUJA', 'HUERTAS', 'HUERTAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1107, '120411', 'JUNIN', 'JAUJA', 'JANJAILLO', 'JANJAILLO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1108, '120412', 'JUNIN', 'JAUJA', 'JULCAN', 'JULCAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1109, '120413', 'JUNIN', 'JAUJA', 'LEONOR ORDOÑEZ', 'HUANCANI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1110, '120414', 'JUNIN', 'JAUJA', 'LLOCLLAPAMPA', 'LLOCLLAPAMPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1111, '120415', 'JUNIN', 'JAUJA', 'MARCO', 'MARCO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1112, '120416', 'JUNIN', 'JAUJA', 'MASMA', 'MASMA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1113, '120417', 'JUNIN', 'JAUJA', 'MASMA CHICCHE', 'MASMA CHICCHE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1114, '120418', 'JUNIN', 'JAUJA', 'MOLINOS', 'MOLINOS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1115, '120419', 'JUNIN', 'JAUJA', 'MONOBAMBA', 'MONOBAMBA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1116, '120420', 'JUNIN', 'JAUJA', 'MUQUI', 'MUQUI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1117, '120421', 'JUNIN', 'JAUJA', 'MUQUIYAUYO', 'MUQUIYAUYO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1118, '120422', 'JUNIN', 'JAUJA', 'PACA', 'PACA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1119, '120423', 'JUNIN', 'JAUJA', 'PACCHA', 'PACCHA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1120, '120424', 'JUNIN', 'JAUJA', 'PANCAN', 'PANCAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1121, '120425', 'JUNIN', 'JAUJA', 'PARCO', 'PARCO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1122, '120426', 'JUNIN', 'JAUJA', 'POMACANCHA', 'POMACANCHA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1123, '120427', 'JUNIN', 'JAUJA', 'RICRAN', 'RICRAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1124, '120428', 'JUNIN', 'JAUJA', 'SAN LORENZO', 'SAN LORENZO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1125, '120429', 'JUNIN', 'JAUJA', 'SAN PEDRO DE CHUNAN', 'SAN PEDRO DE CHUNAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1126, '120430', 'JUNIN', 'JAUJA', 'SAUSA', 'SAUSA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1127, '120431', 'JUNIN', 'JAUJA', 'SINCOS', 'SINCOS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1128, '120432', 'JUNIN', 'JAUJA', 'TUNAN MARCA', 'CONCHO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1129, '120433', 'JUNIN', 'JAUJA', 'YAULI', 'YAULI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1130, '120434', 'JUNIN', 'JAUJA', 'YAUYOS', 'YAUYOS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1131, '120501', 'JUNIN', 'JUNIN', 'JUNIN', 'JUNIN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1132, '120502', 'JUNIN', 'JUNIN', 'CARHUAMAYO', 'CARHUAMAYO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1133, '120503', 'JUNIN', 'JUNIN', 'ONDORES', 'ONDORES', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1134, '120504', 'JUNIN', 'JUNIN', 'ULCUMAYO', 'ULCUMAYO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1135, '120601', 'JUNIN', 'SATIPO', 'SATIPO', 'SATIPO', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1136, '120602', 'JUNIN', 'SATIPO', 'COVIRIALI', 'COVIRIALI', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1137, '120603', 'JUNIN', 'SATIPO', 'LLAYLLA', 'LLAYLLA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1138, '120604', 'JUNIN', 'SATIPO', 'MAZAMARI', 'MAZAMARI', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1139, '120605', 'JUNIN', 'SATIPO', 'PAMPA HERMOSA', 'MARIPOSA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1140, '120606', 'JUNIN', 'SATIPO', 'PANGOA', 'SAN MARTIN DE PANGOA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1141, '120607', 'JUNIN', 'SATIPO', 'RIO NEGRO', 'RIO NEGRO', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1142, '120608', 'JUNIN', 'SATIPO', 'RIO TAMBO', 'PUERTO OCOPA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1143, '120609', 'JUNIN', 'SATIPO', 'VIZCATÁN DEL ENE', 'SAN MIGUEL DEL ENE', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1144, '120701', 'JUNIN', 'TARMA', 'TARMA', 'TARMA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1145, '120702', 'JUNIN', 'TARMA', 'ACOBAMBA', 'ACOBAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1146, '120703', 'JUNIN', 'TARMA', 'HUARICOLCA', 'HUARICOLCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1147, '120704', 'JUNIN', 'TARMA', 'HUASAHUASI', 'HUASAHUASI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1148, '120705', 'JUNIN', 'TARMA', 'LA UNION', 'LETICIA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1149, '120706', 'JUNIN', 'TARMA', 'PALCA', 'PALCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1150, '120707', 'JUNIN', 'TARMA', 'PALCAMAYO', 'PALCAMAYO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1151, '120708', 'JUNIN', 'TARMA', 'SAN PEDRO DE CAJAS', 'SAN PEDRO DE CAJAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1152, '120709', 'JUNIN', 'TARMA', 'TAPO', 'TAPO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1153, '120801', 'JUNIN', 'YAULI', 'LA OROYA', 'LA OROYA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1154, '120802', 'JUNIN', 'YAULI', 'CHACAPALPA', 'CHACAPALPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1155, '120803', 'JUNIN', 'YAULI', 'HUAY-HUAY', 'HUAY-HUAY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1156, '120804', 'JUNIN', 'YAULI', 'MARCAPOMACOCHA', 'MARCAPOMACOCHA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1157, '120805', 'JUNIN', 'YAULI', 'MOROCOCHA', 'NUEVA MOROCOCHA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1158, '120806', 'JUNIN', 'YAULI', 'PACCHA', 'PACCHA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1159, '120807', 'JUNIN', 'YAULI', 'SANTA BARBARA DE CARHUACAYAN', 'SANTA BARBARA DE CARHUACAYAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1160, '120808', 'JUNIN', 'YAULI', 'SANTA ROSA DE SACCO', 'SANTA ROSA DE SACCO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1161, '120809', 'JUNIN', 'YAULI', 'SUITUCANCHA', 'SUITUCANCHA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1162, '120810', 'JUNIN', 'YAULI', 'YAULI', 'YAULI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1163, '120901', 'JUNIN', 'CHUPACA', 'CHUPACA', 'CHUPACA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1164, '120902', 'JUNIN', 'CHUPACA', 'AHUAC', 'AHUAC', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1165, '120903', 'JUNIN', 'CHUPACA', 'CHONGOS BAJO', 'CHONGOS BAJO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1166, '120904', 'JUNIN', 'CHUPACA', 'HUACHAC', 'HUACHAC', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1167, '120905', 'JUNIN', 'CHUPACA', 'HUAMANCACA CHICO', 'HUAMANCACA CHICO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1168, '120906', 'JUNIN', 'CHUPACA', 'SAN JUAN DE ISCOS', 'ISCOS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1169, '120907', 'JUNIN', 'CHUPACA', 'SAN JUAN DE JARPA', 'JARPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1170, '120908', 'JUNIN', 'CHUPACA', 'TRES DE DICIEMBRE', 'TRES DE DICIEMBRE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1171, '120909', 'JUNIN', 'CHUPACA', 'YANACANCHA', 'YANACANCHA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1172, '130101', 'LA LIBERTAD', 'TRUJILLO', 'TRUJILLO', 'TRUJILLO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1173, '130102', 'LA LIBERTAD', 'TRUJILLO', 'EL PORVENIR', 'EL PORVENIR', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1174, '130103', 'LA LIBERTAD', 'TRUJILLO', 'FLORENCIA DE MORA', 'FLORENCIA DE MORA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1175, '130104', 'LA LIBERTAD', 'TRUJILLO', 'HUANCHACO', 'HUANCHACO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1176, '130105', 'LA LIBERTAD', 'TRUJILLO', 'LA ESPERANZA', 'LA ESPERANZA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1177, '130106', 'LA LIBERTAD', 'TRUJILLO', 'LAREDO', 'LAREDO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1178, '130107', 'LA LIBERTAD', 'TRUJILLO', 'MOCHE', 'MOCHE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1179, '130108', 'LA LIBERTAD', 'TRUJILLO', 'POROTO', 'POROTO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1180, '130109', 'LA LIBERTAD', 'TRUJILLO', 'SALAVERRY', 'SALAVERRY', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1181, '130110', 'LA LIBERTAD', 'TRUJILLO', 'SIMBAL', 'SIMBAL', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1182, '130111', 'LA LIBERTAD', 'TRUJILLO', 'VICTOR LARCO HERRERA', 'BUENOS AIRES', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1183, '130112', 'LA LIBERTAD', 'TRUJILLO', 'ALTO TRUJILLO', 'ALTO TRUJILLO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1184, '130201', 'LA LIBERTAD', 'ASCOPE', 'ASCOPE', 'ASCOPE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `locations` (`id`, `iddist`, `nombdep`, `nombprov`, `nombdist`, `nom_capital`, `cod_reg_nat`, `region_natural`, `created_at`, `updated_at`) VALUES
(1185, '130202', 'LA LIBERTAD', 'ASCOPE', 'CHICAMA', 'CHICAMA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1186, '130203', 'LA LIBERTAD', 'ASCOPE', 'CHOCOPE', 'CHOCOPE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1187, '130204', 'LA LIBERTAD', 'ASCOPE', 'MAGDALENA DE CAO', 'MAGDALENA DE CAO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1188, '130205', 'LA LIBERTAD', 'ASCOPE', 'PAIJAN', 'PAIJAN', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1189, '130206', 'LA LIBERTAD', 'ASCOPE', 'RAZURI', 'PUERTO DE MALABRIGO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1190, '130207', 'LA LIBERTAD', 'ASCOPE', 'SANTIAGO DE CAO', 'SANTIAGO DE CAO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1191, '130208', 'LA LIBERTAD', 'ASCOPE', 'CASA GRANDE', 'CASA GRANDE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1192, '130301', 'LA LIBERTAD', 'BOLIVAR', 'BOLIVAR', 'BOLIVAR', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1193, '130302', 'LA LIBERTAD', 'BOLIVAR', 'BAMBAMARCA', 'BAMBAMARCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1194, '130303', 'LA LIBERTAD', 'BOLIVAR', 'CONDORMARCA', 'NUEVO CONDORMARCA /11', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1195, '130304', 'LA LIBERTAD', 'BOLIVAR', 'LONGOTEA', 'LONGOTEA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1196, '130305', 'LA LIBERTAD', 'BOLIVAR', 'UCHUMARCA', 'UCHUMARCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1197, '130306', 'LA LIBERTAD', 'BOLIVAR', 'UCUNCHA', 'UCUNCHA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1198, '130401', 'LA LIBERTAD', 'CHEPEN', 'CHEPEN', 'CHEPEN', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1199, '130402', 'LA LIBERTAD', 'CHEPEN', 'PACANGA', 'PACANGA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1200, '130403', 'LA LIBERTAD', 'CHEPEN', 'PUEBLO NUEVO', 'PUEBLO NUEVO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1201, '130501', 'LA LIBERTAD', 'JULCAN', 'JULCAN', 'JULCAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1202, '130502', 'LA LIBERTAD', 'JULCAN', 'CALAMARCA', 'CALAMARCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1203, '130503', 'LA LIBERTAD', 'JULCAN', 'CARABAMBA', 'CARABAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1204, '130504', 'LA LIBERTAD', 'JULCAN', 'HUASO', 'HUASO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1205, '130601', 'LA LIBERTAD', 'OTUZCO', 'OTUZCO', 'OTUZCO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1206, '130602', 'LA LIBERTAD', 'OTUZCO', 'AGALLPAMPA', 'AGALLPAMPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1207, '130604', 'LA LIBERTAD', 'OTUZCO', 'CHARAT', 'CHARAT', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1208, '130605', 'LA LIBERTAD', 'OTUZCO', 'HUARANCHAL', 'HUARANCHAL', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1209, '130606', 'LA LIBERTAD', 'OTUZCO', 'LA CUESTA', 'LA CUESTA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1210, '130608', 'LA LIBERTAD', 'OTUZCO', 'MACHE', 'MACHE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1211, '130610', 'LA LIBERTAD', 'OTUZCO', 'PARANDAY', 'PARANDAY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1212, '130611', 'LA LIBERTAD', 'OTUZCO', 'SALPO', 'SALPO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1213, '130613', 'LA LIBERTAD', 'OTUZCO', 'SINSICAP', 'SINSICAP', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1214, '130614', 'LA LIBERTAD', 'OTUZCO', 'USQUIL', 'USQUIL', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1215, '130701', 'LA LIBERTAD', 'PACASMAYO', 'SAN PEDRO DE LLOC', 'SAN PEDRO DE LLOC', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1216, '130702', 'LA LIBERTAD', 'PACASMAYO', 'GUADALUPE', 'GUADALUPE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1217, '130703', 'LA LIBERTAD', 'PACASMAYO', 'JEQUETEPEQUE', 'JEQUETEPEQUE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1218, '130704', 'LA LIBERTAD', 'PACASMAYO', 'PACASMAYO', 'PACASMAYO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1219, '130705', 'LA LIBERTAD', 'PACASMAYO', 'SAN JOSE', 'SAN JOSE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1220, '130801', 'LA LIBERTAD', 'PATAZ', 'TAYABAMBA', 'TAYABAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1221, '130802', 'LA LIBERTAD', 'PATAZ', 'BULDIBUYO', 'BULDIBUYO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1222, '130803', 'LA LIBERTAD', 'PATAZ', 'CHILLIA', 'CHILLIA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1223, '130804', 'LA LIBERTAD', 'PATAZ', 'HUANCASPATA', 'HUANCASPATA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1224, '130805', 'LA LIBERTAD', 'PATAZ', 'HUAYLILLAS', 'HUAYLILLAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1225, '130806', 'LA LIBERTAD', 'PATAZ', 'HUAYO', 'HUAYO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1226, '130807', 'LA LIBERTAD', 'PATAZ', 'ONGON', 'ONGON', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1227, '130808', 'LA LIBERTAD', 'PATAZ', 'PARCOY', 'PARCOY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1228, '130809', 'LA LIBERTAD', 'PATAZ', 'PATAZ', 'PATAZ', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1229, '130810', 'LA LIBERTAD', 'PATAZ', 'PIAS', 'PIAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1230, '130811', 'LA LIBERTAD', 'PATAZ', 'SANTIAGO DE CHALLAS', 'CHALLAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1231, '130812', 'LA LIBERTAD', 'PATAZ', 'TAURIJA', 'TAURIJA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1232, '130813', 'LA LIBERTAD', 'PATAZ', 'URPAY', 'URPAY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1233, '130901', 'LA LIBERTAD', 'SANCHEZ CARRION', 'HUAMACHUCO', 'HUAMACHUCO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1234, '130902', 'LA LIBERTAD', 'SANCHEZ CARRION', 'CHUGAY', 'CHUGAY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1235, '130903', 'LA LIBERTAD', 'SANCHEZ CARRION', 'COCHORCO', 'ARICAPAMPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1236, '130904', 'LA LIBERTAD', 'SANCHEZ CARRION', 'CURGOS', 'CURGOS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1237, '130905', 'LA LIBERTAD', 'SANCHEZ CARRION', 'MARCABAL', 'MARCABAL', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1238, '130906', 'LA LIBERTAD', 'SANCHEZ CARRION', 'SANAGORAN', 'SANAGORAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1239, '130907', 'LA LIBERTAD', 'SANCHEZ CARRION', 'SARIN', 'SARIN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1240, '130908', 'LA LIBERTAD', 'SANCHEZ CARRION', 'SARTIMBAMBA', 'SARTIMBAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1241, '131001', 'LA LIBERTAD', 'SANTIAGO DE CHUCO', 'SANTIAGO DE CHUCO', 'SANTIAGO DE CHUCO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1242, '131002', 'LA LIBERTAD', 'SANTIAGO DE CHUCO', 'ANGASMARCA', 'ANGASMARCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1243, '131003', 'LA LIBERTAD', 'SANTIAGO DE CHUCO', 'CACHICADAN', 'CACHICADAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1244, '131004', 'LA LIBERTAD', 'SANTIAGO DE CHUCO', 'MOLLEBAMBA', 'MOLLEBAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1245, '131005', 'LA LIBERTAD', 'SANTIAGO DE CHUCO', 'MOLLEPATA', 'MOLLEPATA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1246, '131006', 'LA LIBERTAD', 'SANTIAGO DE CHUCO', 'QUIRUVILCA', 'QUIRUVILCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1247, '131007', 'LA LIBERTAD', 'SANTIAGO DE CHUCO', 'SANTA CRUZ DE CHUCA', 'SANTA CRUZ DE CHUCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1248, '131008', 'LA LIBERTAD', 'SANTIAGO DE CHUCO', 'SITABAMBA', 'SITABAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1249, '131101', 'LA LIBERTAD', 'GRAN CHIMU', 'CASCAS', 'CASCAS', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1250, '131102', 'LA LIBERTAD', 'GRAN CHIMU', 'LUCMA', 'LUCMA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1251, '131103', 'LA LIBERTAD', 'GRAN CHIMU', 'MARMOT', 'COMPIN /12', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1252, '131104', 'LA LIBERTAD', 'GRAN CHIMU', 'SAYAPULLO', 'SAYAPULLO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1253, '131201', 'LA LIBERTAD', 'VIRU', 'VIRU', 'VIRU', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1254, '131202', 'LA LIBERTAD', 'VIRU', 'CHAO', 'CHAO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1255, '131203', 'LA LIBERTAD', 'VIRU', 'GUADALUPITO', 'GUADALUPITO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1256, '140101', 'LAMBAYEQUE', 'CHICLAYO', 'CHICLAYO', 'CHICLAYO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1257, '140102', 'LAMBAYEQUE', 'CHICLAYO', 'CHONGOYAPE', 'CHONGOYAPE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1258, '140103', 'LAMBAYEQUE', 'CHICLAYO', 'ETEN', 'ETEN', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1259, '140104', 'LAMBAYEQUE', 'CHICLAYO', 'ETEN PUERTO', 'ETEN PUERTO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1260, '140105', 'LAMBAYEQUE', 'CHICLAYO', 'JOSE LEONARDO ORTIZ', 'JOSE LEONARDO ORTIZ', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1261, '140106', 'LAMBAYEQUE', 'CHICLAYO', 'LA VICTORIA', 'LA VICTORIA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1262, '140107', 'LAMBAYEQUE', 'CHICLAYO', 'LAGUNAS', 'MOCUPE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1263, '140108', 'LAMBAYEQUE', 'CHICLAYO', 'MONSEFU', 'MONSEFU', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1264, '140109', 'LAMBAYEQUE', 'CHICLAYO', 'NUEVA ARICA', 'NUEVA ARICA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1265, '140110', 'LAMBAYEQUE', 'CHICLAYO', 'OYOTUN', 'OYOTUN', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1266, '140111', 'LAMBAYEQUE', 'CHICLAYO', 'PICSI', 'PICSI', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1267, '140112', 'LAMBAYEQUE', 'CHICLAYO', 'PIMENTEL', 'PIMENTEL', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1268, '140113', 'LAMBAYEQUE', 'CHICLAYO', 'REQUE', 'REQUE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1269, '140114', 'LAMBAYEQUE', 'CHICLAYO', 'SANTA ROSA', 'SANTA ROSA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1270, '140115', 'LAMBAYEQUE', 'CHICLAYO', 'SAÑA', 'SAÑA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1271, '140116', 'LAMBAYEQUE', 'CHICLAYO', 'CAYALTI', 'CAYALTI', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1272, '140117', 'LAMBAYEQUE', 'CHICLAYO', 'PATAPO', 'PATAPO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1273, '140118', 'LAMBAYEQUE', 'CHICLAYO', 'POMALCA', 'POMALCA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1274, '140119', 'LAMBAYEQUE', 'CHICLAYO', 'PUCALA', 'PUCALA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1275, '140120', 'LAMBAYEQUE', 'CHICLAYO', 'TUMAN', 'TUMAN', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1276, '140201', 'LAMBAYEQUE', 'FERREÑAFE', 'FERREÑAFE', 'FERREÑAFE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1277, '140202', 'LAMBAYEQUE', 'FERREÑAFE', 'CAÑARIS', 'CAÑARIS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1278, '140203', 'LAMBAYEQUE', 'FERREÑAFE', 'INCAHUASI', 'INCAHUASI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1279, '140204', 'LAMBAYEQUE', 'FERREÑAFE', 'MANUEL ANTONIO MESONES MURO', 'MANUEL ANTONIO MESONES MURO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1280, '140205', 'LAMBAYEQUE', 'FERREÑAFE', 'PITIPO', 'PITIPO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1281, '140206', 'LAMBAYEQUE', 'FERREÑAFE', 'PUEBLO NUEVO', 'PUEBLO NUEVO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1282, '140301', 'LAMBAYEQUE', 'LAMBAYEQUE', 'LAMBAYEQUE', 'LAMBAYEQUE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1283, '140302', 'LAMBAYEQUE', 'LAMBAYEQUE', 'CHOCHOPE', 'CHOCHOPE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1284, '140303', 'LAMBAYEQUE', 'LAMBAYEQUE', 'ILLIMO', 'ILLIMO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1285, '140304', 'LAMBAYEQUE', 'LAMBAYEQUE', 'JAYANCA', 'JAYANCA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1286, '140305', 'LAMBAYEQUE', 'LAMBAYEQUE', 'MOCHUMI', 'MOCHUMI', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1287, '140306', 'LAMBAYEQUE', 'LAMBAYEQUE', 'MORROPE', 'MORROPE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1288, '140307', 'LAMBAYEQUE', 'LAMBAYEQUE', 'MOTUPE', 'MOTUPE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1289, '140308', 'LAMBAYEQUE', 'LAMBAYEQUE', 'OLMOS', 'OLMOS', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1290, '140309', 'LAMBAYEQUE', 'LAMBAYEQUE', 'PACORA', 'PACORA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1291, '140310', 'LAMBAYEQUE', 'LAMBAYEQUE', 'SALAS', 'SALAS', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1292, '140311', 'LAMBAYEQUE', 'LAMBAYEQUE', 'SAN JOSE', 'SAN JOSE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1293, '140312', 'LAMBAYEQUE', 'LAMBAYEQUE', 'TUCUME', 'TUCUME', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1294, '150101', 'LIMA', 'LIMA', 'LIMA', 'LIMA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1295, '150102', 'LIMA', 'LIMA', 'ANCON', 'ANCON', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1296, '150103', 'LIMA', 'LIMA', 'ATE', 'VITARTE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1297, '150104', 'LIMA', 'LIMA', 'BARRANCO', 'BARRANCO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1298, '150105', 'LIMA', 'LIMA', 'BREÑA', 'BREÑA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1299, '150106', 'LIMA', 'LIMA', 'CARABAYLLO', 'CARABAYLLO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1300, '150107', 'LIMA', 'LIMA', 'CHACLACAYO', 'CHACLACAYO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1301, '150108', 'LIMA', 'LIMA', 'CHORRILLOS', 'CHORRILLOS', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1302, '150109', 'LIMA', 'LIMA', 'CIENEGUILLA', 'CIENEGUILLA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1303, '150110', 'LIMA', 'LIMA', 'COMAS', 'LA LIBERTAD', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1304, '150111', 'LIMA', 'LIMA', 'EL AGUSTINO', 'EL AGUSTINO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1305, '150112', 'LIMA', 'LIMA', 'INDEPENDENCIA', 'INDEPENDENCIA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1306, '150113', 'LIMA', 'LIMA', 'JESUS MARIA', 'JESUS MARIA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1307, '150114', 'LIMA', 'LIMA', 'LA MOLINA', 'LA MOLINA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1308, '150115', 'LIMA', 'LIMA', 'LA VICTORIA', 'LA VICTORIA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1309, '150116', 'LIMA', 'LIMA', 'LINCE', 'LINCE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1310, '150117', 'LIMA', 'LIMA', 'LOS OLIVOS', 'LAS PALMERAS', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1311, '150118', 'LIMA', 'LIMA', 'LURIGANCHO', 'CHOSICA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1312, '150119', 'LIMA', 'LIMA', 'LURIN', 'LURIN', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1313, '150120', 'LIMA', 'LIMA', 'MAGDALENA DEL MAR', 'MAGDALENA DEL MAR', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1314, '150121', 'LIMA', 'LIMA', 'PUEBLO LIBRE', 'PUEBLO LIBRE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1315, '150122', 'LIMA', 'LIMA', 'MIRAFLORES', 'MIRAFLORES', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1316, '150123', 'LIMA', 'LIMA', 'PACHACAMAC', 'PACHACAMAC', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1317, '150124', 'LIMA', 'LIMA', 'PUCUSANA', 'PUCUSANA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1318, '150125', 'LIMA', 'LIMA', 'PUENTE PIEDRA', 'PUENTE PIEDRA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1319, '150126', 'LIMA', 'LIMA', 'PUNTA HERMOSA', 'PUNTA HERMOSA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1320, '150127', 'LIMA', 'LIMA', 'PUNTA NEGRA', 'PUNTA NEGRA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1321, '150128', 'LIMA', 'LIMA', 'RIMAC', 'RIMAC', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1322, '150129', 'LIMA', 'LIMA', 'SAN BARTOLO', 'SAN BARTOLO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1323, '150130', 'LIMA', 'LIMA', 'SAN BORJA', 'SAN FRANCISCO DE BORJA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1324, '150131', 'LIMA', 'LIMA', 'SAN ISIDRO', 'SAN ISIDRO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1325, '150132', 'LIMA', 'LIMA', 'SAN JUAN DE LURIGANCHO', 'SAN JUAN DE LURIGANCHO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1326, '150133', 'LIMA', 'LIMA', 'SAN JUAN DE MIRAFLORES', 'CIUDAD DE DIOS', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1327, '150134', 'LIMA', 'LIMA', 'SAN LUIS', 'SAN LUIS', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1328, '150135', 'LIMA', 'LIMA', 'SAN MARTIN DE PORRES', 'BARRIO OBRERO INDUSTRIAL', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1329, '150136', 'LIMA', 'LIMA', 'SAN MIGUEL', 'SAN MIGUEL', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1330, '150137', 'LIMA', 'LIMA', 'SANTA ANITA', 'SANTA ANITA - LOS FICUS', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1331, '150138', 'LIMA', 'LIMA', 'SANTA MARIA DEL MAR', 'SANTA MARIA DEL MAR', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1332, '150139', 'LIMA', 'LIMA', 'SANTA ROSA', 'SANTA ROSA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1333, '150140', 'LIMA', 'LIMA', 'SANTIAGO DE SURCO', 'SANTIAGO DE SURCO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1334, '150141', 'LIMA', 'LIMA', 'SURQUILLO', 'SURQUILLO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1335, '150142', 'LIMA', 'LIMA', 'VILLA EL SALVADOR', 'VILLA EL SALVADOR', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1336, '150143', 'LIMA', 'LIMA', 'VILLA MARIA DEL TRIUNFO', 'VILLA MARIA DEL TRIUNFO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1337, '150201', 'LIMA', 'BARRANCA', 'BARRANCA', 'BARRANCA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1338, '150202', 'LIMA', 'BARRANCA', 'PARAMONGA', 'PARAMONGA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1339, '150203', 'LIMA', 'BARRANCA', 'PATIVILCA', 'PATIVILCA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1340, '150204', 'LIMA', 'BARRANCA', 'SUPE', 'SUPE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1341, '150205', 'LIMA', 'BARRANCA', 'SUPE PUERTO', 'SUPE PUERTO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1342, '150301', 'LIMA', 'CAJATAMBO', 'CAJATAMBO', 'CAJATAMBO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1343, '150302', 'LIMA', 'CAJATAMBO', 'COPA', 'COPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1344, '150303', 'LIMA', 'CAJATAMBO', 'GORGOR', 'GORGOR', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1345, '150304', 'LIMA', 'CAJATAMBO', 'HUANCAPON', 'HUANCAPON', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1346, '150305', 'LIMA', 'CAJATAMBO', 'MANAS', 'MANAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1347, '150401', 'LIMA', 'CANTA', 'CANTA', 'CANTA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1348, '150402', 'LIMA', 'CANTA', 'ARAHUAY', 'ARAHUAY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1349, '150403', 'LIMA', 'CANTA', 'HUAMANTANGA', 'HUAMANTANGA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1350, '150404', 'LIMA', 'CANTA', 'HUAROS', 'HUAROS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1351, '150405', 'LIMA', 'CANTA', 'LACHAQUI', 'LACHAQUI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1352, '150406', 'LIMA', 'CANTA', 'SAN BUENAVENTURA', 'SAN BUENAVENTURA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1353, '150407', 'LIMA', 'CANTA', 'SANTA ROSA DE QUIVES', 'YANGAS', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1354, '150501', 'LIMA', 'CAÑETE', 'SAN VICENTE DE CAÑETE', 'SAN VICENTE DE CAÑETE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1355, '150502', 'LIMA', 'CAÑETE', 'ASIA', 'ASIA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1356, '150503', 'LIMA', 'CAÑETE', 'CALANGO', 'CALANGO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1357, '150504', 'LIMA', 'CAÑETE', 'CERRO AZUL', 'CERRO AZUL', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1358, '150505', 'LIMA', 'CAÑETE', 'CHILCA', 'CHILCA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1359, '150506', 'LIMA', 'CAÑETE', 'COAYLLO', 'COAYLLO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1360, '150507', 'LIMA', 'CAÑETE', 'IMPERIAL', 'IMPERIAL', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1361, '150508', 'LIMA', 'CAÑETE', 'LUNAHUANA', 'LUNAHUANA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1362, '150509', 'LIMA', 'CAÑETE', 'MALA', 'MALA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1363, '150510', 'LIMA', 'CAÑETE', 'NUEVO IMPERIAL', 'NUEVO IMPERIAL', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1364, '150511', 'LIMA', 'CAÑETE', 'PACARAN', 'PACARAN', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1365, '150512', 'LIMA', 'CAÑETE', 'QUILMANA', 'QUILMANA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1366, '150513', 'LIMA', 'CAÑETE', 'SAN ANTONIO', 'SAN ANTONIO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1367, '150514', 'LIMA', 'CAÑETE', 'SAN LUIS', 'SAN LUIS', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1368, '150515', 'LIMA', 'CAÑETE', 'SANTA CRUZ DE FLORES', 'SANTA CRUZ DE FLORES', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1369, '150516', 'LIMA', 'CAÑETE', 'ZUÑIGA', 'ZUÑIGA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1370, '150601', 'LIMA', 'HUARAL', 'HUARAL', 'HUARAL', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1371, '150602', 'LIMA', 'HUARAL', 'ATAVILLOS ALTO', 'PIRCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1372, '150603', 'LIMA', 'HUARAL', 'ATAVILLOS BAJO', 'SAN AGUSTIN DE HUAYOPAMPA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1373, '150604', 'LIMA', 'HUARAL', 'AUCALLAMA', 'AUCALLAMA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1374, '150605', 'LIMA', 'HUARAL', 'CHANCAY', 'CHANCAY', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1375, '150606', 'LIMA', 'HUARAL', 'IHUARI', 'IHUARI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1376, '150607', 'LIMA', 'HUARAL', 'LAMPIAN', 'LAMPIAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1377, '150608', 'LIMA', 'HUARAL', 'PACARAOS', 'PACARAOS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1378, '150609', 'LIMA', 'HUARAL', 'SAN MIGUEL DE ACOS', 'ACOS', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1379, '150610', 'LIMA', 'HUARAL', 'SANTA CRUZ DE ANDAMARCA', 'SANTA CRUZ DE ANDAMARCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1380, '150611', 'LIMA', 'HUARAL', 'SUMBILCA', 'SUMBILCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1381, '150612', 'LIMA', 'HUARAL', 'VEINTISIETE DE NOVIEMBRE', 'CARAC', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1382, '150701', 'LIMA', 'HUAROCHIRI', 'MATUCANA', 'MATUCANA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1383, '150702', 'LIMA', 'HUAROCHIRI', 'ANTIOQUIA', 'ANTIOQUIA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1384, '150703', 'LIMA', 'HUAROCHIRI', 'CALLAHUANCA', 'CALLAHUANCA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1385, '150704', 'LIMA', 'HUAROCHIRI', 'CARAMPOMA', 'CARAMPOMA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1386, '150705', 'LIMA', 'HUAROCHIRI', 'CHICLA', 'CHICLA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1387, '150706', 'LIMA', 'HUAROCHIRI', 'CUENCA', 'SAN JOSE DE LOS CHORRILLOS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1388, '150707', 'LIMA', 'HUAROCHIRI', 'HUACHUPAMPA', 'SAN LORENZO DE HUACHUPAMPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1389, '150708', 'LIMA', 'HUAROCHIRI', 'HUANZA', 'HUANZA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1390, '150709', 'LIMA', 'HUAROCHIRI', 'HUAROCHIRI', 'HUAROCHIRI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1391, '150710', 'LIMA', 'HUAROCHIRI', 'LAHUAYTAMBO', 'LAHUAYTAMBO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1392, '150711', 'LIMA', 'HUAROCHIRI', 'LANGA', 'LANGA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1393, '150712', 'LIMA', 'HUAROCHIRI', 'SAN PEDRO DE LARAOS', 'LARAOS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1394, '150713', 'LIMA', 'HUAROCHIRI', 'MARIATANA', 'MARIATANA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1395, '150714', 'LIMA', 'HUAROCHIRI', 'RICARDO PALMA', 'RICARDO PALMA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1396, '150715', 'LIMA', 'HUAROCHIRI', 'SAN ANDRES DE TUPICOCHA', 'SAN ANDRES DE TUPICOCHA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1397, '150716', 'LIMA', 'HUAROCHIRI', 'SAN ANTONIO', 'CHACLLA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1398, '150717', 'LIMA', 'HUAROCHIRI', 'SAN BARTOLOME', 'SAN BARTOLOME', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1399, '150718', 'LIMA', 'HUAROCHIRI', 'SAN DAMIAN', 'SAN DAMIAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1400, '150719', 'LIMA', 'HUAROCHIRI', 'SAN JUAN DE IRIS', 'SAN JUAN DE IRIS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1401, '150720', 'LIMA', 'HUAROCHIRI', 'SAN JUAN DE TANTARANCHE', 'SAN JUAN DE TANTARANCHE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1402, '150721', 'LIMA', 'HUAROCHIRI', 'SAN LORENZO DE QUINTI', 'SAN LORENZO DE QUINTI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1403, '150722', 'LIMA', 'HUAROCHIRI', 'SAN MATEO', 'SAN MATEO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1404, '150723', 'LIMA', 'HUAROCHIRI', 'SAN MATEO DE OTAO', 'SAN JUAN DE LANCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1405, '150724', 'LIMA', 'HUAROCHIRI', 'SAN PEDRO DE CASTA', 'SAN PEDRO DE CASTA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1406, '150725', 'LIMA', 'HUAROCHIRI', 'SAN PEDRO DE HUANCAYRE', 'SAN PEDRO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1407, '150726', 'LIMA', 'HUAROCHIRI', 'SANGALLAYA', 'SANGALLAYA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1408, '150727', 'LIMA', 'HUAROCHIRI', 'SANTA CRUZ DE COCACHACRA', 'COCACHACRA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1409, '150728', 'LIMA', 'HUAROCHIRI', 'SANTA EULALIA', 'SANTA EULALIA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1410, '150729', 'LIMA', 'HUAROCHIRI', 'SANTIAGO DE ANCHUCAYA', 'SANTIAGO DE ANCHUCAYA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1411, '150730', 'LIMA', 'HUAROCHIRI', 'SANTIAGO DE TUNA', 'SANTIAGO DE TUNA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1412, '150731', 'LIMA', 'HUAROCHIRI', 'SANTO DOMINGO DE LOS OLLEROS', 'SANTO DOMINGO DE LOS OLLEROS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1413, '150732', 'LIMA', 'HUAROCHIRI', 'SURCO', 'SURCO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1414, '150801', 'LIMA', 'HUAURA', 'HUACHO', 'HUACHO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1415, '150802', 'LIMA', 'HUAURA', 'AMBAR', 'AMBAR', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1416, '150803', 'LIMA', 'HUAURA', 'CALETA DE CARQUIN', 'CALETA DE CARQUIN', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1417, '150804', 'LIMA', 'HUAURA', 'CHECRAS', 'MARAY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1418, '150805', 'LIMA', 'HUAURA', 'HUALMAY', 'HUALMAY', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1419, '150806', 'LIMA', 'HUAURA', 'HUAURA', 'HUAURA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1420, '150807', 'LIMA', 'HUAURA', 'LEONCIO PRADO', 'SANTA CRUZ', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1421, '150808', 'LIMA', 'HUAURA', 'PACCHO', 'PACCHO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1422, '150809', 'LIMA', 'HUAURA', 'SANTA LEONOR', 'JUCUL', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1423, '150810', 'LIMA', 'HUAURA', 'SANTA MARIA', 'CRUZ BLANCA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1424, '150811', 'LIMA', 'HUAURA', 'SAYAN', 'SAYAN', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1425, '150812', 'LIMA', 'HUAURA', 'VEGUETA', 'VEGUETA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1426, '150901', 'LIMA', 'OYON', 'OYON', 'OYON', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1427, '150902', 'LIMA', 'OYON', 'ANDAJES', 'ANDAJES', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1428, '150903', 'LIMA', 'OYON', 'CAUJUL', 'CAUJUL', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1429, '150904', 'LIMA', 'OYON', 'COCHAMARCA', 'COCHAMARCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1430, '150905', 'LIMA', 'OYON', 'NAVAN', 'NAVAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1431, '150906', 'LIMA', 'OYON', 'PACHANGARA', 'CHURIN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1432, '151001', 'LIMA', 'YAUYOS', 'YAUYOS', 'YAUYOS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1433, '151002', 'LIMA', 'YAUYOS', 'ALIS', 'ALIS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1434, '151003', 'LIMA', 'YAUYOS', 'ALLAUCA', 'ALLAUCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1435, '151004', 'LIMA', 'YAUYOS', 'AYAVIRI', 'AYAVIRI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1436, '151005', 'LIMA', 'YAUYOS', 'AZANGARO', 'AZANGARO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1437, '151006', 'LIMA', 'YAUYOS', 'CACRA', 'CACRA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1438, '151007', 'LIMA', 'YAUYOS', 'CARANIA', 'CARANIA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1439, '151008', 'LIMA', 'YAUYOS', 'CATAHUASI', 'CATAHUASI', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1440, '151009', 'LIMA', 'YAUYOS', 'CHOCOS', 'CHOCOS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1441, '151010', 'LIMA', 'YAUYOS', 'COCHAS', 'COCHAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1442, '151011', 'LIMA', 'YAUYOS', 'COLONIA', 'COLONIA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1443, '151012', 'LIMA', 'YAUYOS', 'HONGOS', 'HONGOS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1444, '151013', 'LIMA', 'YAUYOS', 'HUAMPARA', 'HUAMPARA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1445, '151014', 'LIMA', 'YAUYOS', 'HUANCAYA', 'HUANCAYA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1446, '151015', 'LIMA', 'YAUYOS', 'HUANGASCAR', 'HUANGASCAR', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1447, '151016', 'LIMA', 'YAUYOS', 'HUANTAN', 'HUANTAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1448, '151017', 'LIMA', 'YAUYOS', 'HUAÑEC', 'HUAÑEC', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1449, '151018', 'LIMA', 'YAUYOS', 'LARAOS', 'LARAOS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1450, '151019', 'LIMA', 'YAUYOS', 'LINCHA', 'LINCHA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1451, '151020', 'LIMA', 'YAUYOS', 'MADEAN', 'MADEAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1452, '151021', 'LIMA', 'YAUYOS', 'MIRAFLORES', 'MIRAFLORES', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1453, '151022', 'LIMA', 'YAUYOS', 'OMAS', 'OMAS', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1454, '151023', 'LIMA', 'YAUYOS', 'PUTINZA', 'SAN LORENZO DE PUTINZA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1455, '151024', 'LIMA', 'YAUYOS', 'QUINCHES', 'QUINCHES', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1456, '151025', 'LIMA', 'YAUYOS', 'QUINOCAY', 'QUINOCAY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1457, '151026', 'LIMA', 'YAUYOS', 'SAN JOAQUIN', 'SAN JOAQUIN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1458, '151027', 'LIMA', 'YAUYOS', 'SAN PEDRO DE PILAS', 'SAN PEDRO DE PILAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1459, '151028', 'LIMA', 'YAUYOS', 'TANTA', 'TANTA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1460, '151029', 'LIMA', 'YAUYOS', 'TAURIPAMPA', 'TAURIPAMPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1461, '151030', 'LIMA', 'YAUYOS', 'TOMAS', 'TOMAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1462, '151031', 'LIMA', 'YAUYOS', 'TUPE', 'TUPE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1463, '151032', 'LIMA', 'YAUYOS', 'VIÑAC', 'VIÑAC', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1464, '151033', 'LIMA', 'YAUYOS', 'VITIS', 'VITIS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1465, '160101', 'LORETO', 'MAYNAS', 'IQUITOS', 'IQUITOS', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1466, '160102', 'LORETO', 'MAYNAS', 'ALTO NANAY', 'SANTA MARIA DE NANAY', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1467, '160103', 'LORETO', 'MAYNAS', 'FERNANDO LORES', 'TAMSHIYACU', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1468, '160104', 'LORETO', 'MAYNAS', 'INDIANA', 'INDIANA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1469, '160105', 'LORETO', 'MAYNAS', 'LAS AMAZONAS', 'FRANCISCO DE ORELLANA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1470, '160106', 'LORETO', 'MAYNAS', 'MAZAN', 'MAZAN', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1471, '160107', 'LORETO', 'MAYNAS', 'NAPO', 'SANTA CLOTILDE', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1472, '160108', 'LORETO', 'MAYNAS', 'PUNCHANA', 'PUNCHANA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1473, '160110', 'LORETO', 'MAYNAS', 'TORRES CAUSANA', 'PANTOJA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1474, '160112', 'LORETO', 'MAYNAS', 'BELEN', 'BELEN', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1475, '160113', 'LORETO', 'MAYNAS', 'SAN JUAN BAUTISTA', 'SAN JUAN', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1476, '160201', 'LORETO', 'ALTO AMAZONAS', 'YURIMAGUAS', 'YURIMAGUAS', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1477, '160202', 'LORETO', 'ALTO AMAZONAS', 'BALSAPUERTO', 'BALSAPUERTO', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1478, '160205', 'LORETO', 'ALTO AMAZONAS', 'JEBEROS', 'JEBEROS', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1479, '160206', 'LORETO', 'ALTO AMAZONAS', 'LAGUNAS', 'LAGUNAS', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1480, '160210', 'LORETO', 'ALTO AMAZONAS', 'SANTA CRUZ', 'SANTA CRUZ', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1481, '160211', 'LORETO', 'ALTO AMAZONAS', 'TENIENTE CESAR LOPEZ ROJAS', 'SHUCUSHUYACU', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1482, '160301', 'LORETO', 'LORETO', 'NAUTA', 'NAUTA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1483, '160302', 'LORETO', 'LORETO', 'PARINARI', 'PARINARI', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1484, '160303', 'LORETO', 'LORETO', 'TIGRE', 'INTUTU', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1485, '160304', 'LORETO', 'LORETO', 'TROMPETEROS', 'VILLA TROMPETEROS', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1486, '160305', 'LORETO', 'LORETO', 'URARINAS', 'CONCORDIA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1487, '160401', 'LORETO', 'MARISCAL RAMON CASTILLA', 'RAMON CASTILLA', 'CABALLOCOCHA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1488, '160402', 'LORETO', 'MARISCAL RAMON CASTILLA', 'PEBAS', 'PEBAS', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1489, '160403', 'LORETO', 'MARISCAL RAMON CASTILLA', 'YAVARI', 'ISLANDIA /13', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1490, '160404', 'LORETO', 'MARISCAL RAMON CASTILLA', 'SAN PABLO', 'SAN PABLO DE LORETO', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1491, '160501', 'LORETO', 'REQUENA', 'REQUENA', 'REQUENA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1492, '160502', 'LORETO', 'REQUENA', 'ALTO TAPICHE', 'SANTA ELENA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1493, '160503', 'LORETO', 'REQUENA', 'CAPELO', 'FLOR DE PUNGA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1494, '160504', 'LORETO', 'REQUENA', 'EMILIO SAN MARTIN', 'TAMANCO', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1495, '160505', 'LORETO', 'REQUENA', 'MAQUIA', 'SANTA ISABEL', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1496, '160506', 'LORETO', 'REQUENA', 'PUINAHUA', 'BRETAÑA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1497, '160507', 'LORETO', 'REQUENA', 'SAQUENA', 'BAGAZAN', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1498, '160508', 'LORETO', 'REQUENA', 'SOPLIN', 'NUEVA ALEJANDRIA (CURINGA)', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1499, '160509', 'LORETO', 'REQUENA', 'TAPICHE', 'IBERIA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1500, '160510', 'LORETO', 'REQUENA', 'JENARO HERRERA', 'JENARO HERRERA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1501, '160511', 'LORETO', 'REQUENA', 'YAQUERANA', 'ANGAMOS', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1502, '160601', 'LORETO', 'UCAYALI', 'CONTAMANA', 'CONTAMANA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1503, '160602', 'LORETO', 'UCAYALI', 'INAHUAYA', 'INAHUAYA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1504, '160603', 'LORETO', 'UCAYALI', 'PADRE MARQUEZ', 'TIRUNTAN', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1505, '160604', 'LORETO', 'UCAYALI', 'PAMPA HERMOSA', 'PAMPA HERMOSA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1506, '160605', 'LORETO', 'UCAYALI', 'SARAYACU', 'DOS DE MAYO', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1507, '160606', 'LORETO', 'UCAYALI', 'VARGAS GUERRA', 'ORELLANA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1508, '160701', 'LORETO', 'DATEM DEL MARAÑON', 'BARRANCA', 'SAN LORENZO', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1509, '160702', 'LORETO', 'DATEM DEL MARAÑON', 'CAHUAPANAS', 'SANTA MARIA DE CAHUAPANAS', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1510, '160703', 'LORETO', 'DATEM DEL MARAÑON', 'MANSERICHE', 'SARAMIRIZA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1511, '160704', 'LORETO', 'DATEM DEL MARAÑON', 'MORONA', 'PUERTO ALEGRIA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1512, '160705', 'LORETO', 'DATEM DEL MARAÑON', 'PASTAZA', 'ULLPAYACU', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1513, '160706', 'LORETO', 'DATEM DEL MARAÑON', 'ANDOAS', 'ALIANZA CRISTIANA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1514, '160801', 'LORETO', 'PUTUMAYO', 'PUTUMAYO', 'SAN ANTONIO DEL ESTRECHO', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1515, '160802', 'LORETO', 'PUTUMAYO', 'ROSA PANDURO', 'SANTA MERCEDES', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1516, '160803', 'LORETO', 'PUTUMAYO', 'TENIENTE MANUEL CLAVERO', 'SOPLIN VARGAS', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1517, '160804', 'LORETO', 'PUTUMAYO', 'YAGUAS', 'REMANSO', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1518, '170101', 'MADRE DE DIOS', 'TAMBOPATA', 'TAMBOPATA', 'PUERTO MALDONADO', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1519, '170102', 'MADRE DE DIOS', 'TAMBOPATA', 'INAMBARI', 'MAZUKO', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1520, '170103', 'MADRE DE DIOS', 'TAMBOPATA', 'LAS PIEDRAS', 'LAS PIEDRAS (PLANCHON)', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1521, '170104', 'MADRE DE DIOS', 'TAMBOPATA', 'LABERINTO', 'PUERTO ROSARIO DE LABERINTO', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1522, '170201', 'MADRE DE DIOS', 'MANU', 'MANU', 'SALVACION', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1523, '170202', 'MADRE DE DIOS', 'MANU', 'FITZCARRALD', 'BOCA MANU', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1524, '170203', 'MADRE DE DIOS', 'MANU', 'MADRE DE DIOS', 'BOCA COLORADO', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1525, '170204', 'MADRE DE DIOS', 'MANU', 'HUEPETUHE', 'HUEPETUHE', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1526, '170301', 'MADRE DE DIOS', 'TAHUAMANU', 'IÑAPARI', 'IÑAPARI', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1527, '170302', 'MADRE DE DIOS', 'TAHUAMANU', 'IBERIA', 'IBERIA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1528, '170303', 'MADRE DE DIOS', 'TAHUAMANU', 'TAHUAMANU', 'SAN LORENZO', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1529, '180101', 'MOQUEGUA', 'MARISCAL NIETO', 'MOQUEGUA', 'MOQUEGUA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1530, '180102', 'MOQUEGUA', 'MARISCAL NIETO', 'CARUMAS', 'CARUMAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1531, '180103', 'MOQUEGUA', 'MARISCAL NIETO', 'CUCHUMBAYA', 'CUCHUMBAYA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1532, '180104', 'MOQUEGUA', 'MARISCAL NIETO', 'SAMEGUA', 'SAMEGUA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1533, '180105', 'MOQUEGUA', 'MARISCAL NIETO', 'SAN CRISTOBAL', 'CALACOA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1534, '180106', 'MOQUEGUA', 'MARISCAL NIETO', 'TORATA', 'TORATA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1535, '180107', 'MOQUEGUA', 'MARISCAL NIETO', 'SAN ANTONIO', 'SAN ANTONIO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1536, '180201', 'MOQUEGUA', 'GENERAL SANCHEZ CERRO', 'OMATE', 'OMATE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1537, '180202', 'MOQUEGUA', 'GENERAL SANCHEZ CERRO', 'CHOJATA', 'CHOJATA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1538, '180203', 'MOQUEGUA', 'GENERAL SANCHEZ CERRO', 'COALAQUE', 'COALAQUE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1539, '180204', 'MOQUEGUA', 'GENERAL SANCHEZ CERRO', 'ICHUÑA', 'ICHUÑA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1540, '180205', 'MOQUEGUA', 'GENERAL SANCHEZ CERRO', 'LA CAPILLA', 'LA CAPILLA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1541, '180206', 'MOQUEGUA', 'GENERAL SANCHEZ CERRO', 'LLOQUE', 'LLOQUE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1542, '180207', 'MOQUEGUA', 'GENERAL SANCHEZ CERRO', 'MATALAQUE', 'MATALAQUE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1543, '180208', 'MOQUEGUA', 'GENERAL SANCHEZ CERRO', 'PUQUINA', 'PUQUINA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1544, '180209', 'MOQUEGUA', 'GENERAL SANCHEZ CERRO', 'QUINISTAQUILLAS', 'QUINISTAQUILLAS', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1545, '180210', 'MOQUEGUA', 'GENERAL SANCHEZ CERRO', 'UBINAS', 'UBINAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1546, '180211', 'MOQUEGUA', 'GENERAL SANCHEZ CERRO', 'YUNGA', 'YUNGA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1547, '180301', 'MOQUEGUA', 'ILO', 'ILO', 'ILO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1548, '180302', 'MOQUEGUA', 'ILO', 'EL ALGARROBAL', 'EL ALGARROBAL', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1549, '180303', 'MOQUEGUA', 'ILO', 'PACOCHA', 'PUEBLO NUEVO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1550, '190101', 'PASCO', 'PASCO', 'CHAUPIMARCA', 'CERRO DE PASCO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1551, '190102', 'PASCO', 'PASCO', 'HUACHON', 'HUACHON', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1552, '190103', 'PASCO', 'PASCO', 'HUARIACA', 'HUARIACA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1553, '190104', 'PASCO', 'PASCO', 'HUAYLLAY', 'HUAYLLAY', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1554, '190105', 'PASCO', 'PASCO', 'NINACACA', 'NINACACA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1555, '190106', 'PASCO', 'PASCO', 'PALLANCHACRA', 'PALLANCHACRA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1556, '190107', 'PASCO', 'PASCO', 'PAUCARTAMBO', 'PAUCARTAMBO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1557, '190108', 'PASCO', 'PASCO', 'SAN FRANCISCO DE ASIS DE YARUSYACAN', 'YARUSYACAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1558, '190109', 'PASCO', 'PASCO', 'SIMON BOLIVAR', 'SAN ANTONIO DE RANCAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1559, '190110', 'PASCO', 'PASCO', 'TICLACAYAN', 'TICLACAYAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1560, '190111', 'PASCO', 'PASCO', 'TINYAHUARCO', 'TINYAHUARCO (SMELTER)', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1561, '190112', 'PASCO', 'PASCO', 'VICCO', 'VICCO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1562, '190113', 'PASCO', 'PASCO', 'YANACANCHA', 'YANACANCHA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1563, '190201', 'PASCO', 'DANIEL ALCIDES CARRION', 'YANAHUANCA', 'YANAHUANCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1564, '190202', 'PASCO', 'DANIEL ALCIDES CARRION', 'CHACAYAN', 'CHACAYAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1565, '190203', 'PASCO', 'DANIEL ALCIDES CARRION', 'GOYLLARISQUIZGA', 'GOYLLARISQUIZGA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1566, '190204', 'PASCO', 'DANIEL ALCIDES CARRION', 'PAUCAR', 'PAUCAR', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1567, '190205', 'PASCO', 'DANIEL ALCIDES CARRION', 'SAN PEDRO DE PILLAO', 'SAN PEDRO DE PILLAO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1568, '190206', 'PASCO', 'DANIEL ALCIDES CARRION', 'SANTA ANA DE TUSI', 'SANTA ANA DE TUSI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1569, '190207', 'PASCO', 'DANIEL ALCIDES CARRION', 'TAPUC', 'TAPUC', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1570, '190208', 'PASCO', 'DANIEL ALCIDES CARRION', 'VILCABAMBA', 'VILCABAMBA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1571, '190301', 'PASCO', 'OXAPAMPA', 'OXAPAMPA', 'OXAPAMPA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1572, '190302', 'PASCO', 'OXAPAMPA', 'CHONTABAMBA', 'CHONTABAMBA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1573, '190303', 'PASCO', 'OXAPAMPA', 'HUANCABAMBA', 'HUANCABAMBA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1574, '190304', 'PASCO', 'OXAPAMPA', 'PALCAZU', 'ISCOZACIN', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1575, '190305', 'PASCO', 'OXAPAMPA', 'POZUZO', 'POZUZO', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `locations` (`id`, `iddist`, `nombdep`, `nombprov`, `nombdist`, `nom_capital`, `cod_reg_nat`, `region_natural`, `created_at`, `updated_at`) VALUES
(1576, '190306', 'PASCO', 'OXAPAMPA', 'PUERTO BERMUDEZ', 'PUERTO BERMUDEZ', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1577, '190307', 'PASCO', 'OXAPAMPA', 'VILLA RICA', 'VILLA RICA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1578, '190308', 'PASCO', 'OXAPAMPA', 'CONSTITUCION', 'CONSTITUCIÓN', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1579, '200101', 'PIURA', 'PIURA', 'PIURA', 'PIURA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1580, '200104', 'PIURA', 'PIURA', 'CASTILLA', 'CASTILLA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1581, '200105', 'PIURA', 'PIURA', 'CATACAOS', 'CATACAOS', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1582, '200107', 'PIURA', 'PIURA', 'CURA MORI', 'CUCUNGARA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1583, '200108', 'PIURA', 'PIURA', 'EL TALLAN', 'SINCHAO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1584, '200109', 'PIURA', 'PIURA', 'LA ARENA', 'LA ARENA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1585, '200110', 'PIURA', 'PIURA', 'LA UNION', 'LA UNION', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1586, '200111', 'PIURA', 'PIURA', 'LAS LOMAS', 'LAS LOMAS', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1587, '200114', 'PIURA', 'PIURA', 'TAMBO GRANDE', 'TAMBO GRANDE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1588, '200115', 'PIURA', 'PIURA', 'VEINTISEIS DE OCTUBRE', 'SAN MARTIN', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1589, '200201', 'PIURA', 'AYABACA', 'AYABACA', 'AYABACA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1590, '200202', 'PIURA', 'AYABACA', 'FRIAS', 'FRIAS', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1591, '200203', 'PIURA', 'AYABACA', 'JILILI', 'JILILI', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1592, '200204', 'PIURA', 'AYABACA', 'LAGUNAS', 'LAGUNAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1593, '200205', 'PIURA', 'AYABACA', 'MONTERO', 'MONTERO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1594, '200206', 'PIURA', 'AYABACA', 'PACAIPAMPA', 'PACAIPAMPA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1595, '200207', 'PIURA', 'AYABACA', 'PAIMAS', 'PAIMAS', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1596, '200208', 'PIURA', 'AYABACA', 'SAPILLICA', 'SAPILLICA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1597, '200209', 'PIURA', 'AYABACA', 'SICCHEZ', 'SICCHEZ', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1598, '200210', 'PIURA', 'AYABACA', 'SUYO', 'SUYO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1599, '200301', 'PIURA', 'HUANCABAMBA', 'HUANCABAMBA', 'HUANCABAMBA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1600, '200302', 'PIURA', 'HUANCABAMBA', 'CANCHAQUE', 'CANCHAQUE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1601, '200303', 'PIURA', 'HUANCABAMBA', 'EL CARMEN DE LA FRONTERA', 'SAPALACHE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1602, '200304', 'PIURA', 'HUANCABAMBA', 'HUARMACA', 'HUARMACA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1603, '200305', 'PIURA', 'HUANCABAMBA', 'LALAQUIZ', 'TUNAL', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1604, '200306', 'PIURA', 'HUANCABAMBA', 'SAN MIGUEL DE EL FAIQUE', 'SAN MIGUEL DE EL FAIQUE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1605, '200307', 'PIURA', 'HUANCABAMBA', 'SONDOR', 'SONDOR', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1606, '200308', 'PIURA', 'HUANCABAMBA', 'SONDORILLO', 'SONDORILLO', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1607, '200401', 'PIURA', 'MORROPON', 'CHULUCANAS', 'CHULUCANAS', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1608, '200402', 'PIURA', 'MORROPON', 'BUENOS AIRES', 'BUENOS AIRES', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1609, '200403', 'PIURA', 'MORROPON', 'CHALACO', 'CHALACO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1610, '200404', 'PIURA', 'MORROPON', 'LA MATANZA', 'LA MATANZA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1611, '200405', 'PIURA', 'MORROPON', 'MORROPON', 'MORROPON', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1612, '200406', 'PIURA', 'MORROPON', 'SALITRAL', 'SALITRAL', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1613, '200407', 'PIURA', 'MORROPON', 'SAN JUAN DE BIGOTE', 'BIGOTE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1614, '200408', 'PIURA', 'MORROPON', 'SANTA CATALINA DE MOSSA', 'PALTASHACO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1615, '200409', 'PIURA', 'MORROPON', 'SANTO DOMINGO', 'SANTO DOMINGO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1616, '200410', 'PIURA', 'MORROPON', 'YAMANGO', 'YAMANGO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1617, '200501', 'PIURA', 'PAITA', 'PAITA', 'PAITA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1618, '200502', 'PIURA', 'PAITA', 'AMOTAPE', 'AMOTAPE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1619, '200503', 'PIURA', 'PAITA', 'ARENAL', 'ARENAL', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1620, '200504', 'PIURA', 'PAITA', 'COLAN', 'SAN LUCAS (PUEBLO NUEVO DE COLAN)', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1621, '200505', 'PIURA', 'PAITA', 'LA HUACA', 'LA HUACA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1622, '200506', 'PIURA', 'PAITA', 'TAMARINDO', 'TAMARINDO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1623, '200507', 'PIURA', 'PAITA', 'VICHAYAL', 'SAN FELIPE DE VICHAYAL', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1624, '200601', 'PIURA', 'SULLANA', 'SULLANA', 'SULLANA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1625, '200602', 'PIURA', 'SULLANA', 'BELLAVISTA', 'BELLAVISTA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1626, '200603', 'PIURA', 'SULLANA', 'IGNACIO ESCUDERO', 'SAN JACINTO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1627, '200604', 'PIURA', 'SULLANA', 'LANCONES', 'LANCONES', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1628, '200605', 'PIURA', 'SULLANA', 'MARCAVELICA', 'MARCAVELICA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1629, '200606', 'PIURA', 'SULLANA', 'MIGUEL CHECA', 'SOJO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1630, '200607', 'PIURA', 'SULLANA', 'QUERECOTILLO', 'QUERECOTILLO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1631, '200608', 'PIURA', 'SULLANA', 'SALITRAL', 'SALITRAL', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1632, '200701', 'PIURA', 'TALARA', 'PARIÑAS', 'TALARA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1633, '200702', 'PIURA', 'TALARA', 'EL ALTO', 'EL ALTO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1634, '200703', 'PIURA', 'TALARA', 'LA BREA', 'NEGRITOS', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1635, '200704', 'PIURA', 'TALARA', 'LOBITOS', 'LOBITOS', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1636, '200705', 'PIURA', 'TALARA', 'LOS ORGANOS', 'LOS ORGANOS', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1637, '200706', 'PIURA', 'TALARA', 'MANCORA', 'MANCORA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1638, '200801', 'PIURA', 'SECHURA', 'SECHURA', 'SECHURA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1639, '200802', 'PIURA', 'SECHURA', 'BELLAVISTA DE LA UNION', 'BELLAVISTA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1640, '200803', 'PIURA', 'SECHURA', 'BERNAL', 'BERNAL', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1641, '200804', 'PIURA', 'SECHURA', 'CRISTO NOS VALGA', 'SAN CRISTO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1642, '200805', 'PIURA', 'SECHURA', 'VICE', 'VICE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1643, '200806', 'PIURA', 'SECHURA', 'RINCONADA LLICUAR', 'DOS PUEBLOS', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1644, '210101', 'PUNO', 'PUNO', 'PUNO', 'PUNO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1645, '210102', 'PUNO', 'PUNO', 'ACORA', 'ACORA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1646, '210103', 'PUNO', 'PUNO', 'AMANTANI', 'AMANTANI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1647, '210104', 'PUNO', 'PUNO', 'ATUNCOLLA', 'ATUNCOLLA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1648, '210105', 'PUNO', 'PUNO', 'CAPACHICA', 'CAPACHICA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1649, '210106', 'PUNO', 'PUNO', 'CHUCUITO', 'CHUCUITO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1650, '210107', 'PUNO', 'PUNO', 'COATA', 'COATA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1651, '210108', 'PUNO', 'PUNO', 'HUATA', 'HUATA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1652, '210109', 'PUNO', 'PUNO', 'MAÑAZO', 'MAÑAZO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1653, '210110', 'PUNO', 'PUNO', 'PAUCARCOLLA', 'PAUCARCOLLA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1654, '210111', 'PUNO', 'PUNO', 'PICHACANI', 'LARAQUERI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1655, '210112', 'PUNO', 'PUNO', 'PLATERIA', 'PLATERIA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1656, '210113', 'PUNO', 'PUNO', 'SAN ANTONIO', 'JUNCAL /14', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1657, '210114', 'PUNO', 'PUNO', 'TIQUILLACA', 'TIQUILLACA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1658, '210115', 'PUNO', 'PUNO', 'VILQUE', 'VILQUE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1659, '210201', 'PUNO', 'AZANGARO', 'AZANGARO', 'AZANGARO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1660, '210202', 'PUNO', 'AZANGARO', 'ACHAYA', 'ACHAYA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1661, '210203', 'PUNO', 'AZANGARO', 'ARAPA', 'ARAPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1662, '210204', 'PUNO', 'AZANGARO', 'ASILLO', 'ASILLO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1663, '210205', 'PUNO', 'AZANGARO', 'CAMINACA', 'CAMINACA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1664, '210206', 'PUNO', 'AZANGARO', 'CHUPA', 'CHUPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1665, '210207', 'PUNO', 'AZANGARO', 'JOSE DOMINGO CHOQUEHUANCA', 'ESTACION DE PUCARA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1666, '210208', 'PUNO', 'AZANGARO', 'MUÑANI', 'MUÑANI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1667, '210209', 'PUNO', 'AZANGARO', 'POTONI', 'POTONI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1668, '210210', 'PUNO', 'AZANGARO', 'SAMAN', 'SAMAN', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1669, '210211', 'PUNO', 'AZANGARO', 'SAN ANTON', 'SAN ANTON', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1670, '210212', 'PUNO', 'AZANGARO', 'SAN JOSE', 'SAN JOSE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1671, '210213', 'PUNO', 'AZANGARO', 'SAN JUAN DE SALINAS', 'SAN JUAN DE SALINAS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1672, '210214', 'PUNO', 'AZANGARO', 'SANTIAGO DE PUPUJA', 'SANTIAGO DE PUPUJA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1673, '210215', 'PUNO', 'AZANGARO', 'TIRAPATA', 'TIRAPATA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1674, '210301', 'PUNO', 'CARABAYA', 'MACUSANI', 'MACUSANI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1675, '210302', 'PUNO', 'CARABAYA', 'AJOYANI', 'AJOYANI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1676, '210303', 'PUNO', 'CARABAYA', 'AYAPATA', 'AYAPATA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1677, '210304', 'PUNO', 'CARABAYA', 'COASA', 'COASA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1678, '210305', 'PUNO', 'CARABAYA', 'CORANI', 'CORANI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1679, '210306', 'PUNO', 'CARABAYA', 'CRUCERO', 'CRUCERO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1680, '210307', 'PUNO', 'CARABAYA', 'ITUATA', 'TAMBILLO /15', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1681, '210308', 'PUNO', 'CARABAYA', 'OLLACHEA', 'OLLACHEA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1682, '210309', 'PUNO', 'CARABAYA', 'SAN GABAN', 'LANLACUNI BAJO', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1683, '210310', 'PUNO', 'CARABAYA', 'USICAYOS', 'USICAYOS', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1684, '210401', 'PUNO', 'CHUCUITO', 'JULI', 'JULI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1685, '210402', 'PUNO', 'CHUCUITO', 'DESAGUADERO', 'DESAGUADERO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1686, '210403', 'PUNO', 'CHUCUITO', 'HUACULLANI', 'HUACULLANI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1687, '210404', 'PUNO', 'CHUCUITO', 'KELLUYO', 'KELLUYO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1688, '210405', 'PUNO', 'CHUCUITO', 'PISACOMA', 'PISACOMA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1689, '210406', 'PUNO', 'CHUCUITO', 'POMATA', 'POMATA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1690, '210407', 'PUNO', 'CHUCUITO', 'ZEPITA', 'ZEPITA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1691, '210501', 'PUNO', 'EL COLLAO', 'ILAVE', 'ILAVE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1692, '210502', 'PUNO', 'EL COLLAO', 'CAPAZO', 'CAPAZO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1693, '210503', 'PUNO', 'EL COLLAO', 'PILCUYO', 'PILCUYO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1694, '210504', 'PUNO', 'EL COLLAO', 'SANTA ROSA', 'MAZO CRUZ', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1695, '210505', 'PUNO', 'EL COLLAO', 'CONDURIRI', 'CONDURIRI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1696, '210601', 'PUNO', 'HUANCANE', 'HUANCANE', 'HUANCANE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1697, '210602', 'PUNO', 'HUANCANE', 'COJATA', 'COJATA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1698, '210603', 'PUNO', 'HUANCANE', 'HUATASANI', 'HUATASANI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1699, '210604', 'PUNO', 'HUANCANE', 'INCHUPALLA', 'INCHUPALLA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1700, '210605', 'PUNO', 'HUANCANE', 'PUSI', 'PUSI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1701, '210606', 'PUNO', 'HUANCANE', 'ROSASPATA', 'ROSASPATA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1702, '210607', 'PUNO', 'HUANCANE', 'TARACO', 'TARACO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1703, '210608', 'PUNO', 'HUANCANE', 'VILQUE CHICO', 'VILQUE CHICO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1704, '210701', 'PUNO', 'LAMPA', 'LAMPA', 'LAMPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1705, '210702', 'PUNO', 'LAMPA', 'CABANILLA', 'CABANILLA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1706, '210703', 'PUNO', 'LAMPA', 'CALAPUJA', 'CALAPUJA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1707, '210704', 'PUNO', 'LAMPA', 'NICASIO', 'NICASIO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1708, '210705', 'PUNO', 'LAMPA', 'OCUVIRI', 'OCUVIRI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1709, '210706', 'PUNO', 'LAMPA', 'PALCA', 'PALCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1710, '210707', 'PUNO', 'LAMPA', 'PARATIA', 'PARATIA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1711, '210708', 'PUNO', 'LAMPA', 'PUCARA', 'PUCARA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1712, '210709', 'PUNO', 'LAMPA', 'SANTA LUCIA', 'SANTA LUCIA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1713, '210710', 'PUNO', 'LAMPA', 'VILAVILA', 'VILAVILA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1714, '210801', 'PUNO', 'MELGAR', 'AYAVIRI', 'AYAVIRI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1715, '210802', 'PUNO', 'MELGAR', 'ANTAUTA', 'ANTAUTA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1716, '210803', 'PUNO', 'MELGAR', 'CUPI', 'CUPI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1717, '210804', 'PUNO', 'MELGAR', 'LLALLI', 'LLALLI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1718, '210805', 'PUNO', 'MELGAR', 'MACARI', 'MACARI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1719, '210806', 'PUNO', 'MELGAR', 'NUÑOA', 'NUÑOA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1720, '210807', 'PUNO', 'MELGAR', 'ORURILLO', 'ORURILLO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1721, '210808', 'PUNO', 'MELGAR', 'SANTA ROSA', 'SANTA ROSA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1722, '210809', 'PUNO', 'MELGAR', 'UMACHIRI', 'UMACHIRI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1723, '210901', 'PUNO', 'MOHO', 'MOHO', 'MOHO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1724, '210902', 'PUNO', 'MOHO', 'CONIMA', 'CONIMA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1725, '210903', 'PUNO', 'MOHO', 'HUAYRAPATA', 'HUAYRAPATA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1726, '210904', 'PUNO', 'MOHO', 'TILALI', 'TILALI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1727, '211001', 'PUNO', 'SAN ANTONIO DE PUTINA', 'PUTINA', 'PUTINA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1728, '211002', 'PUNO', 'SAN ANTONIO DE PUTINA', 'ANANEA', 'ANANEA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1729, '211003', 'PUNO', 'SAN ANTONIO DE PUTINA', 'PEDRO VILCA APAZA', 'AYRAMPUNI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1730, '211004', 'PUNO', 'SAN ANTONIO DE PUTINA', 'QUILCAPUNCU', 'QUILCAPUNCU', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1731, '211005', 'PUNO', 'SAN ANTONIO DE PUTINA', 'SINA', 'SINA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1732, '211101', 'PUNO', 'SAN ROMAN', 'JULIACA', 'JULIACA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1733, '211102', 'PUNO', 'SAN ROMAN', 'CABANA', 'CABANA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1734, '211103', 'PUNO', 'SAN ROMAN', 'CABANILLAS', 'DEUSTUA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1735, '211104', 'PUNO', 'SAN ROMAN', 'CARACOTO', 'CARACOTO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1736, '211105', 'PUNO', 'SAN ROMAN', 'SAN MIGUEL', 'SAN MIGUEL', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1737, '211201', 'PUNO', 'SANDIA', 'SANDIA', 'SANDIA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1738, '211202', 'PUNO', 'SANDIA', 'CUYOCUYO', 'CUYOCUYO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1739, '211203', 'PUNO', 'SANDIA', 'LIMBANI', 'LIMBANI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1740, '211204', 'PUNO', 'SANDIA', 'PATAMBUCO', 'PATAMBUCO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1741, '211205', 'PUNO', 'SANDIA', 'PHARA', 'PHARA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1742, '211206', 'PUNO', 'SANDIA', 'QUIACA', 'QUIACA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1743, '211207', 'PUNO', 'SANDIA', 'SAN JUAN DEL ORO', 'SAN JUAN DEL ORO', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1744, '211208', 'PUNO', 'SANDIA', 'YANAHUAYA', 'YANAHUAYA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1745, '211209', 'PUNO', 'SANDIA', 'ALTO INAMBARI', 'MASSIAPO', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1746, '211210', 'PUNO', 'SANDIA', 'SAN PEDRO DE PUTINA PUNCO', 'PUTINA PUNCO', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1747, '211301', 'PUNO', 'YUNGUYO', 'YUNGUYO', 'YUNGUYO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1748, '211302', 'PUNO', 'YUNGUYO', 'ANAPIA', 'ANAPIA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1749, '211303', 'PUNO', 'YUNGUYO', 'COPANI', 'COPANI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1750, '211304', 'PUNO', 'YUNGUYO', 'CUTURAPI', 'SAN JUAN DE CUTURAPI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1751, '211305', 'PUNO', 'YUNGUYO', 'OLLARAYA', 'SAN MIGUEL DE OLLARAYA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1752, '211306', 'PUNO', 'YUNGUYO', 'TINICACHI', 'TINICACHI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1753, '211307', 'PUNO', 'YUNGUYO', 'UNICACHI', 'MARCAJA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1754, '220101', 'SAN MARTIN', 'MOYOBAMBA', 'MOYOBAMBA', 'MOYOBAMBA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1755, '220102', 'SAN MARTIN', 'MOYOBAMBA', 'CALZADA', 'CALZADA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1756, '220103', 'SAN MARTIN', 'MOYOBAMBA', 'HABANA', 'HABANA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1757, '220104', 'SAN MARTIN', 'MOYOBAMBA', 'JEPELACIO', 'JEPELACIO', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1758, '220105', 'SAN MARTIN', 'MOYOBAMBA', 'SORITOR', 'SORITOR', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1759, '220106', 'SAN MARTIN', 'MOYOBAMBA', 'YANTALO', 'YANTALO', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1760, '220201', 'SAN MARTIN', 'BELLAVISTA', 'BELLAVISTA', 'BELLAVISTA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1761, '220202', 'SAN MARTIN', 'BELLAVISTA', 'ALTO BIAVO', 'CUZCO', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1762, '220203', 'SAN MARTIN', 'BELLAVISTA', 'BAJO BIAVO', 'NUEVO LIMA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1763, '220204', 'SAN MARTIN', 'BELLAVISTA', 'HUALLAGA', 'LEDOY', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1764, '220205', 'SAN MARTIN', 'BELLAVISTA', 'SAN PABLO', 'SAN PABLO', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1765, '220206', 'SAN MARTIN', 'BELLAVISTA', 'SAN RAFAEL', 'SAN RAFAEL', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1766, '220301', 'SAN MARTIN', 'EL DORADO', 'SAN JOSE DE SISA', 'SAN JOSE DE SISA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1767, '220302', 'SAN MARTIN', 'EL DORADO', 'AGUA BLANCA', 'AGUA BLANCA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1768, '220303', 'SAN MARTIN', 'EL DORADO', 'SAN MARTIN', 'SAN MARTIN', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1769, '220304', 'SAN MARTIN', 'EL DORADO', 'SANTA ROSA', 'SANTA ROSA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1770, '220305', 'SAN MARTIN', 'EL DORADO', 'SHATOJA', 'SHATOJA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1771, '220401', 'SAN MARTIN', 'HUALLAGA', 'SAPOSOA', 'SAPOSOA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1772, '220402', 'SAN MARTIN', 'HUALLAGA', 'ALTO SAPOSOA', 'PASARRAYA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1773, '220403', 'SAN MARTIN', 'HUALLAGA', 'EL ESLABON', 'EL ESLABON', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1774, '220404', 'SAN MARTIN', 'HUALLAGA', 'PISCOYACU', 'PISCOYACU', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1775, '220405', 'SAN MARTIN', 'HUALLAGA', 'SACANCHE', 'SACANCHE', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1776, '220406', 'SAN MARTIN', 'HUALLAGA', 'TINGO DE SAPOSOA', 'TINGO DE SAPOSOA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1777, '220501', 'SAN MARTIN', 'LAMAS', 'LAMAS', 'LAMAS', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1778, '220502', 'SAN MARTIN', 'LAMAS', 'ALONSO DE ALVARADO', 'ROQUE', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1779, '220503', 'SAN MARTIN', 'LAMAS', 'BARRANQUITA', 'BARRANQUITA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1780, '220504', 'SAN MARTIN', 'LAMAS', 'CAYNARACHI', 'PONGO DE CAYNARACHI', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1781, '220505', 'SAN MARTIN', 'LAMAS', 'CUÑUMBUQUI', 'CUÑUMBUQUI', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1782, '220506', 'SAN MARTIN', 'LAMAS', 'PINTO RECODO', 'PINTO RECODO', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1783, '220507', 'SAN MARTIN', 'LAMAS', 'RUMISAPA', 'RUMISAPA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1784, '220508', 'SAN MARTIN', 'LAMAS', 'SAN ROQUE DE CUMBAZA', 'SAN ROQUE DE CUMBAZA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1785, '220509', 'SAN MARTIN', 'LAMAS', 'SHANAO', 'SHANAO', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1786, '220510', 'SAN MARTIN', 'LAMAS', 'TABALOSOS', 'TABALOSOS', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1787, '220511', 'SAN MARTIN', 'LAMAS', 'ZAPATERO', 'ZAPATERO', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1788, '220601', 'SAN MARTIN', 'MARISCAL CACERES', 'JUANJUI', 'JUANJUI', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1789, '220602', 'SAN MARTIN', 'MARISCAL CACERES', 'CAMPANILLA', 'CAMPANILLA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1790, '220603', 'SAN MARTIN', 'MARISCAL CACERES', 'HUICUNGO', 'HUICUNGO', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1791, '220604', 'SAN MARTIN', 'MARISCAL CACERES', 'PACHIZA', 'PACHIZA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1792, '220605', 'SAN MARTIN', 'MARISCAL CACERES', 'PAJARILLO', 'PAJARILLO', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1793, '220701', 'SAN MARTIN', 'PICOTA', 'PICOTA', 'PICOTA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1794, '220702', 'SAN MARTIN', 'PICOTA', 'BUENOS AIRES', 'BUENOS AIRES', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1795, '220703', 'SAN MARTIN', 'PICOTA', 'CASPISAPA', 'CASPISAPA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1796, '220704', 'SAN MARTIN', 'PICOTA', 'PILLUANA', 'PILLUANA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1797, '220705', 'SAN MARTIN', 'PICOTA', 'PUCACACA', 'PUCACACA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1798, '220706', 'SAN MARTIN', 'PICOTA', 'SAN CRISTOBAL', 'PUERTO RICO', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1799, '220707', 'SAN MARTIN', 'PICOTA', 'SAN HILARION', 'SAN CRISTOBAL DE SISA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1800, '220708', 'SAN MARTIN', 'PICOTA', 'SHAMBOYACU', 'SHAMBOYACU', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1801, '220709', 'SAN MARTIN', 'PICOTA', 'TINGO DE PONASA', 'TINGO DE PONASA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1802, '220710', 'SAN MARTIN', 'PICOTA', 'TRES UNIDOS', 'TRES UNIDOS', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1803, '220801', 'SAN MARTIN', 'RIOJA', 'RIOJA', 'RIOJA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1804, '220802', 'SAN MARTIN', 'RIOJA', 'AWAJUN', 'BAJO NARANJILLO', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1805, '220803', 'SAN MARTIN', 'RIOJA', 'ELIAS SOPLIN VARGAS', 'SEGUNDA JERUSALEN-AZUNGUILLO', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1806, '220804', 'SAN MARTIN', 'RIOJA', 'NUEVA CAJAMARCA', 'NUEVA CAJAMARCA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1807, '220805', 'SAN MARTIN', 'RIOJA', 'PARDO MIGUEL', 'NARANJOS', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1808, '220806', 'SAN MARTIN', 'RIOJA', 'POSIC', 'POSIC', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1809, '220807', 'SAN MARTIN', 'RIOJA', 'SAN FERNANDO', 'SAN FERNANDO', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1810, '220808', 'SAN MARTIN', 'RIOJA', 'YORONGOS', 'YORONGOS', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1811, '220809', 'SAN MARTIN', 'RIOJA', 'YURACYACU', 'YURACYACU', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1812, '220901', 'SAN MARTIN', 'SAN MARTIN', 'TARAPOTO', 'TARAPOTO', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1813, '220902', 'SAN MARTIN', 'SAN MARTIN', 'ALBERTO LEVEAU', 'UTCURARCA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1814, '220903', 'SAN MARTIN', 'SAN MARTIN', 'CACATACHI', 'CACATACHI', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1815, '220904', 'SAN MARTIN', 'SAN MARTIN', 'CHAZUTA', 'CHAZUTA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1816, '220905', 'SAN MARTIN', 'SAN MARTIN', 'CHIPURANA', 'NAVARRO', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1817, '220906', 'SAN MARTIN', 'SAN MARTIN', 'EL PORVENIR', 'PELEJO', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1818, '220907', 'SAN MARTIN', 'SAN MARTIN', 'HUIMBAYOC', 'HUIMBAYOC', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1819, '220908', 'SAN MARTIN', 'SAN MARTIN', 'JUAN GUERRA', 'JUAN GUERRA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1820, '220909', 'SAN MARTIN', 'SAN MARTIN', 'LA BANDA DE SHILCAYO', 'LA BANDA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1821, '220910', 'SAN MARTIN', 'SAN MARTIN', 'MORALES', 'MORALES', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1822, '220911', 'SAN MARTIN', 'SAN MARTIN', 'PAPAPLAYA', 'PAPAPLAYA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1823, '220912', 'SAN MARTIN', 'SAN MARTIN', 'SAN ANTONIO', 'SAN ANTONIO', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1824, '220913', 'SAN MARTIN', 'SAN MARTIN', 'SAUCE', 'SAUCE', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1825, '220914', 'SAN MARTIN', 'SAN MARTIN', 'SHAPAJA', 'SHAPAJA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1826, '221001', 'SAN MARTIN', 'TOCACHE', 'TOCACHE', 'TOCACHE', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1827, '221002', 'SAN MARTIN', 'TOCACHE', 'NUEVO PROGRESO', 'NUEVO PROGRESO', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1828, '221003', 'SAN MARTIN', 'TOCACHE', 'POLVORA', 'POLVORA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1829, '221004', 'SAN MARTIN', 'TOCACHE', 'SHUNTE', 'MONTE CRISTO /16', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1830, '221005', 'SAN MARTIN', 'TOCACHE', 'UCHIZA', 'UCHIZA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1831, '221006', 'SAN MARTIN', 'TOCACHE', 'SANTA LUCIA', 'SANTA LUCIA', '3', 'SELVA ALTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1832, '230101', 'TACNA', 'TACNA', 'TACNA', 'TACNA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1833, '230102', 'TACNA', 'TACNA', 'ALTO DE LA ALIANZA', 'LA ESPERANZA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1834, '230103', 'TACNA', 'TACNA', 'CALANA', 'CALANA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1835, '230104', 'TACNA', 'TACNA', 'CIUDAD NUEVA', 'CIUDAD NUEVA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1836, '230105', 'TACNA', 'TACNA', 'INCLAN', 'SAMA GRANDE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1837, '230106', 'TACNA', 'TACNA', 'PACHIA', 'PACHIA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1838, '230107', 'TACNA', 'TACNA', 'PALCA', 'PALCA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1839, '230108', 'TACNA', 'TACNA', 'POCOLLAY', 'POCOLLAY', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1840, '230109', 'TACNA', 'TACNA', 'SAMA', 'LAS YARAS', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1841, '230110', 'TACNA', 'TACNA', 'CORONEL GREGORIO ALBARRACIN LANCHIPA', 'ALFONSO UGARTE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1842, '230111', 'TACNA', 'TACNA', 'LA YARADA LOS PALOS', 'LOS PALOS', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1843, '230201', 'TACNA', 'CANDARAVE', 'CANDARAVE', 'CANDARAVE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1844, '230202', 'TACNA', 'CANDARAVE', 'CAIRANI', 'CAIRANI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1845, '230203', 'TACNA', 'CANDARAVE', 'CAMILACA', 'ALTO CAMILACA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1846, '230204', 'TACNA', 'CANDARAVE', 'CURIBAYA', 'CURIBAYA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1847, '230205', 'TACNA', 'CANDARAVE', 'HUANUARA', 'HUANUARA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1848, '230206', 'TACNA', 'CANDARAVE', 'QUILAHUANI', 'QUILAHUANI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1849, '230301', 'TACNA', 'JORGE BASADRE', 'LOCUMBA', 'LOCUMBA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1850, '230302', 'TACNA', 'JORGE BASADRE', 'ILABAYA', 'ILABAYA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1851, '230303', 'TACNA', 'JORGE BASADRE', 'ITE', 'ITE', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1852, '230401', 'TACNA', 'TARATA', 'TARATA', 'TARATA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1853, '230402', 'TACNA', 'TARATA', 'HEROES ALBARRACIN', 'CHUCATAMANI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1854, '230403', 'TACNA', 'TARATA', 'ESTIQUE', 'ESTIQUE', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1855, '230404', 'TACNA', 'TARATA', 'ESTIQUE-PAMPA', 'ESTIQUE-PAMPA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1856, '230405', 'TACNA', 'TARATA', 'SITAJARA', 'SITAJARA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1857, '230406', 'TACNA', 'TARATA', 'SUSAPAYA', 'SUSAPAYA', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1858, '230407', 'TACNA', 'TARATA', 'TARUCACHI', 'TARUCACHI', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1859, '230408', 'TACNA', 'TARATA', 'TICACO', 'TICACO', '2', 'SIERRA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1860, '240101', 'TUMBES', 'TUMBES', 'TUMBES', 'TUMBES', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1861, '240102', 'TUMBES', 'TUMBES', 'CORRALES', 'SAN PEDRO DE LOS INCAS', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1862, '240103', 'TUMBES', 'TUMBES', 'LA CRUZ', 'CALETA CRUZ', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1863, '240104', 'TUMBES', 'TUMBES', 'PAMPAS DE HOSPITAL', 'PAMPAS DE HOSPITAL', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1864, '240105', 'TUMBES', 'TUMBES', 'SAN JACINTO', 'SAN JACINTO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1865, '240106', 'TUMBES', 'TUMBES', 'SAN JUAN DE LA VIRGEN', 'SAN JUAN DE LA VIRGEN', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1866, '240201', 'TUMBES', 'CONTRALMIRANTE VILLAR', 'ZORRITOS', 'ZORRITOS', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1867, '240202', 'TUMBES', 'CONTRALMIRANTE VILLAR', 'CASITAS', 'CAÑAVERAL', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1868, '240203', 'TUMBES', 'CONTRALMIRANTE VILLAR', 'CANOAS DE PUNTA SAL', 'CANCAS', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1869, '240301', 'TUMBES', 'ZARUMILLA', 'ZARUMILLA', 'ZARUMILLA', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1870, '240302', 'TUMBES', 'ZARUMILLA', 'AGUAS VERDES', 'AGUAS VERDES', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1871, '240303', 'TUMBES', 'ZARUMILLA', 'MATAPALO', 'MATAPALO', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1872, '240304', 'TUMBES', 'ZARUMILLA', 'PAPAYAL', 'PAPAYAL', '1', 'COSTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1873, '250101', 'UCAYALI', 'CORONEL PORTILLO', 'CALLERIA', 'PUCALLPA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1874, '250102', 'UCAYALI', 'CORONEL PORTILLO', 'CAMPOVERDE', 'CAMPO VERDE', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1875, '250103', 'UCAYALI', 'CORONEL PORTILLO', 'IPARIA', 'IPARIA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1876, '250104', 'UCAYALI', 'CORONEL PORTILLO', 'MASISEA', 'MASISEA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1877, '250105', 'UCAYALI', 'CORONEL PORTILLO', 'YARINACOCHA', 'PUERTO CALLAO', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1878, '250106', 'UCAYALI', 'CORONEL PORTILLO', 'NUEVA REQUENA', 'NUEVA REQUENA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1879, '250107', 'UCAYALI', 'CORONEL PORTILLO', 'MANANTAY', 'SAN FERNANDO', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1880, '250201', 'UCAYALI', 'ATALAYA', 'RAIMONDI', 'ATALAYA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1881, '250202', 'UCAYALI', 'ATALAYA', 'SEPAHUA', 'SEPAHUA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1882, '250203', 'UCAYALI', 'ATALAYA', 'TAHUANIA', 'BOLOGNESI', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1883, '250204', 'UCAYALI', 'ATALAYA', 'YURUA', 'BREU', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1884, '250301', 'UCAYALI', 'PADRE ABAD', 'PADRE ABAD', 'AGUAYTIA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1885, '250302', 'UCAYALI', 'PADRE ABAD', 'IRAZOLA', 'SAN ALEJANDRO', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1886, '250303', 'UCAYALI', 'PADRE ABAD', 'CURIMANA', 'CURIMANA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1887, '250304', 'UCAYALI', 'PADRE ABAD', 'NESHUYA', 'MONTE ALEGRE', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1888, '250305', 'UCAYALI', 'PADRE ABAD', 'ALEXANDER VON HUMBOLDT', 'ALEXANDER VON HUMBOLDT', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1889, '250306', 'UCAYALI', 'PADRE ABAD', 'HUIPOCA', 'HUIPOCA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1890, '250307', 'UCAYALI', 'PADRE ABAD', 'BOQUERON', 'BOQUERON', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1891, '250401', 'UCAYALI', 'PURUS', 'PURUS', 'ESPERANZA', '4', 'SELVA BAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `merit_rankings`
--

CREATE TABLE `merit_rankings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `academic_period_id` bigint(20) UNSIGNED NOT NULL,
  `module_id` bigint(20) UNSIGNED DEFAULT NULL,
  `weighted_average` decimal(5,3) NOT NULL,
  `general_position` int(11) NOT NULL,
  `module_position` int(11) DEFAULT NULL,
  `period_credits` int(11) NOT NULL,
  `calculation_date` timestamp NOT NULL DEFAULT '2025-11-26 18:44:48',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_11_06_235616_add_two_factor_columns_to_users_table', 1),
(5, '2025_11_07_000011_create_personal_access_tokens_table', 1),
(6, '2025_11_07_010736_create_institutions_table', 1),
(7, '2025_11_07_010748_create_academic_years_table', 1),
(8, '2025_11_07_010757_create_shifts_table', 1),
(9, '2025_11_07_010803_create_classroom_resources_table', 1),
(10, '2025_11_07_010809_create_evaluation_types_table', 1),
(11, '2025_11_07_010821_create_payment_concepts_table', 1),
(12, '2025_11_07_010830_create_system_settings_table', 1),
(13, '2025_11_07_023231_create_careers_table', 1),
(14, '2025_11_07_023237_create_study_plans_table', 1),
(15, '2025_11_07_023244_create_modules_table', 1),
(16, '2025_11_07_023253_create_didactic_units_table', 1),
(17, '2025_11_07_023259_create_prerequisites_table', 1),
(18, '2025_11_07_114331_create_teachers_table', 1),
(19, '2025_11_07_114351_create_students_table', 1),
(20, '2025_11_07_114359_create_enrollment_reserves_table', 1),
(21, '2025_11_07_115238_add_user_type_to_users_table', 1),
(22, '2025_11_07_225002_create_academic_periods_table', 1),
(23, '2025_11_07_225010_create_teacher_assignments_table', 1),
(24, '2025_11_07_225018_create_schedules_table', 1),
(25, '2025_11_08_224312_create_enrollments_table', 1),
(26, '2025_11_08_224335_create_registrations_table', 1),
(27, '2025_11_09_075448_create_grades_table', 1),
(28, '2025_11_09_075504_create_academic_records_table', 1),
(29, '2025_11_09_075517_create_attendances_table', 1),
(30, '2025_11_09_091059_create_student_payments_table', 1),
(31, '2025_11_09_154538_create_internships_table', 1),
(32, '2025_11_09_154624_create_graduation_processes_table', 1),
(33, '2025_11_09_154639_create_certificates_table', 1),
(34, '2025_11_10_100639_create_syllabi_table', 1),
(35, '2025_11_10_103939_add_observations_to_syllabi_table', 1),
(36, '2025_11_10_113202_create_library_resources_table', 1),
(37, '2025_11_10_113214_create_library_loans_table', 1),
(38, '2025_11_10_132213_create_permission_tables', 1),
(39, '2025_11_11_030309_create_tutorings_table', 1),
(40, '2025_11_11_031532_create_academic_activities_table', 1),
(41, '2025_11_11_031539_create_activity_submissions_table', 1),
(42, '2025_11_11_170210_create_announcements_table', 1),
(43, '2025_11_11_175313_create_merit_rankings_table', 1),
(44, '2025_11_12_110842_add_grade_entry_dates_to_academic_periods_table', 1),
(45, '2025_11_15_205930_create_cash_sessions_table', 1),
(46, '2025_11_15_205931_create_voucher_series_table', 1),
(47, '2025_11_15_205940_create_vouchers_table', 1),
(48, '2025_11_15_205949_create_voucher_items_table', 1),
(49, '2025_11_15_210006_create_credit_notes_table', 1),
(50, '2025_11_15_210037_add_voucher_id_to_student_payments_table', 1),
(51, '2025_11_19_232310_add_document_number_to_users_table', 1),
(52, '2025_11_22_191519_create_locations_table', 1),
(53, '2025_11_22_191624_create_origin_schools_table', 1),
(54, '2025_11_22_211031_create_admission_modalities_table', 1),
(55, '2025_11_22_211035_create_financial_entities_table', 1),
(56, '2025_11_22_211038_create_admission_offerings_table', 1),
(57, '2025_11_22_211042_update_users_table_add_surnames', 1),
(58, '2025_11_22_214345_create_applicants_table', 1),
(59, '2025_11_26_135009_add_details_to_students_table', 2),
(60, '2025_11_28_201012_add_resolution_code_to_enrollment_reserves_table', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 62),
(1, 'App\\Models\\User', 63),
(1, 'App\\Models\\User', 64),
(2, 'App\\Models\\User', 2),
(2, 'App\\Models\\User', 3),
(2, 'App\\Models\\User', 4),
(2, 'App\\Models\\User', 5),
(2, 'App\\Models\\User', 6),
(2, 'App\\Models\\User', 7),
(2, 'App\\Models\\User', 8),
(2, 'App\\Models\\User', 9),
(2, 'App\\Models\\User', 10),
(2, 'App\\Models\\User', 11),
(5, 'App\\Models\\User', 18),
(7, 'App\\Models\\User', 1),
(8, 'App\\Models\\User', 65);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modules`
--

CREATE TABLE `modules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `study_plan_id` bigint(20) UNSIGNED NOT NULL,
  `module_number` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `minimum_credits_approval` int(11) NOT NULL,
  `total_hours` int(11) NOT NULL,
  `competencies` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `modules`
--

INSERT INTO `modules` (`id`, `study_plan_id`, `module_number`, `name`, `description`, `minimum_credits_approval`, `total_hours`, `competencies`, `sort_order`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 'Soporte, mantenimiento y control de riesgos en sistemas informáticos', NULL, 25, 800, NULL, 1, 'active', '2025-11-26 18:45:43', '2025-11-26 18:45:43', NULL),
(2, 1, 2, 'Desarrollo de sistemas informáticos y gestión', NULL, 30, 768, NULL, 2, 'active', '2025-11-26 18:45:43', '2025-11-26 18:45:43', NULL),
(3, 1, 3, 'Arquitectura y proyectos TI', NULL, 35, 816, NULL, 3, 'active', '2025-11-26 18:45:43', '2025-11-26 18:45:43', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `origin_schools`
--

CREATE TABLE `origin_schools` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `modular_code` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `d_niv_mod` varchar(255) NOT NULL,
  `management_type` varchar(50) DEFAULT NULL,
  `ubigeo_code` varchar(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `origin_schools`
--

INSERT INTO `origin_schools` (`id`, `modular_code`, `name`, `d_niv_mod`, `management_type`, `ubigeo_code`, `created_at`, `updated_at`) VALUES
(1, '1031194', 'PRONOE CESAR VALLEJO', 'Secundaria de Adultos', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, '1337906', 'LA VALLEJO', 'Secundaria de Adultos', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, '0308015', '41008 MANUEL MUÑOZ NAJAR', 'Básica Alternativa', 'Pública de gestión directa', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, '0309377', 'MICAELA BASTIDAS', 'Secundaria', 'Pública de gestión directa', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, '0309385', 'NUESTRA SEÑORA DE LA ASUNCION', 'Secundaria', 'Pública de gestión privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, '0309906', 'SAN PEDRO PASCUAL', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, '0309914', 'SAN JERONIMO', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, '0309955', 'DE LA SALLE', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, '0309989', 'SALESIANOS DON BOSCO', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, '0310037', 'ESCLAVAS DEL SAGRADO CORAZON DE JESUS', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, '0310052', 'NUESTRA SEÑORA DEL ROSARIO', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, '0310078', 'NUESTRA SRA. DEL CARMEN', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13, '0310110', 'MARIA MONTESSORI', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, '0310136', 'SANTA ROSA DE VITERBO', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, '0310391', 'MICAELA BASTIDAS', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, '0310466', 'NUESTRA SEÑORA DE FATIMA', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, '0310573', 'ROSA DE SANTA MARIA', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(18, '0472209', '40025 SANTA DOROTEA', 'Secundaria', 'Pública de gestión directa', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(19, '0518241', '40165 SAN JUAN BAUTISTA DE LA SALLE', 'Secundaria', 'Pública de gestión privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, '0570044', '41008 MANUEL MUÑOZ NAJAR', 'Secundaria', 'Pública de gestión directa', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, '0616953', 'FRANCO PERUANO DU PETIT THOUARS', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, '0616979', 'ANA DE LOS ANGELES', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, '0617084', 'MENDEL UMACOLLO', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(24, '0619106', 'CACEI PASTEUR', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(25, '0643684', 'ISAAC NEWTON', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(26, '0695189', 'MADAME MARIA CURIE', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(27, '0695205', 'SANTA MARIA', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(28, '0695213', 'STANFORD', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(29, '0723296', 'ALMIRANTE DU PETIT THOUARS', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30, '0723866', 'JESUS MARIA - SAN MARTIN DE PORRES', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(31, '0745620', 'MARIA MONTESSORI', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(32, '0794388', 'ENRIQUE LOPEZ ALBUJAR', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(33, '0890947', 'PESTALOZZI', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(34, '0894972', 'RAUL PORRAS BARRENECHEA', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(35, '0895631', 'VIRGEN MORENA DE JASNA GORA', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(36, '0898585', 'ALFA', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(37, '0898619', 'BLAS PASCAL', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(38, '0898643', 'CARLOS ANDERSON', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(39, '0898676', 'LA RECOLETA', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(40, '0898700', 'MONS. JULIO GONZALES RUIZ', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(41, '0898734', 'NIKOLA TESLA', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(42, '0898767', 'JOHN VON NEUMANN', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(43, '0898791', 'PEDRO PAULET MOSTAJO', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(44, '0898825', 'SAGRADO CORAZON DE JESUS', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(45, '0898841', 'FLORA TRISTAN', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(46, '0898858', 'FRANCISCO ROJAS SCHOOL', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(47, '0898882', 'MATER INMACULADA', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(48, '0898916', 'SCIENTIF SCHOOL COMPUTER', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(49, '0898940', 'SOR ANA DE LOS ANGELES', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(50, '0898973', 'TRILCE', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(51, '0899005', 'UNIVERSAL XXI', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(52, '1031020', 'ESTUDIOS INDEPENDIENTES', 'Secundaria de Adultos', 'Pública de gestión directa', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(53, '1031061', 'HONORIO DELGADO ESPINOZA', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(54, '1031079', 'HONORIO DELGADO ESPINOZA', 'Básica Alternativa-Avanzado', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(55, '1031160', 'PRONOE PERUANO HOLANDES VAN\'THOFF', 'Secundaria de Adultos', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(56, '1031251', 'EDUARDO DE HABICH', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(57, '1031277', 'LA CATOLICA', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(58, '1031293', 'LOS RANGER\'S', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(59, '1031301', 'DALTON', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(60, '1031467', 'NIÑO JESUS DE PRAGA', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(61, '1120047', 'MICAELA BASTIDAS', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(62, '1120195', 'PRONOE GARCI CARBAJAL CESCA', 'Secundaria de Adultos', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(63, '1202316', 'PRONOE HIPOLITO UNANUE', 'Secundaria de Adultos', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(64, '1202647', 'CESCA', 'Secundaria de Adultos', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(65, '1238724', 'CARMELITAS', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(66, '1238799', 'SAULO DE TARSO', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(67, '1238807', 'PRONOE SAN PABLO', 'Secundaria de Adultos', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(68, '1240126', 'CD CARPE DIEM', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(69, '1253384', 'SANTO TOMAS DE AQUINO', 'Secundaria de Adultos', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(70, '1260306', 'AMERICA HIGH SCHOOL', 'Básica Alternativa-Avanzado', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(71, '1260702', 'TERESA GONZALES DE FANNING', 'Básica Alternativa-Avanzado', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(72, '1271360', 'DOMINGO DE GUZMAN', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(73, '1273259', 'CRISTO REY DEL MUNDO', 'Secundaria de Adultos', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(74, '1273333', 'TEKNOS', 'Secundaria de Adultos', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(75, '1273572', 'SAMUEL ORTON', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(76, '1333327', 'PRONOE GRAHAN BELL', 'Secundaria de Adultos', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(77, '1031152', 'PRONOE SAN MARCOS', 'Secundaria de Adultos', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(78, '0308064', '41014 FORTUNATA GUTIERREZ DE BERNED', 'Básica Alternativa', 'Pública de gestión directa', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(79, '0308353', 'MARIA INMACULADA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(80, '0308676', 'NUESTRA SEÑORA DEL PILAR', 'Básica Alternativa', 'Pública de gestión privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(81, '0309211', 'CLARET', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(82, '0309229', 'INDEPENDENCIA AMERICANA', 'Secundaria', 'Pública de gestión directa', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(83, '0309302', 'JUANA CERVANTES DE BOLOGNESI', 'Secundaria', 'Pública de gestión directa', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(84, '0309344', 'PADRE DAMIAN DE LOS SAGRADOS CORAZONES', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(85, '0309633', 'NUESTRA SEÑORA DE LOURDES', 'Secundaria', 'Pública de gestión privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(86, '0309930', 'SAN JOSE DE AREQUIPA', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(87, '0310003', 'ORLEANS GOLEMAN', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(88, '0310060', 'DE LOS SAGRADOS CORAZONES', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(89, '0310086', 'CHAVES DE LA ROSA', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(90, '0310102', 'SAN PEDRO', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(91, '0310193', 'NUESTRA SEÑORA DEL PILAR', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(92, '0310201', 'COLEGIO ANGLO AMERICANO PRESCOTT', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(93, '0310326', 'INDEPENDENCIA AMERICANA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(94, '0490631', 'PADRE DAMIAN DE LOS SAGRADOS CORAZONES', 'Secundaria de Adultos', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(95, '0529149', 'INDEPENDENCIA AMERICANA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(96, '0569590', 'ADVENTISTA GENERAL JOSE DE SAN MARTIN', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(97, '0616987', 'NUESTRA SEÑORA DEL PILAR', 'Secundaria de Adultos', 'Pública de gestión privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(98, '0695239', 'SAN HILARION', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(99, '0695247', 'MATER PURISSIMA', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(100, '0894709', 'ALMIRANTE GRAU', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(101, '0894733', 'BERMONDT', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(102, '0894766', 'CLAUDIO GALENO', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(103, '0894915', 'MARIA AUXILIADORA', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(104, '0894949', 'MAX PLANCK', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(105, '0895003', 'SAINTE CATHERINE LABOURE', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(106, '0895276', 'MICHELSON', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(107, '0895573', 'MAGNA MATER AUSTRIAE', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(108, '0898551', 'ALEXANDER FLEMING', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(109, '1030337', 'EUROAMERICANO', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(110, '1030410', 'ALAS PERUANAS', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(111, '1030436', 'JOSE ORTEGA Y GASSET', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(112, '1030444', 'INGLES CATOLICO JOSBER', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(113, '1031053', 'ANDRES BELLO', 'Básica Alternativa-Avanzado', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(114, '1031731', 'JOHN WESLEY', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(115, '1116417', 'MARIA REYNA', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(116, '1118728', 'CARL FREDERICK SANGER', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(117, '1118801', 'BALDOR', 'Básica Alternativa-Avanzado', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(118, '1118835', 'MATER ADMIRABILIS', 'Básica Alternativa-Avanzado', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(119, '1120526', 'GALILEANO PERUANO ITALIANO', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(120, '1202597', 'ANDRES BELLO', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(121, '1236389', 'LIBERTAD', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(122, '1239367', 'ITALO PERUANO SANTA MARIA MAZZARELLO', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(123, '1239391', 'PRONOE BOLIVAR', 'Secundaria de Adultos', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(124, '1254069', 'MY ANGEL SCHOOL', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(125, '1255066', 'METROPOLITANO', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(126, '1261965', 'WILLIAM MORRIS', 'Básica Alternativa-Avanzado', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(127, '1271642', 'PADRE JUAN PABLO II', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(128, '1272251', 'BALDOR', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(129, '1336783', 'ALBERT EINSTEIN', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(130, '1337005', 'VIRGEN DE CHAPI B', 'Básica Alternativa', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(131, '1337013', 'PRONOE VIRGEN DE CHAPI B', 'Secundaria de Adultos', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(132, '1337070', 'ALFEREZ FAP RAUL J. LEGUIA DRAGO', 'Secundaria', 'Pública de gestión directa', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(133, '1236462', 'MAHANAIM', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(134, '1333525', 'PRONOE TRILCE E.I.R.L.', 'Secundaria de Adultos', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, '1333624', 'ANGLO AMERICANO SAMUEL', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(136, '1333681', 'BRYCE S.A.C.', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(137, '1333798', 'INGER', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(138, '1333806', 'INGER', 'Básica Alternativa-Avanzado', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(139, '1333939', 'G.G.R. LAS AMERICAS', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(140, '1277185', 'PRONOE MARTIN LUTHER KING JR.', 'Secundaria de Adultos', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(141, '1334259', 'MARIA INMACULADA', 'Básica Alternativa-Avanzado', 'Pública de gestión privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(142, '1333020', 'PRONOE SANTA ROSA', 'Secundaria de Adultos', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, '1277466', 'TECSIG', 'Básica Alternativa-Avanzado', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(144, '1334366', 'PIER GIORGIO FRASSATI', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(145, '0477679', 'AREQUIPA ANTONIANO', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(146, '0309641', 'AREQUIPA', 'Secundaria', 'Pública de gestión directa', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(147, '0310342', 'AREQUIPA ANTONIANO', 'Secundaria de Adultos', 'Pública de gestión directa', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(148, '1237627', 'PRONOE GRAN PADRE SAN FRANCISCO', 'Secundaria de Adultos', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(149, '1334689', 'GABRIELA MISTRAL', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(150, '1278530', 'JOYCE', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(151, '1278654', 'PRONOE EDUARDO DE RIVERO Y USTARIZ', 'Secundaria de Adultos', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(152, '1278779', 'PRONOE CITEM', 'Secundaria de Adultos', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(153, '1278894', 'CRISTO DE LA LUZ', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(154, '1334168', 'PERUANO SUECO', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(155, '1335074', 'EL UNIVERSITARIO', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(156, '1335132', 'FARADAY', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(157, '1335199', 'GRAN PACIFICADOR LINUS PAULING', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(158, '1335272', 'LUCIEN FREUD', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(159, '0750075', 'FREINETT', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(160, '1337039', 'LA VALLEJO', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(161, '1337302', 'MARIA AUXILIADORA', 'Básica Alternativa-Avanzado', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(162, '1337914', 'CIDECH PERU MAESTRO JESUS', 'Básica Alternativa-Avanzado', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(163, '1202407', 'PRONOE JOSE ANTONIO ENCINAS', 'Secundaria de Adultos', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(164, '1339050', 'PRINCIPE DE ASTURIAS', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(165, '1334523', 'TRILCE AREQUIPA', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(166, '1339936', 'INMACULADO CORAZON DE MARIA', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(167, '1339977', 'JENS KNUDSEN', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(168, '1303759', 'MARIA INMACULADA', 'Secundaria', 'Pública de gestión privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(169, '0695197', 'SAN PABLO', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(170, '1270818', 'JOSE CRISAM', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(171, '1306976', 'GRAN MAESTRO RAUL PORRAS', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(172, '1307024', 'TONY BUZAN', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(173, '1314046', 'SAN PATRICIO', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(174, '1342567', 'EBENEZER', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(175, '1351642', 'EL BUEN MAESTRO', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(176, '1354422', 'DIVINO NIÑO CORPAIDOS', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(177, '1361153', 'PRONOE ESTUDIOS INDEPENDIENTES', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(178, '1361898', 'HENRY KENDALL', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(179, '1363431', 'SIR WALTER SCOTT ASDI SYSTEM', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(180, '1030741', 'CIENCIAS APLICADAS CEDUNSA - SAN AGUSTIN', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(181, '1369701', 'CEDEU LEONCIO PRADO', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(182, '1369743', 'LORD KELVIN', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(183, '1369883', 'AUGUSTO SALAZAR BONDY', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(184, '1369636', 'SAN IGNACIO DE JESUS', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(185, '1370097', 'MAGNUS EDUCATORIS', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(186, '0894675', 'SEYMOUR BRUNER', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(187, '1339365', 'GRAN CORAZON DE JESUS', 'Básica Alternativa-Avanzado', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(188, '1336684', 'VIRGEN DEL CARMEN', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(189, '1336692', 'VIRGEN DEL CARMEN', 'Básica Alternativa-Avanzado', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(190, '1390855', 'LICEE JEAN BAPTISTE LAMARCK', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(191, '1391077', 'JUAN PABLO SCHOOL', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(192, '1414739', 'UNI - CIENCIAS', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(193, '1420488', 'LUIS FELIPE DE ORLEANS', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(194, '1432061', 'JHON DALTON', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(195, '1432020', 'ABRAHAM LINCOLN INTERNATIONAL SCHOOL', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(196, '1434810', 'COLEGIO DE CIENCIAS PROMEDIO 21', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(197, '1435288', 'PANAMERICANO', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(198, '1435551', 'VILLEGAS', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(199, '1435528', 'GRUPO AMERICAN SCHOOLS', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(200, '1437573', 'SANTA LUCIA', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(201, '1437607', 'JAIME SMITH', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(202, '1443795', 'CEDEU ALFRED BINET', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(203, '1452606', 'GLORIOSO CORAZON DE JESUS', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(204, '1465772', 'JAMES JOULE', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(205, '1470046', 'REICH LA PERLA', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(206, '1470582', 'VIRGEN DEL CARMEN MILAGROSA', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(207, '1470566', 'SANTANDER F.C.E.', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(208, '1470525', 'FLAVISUR', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(209, '1504885', 'SANTA VICENTA MARIA', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(210, '1520568', 'JESUS DIVINO MAESTRO', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(211, '1520584', 'INTERNACIONAL ALBERT L. LEHNINGER', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(212, '1528843', 'SANTA CLARA DE JESUS', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(213, '1530401', 'JOHN NASH', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(214, '1532241', 'SANTO DOMINGO DE GUZMAN', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(215, '1536960', 'CHAVES SCHOOL', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(216, '1562834', 'INDUSTRIAL DEL SUR PERUANO JAPONES', 'Básica Alternativa-Avanzado', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(217, '1567643', 'CORAZON DE MARIA', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(218, '1574342', 'JOULE CERCADO', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(219, '1622851', 'FERMAT CRAMER', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(220, '1624170', 'ACUARELA DEL SOL', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(221, '1629559', 'CORAZON INMACULADO DE MARIA', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(222, '1633817', 'TRES ANGELES', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(223, '1639087', 'COLEGIO MAYOR MENDEL', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(224, '1639707', 'ETP ESCUELAS TECNICAS DEL PERU', 'Básica Alternativa-Avanzado', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(225, '1666254', 'POLIVALENTE', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(226, '1679760', 'NUCLEO JOULE', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(227, '1697002', 'CIRCULO JOULE', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(228, '1703560', 'SAN JOAQUIN', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(229, '1720317', 'EL UNIVERSITARIO', 'Básica Alternativa-Avanzado', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(230, '1720564', 'SIR ALEXANDER FLEMING', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(231, '1724095', 'SANTO DOMINGO DE GUZMAN', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(232, '1725373', 'MARIA AUXILIADORA', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(233, '1726439', 'ETP ESCUELAS TECNICAS DEL PERU', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(234, '1734037', 'TALENTOS AREQUIPA', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(235, '1759968', 'TALENT SCHOOL', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(236, '1763176', 'CULTURA ORIENTAL', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(237, '1778497', 'THOMAS UNGER', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(238, '3000619', 'ESPARTA', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(239, '3020617', 'SAN PABLO DE AREQUIPA', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(240, '3039872', 'BRICEÑO', 'Secundaria', 'Privada', '040101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(241, '0308460', '40028 GUILLERMO MERCADO BARROSO', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(242, '0309252', 'MILITAR FRANCISCO BOLOGNESI', 'Secundaria', 'Pública de gestión directa', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(243, '0309682', '40024 MANUEL GONZALES PRADA', 'Secundaria', 'Pública de gestión directa', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(244, '0310581', '40029 LUDWING VAN BEETHOVEN', 'Secundaria de Adultos', 'Pública de gestión directa', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(245, '0515668', 'SANTA ROSA DE LIMA', 'Secundaria', 'Pública de gestión privada', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(246, '0569921', '40024 MANUEL GONZALES PRADA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(247, '0569988', '40028 GUILLERMO MERCADO BARROSO', 'Secundaria', 'Pública de gestión directa', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(248, '0570010', '40029 LUDWING VAN BEETHOVEN', 'Secundaria', 'Pública de gestión directa', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(249, '0636480', 'ALTO SELVA ALEGRE LINUS PAULING', 'Secundaria', 'Privada', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(250, '0894402', '40024 MANUEL GONZALES PRADA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(251, '0894618', 'SAN FRANCISCO SOLANO', 'Secundaria', 'Privada', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(252, '0894642', 'WILLIAM MORRIS', 'Secundaria', 'Privada', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(253, '0895482', 'SAN MARTIN DE PORRES', 'Secundaria', 'Pública de gestión privada', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(254, '0895961', '40028 GUILLERMO MERCADO BARROSO', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(255, '1031640', '40222 DIEGO THOMSON', 'Secundaria', 'Pública de gestión privada', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(256, '1204114', 'JHON HOPKINS', 'Secundaria', 'Privada', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(257, '1237346', 'ANDENES DE CHILINA', 'Secundaria', 'Privada', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(258, '1253863', 'NUESTRA SEÑORA DE GUADALUPE', 'Secundaria', 'Pública de gestión privada', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(259, '1253905', 'SAN JOSE OBRERO', 'Secundaria', 'Pública de gestión privada', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(260, '1253947', 'LEONCIO PRADO', 'Secundaria', 'Privada', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(261, '1254903', 'SAN JUAN APOSTOL', 'Secundaria', 'Privada', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(262, '1336866', 'D\'ANGELOUS CRISTI', 'Secundaria', 'Privada', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(263, '1336874', 'D\'ANGELOUS CRISTI', 'Básica Alternativa-Avanzado', 'Privada', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(264, '1336924', 'ANNA JARVIS', 'Secundaria', 'Privada', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(265, '1271931', '40003 SANTISIMA VIRGEN DEL CARMEN', 'Secundaria', 'Pública de gestión directa', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(266, '1337328', 'PABLO VALERY', 'Básica Alternativa', 'Privada', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(267, '1338615', 'ADOLFO KOLPING', 'Secundaria', 'Privada', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(268, '1338730', 'FEDERICO VILLARREAL', 'Secundaria', 'Privada', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(269, '1338854', 'WILLIAMS BRIDGMANN', 'Básica Alternativa-Avanzado', 'Privada', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(270, '1339399', 'THE PROVIDENCE SCHOOL', 'Secundaria', 'Privada', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(271, '1339480', 'PRONOE GARIBALDI', 'Secundaria de Adultos', 'Privada', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(272, '1339555', 'SAGRADOS CORAZONES DE JESUS Y MARIA', 'Secundaria', 'Privada', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(273, '1339621', 'SANTISIMA TRINIDAD', 'Secundaria', 'Privada', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(274, '1315340', 'PAEBA ALTO SELVA ALEGRE', 'Básica Alternativa', 'Pública de gestión directa', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(275, '1343474', 'TOMAS MARSANO', 'Secundaria', 'Privada', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(276, '1361112', '40029 LUDWING VAN BEETHOVEN', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(277, '1371384', 'CESAR VALLEJO', 'Secundaria', 'Privada', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(278, '1402478', 'MUNDO PACIFICO', 'Secundaria', 'Privada', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(279, '1419423', 'CEDEU JULIO RODRIGUEZ ENRIQUEZ', 'Secundaria', 'Privada', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(280, '1532852', 'JEROME BRUNER', 'Secundaria', 'Privada', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(281, '1532886', 'PINTO TALAVERA', 'Secundaria', 'Pública de gestión directa', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(282, '1721638', 'ADDISON', 'Secundaria', 'Privada', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(283, '1755529', 'JOULE ALTO SELVA ALEGRE', 'Secundaria', 'Privada', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(284, '1770262', '40029 LUDWING VAN BEETHOVEN', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(285, '1782846', 'EL MIRADOR AQP', 'Secundaria', 'Pública de gestión privada', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(286, '1787142', 'HEINRICH HERMANN ROBERT KOCH', 'Secundaria', 'Privada', '040102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(287, '0308130', 'MAYTA CAPAC', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(288, '0309260', 'HONORIO DELGADO ESPINOZA', 'Secundaria', 'Pública de gestión directa', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(289, '0309401', 'MAYTA CAPAC', 'Secundaria', 'Pública de gestión directa', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(290, '0309997', 'SAN FRANCISCO DE ASIS', 'Secundaria', 'Privada', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(291, '0310409', 'HONORIO DELGADO ESPINOZA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(292, '0526855', '40049', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(293, '0637306', 'LEON XIII', 'Secundaria', 'Pública de gestión privada', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(294, '0644971', 'MAYTA CAPAC', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(295, '0655811', 'LORD BYRON', 'Secundaria', 'Privada', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(296, '0749309', '40618', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(297, '0749317', '40081 CESAR AUGUSTO MAZEIRA ACOSTA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(298, '0749325', '40046 JOSE L. CORNEJO A.', 'Secundaria', 'Pública de gestión directa', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(299, '0898759', 'APARICIO SAICO', 'Secundaria', 'Privada', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(300, '0899039', '40052 EL PERUANO DEL MILENIO ALMIRANTE MIGUEL GRAU', 'Secundaria', 'Pública de gestión directa', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(301, '0899062', '40616 CASIMIRO CUADROS I', 'Secundaria', 'Pública de gestión directa', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(302, '0899096', 'EL PIONERO G-2', 'Secundaria', 'Pública de gestión privada', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(303, '1030188', 'SAN JOSE DE CALASANZ', 'Secundaria', 'Pública de gestión privada', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(304, '1116300', '40049 CORONEL FRANCISCO BOLOGNESI CERVANTES', 'Secundaria', 'Pública de gestión directa', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(305, '1119882', 'CRISTIAN BARNARD', 'Básica Alternativa-Avanzado', 'Privada', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(306, '1238591', 'REY DE REYES', 'Secundaria', 'Privada', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(307, '1262328', 'CRISTIAN BARNARD', 'Secundaria', 'Privada', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(308, '1272897', '40669 DEAN VALDIVIA', 'Secundaria', 'Pública de gestión directa', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(309, '1253988', 'JEAN PAUL SARTRE', 'Secundaria', 'Privada', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(310, '1334374', 'NIÑO JESUS EL TERREMOTITO', 'Secundaria', 'Privada', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(311, '1279256', '40040 JOSE TRINIDAD MORAN', 'Secundaria', 'Pública de gestión directa', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(312, '1335033', 'JAMES CLERK MAXWELL', 'Secundaria', 'Privada', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(313, '1339472', 'MENDEL CAYMA', 'Secundaria', 'Privada', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(314, '1308204', 'JOSE CARUANA', 'Secundaria', 'Pública de gestión privada', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(315, '1305770', 'THOMAS M.C. II', 'Secundaria', 'Privada', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(316, '1314616', 'SAN PABLO APOSTOL', 'Básica Alternativa-Avanzado', 'Pública de gestión privada', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(317, '1314624', 'SAN PABLO APOSTOL', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión privada', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(318, '1323898', 'NIÑO MAGISTRAL', 'Secundaria', 'Privada', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(319, '1343771', 'DIVINO CRISTO OBRERO', 'Secundaria', 'Privada', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(320, '1361146', 'HONORIO DELGADO ESPINOZA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(321, '1361880', 'EL MERCEDARIO RVDO PADRE ELEUTERIO ALARCON BEJARANO', 'Secundaria', 'Privada', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(322, '1369610', 'HOLY FAMILY SCHOOL', 'Secundaria', 'Privada', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(323, '1372416', 'SEÑOR DE LUREN', 'Secundaria', 'Privada', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(324, '1406347', 'MARIA MAZZARELLO', 'Secundaria', 'Privada', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(325, '1415967', 'SAN JUAN MASIAS', 'Secundaria', 'Privada', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(326, '1443860', 'SAN FERNANDO', 'Secundaria', 'Privada', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(327, '1622836', 'JOULE CAYMA', 'Secundaria', 'Privada', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(328, '1630490', 'JAN AMOS KOMENSKY', 'Secundaria', 'Privada', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(329, '1661149', 'JOHN G. LAKE', 'Secundaria', 'Pública de gestión privada', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(330, '1717479', '11 DE MAYO', 'Secundaria', 'Pública de gestión directa', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(331, '1724111', 'CRISTIAN BARNARD', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(332, '1751056', 'BALMER CAYMA', 'Secundaria', 'Privada', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(333, '1801216', 'MI DIVINO NIÑO JESUS', 'Secundaria', 'Pública de gestión directa', '040103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(334, '0308403', '40058 IGNACIO ALVAREZ THOMAS', 'Básica Alternativa', 'Pública de gestión directa', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(335, '0309310', 'NUESTRA SRA. DE LOS DOLORES', 'Secundaria', 'Pública de gestión directa', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(336, '0309435', '40054 JUAN DOMINGO ZAMACOLA Y JAUREGUI', 'Secundaria', 'Pública de gestión directa', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(337, '0310144', 'SAGRADO CORAZON', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(338, '0490565', '40054 JUAN DOMINGO ZAMACOLA Y JAUREGUI', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(339, '0562587', '40056 HORACIO ZEVALLOS GAMEZ', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(340, '0589200', '40055 ROMEO LUNA VICTORIA', 'Secundaria', 'Pública de gestión directa', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(341, '0589234', '40056 HORACIO ZEVALLOS GAMEZ', 'Secundaria', 'Pública de gestión directa', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(342, '0589291', '40054 JUAN DOMINGO ZAMACOLA Y JAUREGUI', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(343, '0612945', 'GRAN PACHACUTEC', 'Secundaria', 'Pública de gestión directa', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(344, '0641647', '40055 ROMEO LUNA VICTORIA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(345, '0641670', '40055 ROMEO LUNA VICTORIA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(346, '0655746', '40103 LIBERTADORES DE AMERICA', 'Secundaria', 'Pública de gestión directa', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(347, '0749358', '40058 IGNACIO ALVAREZ THOMAS', 'Secundaria', 'Pública de gestión directa', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(348, '0794420', 'JUAN DE LA CRUZ CALIENES', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(349, '0895763', 'VINCENT VAN GOGH', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(350, '0897728', 'CRISTO REY', 'Secundaria', 'Pública de gestión privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(351, '0897751', 'SEÑOR DE LAS PIEDADES', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(352, '0897785', 'STEPHEN HAWKING', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(353, '0897819', 'SANTO TOMAS DE AQUINO', 'Secundaria', 'Pública de gestión privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(354, '0899112', '41026 MARIA MURILLO DE BERNAL', 'Secundaria', 'Pública de gestión directa', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(355, '0899336', '40035 V. A. BELAUNDE', 'Secundaria', 'Pública de gestión directa', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(356, '0899369', 'CRISTO MORADO', 'Secundaria', 'Pública de gestión directa', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(357, '0899393', '40061 ESTADO DE SUECIA', 'Secundaria', 'Pública de gestión directa', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(358, '0899427', 'BRUNING HANS HEIMBRICH', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(359, '1032093', 'SAN IGNACIO DE JESUS', 'Básica Alternativa-Avanzado', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(360, '1116227', 'JAVIER HERAUD POETA', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(361, '1116664', 'VIRGEN DEL ROSARIO', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(362, '1120369', 'VIRGEN DEL ROSARIO', 'Básica Alternativa-Avanzado', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(363, '1260348', 'HIJOS DE MARIA AUXILIADORA', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(364, '1260660', 'BRITISH COLUMBIA', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(365, '1261189', '40670 EL EDEN FE Y ALEGRIA 51', 'Secundaria', 'Pública de gestión privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(366, '1261346', 'PERUANO - ITALIANA DOMINGO SAVIO', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(367, '1273499', 'SAN MIGUEL ARCANGEL', 'Secundaria de Adultos', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(368, '1333178', 'NUESTRA SRA. DE LA ASUNTA', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(369, '1333426', 'VIRGEN DEL ROSARIO', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(370, '1254267', 'SAN PEDRO CHANEL', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(371, '1333715', 'CRISTIANO ANGLO AMERICANO VENCEDOR', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(372, '1333947', 'SANTA RAFAELA MARIA', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(373, '1334283', 'PRONOE SEÑOR DE LAS PIEDADES', 'Secundaria de Adultos', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(374, '1334010', 'HUSARES DE JUNIN', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(375, '1334473', 'CASA DE CARIDAD ARTES Y OFICIOS', 'Secundaria', 'Pública de gestión privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(376, '1279652', 'SAN MATEO ANGLICAN SCHOOL SEDE ZAMACOLA', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(377, '1279017', 'ANGELUS', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(378, '1279132', 'SAN PIO X', 'Secundaria', 'Pública de gestión privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(379, '1279538', 'SEÑOR DE LA ESPERANZA', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(380, '1335025', 'PADRE SANTIAGO APOSTOL', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(381, '1335355', 'NANTERRE', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(382, '1259985', 'MENDEL CERRO COLORADO', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(383, '1281898', 'JUVENTUS', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(384, '1310739', 'MIGUEL DE CERVANTES SAAVEDRA', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(385, '1330158', 'ALBERTO WAGNER DE REINA', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(386, '1346667', 'SAN JUAN APOSTOL', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(387, '1363464', 'SAN JOSE DE COTTOLENGO', 'Secundaria', 'Pública de gestión privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `origin_schools` (`id`, `modular_code`, `name`, `d_niv_mod`, `management_type`, `ubigeo_code`, `created_at`, `updated_at`) VALUES
(388, '1369651', 'MADRE PEREGRINA SANTISIMA VIRGEN DE CHAPI', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(389, '1371095', 'JOSE LUIS BUSTAMANTE Y RIVERO', 'Secundaria', 'Pública de gestión directa', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(390, '1372390', 'JESUS MAESTRO', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(391, '1391929', 'IBEROAMERICANO', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(392, '1395367', '40677 SAN MIGUEL FEBRES CORDERO', 'Secundaria', 'Pública de gestión directa', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(393, '1415959', 'DE LA VIDA Y DE LA PAZ', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(394, '1431865', 'BALMER', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(395, '1437565', 'SANTA IMELDA', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(396, '1437557', 'BELEN', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(397, '1443894', 'W. BUSTINZA', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(398, '1538511', 'ST MICHAEL\'S SCHOOL', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(399, '1540947', 'NUESTRA SEÑORA DEL DIVINO AMOR', 'Secundaria', 'Pública de gestión privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(400, '1572346', 'MILAGROSA VIRGEN DEL CARMEN', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(401, '1572312', 'JOANNES PAULUS II', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(402, '1622745', 'PERCY GIBSON MOLLER', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(403, '1622778', 'JOHN FORBES', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(404, '1628767', 'SAN MAXIMILIANO KOLBE', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(405, '1629542', '40106 JUAN SANTOS ATAHUALPA', 'Secundaria', 'Pública de gestión directa', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(406, '1634187', 'GRECOS', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(407, '1636406', 'ANDRES DE SANTA CRUZ', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(408, '1639715', 'PEDRO LUIS GONZALES PASTOR', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(409, '1640937', 'FUTURA SCHOOLS CERRO COLORADO', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(410, '1642867', 'SEÑOR DE LA CAÑA', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(411, '1647130', '40705', 'Secundaria', 'Pública de gestión directa', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(412, '1650548', 'JOULE CERRO COLORADO', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(413, '1651488', 'CONO NORTE LINUS PAULING', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(414, '1651868', 'KINDER BLESSED', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(415, '1659085', 'SEÑOR DE YATO', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(416, '1659812', 'EL GRAN PODER DE JESUS', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(417, '1662949', 'SAN MARCOS - AREQUIPA', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(418, '1665314', 'SAN JOSE MARIA E.B.', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(419, '1672591', 'JUILLIARD', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(420, '1672625', 'LUIZ FRANCISCO FONTES', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(421, '1673060', 'SHERADON BRYCE SCIENCIE SCHOOL', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(422, '1673086', 'EL NAZARENO', 'Secundaria', 'Pública de gestión privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(423, '1673102', 'SEÑOR DE HUANCA', 'Secundaria', 'Pública de gestión privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(424, '1696475', 'LAS FLORES', 'Secundaria', 'Pública de gestión directa', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(425, '1703701', 'JOULE CIUDAD MUNICIPAL', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(426, '1705441', 'JOHN KEYNES', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(427, '1717461', 'APIPA SECTOR III', 'Secundaria', 'Pública de gestión directa', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(428, '1720101', 'SAN ESTEBAN', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(429, '1722156', 'NEWTON PERUARBO', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(430, '1729045', '40056 HORACIO ZEVALLOS GAMEZ', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(431, '1730134', 'TESORO DE JESUS', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(432, '1731397', 'MIGUEL ANGEL ASTURIAS SCHOOL', 'Básica Alternativa-Avanzado', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(433, '1748482', 'SAN FRANCISCO XAVIER', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(434, '1751320', 'ILUMINADORA VIRGEN DEL CARMEN', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(435, '1751841', 'INNOVA SCHOOLS - CERRO COLORADO', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(436, '1753847', 'MONTESSORI SCHOOL', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(437, '1755842', 'SABIO ALFRED BINET', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(438, '1756394', 'HOLY SCHOOLS', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(439, '1761188', 'SAN MATEO ANGLICAN SCHOOL SEDE ALTO LIBERTAD', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(440, '1761675', 'EUROAMERICANO DIVINO NIÑO JESUS', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(441, '1768506', 'EL EMPERADOR', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(442, '1770254', 'ALEXANDER FRIEDMAN', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(443, '1776129', 'DANIEL CHRISTIAN SCHOOL', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(444, '1777069', 'RONALD FISHER', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(445, '1777606', 'VIA 54 LINUS PAULING SEÑOR DE YATO', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(446, '1780865', 'BALMER JUVENTUS', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(447, '1784115', 'TALENT\'S SCHOOL AQP', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(448, '3001088', 'J. POINCARE', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(449, '1796192', 'LAS PLUMAS DEL AMAUTA', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(450, '1796564', 'JESUS DE NAZARETH', 'Secundaria', 'Pública de gestión privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(451, '1797398', 'LUCERITOS DE DIOS', 'Secundaria', 'Pública de gestión directa', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(452, '1798354', 'MIGUEL ANGEL ASTURIAS SCHOOL', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(453, '1798487', 'ANDREW ROBERT MILLIKAN', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(454, '1799105', 'SAN FRANCISCO DE SALES', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(455, '1799295', 'LEONARDO DA VINCI', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(456, '1799774', 'THOMAS M.C. III', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(457, '3026705', 'CLAUDIO GALENO', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(458, '3028412', 'DAVID AUSUBEL', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(459, '3040623', 'INTERNACIONAL JOHANNES KEPLER', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(460, '3043015', 'JOSE MARIA ARGUEDAS', 'Secundaria', 'Pública de gestión directa', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(461, '3047768', 'JAMES REICH', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(462, '3047818', 'PERUANO - ISRAELI KEDUSHA', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(463, '3050341', 'JESUS MAESTRO I', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(464, '3051109', 'EL PACIFICADOR LINUS PAULING', 'Secundaria', 'Privada', '040104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(465, '0309443', 'ANGEL FRANCISCO ALI GUILLEN', 'Secundaria', 'Pública de gestión directa', '040105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(466, '0896837', 'PABLO FREIRE', 'Secundaria', 'Privada', '040105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(467, '1525245', 'ITALO PERUANO ALESSANDRO VOLTA', 'Secundaria', 'Privada', '040105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(468, '1789874', 'SAN JOSE SCHOOL', 'Secundaria', 'Privada', '040105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(469, '3014404', 'JOULE - SOCABAYA', 'Secundaria', 'Privada', '040105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(470, '3025863', 'GRUPO JOULE', 'Secundaria', 'Privada', '040105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(471, '3050168', 'MATER DEI SCHOOL', 'Secundaria', 'Privada', '040105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(472, '0501189', '40127 SEÑOR DEL ESPIRITU SANTO', 'Secundaria', 'Pública de gestión directa', '040106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(473, '1337260', '40637 FERNANDO BELAUNDE TERRY', 'Secundaria', 'Pública de gestión directa', '040106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(474, '1799782', '40675 GRAL. VELAZCO ALVARADO', 'Secundaria', 'Pública de gestión directa', '040106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(475, '0308411', '40200 REPUBLICA FEDERAL ALEMANA', 'Básica Alternativa', 'Pública de gestión directa', '040107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(476, '0309492', 'JUAN PABLO VIZCARDO Y GUZMAN', 'Secundaria', 'Pública de gestión directa', '040107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(477, '0579748', '40200 REPUBLICA FEDERAL ALEMANA', 'Secundaria', 'Pública de gestión directa', '040107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(478, '0636217', 'SAN ANTONIO MARIA CLARET', 'Secundaria', 'Pública de gestión privada', '040107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(479, '0723353', 'MARIA DE LA MERCED', 'Secundaria', 'Pública de gestión privada', '040107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(480, '0794438', '40043 NUESTRA SEÑORA DE LA MEDALLA MILAGROSA', 'Secundaria', 'Pública de gestión directa', '040107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(481, '0794479', 'JUAN PABLO VIZCARDO Y GUZMAN', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(482, '0895060', 'ADVENTISTA EDUARDO FRANCISCO FORGA', 'Secundaria', 'Privada', '040107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(483, '0895094', 'HERMANO BLASTE MARIA', 'Secundaria', 'Privada', '040107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(484, '0895128', 'DIN DE JESUS', 'Secundaria', 'Privada', '040107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(485, '0895151', 'SANTA LUISA DE MARILLAC', 'Secundaria', 'Privada', '040107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(486, '0899294', '40033 SAN AGUSTIN DE HUNTER', 'Secundaria', 'Pública de gestión directa', '040107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(487, '1032226', 'JACOBO DICKSON HUNTER', 'Secundaria', 'Privada', '040107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(488, '1236108', 'PERUANO FRANCES JEAN HARZIC', 'Secundaria', 'Privada', '040107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(489, '1262047', 'CARL FRIEDRICH GAUSS', 'Secundaria', 'Privada', '040107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(490, '1270453', 'PRONOE JEAN HARZIC', 'Secundaria de Adultos', 'Privada', '040107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(491, '1271162', 'MARIANISTA', 'Secundaria', 'Privada', '040107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(492, '1271212', 'COLEGIO SAN MARCOS DE LEON', 'Secundaria', 'Privada', '040107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(493, '1277664', 'SAN ANDRES', 'Secundaria', 'Privada', '040107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(494, '1339514', 'PADRE MARTIN', 'Secundaria', 'Privada', '040107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(495, '1339969', 'HERBERT VON BLUMER', 'Secundaria', 'Privada', '040107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(496, '1344464', 'SAN GUILLERMO DE VERCELLI', 'Secundaria', 'Privada', '040107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(497, '1420470', 'JOHANN MENDEL', 'Secundaria', 'Privada', '040107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(498, '1455047', 'GRAN MAESTRO JUAN ENRIQUE PESTALOZZI', 'Secundaria', 'Privada', '040107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(499, '1455583', 'WILLIAM SHAKESPEARE', 'Secundaria', 'Privada', '040107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(500, '1465798', 'SANTO CATOLICO DON BOSCO', 'Secundaria', 'Privada', '040107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(501, '1534890', 'SANTA SOFIA', 'Secundaria', 'Privada', '040107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(502, '1536986', 'SAN PABLO DE TARSO', 'Secundaria', 'Privada', '040107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(503, '1577717', 'SCHOOL INTERNACIONAL BLUMER', 'Secundaria', 'Privada', '040107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(504, '1629849', 'SAN JUAN EUDES', 'Secundaria', 'Privada', '040107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(505, '1629856', 'SAN PEDRO DE ALCANTARA', 'Secundaria', 'Privada', '040107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(506, '1645514', 'MAXWELL', 'Secundaria', 'Privada', '040107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(507, '1664234', 'SAN PABLO AREQUIPA', 'Secundaria', 'Privada', '040107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(508, '1664259', 'MACITEC', 'Secundaria', 'Privada', '040107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(509, '1729052', 'JUAN PABLO VIZCARDO Y GUZMAN', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(510, '1770080', 'FERNANDO VARGAS RUIZ DE SOMOCURCIO', 'Secundaria', 'Privada', '040107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(511, '1801513', 'SANTA PAULA SCHOOL', 'Secundaria', 'Privada', '040107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(512, '0309542', 'CARLOS W. SUTTON', 'Secundaria', 'Pública de gestión directa', '040108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(513, '0501486', '40326 JUAN VELASCO ALVARADO', 'Secundaria', 'Pública de gestión directa', '040108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(514, '0517565', '40065 GLORIOSO HEROES DEL CENEPA', 'Secundaria', 'Pública de gestión directa', '040108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(515, '0517771', 'CARLOS W. SUTTON', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(516, '0617464', 'SGTO. 1ERO. FAP LAZARO ORREGO MORALES', 'Secundaria', 'Pública de gestión directa', '040108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(517, '0695320', 'EL CRUCE', 'Secundaria', 'Pública de gestión directa', '040108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(518, '0695346', 'CORONEL FAP CESAR FAURA GOUBET', 'Secundaria', 'Pública de gestión directa', '040108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(519, '0745976', '40096', 'Secundaria', 'Pública de gestión directa', '040108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(520, '0794487', 'CARLOS W. SUTTON', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(521, '0899278', 'RENE CAMACHO TARQUI', 'Secundaria', 'Pública de gestión directa', '040108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(522, '0899302', 'JESUS', 'Secundaria', 'Privada', '040108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(523, '1261429', 'CIENCIAS SEÑOR DE LA JOYA - JUAN ORELLANA GARCIA', 'Secundaria', 'Privada', '040108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(524, '1271659', '40353 SANTIAGO ANTUNEZ DE MAYOLO', 'Secundaria', 'Pública de gestión directa', '040108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(525, '1333350', 'PRONOE VIRGEN DE GUADALUPE', 'Secundaria de Adultos', 'Privada', '040108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(526, '1333079', 'SAN MARTIN DE PORRES', 'Secundaria de Adultos', 'Privada', '040108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(527, '1607191', 'NUESTRO SEÑOR DE LOS MILAGROS', 'Secundaria', 'Privada', '040108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(528, '1607258', 'SEÑOR DE LOS MILAGROS DE LA JOYA', 'Secundaria', 'Privada', '040108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(529, '1607340', 'SANTISIMA VIRGEN DE CHAPI', 'Secundaria', 'Privada', '040108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(530, '1607373', 'RENE CAMACHO TARQUI', 'Básica Alternativa', 'Pública de gestión directa', '040108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(531, '1341510', 'DIVINO REDENTOR', 'Secundaria', 'Privada', '040108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(532, '1379304', '40068', 'Secundaria', 'Pública de gestión directa', '040108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(533, '1410935', 'SAN FERNANDO', 'Secundaria', 'Privada', '040108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(534, '1544071', 'INTERNACIONAL', 'Básica Alternativa-Avanzado', 'Privada', '040108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(535, '1639970', 'THOMAS JEFFERSON', 'Básica Alternativa-Avanzado', 'Privada', '040108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(536, '1653591', 'EL TRIUNFO II', 'Secundaria', 'Pública de gestión directa', '040108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(537, '1658194', '40093', 'Secundaria', 'Pública de gestión directa', '040108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(538, '1663459', 'LA JOYA', 'Secundaria', 'Privada', '040108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(539, '1694116', 'J.N. ANDREWS - VILLA ESPERANZA', 'Secundaria', 'Privada', '040108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(540, '1719772', 'BRYCE LA JOYA', 'Secundaria', 'Privada', '040108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(541, '1719780', 'INTERNACIONAL SAN JUAN BAUTISTA', 'Secundaria', 'Privada', '040108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(542, '1726454', 'THOMAS JEFFERSON', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '040108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(543, '1747195', '40137 NUESTRA SEÑORA DE LA GLORIA', 'Secundaria', 'Pública de gestión directa', '040108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(544, '1748722', 'ADUNSA', 'Secundaria', 'Privada', '040108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(545, '1760438', 'RENE CAMACHO TARQUI', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(546, '3048592', 'EL PARAISO', 'Secundaria', 'Pública de gestión directa', '040108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(547, '0308197', '41031 MADRE DEL DIVINO AMOR', 'Básica Alternativa', 'Pública de gestión directa', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(548, '0309187', 'G.U.E. MARIANO MELGAR VALDIVIESO', 'Secundaria', 'Pública de gestión directa', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(549, '0309336', 'ANDREA VALDIVIESO DE MELGAR', 'Secundaria', 'Pública de gestión directa', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(550, '0310318', 'G.U.E. MARIANO MELGAR VALDIVIESO', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(551, '0516567', '40129 MANUEL VERAMENDI E HIDALGO', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(552, '0579607', '40129 MANUEL VERAMENDI E HIDALGO', 'Secundaria', 'Pública de gestión directa', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(553, '0579623', 'PIO XII', 'Secundaria', 'Pública de gestión privada', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(554, '0617209', 'CORAZON DE JESUS', 'Secundaria', 'Pública de gestión privada', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(555, '0636019', 'RAFAEL LOAYZA GUEVARA', 'Secundaria', 'Pública de gestión directa', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(556, '0750125', '40139 ANDRES AVELINO CACERES DORREGARAY', 'Secundaria', 'Pública de gestión directa', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(557, '0794529', '40129 MANUEL VERAMENDI E HIDALGO', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(558, '0895185', 'BELEN DEL NIÑO JESUS', 'Secundaria', 'Privada', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(559, '0895219', 'CIENCIAS', 'Secundaria', 'Privada', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(560, '0895243', 'CRISTO SALVADOR', 'Secundaria', 'Privada', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(561, '0895300', 'INCA GARCILAZO DE LA VEGA', 'Secundaria', 'Privada', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(562, '0895979', 'RAFAEL LOAYZA GUEVARA', 'Secundaria de Adultos', 'Pública de gestión directa', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(563, '1031186', 'PRONOE MARIANO MELGAR', 'Secundaria de Adultos', 'Privada', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(564, '1236140', 'WALTER PEÑALOZA RAMELLA', 'Secundaria', 'Privada', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(565, '1254101', 'IBEROAMERICANO', 'Secundaria', 'Privada', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(566, '1336908', 'NIÑA MARIA - SAINT JHON\'S', 'Secundaria', 'Privada', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(567, '1337146', 'MI PEQUEÑO REYNO SAN LORENZO', 'Secundaria', 'Privada', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(568, '1274315', 'SHAMMAH', 'Secundaria', 'Privada', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(569, '1337740', 'EDWIN ALEXANDER', 'Secundaria', 'Privada', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(570, '1361088', 'RAFAEL LOAYZA GUEVARA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(571, '1361096', 'G.U.E. MARIANO MELGAR VALDIVIESO', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(572, '1437615', 'PRINCETON SCHOOL', 'Secundaria', 'Privada', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(573, '1465715', 'GABRIEL CRAMER', 'Secundaria', 'Privada', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(574, '1525278', 'GARCIA LORCA', 'Básica Alternativa-Avanzado', 'Privada', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(575, '1532928', 'SAN IGNACIO', 'Secundaria', 'Pública de gestión privada', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(576, '1577675', 'PREMIO NOBEL MARIO VARGAS LLOSA', 'Secundaria', 'Privada', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(577, '1577667', 'MUNDO MAGICO', 'Secundaria', 'Privada', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(578, '1628221', 'NOVA SCHOOL', 'Secundaria', 'Privada', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(579, '1642156', 'DENYORK SCHOOL', 'Secundaria', 'Privada', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(580, '1642313', 'LEON NEIL COOPER', 'Secundaria', 'Privada', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(581, '1649656', 'NOVA STEINER', 'Secundaria', 'Privada', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(582, '1651884', 'DENYORK ALTERNATIVE', 'Básica Alternativa-Avanzado', 'Privada', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(583, '1659820', 'DIDASCALIO SAN JOSE', 'Secundaria', 'Privada', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(584, '1695402', 'JOULE SAN PABLO', 'Secundaria', 'Privada', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(585, '1726538', 'DENYORK ALTERNATIVE', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(586, '1734904', 'ELOHIM', 'Secundaria', 'Privada', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(587, '1739309', 'BRYCE MARIANO MELGAR', 'Secundaria', 'Privada', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(588, '1764844', 'SAN FRANCISCO JAVIER - CIRCA', 'Secundaria', 'Pública de gestión privada', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(589, '1765544', 'ALEXANDER GRAHAM BELL', 'Secundaria', 'Privada', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(590, '3029790', 'DEEVY COLLEGE\'S', 'Secundaria', 'Privada', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(591, '1802024', 'PALMER DE MARIANO MELGAR', 'Secundaria', 'Privada', '040109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(592, '1334119', 'ADVANCE', 'Secundaria', 'Privada', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(593, '1279777', 'ENRICO FERMI', 'Secundaria', 'Privada', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(594, '0309526', 'FRANCISCO JAVIER DE LUNA PIZARRO', 'Secundaria', 'Pública de gestión directa', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(595, '0490656', 'ANDRES AVELINO CACERES', 'Secundaria de Adultos', 'Pública de gestión directa', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(596, '0501585', 'TUPAC AMARU', 'Secundaria', 'Pública de gestión directa', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(597, '0526459', '40144 AUGUSTO SALAZAR BONDY', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(598, '0569566', '41037 JOSE GALVEZ', 'Secundaria', 'Pública de gestión directa', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(599, '0579565', '40158 EL GRAN AMAUTA', 'Secundaria', 'Pública de gestión directa', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(600, '0579573', '40159 EJERCITO AREQUIPA', 'Secundaria', 'Pública de gestión directa', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(601, '0617159', '40144 AUGUSTO SALAZAR BONDY', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(602, '0636514', 'SANTA TERESITA DE LISIEUX', 'Secundaria', 'Privada', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(603, '0695379', '41037 JOSE GALVEZ', 'Secundaria de Adultos', 'Pública de gestión directa', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(604, '0745729', 'CIRO ALEGRIA', 'Secundaria', 'Privada', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(605, '0794305', '40158 EL GRAN AMAUTA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(606, '0794503', 'TUPAC AMARU', 'Secundaria de Adultos', 'Pública de gestión directa', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(607, '0895367', 'ANGELO PATRI', 'Secundaria', 'Privada', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(608, '0895391', 'MINNE MARTE', 'Secundaria', 'Privada', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(609, '0895425', 'MIRAFLORES', 'Secundaria', 'Privada', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(610, '0895458', 'SAN ANTONIO', 'Secundaria', 'Privada', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(611, '1031087', 'PRONOE RICARDO PALMA', 'Secundaria de Adultos', 'Privada', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(612, '1031111', 'SAN JOSE', 'Secundaria de Adultos', 'Privada', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(613, '1031806', 'GEORGE WASHINGTON', 'Secundaria', 'Privada', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(614, '1118991', '40158 EL GRAN AMAUTA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(615, '1119551', 'JUAN GREGORIO MENDEL', 'Secundaria', 'Privada', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(616, '1237742', 'SAN ANTONIO', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(617, '1237783', 'SAN ANTONIO', 'Básica Alternativa-Avanzado', 'Privada', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(618, '1239672', 'GEORGE WASHINGTON', 'Básica Alternativa-Avanzado', 'Privada', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(619, '1254028', 'SAN MIGUEL ARCANGEL', 'Secundaria', 'Privada', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(620, '1262401', 'ANDRES RAZURI', 'Secundaria de Adultos', 'Privada', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(621, '1337963', 'HERMANO FERMIN LUIS DE LA SALLE', 'Secundaria', 'Privada', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(622, '1338243', 'SALOMON', 'Secundaria', 'Privada', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(623, '1338482', 'KINDERGARTEN', 'Secundaria', 'Privada', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(624, '1338516', 'CRISTOPHER', 'Secundaria', 'Privada', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(625, '1338862', 'REINA DE LOS ANGELES', 'Secundaria', 'Privada', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(626, '1117456', 'LA CANTUTA DE AREQUIPA', 'Secundaria', 'Privada', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(627, '1339142', 'EMANUEL CHRISTIAN SCHOOL', 'Secundaria', 'Privada', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(628, '1361104', '41037 JOSE GALVEZ', 'Básica Alternativa', 'Pública de gestión directa', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(629, '1361138', 'ANDRES AVELINO CACERES', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(630, '1368364', 'JESUS ES MI SEÑOR', 'Secundaria', 'Privada', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(631, '1371442', 'INTERNACIONAL ELIM', 'Secundaria', 'Privada', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(632, '1375435', 'SAN ALEJANDRO', 'Básica Alternativa-Avanzado', 'Privada', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(633, '1455575', 'FRANCIS COLLINS SCHOOL', 'Secundaria', 'Privada', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(634, '1465756', 'JOULMER', 'Secundaria', 'Privada', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(635, '1466234', 'DE FOMENTO ANDERS CELSIUS', 'Secundaria', 'Privada', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(636, '1532936', 'LORD KARMEL', 'Secundaria', 'Privada', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(637, '1645548', 'SANTISIMO NIÑO DE MARIA', 'Secundaria', 'Privada', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(638, '1651918', 'CORPUS CHRISTI', 'Secundaria', 'Privada', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(639, '1691625', 'MERE DU CHRIST', 'Secundaria', 'Privada', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(640, '1695360', 'JOULE MIRAFLORES', 'Secundaria', 'Privada', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(641, '1698778', 'CARLOS DARWIN', 'Secundaria', 'Privada', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(642, '1789684', 'INNOVA SCHOOLS - AREQUIPA MIRAFLORES', 'Secundaria', 'Privada', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(643, '3000791', 'CORPUS CHRISTI SCHOOL', 'Secundaria', 'Privada', '040110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(644, '0899328', '40160 OBDULIO BARRIGA VIZCARRA', 'Secundaria', 'Pública de gestión directa', '040111', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(645, '0308445', '40174 PAOLA FRASSINETTI', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(646, '0309518', 'JOSE TEOBALDO PAREDES VALDEZ', 'Secundaria', 'Pública de gestión directa', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(647, '0310185', 'JESUS NAZARENO', 'Secundaria', 'Pública de gestión privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(648, '0477612', 'JOSE TEOBALDO PAREDES VALDEZ', 'Secundaria de Adultos', 'Pública de gestión directa', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(649, '0516765', '40180 JESUS MARIA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(650, '0516963', '40164 JOSE CARLOS MARIATEGUI', 'Secundaria', 'Pública de gestión directa', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(651, '0525725', 'NEPTALI VALDERRAMA AMPUERO', 'Secundaria', 'Pública de gestión directa', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(652, '0579599', '40010 JULIO C.TELLO', 'Secundaria', 'Pública de gestión directa', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(653, '0579615', '40178 VICTOR RAUL HAYA DE LA TORRE', 'Secundaria', 'Pública de gestión directa', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(654, '0579631', '40009 SAN MARTIN DE PORRES', 'Secundaria', 'Pública de gestión privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(655, '0579672', '40177 DIVINO CORAZON DE JESUS', 'Secundaria', 'Pública de gestión directa', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(656, '0579698', '40163 BENIGNO BALLON FARFAN', 'Secundaria', 'Pública de gestión directa', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(657, '0579706', '40161 MONSEÑOR JOSE L. DEL CARPIO', 'Secundaria', 'Pública de gestión directa', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(658, '0617183', '40174 PAOLA FRASSINETTI', 'Secundaria', 'Pública de gestión privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(659, '0617191', '40315 JOSE MARIA ARGUEDAS', 'Secundaria', 'Pública de gestión directa', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(660, '0617217', 'JUAN XXIII', 'Secundaria', 'Pública de gestión privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(661, '0617233', 'NUESTRA SEÑORA DE LOURDES', 'Secundaria', 'Pública de gestión privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(662, '0635987', 'VIRGEN DE CHAPI', 'Secundaria', 'Pública de gestión privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(663, '0695221', 'BEN CARSON', 'Secundaria', 'Privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(664, '0695262', 'SANTA ROSA DE LIMA', 'Secundaria', 'Pública de gestión privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(665, '0723486', '40211 HEROES DEL PACIFICO', 'Secundaria', 'Pública de gestión directa', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(666, '0794362', '40010 JULIO C.TELLO', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(667, '0794370', 'JOSE TEOBALDO PAREDES VALDEZ', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(668, '0794495', '40164 JOSE CARLOS MARIATEGUI', 'Secundaria de Adultos', 'Pública de gestión directa', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(669, '0794511', '40010 JULIO C. TELLO', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(670, '0895516', '40220 HEROES DEL CENEPA', 'Secundaria', 'Pública de gestión directa', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(671, '0895540', 'DIVINA FAMILIA', 'Secundaria', 'Privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(672, '0895607', 'NUESTRA SEÑORA DE COPACABANA', 'Secundaria', 'Pública de gestión privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(673, '0895664', 'NUESTRA SRA.DE LA PROVIDENCIA', 'Secundaria', 'Privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(674, '0895698', 'SOR ANA DE LOS ANGELES MONTEAGUDO', 'Secundaria', 'Privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(675, '0895755', 'SOR ANA', 'Secundaria', 'Pública de gestión privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(676, '0895789', 'SANTA MARIA DE BELEN', 'Secundaria', 'Privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(677, '0895813', 'SANTA MARIA DE LA PAZ', 'Secundaria', 'Pública de gestión privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(678, '0896001', '40174 PAOLA FRASSINETTI', 'Básica Alternativa-Avanzado', 'Pública de gestión privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(679, '0899237', '40300 MIGUEL GRAU', 'Secundaria', 'Pública de gestión directa', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(680, '1031145', 'JULIO VERNE', 'Básica Alternativa', 'Privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(681, '1031707', 'PAULO VI', 'Secundaria', 'Pública de gestión privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(682, '1032184', 'SAN PEDRO Y SAN PABLO', 'Secundaria', 'Pública de gestión privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(683, '1117894', '40185 SAN JUAN BAUTISTA DE JESUS', 'Secundaria', 'Pública de gestión directa', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(684, '1119049', '40178 VICTOR RAUL HAYA DE LA TORRE', 'Secundaria de Adultos', 'Pública de gestión directa', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(685, '1119072', 'LATINOAMERICANO', 'Secundaria', 'Privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(686, '1120005', 'PADRE PEREZ DE GUEREÑU', 'Secundaria', 'Pública de gestión directa', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(687, '1259464', 'PRONOE SAN JOSE NIÑO', 'Secundaria de Adultos', 'Privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(688, '1270537', 'JUAN JACOBO ROUSSEAU', 'Secundaria', 'Privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(689, '1270800', 'HANS CHRISTIAN ANDERSEN', 'Secundaria', 'Privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(690, '1271683', 'ALMIRANTE GRAU', 'Secundaria', 'Privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(691, '1336957', 'SAN SEBASTIAN', 'Secundaria', 'Privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(692, '1337856', 'VIRGEN DEL CARMEN', 'Secundaria', 'Privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(693, '1337872', 'SAN IGNACIO EDUCADOR', 'Secundaria', 'Privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(694, '1274182', 'AMANECER', 'Secundaria', 'Privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(695, '1338920', 'MARKKHAN', 'Secundaria', 'Privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(696, '1274109', 'PRONOE AMANECER', 'Secundaria de Adultos', 'Privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(697, '1339613', '40163 BENIGNO BALLON FARFAN', 'Secundaria de Adultos', 'Pública de gestión directa', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(698, '1361120', '40696 SANTA MARIA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(699, '1375450', 'SAN ANTONIO DE PADUA', 'Secundaria', 'Privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(700, '1463694', 'GRAN PADRE AMADO', 'Secundaria', 'Privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(701, '1577279', 'THOMAS A. EDISON SCHOOL', 'Secundaria', 'Privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(702, '1577592', 'AGUEDA VIGNES', 'Secundaria', 'Privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(703, '1634252', 'MARIA DEL REDENTOR', 'Secundaria', 'Pública de gestión privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(704, '1654136', 'PALMER', 'Secundaria', 'Privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(705, '1668771', 'COAR AREQUIPA', 'Secundaria', 'Pública de gestión directa', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(706, '1700087', 'ADONAI INTERNACIONAL', 'Secundaria', 'Privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(707, '1721026', 'ROBERT F. KENNEDY', 'Secundaria', 'Privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(708, '1721588', 'JOULE DIVINO NIÑO', 'Secundaria', 'Privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(709, '1729607', '40696 SANTA MARIA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(710, '1734060', 'FUTURA SCHOOLS PAUCARPATA', 'Secundaria', 'Privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(711, '1755537', 'AMERICAN SCHOOL', 'Secundaria', 'Privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(712, '1770270', 'NUESTRA SEÑORA DE LOS INFANTES SCHOOL', 'Secundaria', 'Privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(713, '1777820', 'SANTA MARIA MADRE SCHOOL', 'Secundaria', 'Privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(714, '1790161', 'DEL SOLAR', 'Secundaria', 'Privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(715, '1797067', 'DE CIENCIAS Y HUMANIDADES LORD KELVIN BUSTAMANTE', 'Secundaria', 'Privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(716, '1799303', 'ECOSCHOOL', 'Secundaria', 'Privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(717, '1802123', 'JOSE LUIS BUSTAMANTE SCHOOL', 'Secundaria', 'Privada', '040112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(718, '0794404', '40188', 'Secundaria', 'Pública de gestión directa', '040113', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(719, '0579722', '40190 SANTISIMA VIRGEN DE CHAPI', 'Secundaria', 'Pública de gestión directa', '040114', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(720, '0579664', '40193 FLORENTINO PORTUGAL', 'Secundaria', 'Pública de gestión directa', '040116', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(721, '0310177', 'PERUANO ALEMAN MAX UHLE', 'Secundaria', 'Privada', '040117', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(722, '0498782', '40075 HORACIO MORALES DELGADO', 'Secundaria', 'Pública de gestión directa', '040117', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(723, '0498881', '40074 JOSE L. BUSTAMANTE Y RIVERO', 'Secundaria', 'Pública de gestión directa', '040117', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(724, '0899120', '40079 VICTOR NUÑEZ VALENCIA', 'Secundaria', 'Pública de gestión directa', '040117', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(725, '1031038', 'PRONOE MAGISTER LAGRANGE', 'Secundaria de Adultos', 'Privada', '040117', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(726, '1031046', 'MAGISTER LAGRANGE', 'Básica Alternativa', 'Privada', '040117', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(727, '1239128', 'EL MILAGRO DE FATIMA', 'Secundaria', 'Pública de gestión privada', '040117', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(728, '1273705', 'PEDRO PABLO RUBENS', 'Secundaria', 'Privada', '040117', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(729, '0894824', 'GRAN PADRE SAN AGUSTIN', 'Secundaria', 'Privada', '040117', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(730, '1334051', 'MATER GRATIAE', 'Secundaria', 'Privada', '040117', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(731, '1333905', 'FRANCO PERUANO GUSTAVE EIFFEL', 'Secundaria', 'Privada', '040117', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(732, '1281971', 'MENDEL', 'Secundaria', 'Privada', '040117', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(733, '1340249', 'LA FAYETTE', 'Secundaria', 'Privada', '040117', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(734, '1364892', 'BRITANICO EUROPEO DUNALASTAIR', 'Secundaria', 'Privada', '040117', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(735, '1338763', 'BENJAMIN FRANKLIN', 'Secundaria', 'Privada', '040117', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(736, '1417542', 'ANGLO AMERICANO VICTOR GARCIA HOZ', 'Secundaria', 'Privada', '040117', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(737, '1731991', 'SANTA ANA', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '040117', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(738, '1732163', 'SANTA ANA', 'Básica Alternativa-Avanzado', 'Privada', '040117', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(739, '1761667', 'LIFE SCHOOL', 'Secundaria', 'Privada', '040117', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(740, '1768159', 'INNOVA SCHOOLS SACHACA', 'Secundaria', 'Privada', '040117', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(741, '1770783', 'VIRGEN MORENA DE JASNA GORA', 'Secundaria', 'Privada', '040117', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(742, '0579516', '40072', 'Secundaria', 'Pública de gestión directa', '040118', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(743, '0579656', '40196 TECNICO AGROPECUARIO ARTESANAL', 'Secundaria', 'Pública de gestión directa', '040119', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(744, '0895847', '40217 VIRGEN DE LA ASUNTA', 'Secundaria', 'Pública de gestión directa', '040119', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(745, '1577584', '40216 VIRGEN DEL ROSARIO DE HUAYLLACUCHO', 'Secundaria', 'Pública de gestión directa', '040119', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(746, '0579755', '40187', 'Secundaria', 'Pública de gestión directa', '040120', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(747, '0899153', '40070 SANTA ISABEL DE HUNGRIA', 'Secundaria', 'Pública de gestión directa', '040120', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(748, '0617456', 'SANTA RITA DE SIGUAS', 'Secundaria', 'Pública de gestión directa', '040121', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(749, '1261726', 'SANTISIMO NIÑO JESUS SCHOOL', 'Secundaria', 'Privada', '040121', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(750, '1333418', 'PRONOE NUESTRA SRA. DE CHAPI', 'Secundaria de Adultos', 'Privada', '040121', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(751, '1665355', 'SAN MARCOS', 'Básica Alternativa-Avanzado', 'Privada', '040121', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(752, '1726702', 'SAN MARCOS', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '040121', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(753, '1749910', 'NUEVA JUVENTUD', 'Secundaria', 'Pública de gestión directa', '040121', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(754, '0309468', 'SAN MARTIN DE SOCABAYA', 'Secundaria', 'Pública de gestión directa', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(755, '0579649', '40197 FELIPE SANTIAGO SALAVERRY', 'Secundaria', 'Pública de gestión directa', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(756, '0610683', '40204 NESTOR CACERES VELASQUEZ', 'Secundaria', 'Pública de gestión directa', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(757, '0617290', '40208 PADRE FRANCOIS DELATTE', 'Secundaria', 'Pública de gestión privada', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(758, '0695270', '40256 CARLOS MANCHEGO RENDON', 'Secundaria', 'Pública de gestión directa', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(759, '0695296', 'SAN LUIS GONZAGA', 'Secundaria', 'Pública de gestión privada', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(760, '0745943', '40199', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(761, '0895870', 'FRAY MARTIN', 'Secundaria', 'Privada', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(762, '0895904', 'MARIA IANUA COELI', 'Secundaria', 'Privada', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(763, '0895938', 'SAN BENITO DE PALERMO', 'Secundaria', 'Privada', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(764, '0899260', 'HIJOS DE DIOS', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(765, '1031582', '40172', 'Secundaria', 'Pública de gestión directa', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(766, '1117571', 'CIENCIAS LEONARD EULER', 'Secundaria', 'Privada', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(767, '1117696', 'OBO J. WATT', 'Secundaria', 'Privada', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(768, '1259381', 'LA CAMPIÑA', 'Secundaria', 'Pública de gestión directa', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `origin_schools` (`id`, `modular_code`, `name`, `d_niv_mod`, `management_type`, `ubigeo_code`, `created_at`, `updated_at`) VALUES
(769, '1262120', 'SANTA MARIA DE FATIMA', 'Secundaria', 'Privada', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(770, '1272574', 'PRONOE FRAY MARTIN', 'Secundaria de Adultos', 'Privada', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(771, '1336726', 'MIGUEL ANGEL', 'Básica Alternativa-Avanzado', 'Privada', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(772, '1337088', 'THALES DE MILETO', 'Secundaria', 'Privada', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(773, '0310441', '40205 MANUEL BENITO LINARES A.', 'Secundaria', 'Pública de gestión directa', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(774, '1338839', 'JOSE ANTONIO ENCINAS', 'Secundaria', 'Privada', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(775, '1327824', 'EL GRAN MAESTRO', 'Secundaria', 'Pública de gestión directa', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(776, '1343417', 'HIJOS DE DIOS', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(777, '1344514', 'SANTILLANA', 'Secundaria', 'Privada', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(778, '1354653', 'CORAZON DE ORO', 'Secundaria', 'Privada', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(779, '1368372', 'SANTISIMO SALVADOR', 'Secundaria', 'Privada', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(780, '1371392', 'DIVINA PROVIDENCIA', 'Secundaria', 'Pública de gestión privada', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(781, '1419415', 'ERNEST RUTHERFORD', 'Secundaria', 'Privada', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(782, '1455567', 'PATER NOSTER', 'Secundaria', 'Privada', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(783, '1525344', 'STANFORD MOORE', 'Secundaria', 'Privada', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(784, '1620236', '40221 CORAZON DE JESUS', 'Secundaria', 'Pública de gestión directa', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(785, '1656644', 'SEÑOR DEL GRAN PODER', 'Secundaria', 'Privada', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(786, '1663871', 'ALEJANDRO BULLON PAUCAR', 'Secundaria', 'Privada', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(787, '1667286', 'SAN FERNANDO REY', 'Secundaria', 'Privada', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(788, '1731785', 'VILLARREAL', 'Secundaria', 'Privada', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(789, '1751338', 'VIRGEN DEL CARMEN GLORIOSA', 'Básica Alternativa-Avanzado', 'Privada', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(790, '1757178', 'MI AMIGO NIÑO JESUS', 'Secundaria', 'Privada', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(791, '1757194', 'LUZ DE ESPERANZA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(792, '1757236', 'SANTA ANNA', 'Básica Alternativa-Avanzado', 'Pública de gestión privada', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(793, '1757343', 'LUZ DE ESPERANZA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(794, '1767441', 'SANTA MARIA MAZZARELLO', 'Secundaria', 'Privada', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(795, '1779024', 'SANTA ANNA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión privada', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(796, '1790690', 'EDWARD NORTON', 'Secundaria', 'Privada', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(797, '1799741', 'KAROL JOSEF WOJTYLA', 'Secundaria', 'Privada', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(798, '1801919', 'SANTISIMA VIRGEN DE CHAPI', 'Secundaria', 'Privada', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(799, '3050218', 'DIVINO NIÑO DE BELEN', 'Secundaria', 'Privada', '040122', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(800, '0309500', 'FRANCISCO MOSTAJO', 'Secundaria', 'Pública de gestión directa', '040123', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(801, '0517466', 'PEDRO PAULET MOSTAJO', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040123', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(802, '0636605', 'PEDRO PAULET MOSTAJO', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040123', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(803, '0750083', 'CARLOS JOSE ECHAVARRY OSACAR', 'Secundaria', 'Pública de gestión directa', '040123', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(804, '0899187', '40083 FRANKLIN ROOSEVELT', 'Secundaria', 'Pública de gestión directa', '040123', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(805, '1116185', 'SAN IGNACIO DE LOYOLA', 'Secundaria', 'Privada', '040123', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(806, '1335231', 'DE CIENCIAS DIVINO NIÑO JESUS', 'Secundaria', 'Privada', '040123', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(807, '1238435', 'JESUS DIVINO NIÑO', 'Secundaria', 'Privada', '040123', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(808, '1282292', 'CRISTO SALVADOR DE MONTECARLO', 'Secundaria', 'Privada', '040123', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(809, '1618917', 'JUAN PABLO MAGNO', 'Secundaria', 'Privada', '040123', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(810, '1640903', 'FUTURA SCHOOLS TIABAYA', 'Secundaria', 'Privada', '040123', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(811, '1790344', 'PERUANO FRANCES ANTOINE DE SAINT EXUPERY', 'Secundaria', 'Privada', '040123', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(812, '3049384', 'SAN JOSE SANCHEZ DEL RIO', 'Secundaria', 'Privada', '040123', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(813, '0579730', '40092 JOSE D. ZUZUNAGA OBANDO', 'Secundaria', 'Pública de gestión directa', '040124', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(814, '0619361', '40088 REYNO DE BELGICA', 'Secundaria', 'Pública de gestión directa', '040124', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(815, '1273465', 'ALVAREZ THOMAS', 'Secundaria de Adultos', 'Privada', '040124', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(816, '1333665', 'SANTA MARIA DEL VALLE', 'Secundaria', 'Privada', '040124', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(817, '1515311', 'SANTIAGO RAMON Y CAJAL', 'Secundaria', 'Privada', '040124', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(818, '1530393', 'CEDEU PITAGORAS SCHOOL', 'Secundaria', 'Privada', '040124', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(819, '1583137', '40091 ALMA MATER DE CONGATA', 'Secundaria', 'Pública de gestión directa', '040124', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(820, '3047594', 'DIOS ES AMOR', 'Secundaria', 'Privada', '040124', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(821, '0309724', 'VICTOR RAUL HAYA DE LA TORRE', 'Secundaria', 'Pública de gestión directa', '040125', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(822, '1575992', 'JESUS BENAVIDES MOSCOSO', 'Secundaria', 'Privada', '040125', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(823, '0310128', 'NUESTRA SRA. DE LA MERCED', 'Secundaria', 'Privada', '040126', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(824, '0310151', 'INTERNACIONAL PERUANO BRITANICO', 'Secundaria', 'Privada', '040126', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(825, '0636548', 'CHAMPAGNAT', 'Secundaria', 'Privada', '040126', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(826, '0637249', '40048 ANTONIO JOSE DE SUCRE', 'Secundaria', 'Pública de gestión directa', '040126', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(827, '0894790', 'GEORGE BOOLE', 'Secundaria', 'Privada', '040126', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(828, '0898932', 'VANCOUVER', 'Secundaria', 'Privada', '040126', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(829, '0899211', '40039 SANTA MARIA', 'Secundaria', 'Pública de gestión directa', '040126', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(830, '1117290', '40048 ANTONIO JOSE DE SUCRE', 'Básica Alternativa', 'Pública de gestión directa', '040126', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(831, '1203843', 'BALMER JESUCRISTO REY', 'Secundaria', 'Privada', '040126', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(832, '1261064', 'NORTH AMERICAN COLLEGE', 'Secundaria', 'Privada', '040126', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(833, '1367184', 'HEFZIBA', 'Secundaria', 'Privada', '040126', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(834, '1338466', 'WORLD SCHOOL', 'Secundaria', 'Privada', '040126', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(835, '1531227', 'AMERICO GARIBALDI', 'Secundaria', 'Privada', '040126', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(836, '1730191', 'LAUNCESTON', 'Secundaria', 'Privada', '040126', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(837, '0309450', 'MONSEÑOR LEONIDAS BERNEDO MALAGA', 'Secundaria', 'Pública de gestión directa', '040127', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(838, '0655795', 'SAN BERNARDO', 'Secundaria', 'Pública de gestión privada', '040128', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(839, '0749366', 'JORGE SANJINEZ LENZ', 'Secundaria', 'Pública de gestión directa', '040128', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(840, '0794412', 'SEÑOR DE LOS MILAGROS', 'Secundaria', 'Pública de gestión privada', '040128', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(841, '1260942', '40202 CHARLOTTE', 'Secundaria', 'Pública de gestión directa', '040128', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(842, '1334663', 'SOLARIS', 'Secundaria', 'Privada', '040128', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(843, '1278571', 'THOMAS M.C.', 'Secundaria', 'Privada', '040128', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(844, '1379395', 'CPED - 40101 DIVINO JESUS', 'Secundaria', 'Pública de gestión directa', '040128', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(845, '1434737', 'MUNICIPAL AREQUIPA', 'Básica Alternativa-Avanzado', 'Privada', '040128', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(846, '1643246', '40694 CENTRO DE INNOVACION PEDAGOGICA ISPPA', 'Secundaria', 'Pública de gestión privada', '040128', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(847, '1692060', 'PIEDADES DE CIUDAD DE DIOS', 'Secundaria', 'Privada', '040128', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(848, '1717487', 'ALTIPLANO', 'Secundaria', 'Pública de gestión directa', '040128', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(849, '1725241', 'MUNICIPAL AREQUIPA', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '040128', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(850, '1750850', 'JOHANNES KEPLER', 'Secundaria', 'Privada', '040128', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(851, '1755180', 'COLEGIO SUPERIOR DE CIENCIAS NEWTON VIANNEY', 'Secundaria', 'Privada', '040128', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(852, '1769041', 'HOWARD GARDNER SCHOOL', 'Secundaria', 'Privada', '040128', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(853, '1769603', '40101 DIVINO JESUS', 'Secundaria', 'Pública de gestión directa', '040128', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(854, '3000387', 'GRAN UNIDAD ESCOLAR INCA TARCO HUAMAN - CONO NORTE', 'Secundaria', 'Pública de gestión directa', '040128', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(855, '1794577', 'SANTO TORIBIO DE MOGROVEJO', 'Secundaria', 'Pública de gestión privada', '040128', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(856, '1799857', 'REAL SCHOOL C.A.M', 'Secundaria', 'Privada', '040128', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(857, '1800838', 'SAN FRANCISCO DE CIUDAD DE DIOS', 'Secundaria', 'Privada', '040128', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(858, '3042579', 'CLEMENTS MARKHAM', 'Secundaria', 'Privada', '040128', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(859, '3043007', 'JARDIN DEL COLCA', 'Secundaria', 'Pública de gestión directa', '040128', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(860, '3044534', 'MAYOR JOSE ANTONIO ENCINAS', 'Secundaria', 'Privada', '040128', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(861, '0310219', 'COLEGIO PARTICULAR MIXTO SANTA CLARA', 'Secundaria', 'Privada', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(862, '0745935', 'ST. ANDREW', 'Secundaria', 'Privada', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(863, '0895722', 'PAMER AREQUIPA', 'Secundaria', 'Privada', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(864, '0899419', 'ESPECIAL DE RAPIDO APRENDIZAJE ALFRED BINET', 'Secundaria', 'Pública de gestión directa', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(865, '1030303', 'MAGISTER LAGRANGE', 'Secundaria', 'Privada', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(866, '1031780', 'PERUANO SUIZO ALFRED WERNER', 'Secundaria', 'Privada', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(867, '1120484', 'SAN CARLOS', 'Secundaria', 'Privada', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(868, '1237700', 'CORONEL FRANCISCO BOLOGNESI', 'Básica Alternativa-Avanzado', 'Privada', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(869, '1261882', 'SAN AGUSTIN DE HIPONA', 'Básica Alternativa', 'Privada', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(870, '1339332', 'PRONOE EL GRAN MAESTRO', 'Secundaria de Adultos', 'Privada', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(871, '0308254', '41038 JOSE OLAYA BALANDRA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(872, '0490698', '41038 JOSE OLAYA BALANDRA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(873, '0492769', '40038 JORGE BASADRE GROHMANN', 'Secundaria', 'Pública de gestión directa', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(874, '0579680', '40175 GRAN LIBERTADOR SIMON BOLIVAR', 'Secundaria', 'Pública de gestión directa', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(875, '0579714', 'INMACULADA CONCEPCION', 'Secundaria', 'Pública de gestión directa', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(876, '0695254', 'SANTA URSULA', 'Secundaria', 'Privada', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(877, '0695288', '40122 MANUEL SCORZA TORRES', 'Secundaria', 'Pública de gestión directa', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(878, '0695304', 'NIÑO DE LA PAZ', 'Secundaria', 'Privada', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(879, '0695312', '7 DE AGOSTO', 'Secundaria', 'Pública de gestión directa', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(880, '0723478', '40166 BELGICA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(881, '1032135', 'WOLFGANG GOETHE', 'Secundaria', 'Privada', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(882, '1032234', 'SAN VICENTE DE PAUL', 'Secundaria', 'Privada', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(883, '1119676', 'NUESTRA SEÑORA DE LA PAZ', 'Secundaria', 'Privada', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(884, '1203470', 'PEDRO ALVAREZ CABRAL', 'Secundaria', 'Privada', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(885, '1237106', '40121 EVERARDO ZAPATA SANTILLANA', 'Secundaria', 'Pública de gestión directa', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(886, '1237544', 'PRONOE MARIA REICHE', 'Secundaria de Adultos', 'Privada', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(887, '1254507', 'NUESTRA SRA.DE MONTSERRAT', 'Secundaria', 'Privada', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(888, '1270214', 'AREQUIPA', 'Secundaria', 'Privada', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(889, '1270768', 'MADRE SANTA BEATRIZ', 'Secundaria', 'Privada', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(890, '1337674', 'SANTA MARIA GORETTI', 'Secundaria', 'Privada', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(891, '1338680', 'STELLA MARIS', 'Secundaria', 'Privada', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(892, '1338821', 'MARIAM ROSSE', 'Secundaria', 'Privada', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(893, '1371582', 'SANTA ROSA DE LIMA', 'Secundaria', 'Privada', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(894, '1402429', 'CEDEU DE CIENCIAS PITAGORAS', 'Secundaria', 'Privada', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(895, '1402528', 'WOLFANG AMADEUS MOZART', 'Secundaria', 'Privada', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(896, '1577659', 'NUESTRA SEÑORA DE LAS GRACIAS', 'Secundaria', 'Privada', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(897, '1627470', 'PERUANO BRASILERO PAULO COELHO', 'Secundaria', 'Privada', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(898, '1631100', 'ARCANGEL SAN MIGUEL', 'Secundaria', 'Privada', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(899, '1640507', 'MUNDO ECOLOGICO', 'Secundaria', 'Privada', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(900, '1642263', 'FUTURA SCHOOLS JOSE LUIS BUSTAMANTE Y RIVERO', 'Secundaria', 'Privada', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(901, '1698729', 'DIVERSITY SCHOOL', 'Secundaria', 'Privada', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(902, '1699305', 'INNOVA SCHOOLS', 'Secundaria', 'Privada', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(903, '1702943', 'VECTOR', 'Secundaria', 'Privada', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(904, '1801430', 'TORRE FUERTE', 'Secundaria', 'Privada', '040129', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(905, '0309237', 'SEBASTIAN BARRANCA', 'Secundaria', 'Pública de gestión directa', '040201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(906, '0310334', 'SEBASTIAN BARRANCA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(907, '0891788', '41041 CRISTO REY', 'Secundaria', 'Pública de gestión directa', '040201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(908, '0891879', 'DE JESUS E.I.R.L.', 'Secundaria', 'Privada', '040201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(909, '0891937', 'GRUPO MEGA', 'Secundaria', 'Privada', '040201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(910, '0891960', 'SANTA ISABEL', 'Secundaria', 'Privada', '040201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(911, '0891994', 'MENDEL CAMANA', 'Secundaria', 'Privada', '040201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(912, '1116623', 'DOMINGO SAVIO', 'Básica Alternativa-Avanzado', 'Privada', '040201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(913, '1119247', 'SANTA MARIA NEW SCHOOL', 'Secundaria', 'Privada', '040201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(914, '1238351', 'HONORIO DELGADO ESPINOZA', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '040201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(915, '1238369', 'HONORIO DELGADO ESPINOZA', 'Básica Alternativa-Avanzado', 'Privada', '040201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(916, '1341353', 'PRONOE SAN PEDRO', 'Secundaria de Adultos', 'Privada', '040201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(917, '1303080', 'AMAUTA', 'Secundaria', 'Privada', '040201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(918, '1349380', 'VIRGEN DE LA RECONCILIACION', 'Secundaria', 'Privada', '040201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(919, '1361161', 'SEBASTIAN BARRANCA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(920, '1361724', 'BRYCE CAMANA', 'Secundaria', 'Privada', '040201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(921, '1374446', 'DIVINO NIÑO JESUS CAMANA', 'Secundaria', 'Privada', '040201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(922, '0891812', 'JULIO ERNESTO PORTUGAL', 'Secundaria', 'Pública de gestión directa', '040202', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(923, '1527001', '40194', 'Secundaria', 'Pública de gestión directa', '040203', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(924, '1654573', 'GRAN SABIO ALBERT EINSTEIN', 'Secundaria', 'Privada', '040203', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(925, '1686823', 'CRISTO REY DE REYES', 'Secundaria', 'Privada', '040203', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(926, '1692078', 'VIRGEN DE COPACABANA', 'Secundaria', 'Privada', '040203', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(927, '1692086', 'VIRGEN DE COPACABANA', 'Básica Alternativa-Avanzado', 'Privada', '040203', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(928, '1726447', 'VIRGEN DE COPACABANA', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '040203', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(929, '1749373', '40667 CRISTO MORADO', 'Secundaria', 'Pública de gestión directa', '040203', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(930, '1773258', '40234 JUAN PABLO II', 'Secundaria', 'Pública de gestión directa', '040203', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(931, '1800820', 'VENADO', 'Secundaria', 'Pública de gestión directa', '040203', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(932, '1801398', 'DOMINGO SAVIO', 'Secundaria', 'Privada', '040203', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(933, '1341320', '40236 CESAR VALLEJO', 'Secundaria', 'Pública de gestión directa', '040204', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(934, '1696301', 'MARIANO MELGAR', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040204', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(935, '1792696', 'MARIANO MELGAR', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040204', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(936, '0712265', '40239 NICOLAS DE PIEROLA', 'Secundaria', 'Pública de gestión directa', '040205', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(937, '0892083', '40239 NICOLAS DE PIEROLA', 'Secundaria de Adultos', 'Pública de gestión directa', '040205', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(938, '1370105', 'CRISTIANO JESUS EL MAESTRO', 'Secundaria', 'Privada', '040205', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(939, '0309757', 'JOSE MARIA MORANTE', 'Secundaria', 'Pública de gestión directa', '040206', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(940, '0891846', '41515 REINO DE ESPAÑA', 'Secundaria', 'Pública de gestión directa', '040206', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(941, '1379379', '40259', 'Secundaria', 'Pública de gestión directa', '040206', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(942, '0679001', '40244 VIRGEN DE LA CANDELARIA DE QUILCA', 'Secundaria', 'Pública de gestión directa', '040207', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(943, '0309351', 'NUESTRA SEÑORA DE LA CANDELARIA', 'Secundaria', 'Pública de gestión privada', '040208', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(944, '0310417', 'FAUSTINO B. FRANCO', 'Secundaria', 'Pública de gestión directa', '040208', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(945, '0892117', '40238', 'Secundaria de Adultos', 'Pública de gestión directa', '040208', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(946, '1379361', '40281', 'Secundaria', 'Pública de gestión directa', '040208', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(947, '1442979', 'ANGEL DE LA GUARDA', 'Secundaria', 'Pública de gestión privada', '040208', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(948, '1620194', 'NUEVA GENERACION CAMANA', 'Básica Alternativa-Avanzado', 'Privada', '040208', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(949, '1701655', 'VILLA LINARES', 'Secundaria', 'Pública de gestión directa', '040208', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(950, '1776590', 'MONTE CARMELO', 'Secundaria', 'Privada', '040208', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(951, '0309534', 'INDEPENDENCIA DEL PERU', 'Secundaria', 'Pública de gestión directa', '040301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(952, '1341858', 'MARIANO MELGAR VALDIVIESO', 'Básica Alternativa', 'Privada', '040301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(953, '1315639', 'SAN MIGUEL', 'Secundaria', 'Pública de gestión privada', '040301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(954, '0309591', 'NICOLAS DE PIEROLA', 'Secundaria', 'Pública de gestión directa', '040302', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(955, '0655753', 'NICOLAS DE PIEROLA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040302', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(956, '0679019', 'FRANCISCO BOLOGNESI', 'Secundaria', 'Pública de gestión directa', '040302', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(957, '1031525', 'SAN MARTIN DE PORRES', 'Secundaria', 'Pública de gestión privada', '040302', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(958, '0309716', 'MIGUEL GRAU', 'Secundaria', 'Pública de gestión directa', '040303', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(959, '1203447', 'SEÑOR DE LOS MILAGROS', 'Básica Alternativa-Avanzado', 'Privada', '040303', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(960, '1624196', 'HOWARD GARDNER', 'Secundaria', 'Privada', '040303', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(961, '3039377', 'HAPPY CHILDREN', 'Secundaria', 'Privada', '040303', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(962, '1794957', '41052 MANUEL ALVA CABRERA', 'Secundaria', 'Pública de gestión directa', '040304', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(963, '0589382', 'FRANCISCO FLORES BERRUEZO', 'Secundaria', 'Pública de gestión directa', '040305', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(964, '0712414', 'VIRGEN DE COPACABANA', 'Secundaria', 'Pública de gestión directa', '040306', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(965, '0309583', 'HORTENCIA PARDO MANCEBO', 'Secundaria', 'Pública de gestión directa', '040307', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(966, '1341866', 'HENRY FORD', 'Básica Alternativa-Avanzado', 'Privada', '040307', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(967, '1368463', 'HENRY FORD', 'Secundaria', 'Privada', '040307', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(968, '1443902', 'DIVINO NIÑO JESUS', 'Secundaria', 'Privada', '040307', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(969, '1624188', 'SAN JUAN BAUTISTA DE CHALA', 'Secundaria', 'Privada', '040307', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(970, '3054525', 'DIVINO NIÑO JESUS', 'Secundaria', 'Privada', '040307', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(971, '0589416', 'NUESTRA SEÑORA MARIA DEL PILAR', 'Secundaria', 'Pública de gestión directa', '040308', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(972, '0892091', 'SIMON BOLIVAR', 'Secundaria', 'Pública de gestión directa', '040308', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(973, '1751437', 'DIVINO MAESTRO DE CHAPARRA', 'Secundaria', 'Privada', '040308', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(974, '1271733', 'CARLOS NORIEGA JIMENEZ', 'Secundaria', 'Pública de gestión directa', '040309', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(975, '0613158', 'SAN FRANCISCO DE ASIS', 'Secundaria', 'Pública de gestión directa', '040310', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(976, '0898395', 'INDALECIO TRILLO', 'Secundaria', 'Pública de gestión directa', '040311', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(977, '0589440', 'SAN ANTONIO DE LA PIEDRA', 'Secundaria', 'Pública de gestión directa', '040312', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(978, '1794940', '40278', 'Secundaria', 'Pública de gestión directa', '040312', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(979, '0517979', 'SAN PEDRO DE YAUCA', 'Secundaria', 'Pública de gestión directa', '040313', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(980, '0309245', 'LIBERTADOR CASTILLA', 'Secundaria', 'Pública de gestión directa', '040401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(981, '0678995', 'LIBERTADOR CASTILLA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(982, '0679027', 'NUESTRA SEÑORA DE LAS PEÑAS', 'Secundaria', 'Pública de gestión privada', '040401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(983, '0796771', '40310', 'Secundaria', 'Pública de gestión directa', '040401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(984, '0796797', 'LIBERTADOR CASTILLA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(985, '0891168', '40313 SAN IGNACIO DE LOYOLA', 'Secundaria', 'Pública de gestión directa', '040401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(986, '1253624', '41053 SAN TARCISIO', 'Secundaria', 'Pública de gestión privada', '040401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(987, '1273739', 'PRONOE BLAS PASCAL', 'Secundaria de Adultos', 'Privada', '040401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(988, '1342369', 'CRISTO REY', 'Secundaria', 'Privada', '040401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(989, '1379312', '40311 JOSE RODRIGUEZ BUSTAMANTE', 'Secundaria', 'Pública de gestión directa', '040401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(990, '1379346', 'CPED - 40346', 'Secundaria', 'Pública de gestión directa', '040401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(991, '1379353', '40309', 'Secundaria', 'Pública de gestión directa', '040401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(992, '0518274', '40314 NUESTRA SEÑORA DE LA ASUNCION', 'Secundaria', 'Pública de gestión directa', '040402', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(993, '3043502', '40316 JULIO ERNESTO LAZO DIAZ', 'Secundaria', 'Pública de gestión directa', '040403', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(994, '1379320', '40317', 'Secundaria', 'Pública de gestión directa', '040404', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(995, '1380120', 'ALLIN YACHAYWASI', 'Secundaria', 'Pública de gestión directa', '040404', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(996, '1314376', '40352 BENJAMIN GOMEZ YANCAPALLO', 'Secundaria', 'Pública de gestión directa', '040405', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(997, '1717495', 'CHOCO', 'Secundaria', 'Pública de gestión directa', '040406', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(998, '0589473', '40320 NUESTRA SEÑORA DEL ROSARIO', 'Secundaria', 'Pública de gestión directa', '040407', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(999, '1379338', '40322 NUESTRA SEÑORA DE LA ASUNTA', 'Secundaria', 'Pública de gestión directa', '040408', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1000, '0612176', '41505', 'Secundaria', 'Privada', '040409', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1001, '0637272', 'ALBERTO FLORES GALINDO', 'Secundaria', 'Pública de gestión directa', '040409', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1002, '0723056', '40324', 'Básica Alternativa', 'Pública de gestión directa', '040409', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1003, '0723080', 'ALBERTO FLORES GALINDO', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040409', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1004, '1342310', 'PRONOE CESAR VALLEJO', 'Secundaria de Adultos', 'Privada', '040409', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1005, '1333772', 'JEAN PIAGET', 'Secundaria', 'Privada', '040409', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1006, '1402536', '40702 ALBERTO BENAVIDES DE LA QUINTANA', 'Secundaria', 'Pública de gestión directa', '040409', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1007, '1729060', 'ALBERTO FLORES GALINDO', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040409', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1008, '1753235', 'VENN EULER', 'Secundaria', 'Privada', '040409', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1009, '1780238', 'SANTISIMO NIÑO DE JESUS', 'Secundaria', 'Privada', '040409', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1010, '0310433', 'GLORIOSO JUAN PABLO VISCARDO Y GUZMAN', 'Secundaria', 'Pública de gestión directa', '040410', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1011, '0612051', 'CESAR DURAN LAZARTE', 'Secundaria', 'Pública de gestión directa', '040411', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1012, '0310425', 'CORIRE', 'Secundaria', 'Pública de gestión directa', '040413', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1013, '0723049', '40331 MARIA AUXILIADORA', 'Básica Alternativa', 'Pública de gestión directa', '040413', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1014, '0723072', 'CORIRE', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040413', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1015, '0735522', 'NUESTRA SEÑORA DEL CARMEN', 'Secundaria', 'Pública de gestión privada', '040413', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1016, '1342302', 'PRONOE SAN AGUSTIN DE HIPONA', 'Secundaria de Adultos', 'Privada', '040413', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1017, '1411347', 'NUESTRA SEÑORA DE FATIMA', 'Secundaria', 'Privada', '040413', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1018, '1656198', 'VIRGEN DE CHAPI DE CORIRE', 'Secundaria', 'Privada', '040413', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1019, '1729078', 'CORIRE', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040413', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1020, '0490714', '40336 SAGRADO CORAZON DE JESUS', 'Secundaria', 'Pública de gestión directa', '040414', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1021, '1790559', '40336 SAGRADO CORAZON DE JESUS', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040414', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1022, '1790716', '40336 SAGRADO CORAZON DE JESUS', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040414', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1023, '0736173', '40375 MARIA AUXILIADORA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1024, '0736124', '40375 MARIA AUXILIADORA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1025, '0309567', 'FRANCISCO GARCIA CALDERON', 'Secundaria', 'Pública de gestión directa', '040501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1026, '1274398', 'SANTA CRUZ', 'Secundaria', 'Privada', '040501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1027, '1343060', 'NUESTRA SEÑORA DEL ROSARIO', 'Secundaria', 'Privada', '040501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1028, '1276823', 'SANTA CRUZ', 'Básica Alternativa-Avanzado', 'Privada', '040501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1029, '1578525', 'HATARISUNCHIS - EL PRINCIPITO', 'Secundaria', 'Privada', '040501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1030, '0589747', 'DANIEL ALCIDES CARRION', 'Secundaria', 'Pública de gestión directa', '040502', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1031, '0309559', 'HIPOLITO SANCHEZ TRUJILLO', 'Secundaria', 'Pública de gestión directa', '040503', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1032, '0797001', 'HIPOLITO SANCHEZ TRUJILLO', 'Secundaria de Adultos', 'Pública de gestión directa', '040503', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1033, '1379387', '40379', 'Secundaria', 'Pública de gestión directa', '040503', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1034, '0589770', 'LUIS PONCE GARCIA', 'Secundaria', 'Pública de gestión directa', '040504', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1035, '0589689', '41512', 'Secundaria', 'Privada', '040504', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1036, '0736181', 'CALLALLI', 'Secundaria de Adultos', 'Pública de gestión directa', '040504', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1037, '0736140', 'CAYLLOMA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040505', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1038, '0589804', 'GRAN LIBERTADOR SIMON BOLIVAR', 'Secundaria', 'Pública de gestión directa', '040505', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1039, '1117704', '40030 SAN FRANCISCO DE ASIS', 'Secundaria', 'Pública de gestión directa', '040505', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1040, '0797019', 'GRAN LIBERTADOR SIMON BOLIVAR', 'Secundaria de Adultos', 'Pública de gestión directa', '040505', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1041, '0892273', '40382 VIRGEN DE CHAPI', 'Secundaria', 'Pública de gestión directa', '040506', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1042, '1744226', 'SOL DEL COLCA', 'Secundaria', 'Pública de gestión directa', '040506', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1043, '0589838', 'ANDRES AVELINO CACERES', 'Secundaria', 'Pública de gestión directa', '040507', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1044, '0500736', 'CARLOS LA FUENTE LARRAURI', 'Secundaria', 'Pública de gestión directa', '040508', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1045, '0898247', '40386 TUPAC AMARU', 'Secundaria', 'Pública de gestión directa', '040509', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1046, '0589861', '40387', 'Secundaria', 'Pública de gestión directa', '040510', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1047, '0498972', '40388 CORAZON SAGRADO DE JESUS DE LLUTA', 'Secundaria', 'Pública de gestión directa', '040511', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1048, '0796920', '40389 MIGUEL LINARES MALAGA', 'Secundaria', 'Pública de gestión directa', '040511', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1049, '0897975', '40626', 'Secundaria', 'Pública de gestión directa', '040511', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1050, '0796953', '40390 MAYTA CAPAC', 'Secundaria', 'Pública de gestión directa', '040512', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1051, '0723189', 'TECNICO AGROPECUARIO MADRIGAL', 'Secundaria', 'Pública de gestión directa', '040513', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1052, '0796961', '40392 JOSE A. ENCINAS FRANCO', 'Secundaria', 'Pública de gestión directa', '040514', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1053, '0736157', 'AGROPECUARIO SIBAYO', 'Secundaria', 'Pública de gestión directa', '040515', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1054, '1202605', '40418', 'Secundaria', 'Pública de gestión directa', '040516', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1055, '0796946', '40321', 'Secundaria', 'Pública de gestión directa', '040517', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1056, '0723197', '40395', 'Secundaria', 'Pública de gestión directa', '040517', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1057, '1309608', '40421 JOSE OLAYA BALANDRA', 'Secundaria', 'Pública de gestión directa', '040517', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1058, '0736165', 'JOSE CARLOS MARIATEGUI', 'Secundaria', 'Pública de gestión directa', '040518', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1059, '0679035', 'AGROPECUARIO YANQUE', 'Secundaria', 'Pública de gestión directa', '040519', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1060, '0679043', '40399 GRAL. JUAN VELASCO ALVARADO', 'Secundaria', 'Pública de gestión directa', '040519', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1061, '0619312', 'ALMIRANTE MIGUEL GRAU', 'Secundaria', 'Pública de gestión directa', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1062, '0698308', '40201 TECNICO AGROPECUARIO LA COLINA', 'Secundaria', 'Pública de gestión directa', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1063, '0745968', '40594 JUAN VELASCO A.', 'Secundaria', 'Pública de gestión directa', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1064, '0794552', '40284 PEDRO PAULET MOSTAJO', 'Secundaria', 'Pública de gestión directa', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1065, '0897942', '40625 CORAZON DE JESUS', 'Secundaria', 'Pública de gestión directa', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1066, '0898007', '41061 JOSE ANTONIO ENCINAS', 'Secundaria', 'Pública de gestión directa', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1067, '0898031', 'JUSTO JUEZ', 'Secundaria', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1068, '0898064', 'NUESTRA SRA. DE LA PAZ', 'Secundaria', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1069, '0898098', 'SANTA TERESA DE LISIEUX', 'Secundaria', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1070, '0898122', 'VIRGEN DE CHAPI', 'Secundaria', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1071, '1032218', 'SANTA ROSA', 'Secundaria', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1072, '1119924', 'MAJES INTERNACIONAL', 'Secundaria', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1073, '1238518', 'VALORES SCHOOL', 'Secundaria', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1074, '1238559', 'MARIA REICHE', 'Secundaria', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1075, '1238831', 'NUEVA GENERACION ESTUDIANTIL', 'Secundaria', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1076, '1262484', 'CIENCIAS APLICADAS', 'Secundaria', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1077, '1263441', 'MAXWELL SCHOOL', 'Secundaria', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1078, '1271691', '40661 ISABEL KRIEGER BEATO', 'Secundaria', 'Pública de gestión directa', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1079, '1273341', 'NUESTRA SEÑORA DE LAS MERCEDES', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1080, '1273382', 'NUESTRA SEÑORA DE LAS MERCEDES', 'Básica Alternativa-Avanzado', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1081, '1333137', 'SAGRADA FAMILIA', 'Secundaria', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1082, '1333210', '40629 DIVINO NIÑO JESUS', 'Secundaria', 'Pública de gestión directa', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1083, '1333400', 'ALEXANDER FLEMING', 'Secundaria', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1084, '1333434', 'MAJES INTERNACIONAL', 'Secundaria de Adultos', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1085, '1333459', 'NUEVA GENERACION ESTUDIANTIL', 'Básica Alternativa-Avanzado', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1086, '1333509', 'JOSE MARIA ARGUEDAS DEL PEDREGAL', 'Secundaria', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1087, '1607159', '40656 SAN FRANCISCO DE ASIS', 'Secundaria', 'Pública de gestión directa', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1088, '1334150', '40230 SAN ANTONIO DEL PEDREGAL', 'Secundaria', 'Pública de gestión directa', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1089, '1334176', 'JOHAN WOLFGANG GOETHE EUROPEO', 'Básica Alternativa-Avanzado', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1090, '1277102', 'LIBERTAD', 'Secundaria', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1091, '1607225', 'BRYCE PEDREGAL', 'Secundaria', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1092, '1607274', 'NUESTRA SEÑORA DE GUADALUPE', 'Secundaria', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1093, '1607316', '40097 REPUBLICA DE CANADA', 'Secundaria', 'Pública de gestión directa', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1094, '1607365', 'JOHAN WOLFGANG GOETHE EUROPEO', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1095, '1334002', 'CIENCIAS', 'Secundaria', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1096, '1334218', 'CIENCIAS', 'Básica Alternativa-Avanzado', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1097, '1607498', 'SANTA MARIA DEL ROSARIO', 'Secundaria', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1098, '1607506', 'PRONOE ISAAC NEWTON', 'Secundaria de Adultos', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1099, '1607522', 'PRONOE NIÑO DE LA PAZ', 'Secundaria de Adultos', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1100, '1341502', 'CIMAS', 'Secundaria', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1101, '1354695', 'NUESTRA SEÑORA DE LAS MERCEDES', 'Secundaria', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1102, '1607464', 'ALAS PERUANAS MAJES', 'Secundaria', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1103, '1370139', 'SAN CARLOS', 'Secundaria', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1104, '1371640', 'CORONEL LEONCIO PRADO', 'Secundaria', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1105, '1371632', 'GREGORY MENDEL', 'Secundaria', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1106, '1384833', 'MENDEL PEDREGAL', 'Secundaria', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1107, '1399187', 'CRISTIANA MARIA MONTESSORI', 'Secundaria', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1108, '1420496', 'ANGELES EN EL PARAISO', 'Secundaria', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1109, '1422468', '40698 SAN JUAN BAUTISTA DE MAJES', 'Secundaria', 'Pública de gestión directa', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1110, '1435569', 'JOSE A. QUIÑONES GONZALES', 'Secundaria', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1111, '1470590', 'ALEXANDER FLEMING', 'Básica Alternativa', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1112, '1564210', 'EL CARMEN', 'Básica Alternativa-Avanzado', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1113, '1576776', 'DIVINA INFANCIA', 'Secundaria', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1114, '1576768', 'NUEVO MUNDO', 'Básica Alternativa-Avanzado', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1115, '1629682', 'LA INMACULADA DE LA COLINA', 'Secundaria', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1116, '1653609', 'DR JUAN MANUEL GUILLEN BENAVIDES', 'Secundaria', 'Pública de gestión directa', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1117, '1653625', '41062 DR. MARIO VARGAS LLOSA', 'Secundaria', 'Pública de gestión directa', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1118, '1662410', 'JUVENTUD VENCEDORA', 'Básica Alternativa-Avanzado', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1119, '1663103', 'SANTA TERESA DE LISIEUX', 'Básica Alternativa-Avanzado', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1120, '1692425', 'CORONEL FRANCISCO BOLOGNESI', 'Secundaria', 'Pública de gestión directa', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1121, '1717628', 'RICARDO PALMA SORIANO', 'Secundaria', 'Pública de gestión directa', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1122, '1724129', 'SANTA TERESA DE LISIEUX', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1123, '1726629', 'JUVENTUD VENCEDORA', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1124, '1734730', 'ADVENTISTA DE MAJES', 'Secundaria', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1125, '1752906', 'TECNOLOGICO PERUANO JAPONES', 'Secundaria', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1126, '1756568', 'MARIA DE LOS ANGELES', 'Secundaria', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1127, '1759414', 'JESUS EL GRAN MAESTRO', 'Básica Alternativa-Avanzado', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1128, '3000288', 'GRAN UNIDAD ESCOLAR MAJES', 'Secundaria', 'Pública de gestión directa', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1129, '1797919', '41502', 'Secundaria', 'Pública de gestión directa', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1130, '1798677', 'LOS DINAMICOS', 'Secundaria', 'Pública de gestión directa', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1131, '1801901', 'SAN DIEGO', 'Secundaria', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1132, '3046844', 'LA CLAVE ES JESUCRISTO', 'Secundaria', 'Privada', '040520', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1133, '0308304', '41045 CORAZON DE JESUS', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1134, '0309286', 'SAN LUIS GONZAGA', 'Secundaria', 'Pública de gestión directa', '040601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1135, '0309419', 'CORAZON DE MARIA', 'Secundaria', 'Pública de gestión directa', '040601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1136, '0891556', '40430 JOSE SIMEON TEJEDA', 'Secundaria', 'Pública de gestión directa', '040602', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1137, '1031574', '40458 SAN JUAN BAUTISTA', 'Secundaria', 'Pública de gestión directa', '040603', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1138, '1273655', '40568', 'Secundaria', 'Pública de gestión directa', '040603', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1139, '1625532', '40459 SAN ROQUE', 'Secundaria', 'Pública de gestión directa', '040603', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1140, '1626928', '40577', 'Secundaria', 'Pública de gestión directa', '040603', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `origin_schools` (`id`, `modular_code`, `name`, `d_niv_mod`, `management_type`, `ubigeo_code`, `created_at`, `updated_at`) VALUES
(1141, '1659101', '40429 JOSE GABRIEL CONDORCANQUI', 'Secundaria', 'Pública de gestión directa', '040603', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1142, '0712620', '40432 VICTOR RAUL HAYA DE LA TORRE', 'Secundaria', 'Pública de gestión directa', '040604', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1143, '0591404', '40446 MIGUEL GRAU', 'Secundaria', 'Pública de gestión directa', '040606', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1144, '0613067', '41511 LIBERTADORES DE AMERICA', 'Secundaria', 'Pública de gestión directa', '040606', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1145, '0891580', 'PIUCA', 'Secundaria', 'Pública de gestión directa', '040606', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1146, '0579763', 'SAN JUAN BAUTISTA DE SALAMANCA', 'Secundaria', 'Pública de gestión directa', '040607', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1147, '0518373', 'JORGE BASADRE', 'Secundaria', 'Pública de gestión directa', '040608', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1148, '0612440', 'ANDRES AVELINO CACERES', 'Secundaria', 'Pública de gestión directa', '040608', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1149, '1328277', '40426 JOSE OLAYA', 'Secundaria', 'Pública de gestión directa', '040608', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1150, '1754506', 'SAN CRISTOBAL', 'Secundaria', 'Pública de gestión directa', '040608', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1151, '1759117', '40684 SAN JOSE OBRERO', 'Secundaria', 'Pública de gestión directa', '040608', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1152, '0309195', 'DEAN VALDIVIA', 'Secundaria', 'Pública de gestión directa', '040701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1153, '0309328', 'SAN VICENTE DE PAUL', 'Secundaria', 'Pública de gestión privada', '040701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1154, '0309922', 'SAN FRANCISCO DE ASIS', 'Secundaria', 'Privada', '040701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1155, '0310045', 'MARIA AUXILIADORA', 'Secundaria', 'Privada', '040701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1156, '0310300', 'DEAN VALDIVIA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1157, '0610667', 'DEAN VALDIVIA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1158, '0617530', '40474 JOSE CARLOS MARIATEGUI', 'Secundaria', 'Pública de gestión directa', '040701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1159, '0891143', 'JOHN F. KENNEDY', 'Secundaria', 'Privada', '040701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1160, '0891200', 'GERMAN OLIVARES SEGURA', 'Secundaria', 'Privada', '040701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1161, '0891762', '40476 MERCEDES MANRIQUE FUENTES', 'Secundaria', 'Pública de gestión directa', '040701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1162, '1117860', 'PRONOE SANTA MARIA', 'Secundaria de Adultos', 'Privada', '040701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1163, '1117902', 'PRONOE CRISTIAN HUYGHENS', 'Secundaria de Adultos', 'Privada', '040701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1164, '1272699', 'PRONOE ISAAC TAPIA ARESTEGUI', 'Secundaria de Adultos', 'Privada', '040701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1165, '1239243', 'CIENCIAS ITALO PERUANO ENRICO FERMI', 'Secundaria', 'Privada', '040701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1166, '1336478', 'LAPLACE MOLLENDO', 'Secundaria', 'Privada', '040701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1167, '1355288', 'M.P.T. LATINOAMERICANO', 'Secundaria', 'Privada', '040701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1168, '1370121', 'MAKKADESH', 'Secundaria', 'Privada', '040701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1169, '1518554', 'CIENCIAS APLICADAS SIR ISAAC NEWTON', 'Secundaria', 'Privada', '040701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1170, '1523885', 'HONORIO DELGADO ESPINOZA', 'Básica Alternativa-Avanzado', 'Privada', '040701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1171, '1764380', 'ABC GREGORIO MENDEL', 'Secundaria', 'Privada', '040701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1172, '0309476', 'MARIANO E. RIVERO Y USTARIZ', 'Secundaria', 'Pública de gestión directa', '040702', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1173, '0310383', 'CEGECOM', 'Básica Alternativa-Avanzado', 'Privada', '040702', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1174, '0610659', 'CHUCARAPI', 'Secundaria', 'Pública de gestión directa', '040702', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1175, '1344233', 'JESUS DE NAZARETH', 'Secundaria', 'Privada', '040702', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1176, '1344266', 'VALLE ARRIBA DE TAMBO', 'Secundaria', 'Pública de gestión directa', '040702', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1177, '0770057', 'CHUCARAPI', 'Secundaria de Adultos', 'Pública de gestión directa', '040702', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1178, '1344282', 'MARIA REICHE', 'Secundaria', 'Privada', '040702', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1179, '0310169', 'FRANCISCO LOPEZ DE ROMAÑA', 'Secundaria', 'Pública de gestión directa', '040703', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1180, '0579771', 'CIRO ALEGRIA BAZAN', 'Secundaria', 'Pública de gestión directa', '040703', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1181, '1344274', 'SAN ANTONIO ABAD', 'Secundaria', 'Privada', '040703', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1182, '1697341', 'LA PLACE', 'Secundaria', 'Privada', '040703', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1183, '1773274', 'SAINT MICHAEL THE ARCHANGEL', 'Secundaria', 'Privada', '040703', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1184, '0309740', '40479 MIGUEL GRAU', 'Secundaria', 'Pública de gestión directa', '040704', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1185, '1518570', 'ESPIRITU SANTO', 'Secundaria', 'Privada', '040704', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1186, '1773712', 'EL BUEN PASTOR', 'Secundaria', 'Pública de gestión directa', '040704', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1187, '0617548', '40494 JOSE ABELARDO QUIÑONES', 'Secundaria', 'Pública de gestión directa', '040705', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1188, '0308346', '41049 EVERARDO ZAPATA SANTILLANA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040706', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1189, '0309484', 'VICTOR MANUEL TORRES CACERES', 'Secundaria', 'Pública de gestión directa', '040706', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1190, '0309294', 'MARISCAL ORBEGOSO', 'Secundaria', 'Pública de gestión directa', '040801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1191, '1555416', 'MARISCAL ORBEGOSO', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '040801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1192, '1729086', 'MARISCAL ORBEGOSO', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '040801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1193, '0518472', '40510 CORONEL CASIMIRO PERALTA', 'Secundaria', 'Pública de gestión directa', '040802', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1194, '1344597', '40512 VIRGEN DE CHAPI', 'Secundaria', 'Pública de gestión directa', '040802', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1195, '0712745', '40515 SAN SEBASTIAN', 'Secundaria', 'Pública de gestión directa', '040803', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1196, '0712778', '40517 CAPITAN EVARISTO AMESQUITA', 'Secundaria', 'Pública de gestión directa', '040804', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1197, '1415983', '40542', 'Secundaria', 'Pública de gestión directa', '040804', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1198, '1344639', '40522 JUAN LUIS SOTO MOTTA', 'Secundaria', 'Pública de gestión directa', '040805', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1199, '0891408', '40525 SAN SANTIAGO', 'Secundaria', 'Pública de gestión directa', '040806', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1200, '1415975', '40526', 'Secundaria', 'Pública de gestión directa', '040808', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1201, '0589531', '40529 VICTOR ANDRES BELAUNDE', 'Secundaria', 'Pública de gestión directa', '040809', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1202, '0612507', '40531 HONOFRE BENAVIDES', 'Secundaria', 'Pública de gestión directa', '040810', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1203, '0639328', '40536 TUPAC AMARU II', 'Secundaria', 'Pública de gestión directa', '040811', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1204, '0655787', '40534 JUAN MANUEL GUILLEN BENAVIDES', 'Secundaria', 'Pública de gestión directa', '040811', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1205, '0236109', 'CLORINDA MATTO DE TURNER', 'Secundaria', 'Pública de gestión directa', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1206, '0236117', 'GLORIOSO COLEGIO NACIONAL DE CIENCIAS', 'Secundaria', 'Pública de gestión directa', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1207, '0236224', '50900 EDUCANDAS', 'Secundaria', 'Pública de gestión privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1208, '0236364', 'FORTUNATO L HERRERA', 'Secundaria', 'Pública de gestión directa', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1209, '0236687', 'SALESIANO', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1210, '0579151', '51015 SAN FRANCISCO DE BORJA', 'Secundaria', 'Pública de gestión directa', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1211, '0579227', 'SAN MARTIN DE PORRES', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1212, '0579235', 'JOSE PARDO', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1213, '0579243', 'SANTA ROSA DE LIMA', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1214, '0207449', 'COMERCIO 41', 'Secundaria', 'Pública de gestión directa', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1215, '0233056', 'INCA GARCILASO DE LA VEGA', 'Secundaria', 'Pública de gestión directa', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1216, '0236232', '50003 SANTA ROSA', 'Secundaria', 'Pública de gestión privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1217, '0236349', 'HUMBERTO LUNA', 'Secundaria', 'Pública de gestión directa', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1218, '0236695', 'SAN ANTONIO ABAD', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1219, '0236711', 'SAN FRANCISCO DE ASIS', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1220, '0236729', 'LA MERCED', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1221, '0236737', 'MARIA AUXILIADORA', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1222, '0236745', 'EL CARMELO', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1223, '0236760', 'LAS MERCEDES', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1224, '0489096', '50707 SIMON BOLIVAR', 'Secundaria', 'Pública de gestión directa', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1225, '0699637', 'ISAIAH BOWMAN', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1226, '0785097', '50048 LOS INCAS', 'Secundaria', 'Pública de gestión directa', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1227, '0782664', '50002 LUIS VALLEJOS SANTONI', 'Secundaria', 'Pública de gestión privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1228, '0785089', 'ALEJANDRO VON HUMBOLDT', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1229, '0927939', 'SANTA MARIA REYNA', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1230, '0928119', 'JUAN LANDAZURI RICKETTS', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1231, '0928143', 'AVANTI PERU', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1232, '0928234', 'UNION DE NUEVOS INTELIGENTES - UNI CUSCO', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1233, '0928267', 'DIVINO MAESTRO', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1234, '0927905', 'ROSA DE AMERICA', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1235, '1061837', 'INMACULADA CONCEPCION', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1236, '0235358', 'HUMBERTO LUNA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1237, '0235457', 'INCA GARCILASO DE LA VEGA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1238, '0235499', 'CLORINDA MATTO DE TURNER', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1239, '0235960', '50022 JORGE CHAVEZ CHAPARRO', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1240, '0207530', 'COMERCIO 41', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1241, '0235374', '51004 SAN VICENTE DE PAUL', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1242, '0235416', 'GLORIOSO COLEGIO NACIONAL DE CIENCIAS', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1243, '0235424', '51009 FRANCISCO SIVIRICHI', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1244, '0235473', '51015 SAN FRANCISCO DE BORJA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1245, '0236810', 'CLORINDA MATTO DE TURNER', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1246, '0236828', 'GLORIOSO COLEGIO NACIONAL DE CIENCIAS', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1247, '0497628', 'HUMBERTO LUNA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1248, '0782649', '51015 SAN FRANCISCO DE BORJA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1249, '0235432', '51003 ROSARIO', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1250, '1060920', 'SEÑOR DE LOS MILAGROS', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1251, '1061332', 'SANTA URSULA', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1252, '1061449', 'JORGE CHAVEZ CHAPARRO', 'Secundaria', 'Pública de gestión directa', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1253, '0928028', 'SAN AGUSTIN', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1254, '1200443', 'PROYECTO INGENIERIA', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1255, '0928416', 'SUIZO PERUANO', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1256, '0928473', 'BLAISE PASCAL DE FRANCIA', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1257, '0928564', 'DANIEL ALCIDES CARRION', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1258, '0928176', 'SAN PABLO', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1259, '0928531', 'CESAR VALLEJO', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1260, '0236836', 'FORTUNATO L.HERRERA', 'Secundaria de Adultos', 'Pública de gestión directa', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1261, '1061126', 'FLORENCE NIGHTINGALE', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1262, '1200260', 'ISAAC NEWTON', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1263, '1200542', 'SAMINCHAY CHRISTIAN SCHOOL', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1264, '1201623', 'SAN JUAN DE DIOS', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1265, '1061696', 'INCA GARCILASO DE LA VEGA', 'Secundaria de Adultos', 'Pública de gestión directa', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1266, '1062488', 'SAN AGUSTIN', 'Básica Alternativa-Avanzado', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1267, '1062249', 'SAN AGUSTIN', 'Secundaria de Adultos', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1268, '1386069', 'CARRION - SAN JERONIMO', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1269, '1269182', 'JUAN BOSCO', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1270, '1269349', 'DIVINO CORAZON', 'Secundaria de Adultos', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1271, '1269109', 'BERNABE COBO', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1272, '1386101', 'FRANCISCO Y JACINTA MARTO', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1273, '1062363', 'SAN ISIDRO LABRADOR', 'Secundaria de Adultos', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1274, '1062330', 'SAN ISIDRO LABRADOR', 'Básica Alternativa-Avanzado', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1275, '0928382', 'JAVIER PEREZ DE CUELLAR', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1276, '1061258', 'ARCO IRIS CUSCO', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1277, '1386267', 'SANTO TOMAS DE AQUINO', 'Básica Alternativa-Avanzado', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1278, '1386275', 'SANTO TOMAS DE AQUINO', 'Secundaria de Adultos', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1279, '1386671', 'JOSE ANDRES RAZURI ESTEVEZ', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1280, '1387414', 'BLAISE PASCAL', 'Secundaria de Adultos', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1281, '1387463', 'DANIEL ALCIDES CARRION', 'Secundaria de Adultos', 'Pública de gestión privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1282, '1387489', 'VIRGEN DEL TRANSITO', 'Secundaria de Adultos', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1283, '1304310', 'TRILCE CUSCO', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1284, '1322593', '51004 SAN VICENTE DE PAUL', 'Secundaria', 'Pública de gestión directa', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1285, '1200369', 'ABRAHAN VALDELOMAR', 'Secundaria de Adultos', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1286, '1200351', 'ABRAHAM VALDELOMAR', 'Básica Alternativa-Avanzado', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1287, '1370345', '51003 ROSARIO', 'Secundaria', 'Pública de gestión directa', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1288, '0236794', 'INCA GARCILASO DE LA VEGA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1289, '1725894', 'SAN AGUSTIN', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1290, '1726256', 'ABRAHAM VALDELOMAR', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1291, '1731546', 'BILINGÜE MANUEL PARDO Y LAVALLE', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1292, '1768076', 'EL NIÑO INVESTIGADOR - K\'USKIQ ERQE', 'Secundaria', 'Privada', '080101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1293, '1379544', 'INTERCULTURAL QHAPAQ ÑAN', 'Secundaria', 'Pública de gestión directa', '080102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1294, '0730481', 'MANUEL SEOANE CORRALES', 'Secundaria', 'Pública de gestión directa', '080103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1295, '1718618', '501222', 'Secundaria', 'Pública de gestión directa', '080103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1296, '3047578', 'MUSUQ', 'Secundaria', 'Privada', '080103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1297, '0536516', 'HUMBERTO VIDAL UNDA', 'Secundaria', 'Privada', '080104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1298, '0233130', '50038 ALEJANDRO VELASCO ASTETE', 'Secundaria', 'Pública de gestión directa', '080104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1299, '0236786', 'NUESTRA SEÑORA DEL ROSARIO FE Y ALEGRIA 21', 'Secundaria', 'Pública de gestión privada', '080104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1300, '0928325', 'PRESBITERO TEOFILO USCAMAITA', 'Secundaria', 'Privada', '080104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1301, '1060334', 'SANTA BERNARDITA', 'Secundaria', 'Privada', '080104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1302, '0235523', '50038 ALEJANDRO VELASCO ASTETE', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '080104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1303, '0622746', '50038 ALEJANDRO VELASCO ASTETE', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '080104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1304, '1200039', 'SAN MARCOS E.I.R.L.', 'Secundaria', 'Privada', '080104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1305, '1399385', 'SAN MARTIN DE PORRES', 'Básica Alternativa-Avanzado', 'Privada', '080104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1306, '1399393', 'SAN MARTIN DE PORRES', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '080104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1307, '1386176', 'LA INMACULADA', 'Secundaria', 'Privada', '080104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1308, '1386432', '51023 SAN LUIS GONZAGA', 'Secundaria', 'Pública de gestión directa', '080104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1309, '1386564', 'BERNABE COBO 2', 'Secundaria', 'Privada', '080104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1310, '1347350', 'SAN ISIDRO', 'Secundaria', 'Privada', '080104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1311, '1354711', 'LA CATOLICA DEL PACIFICO', 'Secundaria', 'Privada', '080104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1312, '1390079', 'ISAIAH BOWMAN SHANTS', 'Secundaria', 'Privada', '080104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1313, '1468750', 'SAN GABRIEL', 'Secundaria', 'Privada', '080104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1314, '1531979', 'PROMESA', 'Secundaria', 'Privada', '080104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1315, '1536259', 'MONTESSORI DEL CUSCO', 'Secundaria', 'Privada', '080104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1316, '1583244', 'SAN AGUSTIN DE HIPONA - CUSCO', 'Secundaria', 'Privada', '080104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1317, '1639277', 'CARRION', 'Secundaria', 'Privada', '080104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1318, '1664127', 'SANTA MARIA REYNA DE LA PAZ', 'Secundaria', 'Privada', '080104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1319, '1718626', '50037 VIRGEN DE LA MERCED', 'Secundaria', 'Pública de gestión directa', '080104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1320, '1762186', 'INNOVA SCHOOLS - CUSCO LARAPA', 'Secundaria', 'Privada', '080104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1321, '1767144', 'SAN JERONIMO', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '080104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1322, '1767417', 'SAN JERONIMO', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '080104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1323, '1772623', 'TALENTOS', 'Secundaria', 'Privada', '080104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1324, '1790526', '51037 VIRGEN DEL CARMEN', 'Secundaria', 'Pública de gestión directa', '080104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1325, '3010071', 'TRILCE MAGISTERIO', 'Secundaria', 'Privada', '080104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1326, '1798719', '50814', 'Secundaria', 'Pública de gestión directa', '080104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1327, '3052115', 'PARETO', 'Básica Alternativa-Avanzado', 'Privada', '080104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1328, '0928051', 'KHIPU', 'Secundaria', 'Privada', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1329, '0928358', 'PUKLLASUNCHIS', 'Secundaria', 'Privada', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1330, '0236679', 'SAN JOSE LA SALLE', 'Secundaria', 'Privada', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1331, '1061670', 'PERUANO SUIZO DE LOS ANDES', 'Secundaria', 'Privada', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1332, '1061951', 'CRISTO SALVADOR', 'Secundaria', 'Privada', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1333, '0616185', 'INCA RIPAQ', 'Secundaria', 'Pública de gestión directa', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1334, '1386143', 'SANTA MARIA GORETTI', 'Secundaria', 'Privada', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1335, '0236414', 'DIEGO QUISPE TITO', 'Secundaria', 'Pública de gestión directa', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1336, '0927848', 'VIRGEN DE FATIMA', 'Secundaria', 'Pública de gestión directa', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1337, '0782680', 'REVOLUCIONARIA SANTA ROSA', 'Secundaria', 'Pública de gestión directa', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1338, '0928291', 'GUADALUPE', 'Secundaria', 'Privada', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1339, '1062272', 'PACHAKUTEQ', 'Secundaria', 'Privada', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1340, '0235515', 'DIEGO QUISPE TITO', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1341, '0671867', 'DIEGO QUISPE TITO', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1342, '0934067', 'LOS LIBERTADORES', 'Secundaria', 'Privada', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1343, '1201094', 'SANTA MARIA MADRE DE DIOS', 'Secundaria', 'Privada', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1344, '1200823', 'VIRGEN DE ASUNCION', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1345, '1200831', 'VIRGEN DE ASUNCION', 'Básica Alternativa-Avanzado', 'Privada', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1346, '1399435', 'QORIKANCHA', 'Secundaria', 'Privada', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1347, '1399476', 'CORONEL LEONCIO PRADO', 'Secundaria', 'Privada', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1348, '1395862', 'ARCO IRIS', 'Secundaria', 'Privada', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1349, '0730515', 'VICTOR RAUL HAYA DE LA TORRE', 'Secundaria', 'Pública de gestión directa', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1350, '1386598', 'WAYNAKUNAQ YACHAYWASIN', 'Secundaria', 'Privada', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1351, '1386606', 'SAN FERNANDO', 'Secundaria', 'Privada', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1352, '1386978', 'SAN TARCISIO', 'Secundaria', 'Privada', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1353, '1352269', 'BOLIVARIANO', 'Secundaria', 'Pública de gestión directa', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1354, '1201300', 'DANIEL ALCIDES CARRION', 'Secundaria', 'Privada', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1355, '1370485', 'PESTALOZZI', 'Secundaria', 'Privada', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1356, '1396654', 'SAN JUAN', 'Secundaria', 'Privada', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1357, '1420827', 'INGENIERIA', 'Secundaria', 'Privada', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1358, '1465988', 'BERNABE COBO 4', 'Secundaria', 'Privada', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1359, '1522770', 'SION', 'Secundaria', 'Privada', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1360, '1637073', 'JOSE ANTONIO ENCINAS', 'Básica Alternativa-Avanzado', 'Privada', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1361, '1639285', 'MONTEVERDE', 'Secundaria', 'Privada', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1362, '1653534', 'QORIKANCHA', 'Básica Alternativa-Avanzado', 'Privada', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1363, '1721380', 'PEDRO PAULET MOSTAJO', 'Secundaria', 'Privada', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1364, '1725605', 'QORIKANCHA', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1365, '1726348', 'JOSE ANTONIO ENCINAS', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1366, '1755024', 'SANTA MARIA DE LOS ANDES', 'Secundaria', 'Privada', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1367, '1765650', 'GAL SCHOOL', 'Secundaria', 'Privada', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1368, '1773415', 'JESUS ES MI MAESTRO', 'Secundaria', 'Privada', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1369, '1793983', '50949 EL CARMELO DE MARIA', 'Secundaria', 'Pública de gestión privada', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1370, '1798370', '50014 VIRGEN DEL CARMEN', 'Secundaria', 'Pública de gestión directa', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1371, '1800762', '50046 TUPAQ YUPANQUI', 'Secundaria', 'Pública de gestión directa', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1372, '3027620', 'LOS PATRIOTAS', 'Secundaria', 'Privada', '080105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1373, '0236778', 'FE Y ALEGRIA 20', 'Secundaria', 'Pública de gestión privada', '080106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1374, '0517698', 'CORONEL FRANCISCO BOLOGNESI', 'Secundaria', 'Pública de gestión directa', '080106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1375, '0621755', 'DIDASKALIO SAN JOSE OBRERO', 'Secundaria', 'Privada', '080106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1376, '0591164', 'VIVA EL PERU', 'Secundaria', 'Pública de gestión directa', '080106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1377, '0927814', 'GRAN MARISCAL ANDRES AVELINO CACERES', 'Secundaria', 'Pública de gestión directa', '080106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1378, '0928440', 'ABRAHAM LINCOLN', 'Secundaria', 'Privada', '080106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1379, '0933564', 'SAN JOSE', 'Secundaria', 'Privada', '080106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1380, '0928507', 'FLEMING', 'Secundaria', 'Privada', '080106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1381, '0616094', 'AMAUTA', 'Secundaria de Adultos', 'Pública de gestión directa', '080106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1382, '0644930', 'CORONEL FRANCISCO BOLOGNESI', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '080106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1383, '1201276', 'WORLD SCHOOL', 'Secundaria', 'Privada', '080106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1384, '1201037', 'AMAUTA', 'Secundaria de Adultos', 'Privada', '080106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1385, '1200781', 'JUAN BOSCO', 'Básica Alternativa', 'Privada', '080106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1386, '1201524', 'LICEO ITALIANO', 'Secundaria', 'Privada', '080106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1387, '1386002', 'CEBA - SANTA GABRIELA', 'Secundaria de Adultos', 'Privada', '080106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1388, '1386010', 'SANTA GABRIELA', 'Básica Alternativa-Avanzado', 'Privada', '080106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1389, '1201771', 'INMACULADA CONCEPCION', 'Básica Alternativa-Avanzado', 'Privada', '080106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1390, '1269943', 'SANTIAGO APOSTOL', 'Secundaria', 'Privada', '080106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1391, '1386119', 'ABRAHAM LINCOLN', 'Básica Alternativa-Avanzado', 'Privada', '080106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1392, '1266543', 'WORLD SCHOOL', 'Secundaria de Adultos', 'Privada', '080106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1393, '1386192', 'MARIA ANGOLA', 'Secundaria', 'Privada', '080106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1394, '1386226', '50723 CECILIA TUPAC AMARU', 'Secundaria', 'Pública de gestión directa', '080106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1395, '1386721', 'SEÑOR DE LA VIDA', 'Básica Alternativa-Avanzado', 'Privada', '080106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1396, '1386739', 'CEBA - SEÑOR DE LA VIDA', 'Secundaria de Adultos', 'Privada', '080106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1397, '1386887', 'ABRAHAM LINCOLN', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '080106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1398, '1328509', 'INMACULADA CONCEPCION', 'Secundaria', 'Privada', '080106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1399, '1329465', 'LIDERES', 'Secundaria', 'Privada', '080106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1400, '1336072', 'WAYNAKUNAQ YACHAYWASIN', 'Secundaria', 'Pública de gestión privada', '080106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1401, '1360965', '51006 TUPAC AMARU', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '080106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1402, '1361740', 'PABLO APOSTOL', 'Secundaria', 'Privada', '080106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1403, '1370378', '51006 TUPAC AMARU', 'Secundaria', 'Pública de gestión directa', '080106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1404, '1695444', 'BRYCE', 'Secundaria', 'Privada', '080106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1405, '1724954', 'INMACULADA CONCEPCION', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '080106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1406, '1773407', 'INNOVA SCHOOLS - HUANCARO', 'Secundaria', 'Privada', '080106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1407, '0928911', 'RICARDO PALMA SORIANO', 'Secundaria', 'Privada', '080107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1408, '0621300', 'ANTONIO RAYMONDI', 'Secundaria', 'Pública de gestión directa', '080107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1409, '3049913', 'RICARDO PALMA', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '080107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1410, '3049921', 'RICARDO PALMA', 'Básica Alternativa-Avanzado', 'Privada', '080107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1411, '0236752', 'SANTA ANA', 'Secundaria', 'Privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1412, '0928085', 'MAYOR DE NAZARENO', 'Secundaria', 'Privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1413, '0236174', 'URIEL GARCIA', 'Secundaria', 'Pública de gestión directa', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1414, '0591131', '50032 MIGUEL GRAU SEMINARIO', 'Secundaria', 'Pública de gestión directa', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1415, '0591198', 'ARTURO PALOMINO RODRIGUEZ', 'Secundaria', 'Pública de gestión directa', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1416, '0735035', '51014 ROMERITOS', 'Secundaria', 'Pública de gestión directa', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1417, '0579177', 'MARIA DE LA MERCED', 'Secundaria', 'Pública de gestión privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1418, '0933598', 'SAGRADO CORAZON DE JESUS', 'Secundaria', 'Pública de gestión directa', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1419, '0933333', 'EL PACIFICO', 'Secundaria', 'Privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1420, '0928200', 'OLIMPICO PERUANO', 'Secundaria', 'Pública de gestión directa', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1421, '0235481', 'URIEL GARCIA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1422, '0622621', 'URIEL GARCIA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1423, '1200419', 'DOMINGO SAVIO', 'Secundaria', 'Privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1424, '0928598', 'QOLLANA YACHAY WASI', 'Secundaria', 'Privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1425, '1201532', 'EL CLARETIANO', 'Secundaria', 'Privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1426, '1201698', 'THOMAS YOUNG', 'Secundaria de Adultos', 'Privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1427, '1061969', 'YACHAYWASI', 'Básica Alternativa-Avanzado', 'Privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1428, '1061977', 'YACHAYWASI', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1429, '1061738', 'VIRGEN DEL CARMEN', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1430, '1061779', 'VIRGEN DEL CARMEN', 'Básica Alternativa-Avanzado', 'Privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1431, '1201789', 'CENTRO JUVENIL MARCAVALLE', 'Secundaria', 'Pública de gestión directa', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1432, '1385988', 'PERUANO AMERICANO', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1433, '1385996', 'PERUANO AMERICANO', 'Básica Alternativa-Avanzado', 'Privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1434, '1269422', 'DOMINGO SAVIO', 'Básica Alternativa-Avanzado', 'Privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1435, '1386135', 'ANDRES BELLO', 'Secundaria de Adultos', 'Privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1436, '1268820', 'MASTER\'S', 'Secundaria', 'Privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1437, '1386168', 'NUESTRA SEÑORA DE FATIMA', 'Secundaria', 'Pública de gestión privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1438, '1386234', '50025 DANIEL ESTRADA PEREZ', 'Secundaria', 'Pública de gestión directa', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1439, '1386341', 'MILLENNIUM', 'Secundaria', 'Privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1440, '1386366', 'ALFA INGENIEROS', 'Secundaria', 'Privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1441, '1386390', 'PURISUNCHIS', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1442, '1386408', 'PURISUNCHIS', 'Básica Alternativa-Avanzado', 'Privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1443, '1386127', 'CAP. FAP JOSE ABELARDO QUIÑONES', 'Secundaria', 'Pública de gestión directa', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1444, '1386614', 'PITAGORAS', 'Secundaria', 'Privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1445, '1386648', 'NUESTRA SEÑORA VIRGEN DEL CARMEN', 'Básica Alternativa-Avanzado', 'Privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1446, '1386879', 'LATINOAMERICANO', 'Secundaria de Adultos', 'Privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1447, '1386986', 'YANAPANAKUSUN', 'Básica Alternativa-Avanzado', 'Privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1448, '1386994', 'YANAPANAKUSUN', 'Secundaria de Adultos', 'Privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1449, '1387315', 'NUESTRA SEÑORA VIRGEN DEL CARMEN', 'Secundaria', 'Privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1450, '1387380', 'BERTRAND RUSSELL', 'Secundaria', 'Privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1451, '1387430', 'SAN MARCOS', 'Básica Alternativa-Avanzado', 'Privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1452, '1313519', 'INFINITUM', 'Secundaria', 'Privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1453, '1323856', 'GALILEO', 'Secundaria', 'Privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1454, '1354729', 'RAIMONDI', 'Secundaria', 'Privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1455, '1370360', '51045 VELASCO ASTETE', 'Secundaria', 'Pública de gestión directa', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1456, '1522721', '50731 NUESTR SEÑORA DE LA NATIVIDAD DE PROGRESO', 'Secundaria', 'Pública de gestión directa', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1457, '1753623', 'FRAY SAN SILVESTRE DEL SUR', 'Secundaria', 'Privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1458, '1767177', 'INTERNATIONAL COLLEGE', 'Secundaria', 'Privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1459, '3001575', 'KING\'S COLLEGE', 'Secundaria', 'Privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1460, '3045457', 'GUAMAN POMA LEARNING COMMUNITY', 'Secundaria', 'Privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1461, '3048378', 'GALILEO', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1462, '3048386', 'GALILEO', 'Básica Alternativa-Avanzado', 'Privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1463, '3055670', 'HAMPTON SCHOOL PERU', 'Secundaria', 'Privada', '080108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1464, '0235531', '50052 LA MERDED', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '080201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1465, '0236430', 'TOMASA TTITO CONDEMAYTA', 'Secundaria', 'Pública de gestión directa', '080201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1466, '1388107', 'VIRGEN DEL CARMEN', 'Secundaria', 'Privada', '080201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1467, '1525047', 'JORGE EFRAIN VILLAFUERTE MUJICA', 'Secundaria', 'Pública de gestión directa', '080201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1468, '1532530', 'HUASCAR', 'Secundaria', 'Pública de gestión directa', '080201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1469, '1534965', 'DANIEL ALCIDES CARRION', 'Secundaria', 'Pública de gestión directa', '080201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1470, '1729185', '50052 LA MERDED', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '080201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1471, '0621607', 'MIGUEL ANGEL HURTADO DELGADO', 'Secundaria', 'Pública de gestión directa', '080202', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1472, '1469212', 'SAGRADO CORAZON DE JESUS', 'Secundaria', 'Pública de gestión directa', '080202', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1473, '1525039', 'DIDASCALIO NUESTRA SEÑORA DE LA ESPERANZA', 'Secundaria', 'Privada', '080202', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1474, '0579284', 'SAN MIGUEL', 'Secundaria', 'Pública de gestión directa', '080203', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1475, '1408426', 'PATRON SANTIAGO', 'Secundaria', 'Pública de gestión directa', '080203', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1476, '0931881', '56373', 'Secundaria', 'Pública de gestión directa', '080204', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1477, '0481119', 'SIMON BOLIVAR', 'Secundaria', 'Pública de gestión directa', '080205', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1478, '0934216', 'SAN JUAN BAUTISTA', 'Secundaria', 'Pública de gestión directa', '080205', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1479, '1062009', '50057 CARMEN ROSA NOGUERA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '080205', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1480, '1200633', 'SAN JOSE OBRERO', 'Secundaria', 'Privada', '080205', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1481, '1408400', 'POLICARPO CABALLERO FARFAN', 'Secundaria', 'Pública de gestión directa', '080205', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1482, '1532548', 'SANTA LUCIA', 'Secundaria', 'Pública de gestión directa', '080205', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1483, '1729193', '50057 CARMEN ROSA NOGUERA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '080205', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1484, '0621391', 'RONDOCAN', 'Secundaria', 'Pública de gestión directa', '080206', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1485, '1372507', 'WAYNACUNAQ TIKARINAN YACHAY WASIN', 'Secundaria', 'Pública de gestión privada', '080206', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1486, '0579342', 'LIBERTADORES DE AMERICA', 'Secundaria', 'Pública de gestión directa', '080207', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1487, '0932442', 'JOSE DE SAN MARTIN', 'Secundaria', 'Pública de gestión directa', '080207', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1488, '1408418', 'LAGUNA AZUL', 'Secundaria', 'Pública de gestión directa', '080207', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1489, '0236422', 'AGUSTIN GAMARRA', 'Secundaria', 'Pública de gestión directa', '080301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1490, '0591222', '50103', 'Secundaria', 'Pública de gestión directa', '080301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1491, '0616110', 'JORGE BASADRE', 'Secundaria', 'Pública de gestión directa', '080301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1492, '0931055', '50102 JUAN VELASCO ALVARADO', 'Secundaria', 'Pública de gestión directa', '080301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1493, '0931329', '50099 SAGRADO CORAZON DE JESUS', 'Secundaria', 'Pública de gestión directa', '080301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1494, '0930966', 'ILLARY LA CATOLICA', 'Secundaria', 'Privada', '080301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1495, '0930990', '51025 LA INTEGRADA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '080301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1496, '1200807', 'AGUSTIN GAMARRA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '080301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1497, '0932483', 'ILLARY', 'Secundaria de Adultos', 'Privada', '080301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1498, '1062041', 'AMAUTA', 'Básica Alternativa-Avanzado', 'Privada', '080301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1499, '1388479', 'FEDERICO VILLARREAL', 'Secundaria', 'Privada', '080301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1500, '1388511', 'AFAC - INMACULADA CONCEPCION', 'Secundaria', 'Privada', '080301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1501, '1388545', 'VIRGEN DEL CARMEN', 'Secundaria', 'Privada', '080301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1502, '1388610', 'VIRGEN DE NATIVIDAD', 'Secundaria', 'Pública de gestión privada', '080301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1503, '1321322', '50100 LA NAVAL', 'Secundaria', 'Pública de gestión directa', '080301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1504, '1355361', 'SANTA ROSA', 'Secundaria', 'Privada', '080301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1505, '1414200', 'CARNOT', 'Secundaria', 'Privada', '080301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1506, '1435395', 'MAXWELL', 'Secundaria', 'Privada', '080301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1507, '1739838', '50098', 'Secundaria', 'Pública de gestión directa', '080301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1508, '1757426', 'EDUCARE', 'Secundaria', 'Privada', '080301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1509, '1773027', 'CARRION', 'Secundaria', 'Privada', '080301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1510, '0591602', '50123 SAN LUIS GONZAGA', 'Secundaria', 'Pública de gestión directa', '080302', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1511, '0931089', '50124 JOAQUIN MESEGUER', 'Secundaria', 'Pública de gestión directa', '080302', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1512, '1388644', '50738', 'Secundaria', 'Pública de gestión directa', '080302', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1513, '1388651', '50127', 'Secundaria', 'Pública de gestión directa', '080302', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `origin_schools` (`id`, `modular_code`, `name`, `d_niv_mod`, `management_type`, `ubigeo_code`, `created_at`, `updated_at`) VALUES
(1514, '1380013', '50147', 'Secundaria', 'Pública de gestión directa', '080302', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1515, '1459791', '50737 CORONEL FRANCISCO BOLOGNESI', 'Secundaria', 'Pública de gestión directa', '080302', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1516, '1459809', '501359 SAN MARTIN DE PORRES', 'Secundaria', 'Pública de gestión directa', '080302', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1517, '1524438', 'JOAQUIN MESEGUER', 'Secundaria', 'Pública de gestión directa', '080302', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1518, '0579250', 'JOSE CARLOS MARIATEGUI', 'Secundaria', 'Pública de gestión directa', '080303', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1519, '1541747', 'ANDINO CUSCO INTERNATIONAL SCHOOL', 'Secundaria', 'Privada', '080303', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1520, '0579268', 'SAN ANTONIO ABAD', 'Secundaria', 'Pública de gestión directa', '080304', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1521, '1388677', '50118 JOSE MARIA ARGUEDAS', 'Secundaria', 'Pública de gestión directa', '080304', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1522, '1321355', 'MARTIN ESTRADA PUMA', 'Secundaria', 'Pública de gestión directa', '080304', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1523, '1321330', '50140', 'Secundaria', 'Pública de gestión directa', '080304', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1524, '1630631', '501096', 'Secundaria', 'Pública de gestión directa', '080304', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1525, '0489120', 'JULIO CESAR BENAVENTE DIAZ', 'Secundaria', 'Pública de gestión directa', '080305', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1526, '0933226', '50109', 'Secundaria', 'Pública de gestión directa', '080305', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1527, '0489146', '50111', 'Secundaria', 'Pública de gestión directa', '080306', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1528, '0621334', '50114', 'Secundaria', 'Pública de gestión directa', '080306', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1529, '1388552', 'RIO BLANCO', 'Secundaria', 'Privada', '080306', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1530, '1388669', 'MOSOQWAYNA', 'Secundaria', 'Pública de gestión privada', '080306', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1531, '1323567', '50138 APU SALQANTAY', 'Secundaria', 'Pública de gestión directa', '080306', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1532, '1729979', '51150', 'Secundaria', 'Pública de gestión directa', '080306', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1533, '0616151', '50115 DAVID SAMANEZ OCAMPO', 'Secundaria', 'Pública de gestión directa', '080307', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1534, '0579276', 'MARISCAL RAMON CASTILLA', 'Secundaria', 'Pública de gestión directa', '080308', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1535, '1669555', 'COAR CUSCO', 'Secundaria', 'Pública de gestión directa', '080308', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1536, '1669563', 'PUCYURA', 'Secundaria', 'Pública de gestión directa', '080308', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1537, '0207407', '118', 'Secundaria', 'Pública de gestión directa', '080309', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1538, '0233114', 'HUMBERTO LUNA', 'Secundaria', 'Pública de gestión directa', '080401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1539, '0236901', '28', 'Secundaria', 'Pública de gestión directa', '080401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1540, '0489302', 'HUMBERTO LUNA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '080401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1541, '0932087', 'NUESTRA SEÑORA DE BELEN', 'Secundaria', 'Pública de gestión directa', '080401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1542, '0932178', 'SAGRADO CORAZON DE JESUS', 'Secundaria', 'Pública de gestión directa', '080401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1543, '0932202', 'JUAN VELASCO ALVARADO', 'Secundaria', 'Pública de gestión directa', '080401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1544, '0933291', 'NUESTRA SEÑORA DE LA MERCED', 'Secundaria', 'Privada', '080401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1545, '0235556', 'HUMBERTO LUNA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '080401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1546, '1389220', 'CESAR VALLEJO', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '080401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1547, '1389238', 'CESAR VALLEJO', 'Básica Alternativa-Avanzado', 'Privada', '080401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1548, '1389352', 'THOMAS ALVA EDISON', 'Secundaria', 'Privada', '080401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1549, '1721422', '50187 SAN JOSE', 'Secundaria', 'Pública de gestión directa', '080401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1550, '1748375', 'AGROECOLOGICA SAN JOSE OBRERO', 'Secundaria', 'Pública de gestión directa', '080401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1551, '1756857', '50955', 'Secundaria', 'Pública de gestión directa', '080401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1552, '1769645', '50189', 'Secundaria', 'Pública de gestión directa', '080401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1553, '1772649', 'IMPERIO DEL SUR', 'Secundaria', 'Privada', '080401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1554, '0517995', 'SAN JUAN BAUTISTA', 'Secundaria', 'Pública de gestión directa', '080402', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1555, '1370386', '50190', 'Secundaria', 'Pública de gestión directa', '080402', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1556, '1777150', '50743', 'Secundaria', 'Pública de gestión directa', '080402', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1557, '0616128', 'EUSEBIO CORAZAO', 'Secundaria', 'Pública de gestión directa', '080403', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1558, '1343573', '50161', 'Secundaria', 'Pública de gestión directa', '080403', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1559, '1418615', '501225', 'Secundaria', 'Pública de gestión directa', '080403', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1560, '1458348', '50210', 'Secundaria', 'Pública de gestión directa', '080403', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1561, '1480011', '50162', 'Secundaria', 'Pública de gestión directa', '080403', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1562, '0616177', 'CESAR VALLEJO', 'Secundaria', 'Pública de gestión directa', '080404', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1563, '0730754', 'CCACHIN', 'Secundaria', 'Pública de gestión directa', '080404', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1564, '1389360', '50205', 'Secundaria', 'Pública de gestión directa', '080404', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1565, '1321371', '50171', 'Secundaria', 'Pública de gestión directa', '080404', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1566, '0730762', 'NUESTRA SEÑORA DE FATIMA', 'Secundaria', 'Pública de gestión directa', '080404', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1567, '1540889', '50206', 'Secundaria', 'Pública de gestión directa', '080404', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1568, '3043023', '501276', 'Secundaria', 'Pública de gestión directa', '080404', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1569, '3043031', '50198', 'Secundaria', 'Pública de gestión directa', '080404', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1570, '0236448', '50178 BERNARDO TAMBOHUACSO', 'Secundaria', 'Pública de gestión directa', '080405', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1571, '0932236', '50179 TAHUANTINSUYO', 'Secundaria', 'Pública de gestión directa', '080405', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1572, '0933325', 'SAGRADO CORAZON DE JESUS', 'Secundaria', 'Privada', '080405', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1573, '1062090', 'SAN MARTIN DE PORRES', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '080405', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1574, '1389212', 'ALBERT EINSTEIN', 'Secundaria de Adultos', 'Privada', '080405', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1575, '1389261', '50180 JOSE CARLOS MARIATEGUI', 'Secundaria', 'Pública de gestión directa', '080405', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1576, '1389279', 'AMAUTA', 'Secundaria', 'Pública de gestión directa', '080405', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1577, '1389303', 'DIVINO CORAZON', 'Secundaria', 'Privada', '080405', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1578, '1389428', 'ALBERT EINSTEIN', 'Básica Alternativa-Avanzado', 'Privada', '080405', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1579, '1639103', 'KUSI KAWSAY', 'Secundaria', 'Privada', '080405', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1580, '1661164', 'TAMBO DE GOZO', 'Secundaria', 'Privada', '080405', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1581, '1734219', 'SAN MARTIN DE PORRES', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '080405', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1582, '1759711', '50214', 'Secundaria', 'Pública de gestión directa', '080405', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1583, '1797281', '50182', 'Secundaria', 'Pública de gestión directa', '080405', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1584, '0579292', 'SEÑOR DE HUANCA', 'Secundaria', 'Pública de gestión directa', '080406', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1585, '1321421', 'CORAZON DE JESUS', 'Secundaria', 'Pública de gestión directa', '080406', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1586, '1343581', 'SEÑOR JUSTO JUEZ', 'Secundaria', 'Pública de gestión directa', '080406', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1587, '1748409', '50217', 'Secundaria', 'Pública de gestión directa', '080406', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1588, '1387067', 'VIRGEN ROSARIO', 'Secundaria', 'Pública de gestión directa', '080407', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1589, '0495408', 'NUESTRA SEÑORA DEL CARMEN', 'Secundaria', 'Pública de gestión directa', '080408', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1590, '0621664', '50174', 'Secundaria', 'Pública de gestión directa', '080408', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1591, '0647602', 'JOSE MARIA ARGUEDAS', 'Secundaria', 'Pública de gestión directa', '080408', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1592, '0647636', '50169', 'Secundaria', 'Pública de gestión directa', '080408', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1593, '0672030', 'AGUSTIN DE ALAMO', 'Secundaria', 'Pública de gestión directa', '080408', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1594, '0791640', '50170', 'Secundaria', 'Pública de gestión directa', '080408', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1595, '0933119', '50176 SAN LUIS GONZAGA', 'Secundaria', 'Pública de gestión directa', '080408', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1596, '0791665', 'MONTE SALVADO', 'Secundaria', 'Pública de gestión privada', '080408', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1597, '1389402', '50950', 'Secundaria', 'Pública de gestión directa', '080408', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1598, '1389337', 'AMAUTA', 'Básica Alternativa-Avanzado', 'Privada', '080408', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1599, '1389444', 'AMAUTA', 'Secundaria de Adultos', 'Privada', '080408', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1600, '1372440', '501306', 'Secundaria', 'Pública de gestión directa', '080408', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1601, '1528132', '50173', 'Secundaria', 'Pública de gestión directa', '080408', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1602, '1568443', '501273 TUPAC AMARU YAVERO', 'Secundaria', 'Pública de gestión directa', '080408', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1603, '1739077', '50704', 'Secundaria', 'Pública de gestión directa', '080408', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1604, '1796937', '501184', 'Secundaria', 'Pública de gestión directa', '080408', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1605, '1801927', '50817', 'Secundaria', 'Pública de gestión directa', '080408', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1606, '0236653', 'JOSE GABRIEL CONDORCANQUI', 'Secundaria', 'Pública de gestión directa', '080501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1607, '0586842', 'JOSE GABRIEL CONDORCANQUI', 'Secundaria de Adultos', 'Pública de gestión directa', '080501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1608, '0931824', 'INDEPENDENCIA AMERICANA', 'Básica Alternativa', 'Pública de gestión directa', '080501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1609, '0791525', '56105 INDEPENDENCIA AMERICANA', 'Secundaria', 'Pública de gestión directa', '080501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1610, '0931857', 'HAMPATURA', 'Secundaria', 'Pública de gestión directa', '080501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1611, '1390087', '56111 RICARDO PALMA', 'Secundaria', 'Pública de gestión directa', '080501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1612, '1325059', '56108', 'Secundaria', 'Pública de gestión directa', '080501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1613, '1325067', '56113', 'Secundaria', 'Pública de gestión directa', '080501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1614, '1349471', '56110', 'Secundaria', 'Pública de gestión directa', '080501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1615, '1361070', 'JGC TUPAC AMARU II', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '080501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1616, '1725233', 'JGC TUPAC AMARU II', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '080501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1617, '1754555', 'CARRION', 'Secundaria', 'Privada', '080501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1618, '0587055', 'SAN ANDRES', 'Secundaria', 'Pública de gestión directa', '080502', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1619, '0931063', 'CESAR VALLEJO', 'Secundaria', 'Pública de gestión directa', '080502', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1620, '1390467', 'ELIAN KARP', 'Secundaria', 'Pública de gestión directa', '080502', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1621, '1390665', 'ALTIVA CANAS', 'Secundaria', 'Pública de gestión directa', '080502', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1622, '1390673', 'INTEQ KANCHAN', 'Secundaria', 'Pública de gestión directa', '080502', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1623, '0517581', 'ANDRES ALENCASTRE GUTIERREZ', 'Secundaria', 'Pública de gestión directa', '080503', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1624, '1201888', 'OSWALDO BACA MENDOZA', 'Secundaria', 'Privada', '080503', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1625, '1390517', 'HUARCACHAPI', 'Secundaria', 'Pública de gestión directa', '080503', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1626, '0931030', 'ANDRES ALENCASTRE GUTIERREZ', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '080503', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1627, '1364868', '56126', 'Secundaria', 'Pública de gestión directa', '080503', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1628, '1374438', '56125 VICTOR PORCEL ESQUIVEL', 'Secundaria', 'Pública de gestión directa', '080503', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1629, '1725449', 'ANDRES ALENCASTRE GUTIERREZ', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '080503', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1630, '0519678', 'AUDAZ DEL CASTILLO', 'Secundaria', 'Pública de gestión directa', '080504', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1631, '1379551', '56413', 'Secundaria', 'Pública de gestión directa', '080504', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1632, '0579458', 'TECNICO AGROPECUARIO', 'Secundaria', 'Pública de gestión directa', '080505', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1633, '0931097', 'LAYO', 'Secundaria de Adultos', 'Pública de gestión directa', '080505', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1634, '1390491', 'HORACIO ZEVALLOS GAMEZ', 'Secundaria', 'Pública de gestión directa', '080505', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1635, '1311240', 'QOTAQWASI', 'Secundaria', 'Pública de gestión directa', '080505', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1636, '1320902', 'SAGRADO CORAZON', 'Básica Alternativa-Avanzado', 'Privada', '080505', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1637, '1801091', 'TECNICO AGROPECUARIO', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '080505', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1638, '0579409', 'MICAELA BASTIDAS', 'Secundaria', 'Pública de gestión directa', '080506', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1639, '1358118', 'HOGAR MARIA DE NAZARETH', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '080506', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1640, '1361310', 'HOGAR MARIA DE NAZARETH', 'Básica Alternativa-Avanzado', 'Privada', '080506', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1641, '0579425', 'SEÑOR DE EXALTACION', 'Secundaria', 'Pública de gestión directa', '080507', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1642, '1201540', 'HIPOLITO TUPAC AMARU', 'Secundaria', 'Pública de gestión directa', '080507', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1643, '1390095', '56123 SAN JUAN BAUTISTA', 'Secundaria', 'Pública de gestión directa', '080507', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1644, '1377183', '56378 MARTIRES DE CHOCCAYHUA', 'Secundaria', 'Pública de gestión directa', '080507', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1645, '0579391', '56114 FERNANDO TUPAC AMARU', 'Secundaria', 'Pública de gestión directa', '080508', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1646, '1201581', '56117 LIBERTADOR TUPAC AMARU', 'Secundaria', 'Pública de gestión directa', '080508', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1647, '1390111', '56116 INCA TUPAC AMARU II', 'Secundaria', 'Pública de gestión directa', '080508', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1648, '0235853', '57001 JORGE CHAVEZ', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '080601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1649, '0235929', '56003 INMACULADA CONCEPCION', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '080601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1650, '0207373', 'AGROPECUARIO', 'Secundaria', 'Pública de gestión directa', '080601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1651, '0236216', 'MATEO PUMACAHUA', 'Secundaria', 'Pública de gestión directa', '080601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1652, '0477828', 'INMACULADA CONCEPCION', 'Secundaria', 'Pública de gestión directa', '080601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1653, '0519173', 'EL AMAUTA', 'Secundaria', 'Pública de gestión directa', '080601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1654, '0617647', 'JAPAM', 'Secundaria', 'Pública de gestión directa', '080601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1655, '0636928', 'VICTOR SANTANDER CASCELLI', 'Secundaria', 'Privada', '080601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1656, '0636936', 'JOSE MARIA ARGUEDAS', 'Secundaria', 'Pública de gestión directa', '080601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1657, '0236893', 'MATEO PUMACAHUA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '080601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1658, '0617654', '56003 INMACULADA CONCEPCION', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '080601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1659, '0639245', '56433 REAL SANTA CRUZ', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '080601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1660, '0579474', 'JOSE CARLOS MARIATEGUI', 'Secundaria', 'Pública de gestión directa', '080601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1661, '0930859', 'TUPAC AMARU II', 'Secundaria', 'Pública de gestión directa', '080601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1662, '0930883', 'MANUEL CALLO ZAVALLOS', 'Secundaria', 'Privada', '080601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1663, '0930917', 'ENSIL LAS AMERICAS', 'Secundaria', 'Privada', '080601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1664, '0930941', 'ALBANO QUINN', 'Secundaria', 'Privada', '080601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1665, '0930974', 'SICUANI', 'Secundaria', 'Privada', '080601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1666, '0931121', 'TAWANTINSUYO', 'Secundaria de Adultos', 'Privada', '080601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1667, '1390558', 'TAWANTINSUYO', 'Básica Alternativa-Avanzado', 'Privada', '080601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1668, '1390533', 'TRILCE', 'Básica Alternativa-Avanzado', 'Privada', '080601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1669, '1390582', '56433 REAL SANTA CRUZ', 'Secundaria', 'Pública de gestión directa', '080601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1670, '1390681', 'KARL FRIEDRICH GAUSS', 'Secundaria', 'Privada', '080601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1671, '1390715', 'VICTOR SANTANDER CASCELLI', 'Secundaria de Adultos', 'Privada', '080601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1672, '1390756', 'SALESIANO DOMINGO SAVIO', 'Secundaria', 'Privada', '080601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1673, '1390772', 'SICUANI', 'Secundaria de Adultos', 'Privada', '080601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1674, '1390780', 'MARIA DEL CARMEN', 'Secundaria', 'Privada', '080601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1675, '1341585', '56334', 'Secundaria', 'Pública de gestión directa', '080601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1676, '1360973', 'MATEO PUMACAHUA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '080601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1677, '1372481', 'SAN MATEO SCHOOL', 'Secundaria', 'Privada', '080601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1678, '1393560', '56006 GAONA CISNEROS', 'Secundaria', 'Pública de gestión directa', '080601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1679, '1434927', 'SAN IGNACIO DE LOYOLA', 'Secundaria', 'Privada', '080601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1680, '1645571', '56008 COLEGIO BOLIVARIANO DE SICUANI', 'Secundaria', 'Pública de gestión directa', '080601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1681, '1671049', 'ADUNIS', 'Secundaria', 'Privada', '080601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1682, '1696624', 'DIOCESANA NUESTRA SEÑORA DEL CARMEN', 'Secundaria', 'Privada', '080601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1683, '0207381', 'ALMIRANTE MIGUEL GRAU', 'Secundaria', 'Pública de gestión directa', '080602', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1684, '1377787', 'MARTIRES DE LA INDEPENDENCIA', 'Secundaria', 'Pública de gestión directa', '080602', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1685, '0236638', 'JERONIMO ZAVALA', 'Secundaria', 'Pública de gestión directa', '080603', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1686, '0931154', 'JERONIMO ZAVALA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '080603', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1687, '0931188', 'COMBAPATA', 'Secundaria de Adultos', 'Privada', '080603', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1688, '1379569', 'MIGUEL TORRES VILLA', 'Secundaria', 'Pública de gestión directa', '080603', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1689, '1412642', 'PAROPATA', 'Secundaria', 'Pública de gestión directa', '080603', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1690, '1724236', 'JERONIMO ZAVALA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '080603', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1691, '0519579', 'JORGE CHAVEZ', 'Secundaria', 'Pública de gestión directa', '080604', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1692, '0579466', 'VICTOR RAUL HAYA DE LA TORRE', 'Secundaria', 'Pública de gestión directa', '080604', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1693, '0679787', '56025 JORGE CHAVEZ', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '080604', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1694, '0680074', '56025 JORGE CHAVEZ', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '080604', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1695, '1390525', 'LUIS NIETO MIRANDA', 'Secundaria', 'Pública de gestión directa', '080604', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1696, '1379577', 'CPED - 56073', 'Secundaria', 'Pública de gestión directa', '080604', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1697, '0579417', 'LIBERTADORES DE AMERICA', 'Secundaria', 'Pública de gestión directa', '080605', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1698, '1390624', 'UCHULLUCLLU', 'Secundaria', 'Pública de gestión directa', '080605', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1699, '1332584', 'PHINAYA', 'Secundaria', 'Pública de gestión directa', '080605', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1700, '1732494', 'AUSANGATE', 'Secundaria', 'Privada', '080605', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1701, '1788884', 'AUSANGATE', 'Secundaria', 'Pública de gestión directa', '080605', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1702, '3027919', '501461', 'Secundaria', 'Pública de gestión directa', '080605', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1703, '0535856', 'LIBERTADOR SIMON BOLIVAR', 'Secundaria', 'Pública de gestión directa', '080606', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1704, '0586750', 'INCA PACHACUTEC', 'Secundaria', 'Pública de gestión directa', '080606', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1705, '1061886', 'SANTA BARBARA', 'Secundaria', 'Pública de gestión directa', '080606', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1706, '0519272', 'SAN PEDRO', 'Secundaria', 'Pública de gestión directa', '080607', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1707, '0679928', '56443', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '080608', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1708, '0499368', 'EMANCIPACION AMERICANA', 'Secundaria', 'Pública de gestión directa', '080608', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1709, '0617613', 'MARINO BLANCAS TUMIALAN', 'Secundaria de Adultos', 'Pública de gestión directa', '080608', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1710, '1360981', 'MARINO BLANCAS TUMIALAN', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '080608', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1711, '1379585', '56042', 'Secundaria', 'Pública de gestión directa', '080608', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1712, '0236661', 'SANTO TOMAS', 'Secundaria', 'Pública de gestión directa', '080701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1713, '0586966', 'CAPITAN FELIPE BERMUDEZ', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '080701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1714, '0933879', 'CAPITAN FELIPE BERMUDEZ', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '080701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1715, '0783787', 'GENERAL OLLANTA', 'Secundaria', 'Pública de gestión directa', '080701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1716, '0933846', 'DANIEL ESTRADA PEREZ', 'Secundaria', 'Pública de gestión directa', '080701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1717, '0934141', 'ANTERO EFRAIN UGARTE VIZCARRA', 'Secundaria', 'Pública de gestión directa', '080701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1718, '0934117', 'ARTESANAL', 'Secundaria', 'Privada', '080701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1719, '1392075', '56256', 'Secundaria', 'Pública de gestión directa', '080701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1720, '1392133', 'LLAPANCHIS YACHASUNCHIS', 'Secundaria', 'Pública de gestión privada', '080701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1721, '1392224', '57004 ROSA DE AMERICA', 'Secundaria', 'Pública de gestión directa', '080701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1722, '1392240', '56318 ALMIRANTE MIGUEL GRAU SEMINARIO', 'Secundaria', 'Pública de gestión directa', '080701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1723, '1401942', '56253 SEÑOR DE LOS MILAGROS', 'Secundaria', 'Pública de gestión directa', '080701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1724, '1401967', '56311', 'Secundaria', 'Pública de gestión directa', '080701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1725, '1423011', '56315', 'Secundaria', 'Pública de gestión directa', '080701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1726, '1786573', 'SAN JUAN INNOVA SCHOOL', 'Secundaria', 'Privada', '080701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1727, '3017910', 'ORLEANS GOLEMAN', 'Secundaria', 'Privada', '080701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1728, '0617829', 'LAS AMERICAS', 'Secundaria', 'Pública de gestión directa', '080702', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1729, '1392232', 'CANCAHUANI', 'Secundaria', 'Pública de gestión directa', '080702', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1730, '1540996', '56277 SAN ANTONIO DE PADUA', 'Secundaria', 'Pública de gestión directa', '080702', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1731, '1540970', '56261', 'Secundaria', 'Pública de gestión directa', '080702', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1732, '0680124', 'DANIEL ALCIDES CARRION', 'Secundaria', 'Pública de gestión directa', '080703', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1733, '0783720', 'ANTONIO RAYMONDI', 'Secundaria', 'Pública de gestión directa', '080703', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1734, '0933283', 'SIMON BOLIVAR', 'Secundaria', 'Pública de gestión directa', '080703', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1735, '1347301', '56265', 'Secundaria', 'Pública de gestión directa', '080703', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1736, '1401959', '56279', 'Secundaria', 'Pública de gestión directa', '080703', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1737, '1733120', '56280 VIRGEN DE FATIMA DE CCACHO', 'Secundaria', 'Pública de gestión directa', '080703', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1738, '0587204', 'LA MERCED', 'Secundaria', 'Pública de gestión directa', '080704', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1739, '0783795', 'ARMIRI', 'Secundaria', 'Pública de gestión directa', '080704', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1740, '1392083', '56324', 'Secundaria', 'Pública de gestión directa', '080704', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1741, '1392091', '56258', 'Secundaria', 'Pública de gestión directa', '080704', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1742, '1327279', '56326', 'Secundaria', 'Pública de gestión directa', '080704', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1743, '1347269', '56257', 'Secundaria', 'Pública de gestión directa', '080704', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1744, '1401934', '56325 INDEPENDENCIA', 'Secundaria', 'Pública de gestión directa', '080704', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1745, '1523794', '56259', 'Secundaria', 'Pública de gestión directa', '080704', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1746, '3050531', 'NIKOLA TESLA', 'Secundaria', 'Privada', '080704', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1747, '0933317', 'SAN BARTOLOME', 'Secundaria', 'Pública de gestión directa', '080705', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1748, '1390129', '56268 FERNANDO BELAUNDE TERRY', 'Secundaria', 'Pública de gestión directa', '080705', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1749, '0525923', 'SAN SEBASTIAN', 'Secundaria', 'Pública de gestión directa', '080705', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1750, '0783696', 'RAMON PONCE MOLINA', 'Secundaria', 'Pública de gestión directa', '080705', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1751, '1392141', '56289 RICARDO PALMA DE QQUECHAPAMPA', 'Secundaria', 'Pública de gestión directa', '080705', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1752, '1325075', '56392 JUAN VELASCO ALVARADO', 'Secundaria', 'Pública de gestión directa', '080705', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1753, '1325083', '56267', 'Secundaria', 'Pública de gestión directa', '080705', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1754, '1347277', '56286', 'Secundaria', 'Pública de gestión directa', '080705', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1755, '1349489', '56283', 'Secundaria', 'Pública de gestión directa', '080705', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1756, '1392117', '56377', 'Secundaria', 'Pública de gestión directa', '080705', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1757, '1401926', '56285 INCA GARCILAZO DE LA VEGA', 'Secundaria', 'Pública de gestión directa', '080705', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1758, '1412873', '56266', 'Secundaria', 'Pública de gestión directa', '080705', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1759, '1523828', '56370 MARIO VARGAS LLOSA', 'Secundaria', 'Pública de gestión directa', '080705', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1760, '1637263', 'JOSE MARIA ARGUEDAS', 'Secundaria', 'Pública de gestión directa', '080705', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1761, '0783704', 'JOSE CARLOS MARIATEGUI', 'Secundaria', 'Pública de gestión directa', '080706', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1762, '0932608', 'JUAN VELASCO ALVARADO', 'Secundaria', 'Pública de gestión directa', '080706', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1763, '1392174', 'INKA YAWAR', 'Secundaria', 'Pública de gestión privada', '080706', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1764, '1347293', '56292', 'Secundaria', 'Pública de gestión directa', '080706', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1765, '1423003', '56271 SEÑOR DE LOS MILAGROS', 'Secundaria', 'Pública de gestión directa', '080706', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1766, '1523810', '56295', 'Secundaria', 'Pública de gestión directa', '080706', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1767, '0639542', 'JOSE ANTONIO ENCINAS', 'Secundaria', 'Pública de gestión directa', '080707', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1768, '1392109', '56297', 'Secundaria', 'Pública de gestión directa', '080707', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1769, '1523802', '56273', 'Secundaria', 'Pública de gestión directa', '080707', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1770, '1540988', '56274', 'Secundaria', 'Pública de gestión directa', '080707', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1771, '0518084', 'JUAN DE DIOS VALENCIA', 'Secundaria', 'Pública de gestión directa', '080708', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1772, '0932632', 'TECNICO ARTESANAL', 'Secundaria', 'Privada', '080708', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1773, '0783738', 'JUAN DE DIOS VALENCIA', 'Secundaria de Adultos', 'Pública de gestión directa', '080708', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1774, '1392125', '56275', 'Secundaria', 'Pública de gestión directa', '080708', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1775, '1392257', '56305', 'Secundaria', 'Pública de gestión directa', '080708', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1776, '1327287', '56300 MARCELINO VALENCIA ALVARO', 'Secundaria', 'Pública de gestión directa', '080708', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1777, '1347285', '56299', 'Secundaria', 'Pública de gestión directa', '080708', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1778, '1360999', 'JUAN DE DIOS VALENCIA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '080708', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1779, '1732825', 'VELILLE', 'Secundaria', 'Privada', '080708', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1780, '0236646', 'CORONEL LADISLAO ESPINAR', 'Secundaria', 'Pública de gestión directa', '080801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1781, '0579433', 'ADVENTISTA', 'Secundaria', 'Privada', '080801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1782, '0680082', 'TENIENTE CORONEL PEDRO RUIZ GALLO', 'Secundaria', 'Pública de gestión directa', '080801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1783, '0680090', 'TINTAYA FISCALIZADO', 'Secundaria', 'Privada', '080801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1784, '0586818', '56175 SAGRADO CORAZON DE JESUS', 'Secundaria de Adultos', 'Pública de gestión directa', '080801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1785, '0783175', 'CORONEL LADISLAO ESPINAR', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '080801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1786, '0931345', 'JOSE ANTONIO ENCINAS', 'Secundaria', 'Pública de gestión directa', '080801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1787, '0931436', '56175 SAGRADO CORAZON DE JESUS', 'Secundaria', 'Pública de gestión directa', '080801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1788, '0931469', '56207 RICARDO PALMA SORIANO', 'Secundaria', 'Pública de gestión directa', '080801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1789, '0931378', 'INCA GARCILASO DE LA VEGA', 'Secundaria', 'Privada', '080801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1790, '0783233', 'TENIENTE CORONEL PEDRO RUIZ GALLO', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '080801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1791, '0931402', 'SAN JUAN BAUTISTA DE LA SALLE', 'Secundaria', 'Privada', '080801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1792, '1200708', 'JORGE BASADRE GROHMANN', 'Secundaria', 'Privada', '080801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1793, '1200286', 'INTERNACIONAL BHP-TINTAYA', 'Secundaria', 'Privada', '080801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1794, '0783217', 'ALTO HUARCA', 'Secundaria', 'Pública de gestión directa', '080801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1795, '0931493', 'JESUS G.ALMANZA QUILLILLI', 'Secundaria de Adultos', 'Pública de gestión directa', '080801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1796, '0933432', 'CESAR VALLEJO', 'Secundaria', 'Privada', '080801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1797, '1200625', 'EL AMAUTA', 'Secundaria de Adultos', 'Privada', '080801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1798, '1062165', 'ANTONIO RAYMONDI', 'Básica Alternativa-Avanzado', 'Privada', '080801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1799, '1201797', 'ALBANO QUINN WILSON', 'Básica Alternativa-Avanzado', 'Privada', '080801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1800, '1201821', 'TINTAYA', 'Secundaria de Adultos', 'Privada', '080801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1801, '1392992', 'CIENCIAS PITAGORAS', 'Secundaria', 'Privada', '080801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1802, '1393065', 'SAN AGUSTIN DE HIPONA', 'Secundaria', 'Privada', '080801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1803, '1393123', 'LEONCIO PRADO - AMAUTA', 'Secundaria', 'Privada', '080801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1804, '1393131', '57003 ALMIRANTE MIGUEL GRAU', 'Secundaria', 'Pública de gestión directa', '080801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1805, '1393248', 'ALFREDO BRYCE ECHENIQUE', 'Secundaria', 'Privada', '080801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1806, '1393313', 'FARADAY', 'Secundaria', 'Privada', '080801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1807, '1393321', 'SAN MARCOS', 'Secundaria de Adultos', 'Privada', '080801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1808, '1393370', 'ALFREDO BRYCE ECHENIQUE', 'Secundaria de Adultos', 'Privada', '080801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1809, '1393388', 'JORGE BASADRE GROHMANN', 'Básica Alternativa-Avanzado', 'Privada', '080801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1810, '1343789', '56394 CESAR VALLEJO', 'Secundaria', 'Pública de gestión directa', '080801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1811, '1361005', 'CORONEL LADISLAO ESPINAR', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '080801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1812, '1361013', 'TENIENTE CORONEL PEDRO RUIZ GALLO', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '080801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1813, '1579325', '56435', 'Secundaria', 'Pública de gestión directa', '080801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1814, '1723469', '501367 INMACULADA CONCEPCION', 'Secundaria', 'Pública de gestión directa', '080801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1815, '1725548', 'ALBANO QUINN WILSON', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '080801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1816, '1727775', '56197', 'Secundaria', 'Pública de gestión directa', '080801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1817, '1773456', '56605', 'Secundaria', 'Pública de gestión directa', '080801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1818, '0617845', 'PEDRO JULIO VALDIVIA DEZA', 'Secundaria', 'Pública de gestión directa', '080802', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1819, '1773464', '501304', 'Secundaria', 'Pública de gestión directa', '080802', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1820, '0617779', 'TUPAC AMARU', 'Secundaria', 'Pública de gestión directa', '080803', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1821, '0617787', 'BARTOLOME DE LAS CASAS', 'Secundaria', 'Pública de gestión directa', '080803', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1822, '0639450', 'HORACIO ZEBALLOS GAMEZ', 'Secundaria', 'Pública de gestión directa', '080803', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1823, '0783225', 'JOSE MARIA ARGUEDAS', 'Secundaria', 'Pública de gestión directa', '080803', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1824, '1320647', '56218', 'Secundaria', 'Pública de gestión directa', '080803', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1825, '1343797', '56214 ANDRES AVELINO CACERES', 'Secundaria', 'Pública de gestión directa', '080803', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1826, '1397637', 'K\'ANAKUNAQ TIKARINAN YACHAYWASI', 'Secundaria', 'Pública de gestión privada', '080803', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1827, '1525617', 'TAHUAPALCCA', 'Secundaria', 'Pública de gestión directa', '080803', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1828, '0636944', 'JOSE CARLOS MARIATEGUI', 'Secundaria', 'Pública de gestión directa', '080804', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1829, '0518282', 'HECTOR TEJADA', 'Secundaria', 'Pública de gestión directa', '080805', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1830, '1062397', '56191 INDEPENDENCIA AMERICANA', 'Secundaria', 'Pública de gestión directa', '080805', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1831, '1393024', 'SAN FRANCISCO DE ASIS', 'Secundaria de Adultos', 'Privada', '080805', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1832, '1393180', 'JAYUNI MAYUCHULLO', 'Secundaria', 'Pública de gestión directa', '080805', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1833, '1320621', 'APARICIO SAICO', 'Secundaria de Adultos', 'Privada', '080805', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1834, '0579441', 'PICHIGUA', 'Secundaria', 'Pública de gestión directa', '080806', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1835, '0783191', 'SAN MIGUEL', 'Secundaria', 'Pública de gestión directa', '080806', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1836, '1320654', '56233', 'Secundaria', 'Pública de gestión directa', '080806', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1837, '0783209', 'CLORINDA MATTO DE TURNER', 'Secundaria', 'Pública de gestión directa', '080807', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1838, '1320639', '56445', 'Secundaria', 'Pública de gestión directa', '080807', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1839, '0617837', 'GRAL JUAN VELASCO ALVARADO', 'Secundaria', 'Pública de gestión directa', '080808', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1840, '0235564', '50236 SANTA ANA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1841, '0233098', 'MANCO II', 'Secundaria', 'Pública de gestión directa', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1842, '0233221', 'LA CONVENCION', 'Secundaria', 'Pública de gestión directa', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1843, '0235986', 'MARISCAL CASTILLA', 'Secundaria', 'Privada', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1844, '0236919', 'INA 67', 'Secundaria', 'Pública de gestión directa', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1845, '0579193', 'TUPAC AMARU', 'Secundaria', 'Pública de gestión directa', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1846, '0621243', 'CHRISTIAN BUES', 'Secundaria', 'Pública de gestión directa', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1847, '0236844', 'MANCO II', 'Secundaria de Adultos', 'Pública de gestión directa', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1848, '0932673', '50236 SANTA ANA', 'Secundaria', 'Pública de gestión directa', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1849, '0932707', '50226 LA INMACULADA', 'Secundaria', 'Pública de gestión directa', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1850, '0932731', 'BUEN MAESTRO', 'Secundaria', 'Privada', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1851, '0932764', 'LICEO MEGANTONI', 'Secundaria', 'Privada', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1852, '0932798', 'MANCO II AREA TECNICA COMERCIAL', 'Secundaria', 'Pública de gestión directa', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1853, '1062520', 'NUESTRA SEÑORA DE FATIMA', 'Secundaria', 'Privada', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1854, '1062561', 'ABRAHAM LINCOLN', 'Secundaria', 'Privada', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1855, '1200732', 'SAN AGUSTIN', 'Secundaria', 'Privada', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1856, '1201938', 'LA SALLE', 'Secundaria', 'Privada', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1857, '1201961', 'LA SALLE', 'Secundaria de Adultos', 'Privada', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1858, '1200195', 'MARISCAL CASTILLA', 'Básica Alternativa-Avanzado', 'Privada', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1859, '1200757', 'PRONESA CHRISTIAN BUEZ', 'Secundaria de Adultos', 'Privada', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1860, '1393925', 'MARIA MONTESSORI', 'Secundaria', 'Privada', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1861, '1393941', 'CHRISTIAN BUEZ', 'Secundaria', 'Privada', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1862, '1394022', 'QUILLABAMBA', 'Secundaria', 'Privada', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1863, '1394105', 'LA SALLE', 'Básica Alternativa-Avanzado', 'Privada', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1864, '1394113', 'LA SALLE', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1865, '1394121', '51027 JUAN DE LA CRUZ MONTES SALAS', 'Secundaria', 'Pública de gestión directa', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1866, '1394188', 'SAN AGUSTIN', 'Secundaria de Adultos', 'Privada', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1867, '1394204', 'KONRAD ADENAHUER', 'Básica Alternativa-Avanzado', 'Privada', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1868, '1394212', 'KONRAD ADENAHUER', 'Secundaria de Adultos', 'Privada', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1869, '1394303', 'SAN IGNACIO DE LOYOLA', 'Secundaria', 'Privada', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1870, '1394311', 'SAN IGNACIO DE LOYOLA', 'Secundaria de Adultos', 'Privada', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1871, '1394337', 'SAN DE LA SAL', 'Secundaria', 'Privada', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1872, '1394345', 'SAN DE LA SAL', 'Secundaria de Adultos', 'Privada', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1873, '1394402', 'MARISCAL CASTILLA', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1874, '1323112', 'SANTA ANA', 'Básica Alternativa-Avanzado', 'Privada', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1875, '1323138', 'VIRGEN DEL CARMEN', 'Secundaria', 'Privada', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1876, '1323146', 'SANTA ANA', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1877, '1324581', 'FRAY MARTIN DE PORRES', 'Secundaria', 'Privada', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1878, '1361021', 'MANCO II', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1879, '1405232', '501156 SAGRADO CORAZON DE JESUS', 'Secundaria', 'Pública de gestión directa', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1880, '1639186', 'TALENTOS DE PITAGORAS', 'Secundaria', 'Privada', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1881, '1701044', 'AURELIO BALDOR', 'Secundaria', 'Privada', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `origin_schools` (`id`, `modular_code`, `name`, `d_niv_mod`, `management_type`, `ubigeo_code`, `created_at`, `updated_at`) VALUES
(1882, '1726322', 'KONRAD ADENAHUER', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1883, '1773472', 'ISAAC NEWTON', 'Básica Alternativa-Avanzado', 'Privada', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1884, '1784370', '50952', 'Secundaria', 'Pública de gestión directa', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1885, '3048048', 'ALBERT EINSTEIN LC', 'Secundaria', 'Privada', '080901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1886, '0236398', 'JOSE MARIA ARGUEDAS', 'Secundaria', 'Pública de gestión directa', '080902', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1887, '0495226', 'JOSE CARLOS MARIATEGUI', 'Secundaria', 'Pública de gestión directa', '080902', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1888, '0621540', '50898 MIGUEL GRAU', 'Secundaria', 'Pública de gestión directa', '080902', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1889, '0672279', 'TUPAC AMARU', 'Secundaria', 'Pública de gestión directa', '080902', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1890, '0699660', 'SAN ANTONIO DE PADUA', 'Secundaria', 'Pública de gestión directa', '080902', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1891, '0750364', 'SANTO DOMINGO', 'Secundaria', 'Pública de gestión directa', '080902', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1892, '0750372', 'MEDIO URUBAMBA', 'Secundaria', 'Pública de gestión directa', '080902', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1893, '0933705', 'ALTO URUBAMBA', 'Secundaria', 'Privada', '080902', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1894, '1062215', 'CHAUPIMAYO C', 'Secundaria', 'Privada', '080902', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1895, '0621516', 'MANUEL GONZALES PRADA', 'Secundaria', 'Pública de gestión directa', '080902', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1896, '0750356', 'NUEVA CALIFORNIA', 'Secundaria', 'Pública de gestión directa', '080902', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1897, '0750398', 'SAN ANTONIO', 'Secundaria', 'Pública de gestión directa', '080902', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1898, '0783092', 'ILLAPANI', 'Secundaria', 'Privada', '080902', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1899, '0932855', 'VIRGEN DEL CARMEN', 'Secundaria', 'Privada', '080902', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1900, '0932889', 'SANTA ROSA DE LIMA', 'Secundaria', 'Privada', '080902', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1901, '0932913', 'MARTIN PIO CONCHA', 'Secundaria', 'Privada', '080902', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1902, '0932947', 'SANTOATO', 'Secundaria', 'Privada', '080902', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1903, '0932970', 'DOS DE MAYO', 'Secundaria', 'Privada', '080902', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1904, '1394246', 'LA SALLE - KITENI', 'Básica Alternativa-Avanzado', 'Privada', '080902', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1905, '1394519', '501108 MEDIO URUBAMBA', 'Secundaria', 'Pública de gestión directa', '080902', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1906, '1394527', 'YOMENTONI', 'Secundaria', 'Privada', '080902', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1907, '1394550', 'MOSOQ ILLARY', 'Secundaria', 'Pública de gestión privada', '080902', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1908, '0713826', '50245 JOSE PIO AZA', 'Secundaria', 'Pública de gestión privada', '080902', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1909, '1323955', 'RIQCHARIY WAYNA', 'Secundaria', 'Pública de gestión privada', '080902', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1910, '1423888', 'OTYARIRA ONEAKOTANA ENKANIRIRA', 'Secundaria', 'Pública de gestión privada', '080902', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1911, '1629369', 'PONGO DE MAYNIQUE DE PACHIRI', 'Secundaria', 'Pública de gestión directa', '080902', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1912, '1722263', '50902', 'Secundaria', 'Pública de gestión directa', '080902', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1913, '1747989', 'MOSOQ ILLARY WAYNA', 'Secundaria', 'Pública de gestión directa', '080902', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1914, '1786540', '50726', 'Secundaria', 'Pública de gestión privada', '080902', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1915, '1790054', '52106', 'Secundaria', 'Pública de gestión privada', '080902', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1916, '0236380', 'JOSE CARLOS MARIATEGUI', 'Secundaria', 'Pública de gestión directa', '080903', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1917, '0621367', 'LEONCIO PRADO', 'Secundaria', 'Pública de gestión directa', '080903', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1918, '0699678', 'CORONEL FRANCISCO BOLOGNESI', 'Secundaria', 'Pública de gestión directa', '080903', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1919, '0934257', 'JOSE CARLOS MARIATEGUI', 'Secundaria de Adultos', 'Pública de gestión directa', '080903', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1920, '1361047', 'JOSE CARLOS MARIATEGUI', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '080903', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1921, '0236372', 'MICAELA BASTIDAS', 'Secundaria', 'Pública de gestión directa', '080904', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1922, '0489153', 'SEÑOR DE LOS MILAGROS', 'Secundaria', 'Pública de gestión directa', '080904', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1923, '0718528', 'MARANURA', 'Secundaria de Adultos', 'Pública de gestión directa', '080904', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1924, '0934315', 'CESAR ABRAHAM VALLEJO MENDOZA', 'Secundaria', 'Pública de gestión directa', '080904', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1925, '1361039', 'JOSE GABRIEL CONDORCANQUI', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '080904', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1926, '0621698', 'INKA PACHAKUTEQ', 'Secundaria', 'Pública de gestión directa', '080905', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1927, '0718551', 'SAN JUAN BAUTISTA', 'Secundaria', 'Pública de gestión directa', '080905', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1928, '0718569', 'SAN LORENZO', 'Secundaria', 'Pública de gestión directa', '080905', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1929, '1389378', '50817', 'Secundaria', 'Pública de gestión directa', '080906', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1930, '0621722', 'JAVIER HERAUD PEREZ', 'Secundaria', 'Pública de gestión directa', '080906', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1931, '0718502', 'JOSE ANTONIO ENCINAS FRANCO', 'Secundaria', 'Pública de gestión directa', '080906', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1932, '0497552', 'JOSE OLAYA', 'Secundaria', 'Pública de gestión directa', '080906', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1933, '0783118', 'INCA GARCILASO DE LA VEGA', 'Secundaria', 'Pública de gestión directa', '080906', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1934, '1201201', 'SAN LUIS GONZAGA', 'Secundaria', 'Privada', '080906', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1935, '1394030', 'NUEVA CONVENCION', 'Secundaria', 'Pública de gestión directa', '080906', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1936, '1394287', 'LA SALLE', 'Secundaria de Adultos', 'Privada', '080906', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1937, '1358167', 'FRAY MARTIN DE PORRES', 'Básica Alternativa-Avanzado', 'Privada', '080906', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1938, '1407105', 'SAGRADO CORAZON DE JESUS', 'Secundaria', 'Pública de gestión directa', '080906', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1939, '1463827', '501419', 'Secundaria', 'Pública de gestión directa', '080906', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1940, '1530732', 'MOSOQ LLACTA', 'Secundaria', 'Pública de gestión privada', '080906', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1941, '1766021', 'WIÑAY QORIWAYNA', 'Secundaria', 'Pública de gestión directa', '080906', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1942, '1766039', 'WIÑAY CONVENCION', 'Secundaria', 'Pública de gestión directa', '080906', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1943, '0933077', 'DIVINO MAESTRO', 'Secundaria', 'Pública de gestión directa', '080907', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1944, '1395888', 'JOSE MARIA ARGUEDAS', 'Secundaria', 'Pública de gestión directa', '080907', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1945, '1323864', '38868', 'Secundaria', 'Pública de gestión directa', '080907', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1946, '1347111', '501349', 'Secundaria', 'Pública de gestión directa', '080907', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1947, '1347129', '38622 INMACULADA CONCEPCION', 'Secundaria', 'Pública de gestión directa', '080907', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1948, '1347137', '38820-A DANIEL ESTRADA PEREZ', 'Secundaria', 'Pública de gestión directa', '080907', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1949, '1538529', 'INTERCULTURAL BILINGÜE ASHANINKA MATSIGENKA', 'Secundaria', 'Pública de gestión directa', '080907', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1950, '1543065', 'RICARDO PALMA', 'Secundaria', 'Privada', '080907', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1951, '1782598', 'DIVINO MAESTRO', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '080907', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1952, '3026952', 'DIEGO THOMPSON', 'Secundaria', 'Privada', '080907', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1953, '3042306', 'SAVA SCHOOL', 'Secundaria', 'Privada', '080907', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1954, '3049343', 'MATH SCIENTIA', 'Secundaria', 'Privada', '080907', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1955, '3052883', '38994-B', 'Secundaria', 'Pública de gestión directa', '080907', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1956, '0489161', 'URIEL GARCIA', 'Secundaria', 'Pública de gestión directa', '080908', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1957, '0750406', 'VIRGEN DEL CARMEN', 'Secundaria', 'Pública de gestión directa', '080908', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1958, '0783050', '50985 ALTO SALKANTAY', 'Secundaria', 'Pública de gestión directa', '080908', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1959, '1394048', '50258 SAN FRANCISCO DE ASIS', 'Secundaria', 'Pública de gestión directa', '080908', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1960, '1394436', 'JOSE CARLOS MARIATEGUI', 'Secundaria de Adultos', 'Privada', '080908', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1961, '1787183', 'KANCHAY ÑAN', 'Secundaria', 'Privada', '080908', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1962, '0481101', '50322 MANCO INCA', 'Secundaria', 'Pública de gestión directa', '080909', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1963, '0518894', '50276 AMAUTA', 'Secundaria', 'Pública de gestión directa', '080909', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1964, '0616169', '50332 JOSE ANTONIO ENCINAS FRANCO', 'Secundaria', 'Pública de gestión directa', '080909', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1965, '0621458', '50268 JOSE BERNARDO ALCEDO', 'Secundaria', 'Pública de gestión directa', '080909', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1966, '0621482', '50333 MIGUEL GRAU', 'Secundaria', 'Pública de gestión privada', '080909', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1967, '0783076', 'YUVENI', 'Secundaria', 'Pública de gestión directa', '080909', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1968, '1200740', 'JOSE GABRIEL CONDORCANQUI', 'Secundaria', 'Privada', '080909', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1969, '0933002', 'EMILIANO HUAMANTICA', 'Secundaria', 'Privada', '080909', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1970, '0933036', 'VIRGEN DEL CARMEN', 'Secundaria', 'Privada', '080909', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1971, '1393909', 'TITO CUSI YUPANQUI', 'Secundaria', 'Pública de gestión directa', '080909', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1972, '1347152', 'SAN LUIS GONZAGA', 'Secundaria', 'Pública de gestión directa', '080909', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1973, '1358175', 'MAESTRO JESUS DE CHUANQUIRI', 'Secundaria', 'Pública de gestión directa', '080909', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1974, '1405240', '50325 INCA SAYRI TUPAC', 'Secundaria', 'Pública de gestión directa', '080909', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1975, '3041209', '50848', 'Secundaria', 'Pública de gestión directa', '080909', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1976, '0932897', 'LA VICTORIA', 'Secundaria', 'Pública de gestión directa', '080910', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1977, '0932921', 'BARTOLOME HERRERA', 'Secundaria', 'Pública de gestión directa', '080910', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1978, '0932988', 'CESAR VALLEJO', 'Secundaria', 'Pública de gestión directa', '080910', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1979, '1386929', 'VISION JUVENIL', 'Secundaria de Adultos', 'Privada', '080910', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1980, '1387026', 'PEDRO PAULET', 'Secundaria', 'Privada', '080910', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1981, '1387034', 'PEDRO PAULET', 'Básica Alternativa-Avanzado', 'Privada', '080910', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1982, '1387141', '38990-A MARAVILLA', 'Secundaria', 'Pública de gestión directa', '080910', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1983, '1387299', 'TUPAC AMARU II', 'Secundaria', 'Privada', '080910', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1984, '1320209', 'TUPAC AMARU II', 'Básica Alternativa-Avanzado', 'Privada', '080910', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1985, '1321033', '38755', 'Secundaria', 'Pública de gestión directa', '080910', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1986, '1321041', '38968 SAN ANTONIO ABAD', 'Secundaria', 'Pública de gestión directa', '080910', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1987, '1346998', '501345', 'Secundaria', 'Pública de gestión directa', '080910', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1988, '1347004', 'PARQUE INDUSTRIAL', 'Secundaria', 'Pública de gestión directa', '080910', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1989, '1347012', '38632 SIMON BOLIVAR', 'Secundaria', 'Pública de gestión directa', '080910', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1990, '1390038', 'ALBERT EINSTEIN', 'Secundaria', 'Privada', '080910', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1991, '1479989', 'LA VERDAD', 'Secundaria', 'Privada', '080910', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1992, '1637891', 'LA VICTORIA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '080910', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1993, '1696145', 'JOSE ANTONIO ENCINAS', 'Secundaria', 'Pública de gestión directa', '080910', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1994, '1733948', '38392 JOSE MARIA ARGUEDAS ALTAMIRANO', 'Secundaria', 'Pública de gestión directa', '080910', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1995, '1734102', '38990-B', 'Secundaria', 'Pública de gestión directa', '080910', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1996, '1739697', 'LA VICTORIA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '080910', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1997, '1784552', 'MARIA MONTESSORI', 'Secundaria', 'Privada', '080910', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1998, '1795251', '501445', 'Secundaria', 'Pública de gestión directa', '080910', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1999, '1797174', '38942', 'Secundaria', 'Pública de gestión directa', '080910', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2000, '1797182', '38776', 'Secundaria', 'Pública de gestión directa', '080910', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2001, '1798784', 'FEDERICO VILLARREAL', 'Secundaria', 'Privada', '080910', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2002, '1799709', '38831', 'Secundaria', 'Pública de gestión directa', '080910', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2003, '1799717', '1369 ISAAC NEWTON', 'Secundaria', 'Pública de gestión directa', '080910', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2004, '0495291', 'SAN MARTIN', 'Secundaria', 'Pública de gestión directa', '080911', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2005, '0783084', '50744', 'Secundaria', 'Pública de gestión directa', '080911', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2006, '1358217', 'SAN FERNANDO', 'Secundaria', 'Pública de gestión directa', '080911', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2007, '0621631', 'INCA GARCILASO DE LA VEGA', 'Secundaria', 'Pública de gestión directa', '080912', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2008, '1546746', '501330', 'Secundaria', 'Pública de gestión directa', '080912', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2009, '1801729', 'INCA GARCILASO DE LA VEGA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '080912', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2010, '1320183', '38761 LIBERTADORES DEL VRAE', 'Secundaria', 'Pública de gestión directa', '080913', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2011, '1405141', '38869', 'Secundaria', 'Pública de gestión directa', '080913', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2012, '1568013', '38634', 'Secundaria', 'Pública de gestión directa', '080913', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2013, '1733930', '501383', 'Secundaria', 'Pública de gestión directa', '080913', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2014, '1784131', '501370', 'Secundaria', 'Pública de gestión directa', '080913', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2015, '3007556', 'JOSE MARIA ARGUEDAS', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '080913', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2016, '0612960', 'FIDEL PEREYRA', 'Secundaria', 'Pública de gestión directa', '080914', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2017, '0750380', 'ELIAS SEBASTIAN KUSHICHINARI', 'Secundaria', 'Pública de gestión directa', '080914', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2018, '1060912', 'JOSE PEREYRA KASHIARI', 'Secundaria', 'Pública de gestión directa', '080914', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2019, '0933739', 'JUAN SANTOS ATAHUALPA', 'Secundaria', 'Pública de gestión directa', '080914', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2020, '0934885', 'MONSEÑOR JAVIER ARIZ', 'Secundaria', 'Pública de gestión privada', '080914', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2021, '1323120', 'CARLOS RIOS RIOS', 'Secundaria', 'Pública de gestión directa', '080914', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2022, '1530336', '64125 FRAY JULIAN MACEGOZA', 'Secundaria', 'Pública de gestión privada', '080914', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2023, '1540897', 'MARIO CHORONTO DOMINGO', 'Secundaria', 'Pública de gestión directa', '080914', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2024, '1579796', 'CARLOS SEBASTIAN PEREZ', 'Secundaria', 'Pública de gestión directa', '080914', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2025, '1645274', 'ANGEL LOPEZ CASTRO', 'Secundaria', 'Pública de gestión directa', '080914', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2026, '1698893', '64553', 'Secundaria', 'Pública de gestión directa', '080914', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2027, '1771815', 'CASHIRIARI', 'Secundaria', 'Pública de gestión directa', '080914', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2028, '1771823', 'TANGOSHIARI', 'Secundaria', 'Pública de gestión directa', '080914', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2029, '1790047', '64518', 'Secundaria', 'Pública de gestión directa', '080914', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2030, '3039559', '501106', 'Secundaria', 'Pública de gestión directa', '080914', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2031, '0932822', 'JAVIER PEREZ DE CUELLAR', 'Secundaria', 'Pública de gestión directa', '080915', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2032, '1459734', 'AGOIGANAERA MAGANIRO', 'Secundaria', 'Pública de gestión privada', '080915', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2033, '1730241', '501109', 'Secundaria', 'Pública de gestión directa', '080915', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2034, '1765106', '501212', 'Secundaria', 'Pública de gestión directa', '080915', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2035, '0932954', 'SAN JOSE', 'Secundaria', 'Pública de gestión directa', '080916', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2036, '1347103', '38615', 'Secundaria', 'Pública de gestión directa', '080916', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2037, '1062553', '38704', 'Secundaria', 'Pública de gestión directa', '080917', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2038, '1372374', 'ANDRES AVELINO CACERES', 'Secundaria', 'Pública de gestión directa', '080917', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2039, '1662139', 'MANITEA ALTA', 'Secundaria', 'Pública de gestión directa', '080917', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2040, '1386861', '38633 JOSE CARLOS MARIATEGUI', 'Secundaria', 'Pública de gestión directa', '080918', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2041, '1396597', 'PITIRINKENI', 'Secundaria', 'Pública de gestión directa', '080918', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2042, '1396704', '38709', 'Secundaria', 'Pública de gestión directa', '080918', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2043, '1465921', 'JOSE ANTONIO ENCINAS', 'Básica Alternativa-Avanzado', 'Privada', '080918', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2044, '1704279', 'ALBERT EINSTEIN', 'Básica Alternativa-Avanzado', 'Privada', '080918', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2045, '1725506', 'JOSE ANTONIO ENCINAS', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '080918', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2046, '1735323', '501389', 'Secundaria', 'Pública de gestión directa', '080918', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2047, '1767474', '38943', 'Secundaria', 'Pública de gestión directa', '080918', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2048, '1800432', 'SANTA INES', 'Secundaria', 'Pública de gestión directa', '080918', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2049, '1800440', '501390 BILINGÜE ASHANINKA EBANKARI IYOTAKANTAJERONE', 'Secundaria', 'Pública de gestión directa', '080918', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2050, '3027190', 'BERTOLT BRECHT', 'Secundaria', 'Privada', '080918', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2051, '3030178', '38987', 'Secundaria', 'Pública de gestión directa', '080918', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2052, '0236463', 'HERMANOS AYAR', 'Secundaria', 'Pública de gestión directa', '081001', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2053, '1200518', 'VIRGEN DE LA NATIVIDAD', 'Secundaria', 'Privada', '081001', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2054, '1794114', 'HERMANOS AYAR', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '081001', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2055, '1794262', 'HERMANOS AYAR', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '081001', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2056, '0495069', 'ACCHA', 'Secundaria', 'Pública de gestión directa', '081002', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2057, '1625557', '50378', 'Secundaria', 'Pública de gestión directa', '081002', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2058, '1800101', '50361', 'Secundaria', 'Pública de gestión directa', '081002', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2059, '0621573', 'SAN MARTIN DE PORRES', 'Secundaria', 'Pública de gestión directa', '081003', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2060, '1373422', 'VIRGEN NATIVIDAD', 'Secundaria', 'Pública de gestión directa', '081003', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2061, '1625573', '50384', 'Secundaria', 'Pública de gestión directa', '081003', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2062, '1452705', 'APU YAURI WAYNAKUNA KALLPACHAQ', 'Secundaria', 'Pública de gestión privada', '081004', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2063, '0647446', 'HUANOQUITE', 'Secundaria', 'Pública de gestión directa', '081005', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2064, '1345024', 'HUANCA HUANCA', 'Secundaria', 'Pública de gestión directa', '081005', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2065, '1372499', 'LLASPAY', 'Secundaria', 'Pública de gestión directa', '081005', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2066, '1800523', '501178', 'Secundaria', 'Pública de gestión directa', '081005', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2067, '0932996', 'ANTAPALLPA', 'Secundaria', 'Pública de gestión directa', '081006', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2068, '1396191', '50803', 'Secundaria', 'Pública de gestión directa', '081006', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2069, '1396225', 'OSCCOLLOPATA', 'Secundaria', 'Pública de gestión directa', '081006', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2070, '1321801', 'SAN ISIDRO', 'Secundaria', 'Pública de gestión directa', '081006', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2071, '1457688', 'CHECCAPUCARA', 'Secundaria', 'Pública de gestión directa', '081006', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2072, '0699603', 'AYAR MANCO', 'Secundaria', 'Pública de gestión directa', '081007', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2073, '1625565', 'AYARKUNAQ YACHAYWASIN', 'Secundaria', 'Pública de gestión privada', '081007', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2074, '0933531', 'VIRGEN ASUNCION', 'Secundaria', 'Pública de gestión directa', '081008', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2075, '0495325', 'YAURISQUE', 'Secundaria', 'Pública de gestión directa', '081009', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2076, '1396209', '50354', 'Secundaria', 'Pública de gestión directa', '081009', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2077, '0235580', '51029 SAGRADO CORAZON DE JESUS', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '081101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2078, '0236471', 'JOSE PEREZ Y ARMENDARIZ', 'Secundaria', 'Pública de gestión directa', '081101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2079, '0783803', 'JOSE PEREZ Y ARMENDARIZ', 'Secundaria de Adultos', 'Pública de gestión directa', '081101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2080, '1201466', 'SAN AGUSTIN', 'Básica Alternativa-Avanzado', 'Privada', '081101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2081, '1201425', 'SAN AGUSTIN', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '081101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2082, '1396829', 'CORONEL FRANCISCO BOLOGNESI', 'Secundaria', 'Privada', '081101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2083, '1320571', 'JESUS DE NAZARETH', 'Secundaria', 'Privada', '081101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2084, '1357722', 'FRANCISCO BOLOGNESI', 'Secundaria', 'Pública de gestión directa', '081101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2085, '1361054', 'JOSE PEREZ Y ARMENDARIZ', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '081101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2086, '1364900', 'SERAPIO CALDERON LAZO DE LA VEGA', 'Secundaria', 'Pública de gestión directa', '081101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2087, '1389717', 'RICARDO PALMA', 'Secundaria', 'Pública de gestión directa', '081101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2088, '1493972', 'INKARI Q\'ERO AYLLU', 'Secundaria', 'Pública de gestión directa', '081101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2089, '1627173', 'ETNICA COMUNITARIA ANDINA DE LA NACION Q\'ERO', 'Secundaria', 'Pública de gestión directa', '081101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2090, '1730258', 'NUESTRA SEÑORA DEL ROSARIO', 'Secundaria', 'Pública de gestión directa', '081101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2091, '1797463', '50439 PATRON DOCTOR SAN JERONIMO', 'Secundaria', 'Pública de gestión directa', '081101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2092, '1201649', 'TAUCAMARCA', 'Secundaria', 'Pública de gestión directa', '081102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2093, '1361773', '50414 JORGE NUÑEZ DEL PRADO', 'Secundaria', 'Pública de gestión directa', '081102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2094, '1396894', '50449 VIRGEN DEL CARMEN', 'Secundaria', 'Pública de gestión directa', '081102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2095, '1796093', '50453', 'Secundaria', 'Pública de gestión directa', '081102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2096, '0930982', '50426', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '081103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2097, '0791319', 'RAMIRO PRIALE PRIALE', 'Secundaria', 'Pública de gestión directa', '081103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2098, '1347434', 'JORGE NUÑEZ DEL PRADO', 'Secundaria', 'Pública de gestión directa', '081103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2099, '1357730', 'CHACLLABAMBA', 'Secundaria', 'Pública de gestión directa', '081103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2100, '0933556', 'GENERAL JUAN VELASCO ALVARADO', 'Secundaria', 'Pública de gestión directa', '081103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2101, '1369230', 'OTOCANI', 'Secundaria', 'Pública de gestión directa', '081103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2102, '1380039', '50465 DANIEL ALCIDES CARRION', 'Secundaria', 'Pública de gestión directa', '081103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2103, '1389725', 'SAN ANTONIO', 'Secundaria', 'Pública de gestión directa', '081103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2104, '1442185', 'SAN JUAN BAUTISTA', 'Secundaria', 'Pública de gestión directa', '081103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2105, '1470004', 'HIJOS DE MAPACHO', 'Secundaria', 'Pública de gestión directa', '081103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2106, '1797299', '50907 SEÑOR DE HUANCA', 'Secundaria', 'Pública de gestión directa', '081103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2107, '1797307', '501125 SAN FRANCISCO DE ASIS', 'Secundaria', 'Pública de gestión directa', '081103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2108, '1797471', '50428', 'Secundaria', 'Pública de gestión directa', '081103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2109, '0579300', '50421 AUGUSTO SALAZAR BONDY', 'Secundaria', 'Pública de gestión directa', '081104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2110, '1396852', 'MIKA', 'Secundaria', 'Pública de gestión directa', '081104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2111, '1396878', '50422 DIEGO QUISPE TITO', 'Secundaria', 'Pública de gestión directa', '081104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2112, '1347459', 'SEÑOR DE ACCHA', 'Secundaria', 'Pública de gestión directa', '081104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2113, '1347442', 'SANTA ROSA DE LIMA', 'Secundaria', 'Pública de gestión directa', '081104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2114, '1347939', 'REVOLUCIONARIO TUPAC AMARU II', 'Secundaria', 'Pública de gestión directa', '081104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2115, '1347988', 'IMACULADA CONCEPCION', 'Secundaria', 'Pública de gestión directa', '081104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2116, '1380021', '50455', 'Secundaria', 'Pública de gestión directa', '081104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2117, '1455328', 'JOSE OLAYA BALANDRA', 'Secundaria', 'Pública de gestión directa', '081104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2118, '3007812', 'JOVENES DEL BICENTENARIO', 'Secundaria', 'Pública de gestión directa', '081104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2119, '1800572', '50424', 'Secundaria', 'Pública de gestión directa', '081104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2120, '0591255', 'SATURNINO HUILCA Q.', 'Secundaria', 'Pública de gestión directa', '081105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2121, '1396886', '50446 VALENTIN PANIAGUA CORAZAO', 'Secundaria', 'Pública de gestión directa', '081105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2122, '1347921', 'SEÑOR EXALTACION', 'Secundaria', 'Pública de gestión directa', '081105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2123, '1347970', 'INCA GARCILASO DE LA VEGA', 'Secundaria', 'Pública de gestión directa', '081105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2124, '1358340', 'VIRGEN DEL CARMEN', 'Secundaria', 'Privada', '081105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2125, '1369248', 'HUAYNA CCAPAC', 'Secundaria', 'Pública de gestión directa', '081105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2126, '1573930', 'GLORIOSO CIENCIAS', 'Secundaria', 'Pública de gestión directa', '081105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2127, '1774835', 'HUAYNA', 'Secundaria', 'Privada', '081105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2128, '0497537', '50430', 'Secundaria', 'Pública de gestión directa', '081106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2129, '0671610', '50429 MARIA NATIVIDAD HONOR ORTIZ DE AQUISE', 'Secundaria', 'Pública de gestión directa', '081106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2130, '1396795', 'SAN AGUSTIN', 'Secundaria de Adultos', 'Privada', '081106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2131, '1396803', 'SAN AGUSTIN', 'Básica Alternativa', 'Privada', '081106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2132, '0235598', '51030 LUIS NAVARRETE', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '081201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2133, '0233106', 'MARIANO SANTOS', 'Secundaria', 'Pública de gestión directa', '081201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2134, '0233239', 'NUESTRA SEÑORA DEL CARMEN', 'Secundaria', 'Pública de gestión privada', '081201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2135, '1062207', '51030', 'Secundaria de Adultos', 'Pública de gestión directa', '081201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2136, '1201318', 'LA MERCED', 'Secundaria', 'Privada', '081201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2137, '1397678', 'LA CATOLICA', 'Secundaria', 'Privada', '081201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2138, '1397702', 'LA CATOLICA', 'Básica Alternativa-Avanzado', 'Privada', '081201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2139, '1397710', 'WAYNAKUNAQ RIKCHARINAN WASI', 'Secundaria', 'Pública de gestión privada', '081201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2140, '1397751', 'CESAR VALLEJO', 'Secundaria de Adultos', 'Privada', '081201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2141, '1343722', 'JOSE MARIA GARCIA GARCIA', 'Secundaria', 'Pública de gestión directa', '081201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2142, '1397900', 'SAN JORGE', 'Secundaria', 'Pública de gestión directa', '081201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2143, '1377241', 'SANTO DOMINGO', 'Secundaria', 'Pública de gestión directa', '081201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2144, '1390137', 'PACHAKUTEQ INKA YUPANKI', 'Secundaria', 'Pública de gestión directa', '081201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2145, '1395680', 'JOSE ABELARDO QUIÑONES', 'Secundaria', 'Pública de gestión directa', '081201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2146, '1724244', '51030 LUIS NAVARRETE', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '081201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2147, '1772664', '50884 URIEL GARCIA CACERES', 'Secundaria', 'Pública de gestión directa', '081201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2148, '1772847', 'JOHANN CARL FRIEDRICH GAUSS', 'Secundaria', 'Privada', '081201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2149, '0497677', 'LUIS VALLEJOS SANTONI', 'Secundaria', 'Pública de gestión directa', '081202', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2150, '1200906', 'SAN IGNACIO DE LOYOLA FE Y ALEGRIA 44', 'Secundaria', 'Pública de gestión privada', '081202', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2151, '1397827', 'KUNTUR KALLPA', 'Secundaria', 'Pública de gestión privada', '081202', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2152, '1377233', 'FRANCISCO Y JACINTA MARTO', 'Secundaria', 'Privada', '081202', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2153, '1423391', 'FRANCISCO Y JACINTA MARTO', 'Básica Alternativa-Avanzado', 'Privada', '081202', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2154, '1725191', 'FRANCISCO Y JACINTA MARTO', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '081202', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2155, '0236356', 'ROSA DE AMERICA', 'Secundaria', 'Pública de gestión directa', '081203', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2156, '1397652', 'SEÑOR DE TAYANCANI', 'Secundaria', 'Pública de gestión directa', '081204', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2157, '1343813', 'ALMIRANTE MIGUEL GRAU SEMINARIO', 'Secundaria', 'Pública de gestión privada', '081204', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2158, '1457365', 'LOS RIT\'IS DEL ALTO ANDINO', 'Secundaria', 'Pública de gestión privada', '081204', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2159, '0236406', 'CESAR VALLEJO MENDOZA', 'Secundaria', 'Pública de gestión directa', '081205', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2160, '0933721', 'JOSE MARIA ARGUEDAS', 'Secundaria', 'Pública de gestión directa', '081205', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2161, '1397926', 'FERNANDO TUPAC AMARU BASTIDAS', 'Secundaria', 'Pública de gestión directa', '081205', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2162, '1397934', 'DANIEL ESTRADA PEREZ', 'Secundaria', 'Pública de gestión directa', '081205', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2163, '1397959', 'LUIS NAVARRETE LECHUGA', 'Secundaria', 'Pública de gestión directa', '081205', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2164, '1395649', 'MANCO INKA', 'Secundaria', 'Pública de gestión directa', '081205', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2165, '1395656', 'INKA TUPAC YUPANQUI', 'Secundaria', 'Pública de gestión directa', '081205', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2166, '1395698', 'ANDRES AVELINO CACERES', 'Secundaria', 'Pública de gestión directa', '081205', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2167, '1579697', 'MARIO VARGAS LLOSA', 'Secundaria', 'Pública de gestión directa', '081205', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2168, '0497586', 'TUPAC AMARU II', 'Secundaria', 'Pública de gestión directa', '081206', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2169, '1397785', '50527', 'Secundaria', 'Pública de gestión directa', '081206', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2170, '1395664', 'JAVIER HERAUD PEREZ', 'Secundaria', 'Pública de gestión directa', '081206', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2171, '0591107', 'NARCISO ARESTEGUI', 'Secundaria', 'Pública de gestión directa', '081207', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2172, '0579201', '51501', 'Secundaria', 'Pública de gestión directa', '081207', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2173, '1397736', 'AMAUTA', 'Secundaria de Adultos', 'Privada', '081207', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2174, '1457340', 'VIRGEN DEL ROSARIO', 'Secundaria', 'Pública de gestión directa', '081207', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2175, '0927871', 'CESAR VALLEJO', 'Secundaria', 'Pública de gestión directa', '081208', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2176, '0518191', '27 DE NOVIEMBRE', 'Secundaria', 'Pública de gestión directa', '081208', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2177, '0495309', 'SAN FRANCISCO DE ASIS', 'Secundaria', 'Pública de gestión directa', '081209', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2178, '1395706', 'APU CHOQQUECHANCA F.H.', 'Secundaria', 'Pública de gestión directa', '081209', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2179, '1697929', 'SAN ROLANDO', 'Secundaria', 'Pública de gestión directa', '081209', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2180, '0592485', 'SEÑOR DE CCOYLLOR RITTY', 'Secundaria', 'Pública de gestión directa', '081210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2181, '1201052', 'SAGRADO CORAZON DE JESUS', 'Secundaria', 'Pública de gestión directa', '081210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2182, '1397728', 'MAJESTUOSO AUSANGATE', 'Secundaria', 'Pública de gestión directa', '081210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2183, '1397843', 'AUSANGATE', 'Secundaria', 'Privada', '081210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2184, '1397868', 'AUSANGATE', 'Básica Alternativa-Avanzado', 'Privada', '081210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2185, '1343847', 'MARIANITO MAYTA', 'Secundaria', 'Pública de gestión directa', '081210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2186, '1343862', '50544 SEÑOR DE LA EXALTACION', 'Secundaria', 'Pública de gestión directa', '081210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2187, '1343854', 'JUAN PABLO II', 'Secundaria', 'Pública de gestión directa', '081210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2188, '1397777', '50853', 'Secundaria', 'Pública de gestión directa', '081210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2189, '1385467', 'MICAELA BASTIDAS', 'Secundaria', 'Pública de gestión directa', '081210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2190, '1395672', 'GENERAL JUAN VELASCO ALVARADO', 'Secundaria', 'Pública de gestión directa', '081210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2191, '1575737', 'ANTONIO RAIMONDI', 'Secundaria', 'Pública de gestión directa', '081210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2192, '1664523', 'VALENTIN PANIAGUA CORAZAO', 'Secundaria', 'Pública de gestión directa', '081210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2193, '1668292', 'JOSE DE SAN MARTIN', 'Secundaria', 'Pública de gestión directa', '081210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2194, '1697937', '50554 AUSANGATE DE PACCHANTA', 'Secundaria', 'Pública de gestión directa', '081210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2195, '1772672', '501432 PAPA FRANCISCO I', 'Secundaria', 'Pública de gestión privada', '081210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2196, '1786698', 'APU AUSANGATE PUKARUMI', 'Secundaria', 'Pública de gestión directa', '081210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2197, '0929638', '50499 JUSTO BARRIONUEVO ALVAREZ', 'Secundaria', 'Pública de gestión directa', '081211', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2198, '1370352', '50500 SAN MARTIN DE PORRES', 'Secundaria', 'Pública de gestión directa', '081211', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2199, '1578947', 'CESAR VALLEJO', 'Básica Alternativa-Avanzado', 'Privada', '081211', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2200, '1642057', 'CESAR VALLEJO', 'Secundaria', 'Privada', '081211', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2201, '1725985', 'CESAR VALLEJO', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '081211', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2202, '1800770', '50501 SAGRADO CORAZON DE JESUS', 'Secundaria', 'Pública de gestión directa', '081211', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2203, '0488866', 'JOSE CARLOS MARIATEGUI', 'Secundaria', 'Pública de gestión directa', '081212', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2204, '0933754', 'REVOLUCIONARIO JUAN VELASCO ALVARADO', 'Secundaria', 'Pública de gestión directa', '081212', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2205, '0737205', 'JOSE C.MARIATEGUI', 'Secundaria de Adultos', 'Pública de gestión directa', '081212', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2206, '1397769', 'JULIAN APAZA', 'Secundaria', 'Privada', '081212', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2207, '1397876', 'ANILMAYO', 'Secundaria', 'Pública de gestión directa', '081212', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2208, '1325547', 'JAVIER PEREZ DE CUELLAR', 'Secundaria', 'Pública de gestión directa', '081212', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2209, '1343730', 'MIGUEL TTUPA LUTHUA', 'Secundaria', 'Pública de gestión directa', '081212', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2210, '3000692', 'ALFONSO UGARTE VERNAL', 'Secundaria', 'Pública de gestión directa', '081212', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2211, '1062355', '51031', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '081301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2212, '0236158', 'GENERAL OLLANTA', 'Secundaria', 'Pública de gestión directa', '081301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2213, '0236281', 'VALLE SAGRADO', 'Secundaria', 'Pública de gestión privada', '081301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2214, '0236927', 'AGROPECUARIO', 'Secundaria', 'Pública de gestión directa', '081301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2215, '0591875', 'SEÑOR DE TORRECHAYOC', 'Secundaria', 'Pública de gestión directa', '081301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2216, '0236000', 'GENERAL OLLANTA', 'Secundaria de Adultos', 'Pública de gestión directa', '081301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2217, '0931725', 'INTEGRANDO', 'Secundaria', 'Privada', '081301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2218, '1398742', 'BOLIVARIANO', 'Secundaria', 'Privada', '081301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2219, '1398783', 'INTERCULTURAL SOL Y LUNA', 'Secundaria', 'Privada', '081301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2220, '1398791', 'SAGRADO CORAZON DE JESUS', 'Secundaria', 'Privada', '081301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2221, '1398809', 'ROSA DE SANTA MARIA', 'Básica Alternativa-Avanzado', 'Privada', '081301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2222, '1398817', 'DIVINO MAESTRO', 'Básica Alternativa-Avanzado', 'Privada', '081301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2223, '1398825', 'DIVINO MAESTRO', 'Secundaria de Adultos', 'Privada', '081301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2224, '1398916', '50575 LA SALLE', 'Secundaria', 'Pública de gestión privada', '081301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2225, '1398932', 'DIVINO MAESTRO', 'Secundaria', 'Privada', '081301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2226, '1361062', 'GENERAL OLLANTA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '081301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2227, '1463991', '50596 TUPAC AMARU', 'Secundaria', 'Pública de gestión directa', '081301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2228, '1531250', 'YACHAYWASI SCHOOL', 'Secundaria', 'Privada', '081301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2229, '1536655', 'APU TORRECHAYOC', 'Secundaria', 'Privada', '081301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2230, '1632868', 'UNION DE NUEVOS INTELIGENTES', 'Secundaria', 'Privada', '081301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2231, '1636687', 'INTEGRANDO LOS ANDES', 'Secundaria', 'Privada', '081301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2232, '1773035', 'JESUS ALBERTO RODRIGUEZ FIGUEROA', 'Secundaria', 'Pública de gestión directa', '081301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2233, '1802032', 'AYNI', 'Secundaria', 'Privada', '081301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2234, '0236489', 'INKA TUPAQ YUPANQUI', 'Secundaria', 'Pública de gestión directa', '081302', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2235, '0931758', 'MATEO PUMACCAHUA CHIHUANTITO', 'Secundaria', 'Pública de gestión directa', '081302', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2236, '1361963', 'JOSE MARIA ARGUEDAS ALTAMIRANO', 'Secundaria', 'Privada', '081302', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2237, '1361955', 'ALTERNATIVO YACHAY', 'Secundaria', 'Privada', '081302', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2238, '1459569', '50609 FELIX PUMA TTITO', 'Secundaria', 'Pública de gestión directa', '081302', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2239, '1541192', '50608 JUAN VELASCO ALVARADO', 'Secundaria', 'Pública de gestión directa', '081302', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2240, '1719210', '50605', 'Secundaria', 'Pública de gestión directa', '081302', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2241, '3029899', 'FELIX PUMA TTITO', 'Secundaria', 'Pública de gestión directa', '081302', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2242, '3047537', 'INNOVACION DELTA', 'Secundaria', 'Privada', '081302', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2243, '0503557', 'NUESTRA SEÑORA DE NATIVIDAD', 'Secundaria', 'Pública de gestión directa', '081303', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2244, '1394907', 'VIRGEN ASUNTA', 'Secundaria', 'Pública de gestión directa', '081303', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2245, '0671651', 'INKA PACHACUTEC', 'Secundaria', 'Pública de gestión directa', '081304', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2246, '0486928', 'ANTONIO SINCHIRROCA', 'Secundaria', 'Pública de gestión directa', '081305', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2247, '0791574', 'SANTO DOMINGO SAVIO', 'Secundaria', 'Pública de gestión directa', '081305', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `origin_schools` (`id`, `modular_code`, `name`, `d_niv_mod`, `management_type`, `ubigeo_code`, `created_at`, `updated_at`) VALUES
(2248, '1377407', 'JOSE CARLOS MARIATEGUI', 'Secundaria', 'Pública de gestión directa', '081305', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2249, '1377415', '50601', 'Secundaria', 'Pública de gestión directa', '081305', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2250, '0496661', 'OLLANTAY', 'Secundaria', 'Pública de gestión directa', '081306', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2251, '1398890', 'TECNICO AGROPECUARIO BILINGUE', 'Secundaria', 'Pública de gestión directa', '081306', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2252, '1398908', 'SAN ISIDRO', 'Secundaria', 'Pública de gestión directa', '081306', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2253, '1391036', 'INKA WAYNA QHAPAQ', 'Secundaria', 'Pública de gestión directa', '081306', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2254, '0621276', 'DIDASKALIO NUESTRA SEÑORA DEL ROSARIO', 'Secundaria', 'Pública de gestión privada', '081307', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2255, '3040581', 'DIDASKALIO HERMANA JOSEFINA SERRANO', 'Secundaria', 'Privada', '081307', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2256, '0680785', 'JUAN XXIII', 'Secundaria', 'Privada', '180101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2257, '1126945', 'COLEGIO ADVENTISTA FERNANDO STAHL', 'Secundaria', 'Privada', '180101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2258, '1126861', 'MARISCAL DOMINGO NIETO', 'Secundaria', 'Pública de gestión directa', '180101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2259, '0745752', 'DANIEL BECERRA OCAMPO', 'Secundaria', 'Pública de gestión directa', '180101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2260, '0656587', 'LUIS E. PINTO SOTOMAYOR', 'Secundaria', 'Pública de gestión directa', '180101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2261, '0308643', 'LA LIBERTAD', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '180101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2262, '1210228', 'SAN FRANCISCO', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '180101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2263, '0745745', 'RAFAEL DIAZ', 'Secundaria', 'Pública de gestión directa', '180101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2264, '0309781', 'SIMON BOLIVAR', 'Secundaria', 'Pública de gestión directa', '180101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2265, '0524637', 'CORONEL MANUEL C. DE LA TORRE', 'Secundaria', 'Pública de gestión directa', '180101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2266, '1125855', 'ROBERT GAGNE', 'Secundaria', 'Privada', '180101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2267, '1228766', 'SAN FRANCISCO', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '180101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2268, '0310490', 'TECNICO AGROPECUARIO DE MOQUEGUA', 'Secundaria', 'Pública de gestión directa', '180101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2269, '1125772', 'LOS ANGELES', 'Secundaria', 'Pública de gestión directa', '180101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2270, '1209709', 'LORD BYRON', 'Básica Alternativa-Avanzado', 'Privada', '180101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2271, '0310359', 'LA LIBERTAD', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '180101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2272, '1545615', 'FRANCISCO FAHLMAN SELINGER', 'Secundaria', 'Privada', '180101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2273, '1545797', 'BILINGÜE MAX UHLE', 'Secundaria', 'Privada', '180101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2274, '1347624', 'CIENCIAS APLICADAS', 'Secundaria', 'Privada', '180101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2275, '1423946', 'SEÑOR DE LOS MILAGROS', 'Secundaria', 'Pública de gestión directa', '180101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2276, '1457159', 'MITCHELL & PORTER', 'Secundaria', 'Privada', '180101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2277, '1514827', 'VIRGEN DE GUADALUPE', 'Secundaria', 'Privada', '180101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2278, '1578160', 'ALEXANDER FLEMING', 'Secundaria', 'Privada', '180101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2279, '1719913', 'ALBERT EINSTEIN & ADAM SMITH', 'Secundaria', 'Privada', '180101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2280, '1720226', 'BENJAMIN FRANKLIN', 'Secundaria', 'Privada', '180101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2281, '1774231', 'INNOVA SCHOOLS MOQUEGUA - FUNDO EL GRAMADAL', 'Secundaria', 'Privada', '180101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2282, '3022860', 'NUEVA QUERAPI', 'Secundaria', 'Pública de gestión directa', '180101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2283, '0708735', 'JOSE MARIA ARGUEDAS', 'Secundaria', 'Pública de gestión directa', '180102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2284, '0309872', 'HORACIO ZEBALLOS GAMEZ', 'Secundaria', 'Pública de gestión directa', '180102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2285, '0656603', 'FRANCISCO BOLOGNESI', 'Secundaria', 'Pública de gestión directa', '180103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2286, '0656611', 'CUCHUMBAYA', 'Secundaria', 'Pública de gestión directa', '180103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2287, '0591065', 'JUAN BAUTISTA SCARSI VALDIVIA', 'Secundaria', 'Pública de gestión directa', '180104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2288, '1209741', 'RICARDO PALMA', 'Básica Alternativa-Avanzado', 'Privada', '180104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2289, '0309807', 'SANTA FORTUNATA', 'Secundaria', 'Pública de gestión directa', '180104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2290, '1760990', 'FUTURE TECH', 'Secundaria', 'Privada', '180104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2291, '1126697', 'AGROPECUARIO ARTESANAL DE ARUNTAYA', 'Secundaria', 'Pública de gestión directa', '180105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2292, '1126739', 'MIGUEL CONSTANTINIDES ROSADO', 'Secundaria', 'Pública de gestión directa', '180105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2293, '0477745', 'CESAR VALLEJO', 'Secundaria', 'Pública de gestión directa', '180105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2294, '1126655', 'CESAR VIZCARRA VARGAS', 'Secundaria', 'Pública de gestión directa', '180105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2295, '1210582', 'TECNICO AGROPECUARIO DE SIJUAYA', 'Secundaria', 'Pública de gestión directa', '180105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2296, '1545789', 'TITIRE', 'Secundaria', 'Pública de gestión directa', '180105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2297, '0550418', 'JUAN VELEZ DE CORDOVA', 'Secundaria', 'Privada', '180106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2298, '0550616', 'DANIEL ALCIDES CARRION', 'Secundaria', 'Privada', '180106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2299, '0550319', 'JOSE CARLOS MARIATEGUI', 'Secundaria', 'Pública de gestión directa', '180106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2300, '0745760', 'VIDAL HERRERA DIAZ', 'Secundaria', 'Pública de gestión directa', '180106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2301, '1210061', 'PRONOEPSA DANIEL ALCIDES CARRION', 'Secundaria de Adultos', 'Privada', '180106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2302, '3040326', 'SAN JUAN SAN JUNE', 'Secundaria', 'Pública de gestión directa', '180106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2303, '1125731', 'MODELO SAN ANTONIO', 'Secundaria', 'Pública de gestión directa', '180107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2304, '1260512', 'CORAZON DE MARIA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '180107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2305, '1260553', 'CORAZON DE MARIA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '180107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2306, '1545763', 'FERNANDO BELAUNDE TERRY', 'Secundaria', 'Pública de gestión directa', '180107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2307, '1668425', 'COAR MOQUEGUA', 'Secundaria', 'Pública de gestión directa', '180107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2308, '0309609', 'MARISCAL DOMINGO NIETO', 'Secundaria', 'Pública de gestión directa', '180201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2309, '0680942', 'ALBERTO FARAH DAVID', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '180201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2310, '1210541', 'PRONOESA SAN MARCOS', 'Secundaria de Adultos', 'Privada', '180201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2311, '1360072', 'ALBERTO FARAH DAVID', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '180201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2312, '0614800', 'TUPAC AMARU II', 'Secundaria', 'Pública de gestión directa', '180202', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2313, '1127414', 'TECNICO AGROPECUARIO SANTIAGO DE PACHAS', 'Secundaria', 'Pública de gestión directa', '180202', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2314, '1127455', 'SAN MIGUEL ARCANGEL', 'Secundaria', 'Pública de gestión directa', '180202', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2315, '0614867', 'SAN PEDRO', 'Secundaria', 'Pública de gestión directa', '180203', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2316, '1125301', 'TECNICO AGROPECUARIO CESAR VIZCARRA VARGAS', 'Secundaria', 'Pública de gestión directa', '180204', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2317, '0591099', 'MARISCAL RAMON CASTILLA', 'Secundaria', 'Pública de gestión directa', '180204', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2318, '0708701', 'ANTONIO RAYMONDI', 'Secundaria', 'Pública de gestión directa', '180204', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2319, '1125384', 'MRCAL.RAMON CASTILLA', 'Secundaria de Adultos', 'Pública de gestión directa', '180204', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2320, '1260439', 'ESTATAL DE TOLAPALCA', 'Secundaria', 'Pública de gestión directa', '180204', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2321, '1125343', 'CORONEL FRANCISCO BOLOGNESI CERVANTES', 'Secundaria', 'Pública de gestión directa', '180204', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2322, '1546993', 'FERNANDO BELAUNDE TERRY', 'Secundaria', 'Pública de gestión directa', '180204', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2323, '1545649', 'TECNICO AGROPECUARIO CHARAMAYA', 'Secundaria', 'Pública de gestión directa', '180204', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2324, '1373604', 'SAN IGNACIO DE LOYOLA', 'Secundaria', 'Pública de gestión directa', '180204', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2325, '1373596', 'CORONEL ALFONSO UGARTE', 'Secundaria', 'Pública de gestión directa', '180204', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2326, '1126978', 'SAN ISIDRO LABRADOR', 'Secundaria', 'Pública de gestión directa', '180205', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2327, '1126937', 'LA CAPILLA', 'Secundaria', 'Pública de gestión directa', '180205', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2328, '1765080', '43126', 'Secundaria', 'Pública de gestión directa', '180205', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2329, '0644021', 'ELIAS AGUIRRE ROMERO', 'Secundaria', 'Pública de gestión directa', '180206', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2330, '0614776', 'RICARDO PALMA', 'Secundaria', 'Pública de gestión directa', '180207', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2331, '3016110', '43119', 'Secundaria', 'Pública de gestión directa', '180207', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2332, '1127059', 'JUAN PABLO II', 'Secundaria', 'Pública de gestión directa', '180208', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2333, '0309575', 'MARIANO LINO URQUIETA', 'Secundaria', 'Pública de gestión directa', '180208', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2334, '1127307', 'VIRGEN DEL CARMEN', 'Secundaria', 'Pública de gestión directa', '180208', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2335, '1127091', 'CHILATA', 'Secundaria', 'Pública de gestión directa', '180208', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2336, '1127372', 'AGROPECUARIO SALINAS MOCHE', 'Secundaria', 'Pública de gestión directa', '180208', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2337, '0680850', 'VICTOR RAUL HAYA DE LA TORRE', 'Secundaria', 'Pública de gestión directa', '180209', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2338, '1127216', 'ALMIRANTE MIGUEL GRAU SEMINARIO', 'Secundaria', 'Pública de gestión directa', '180210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2339, '1127331', 'TASSA', 'Secundaria', 'Pública de gestión directa', '180210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2340, '0309765', 'JOSE CARLOS MARIATEGUI', 'Secundaria', 'Pública de gestión directa', '180210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2341, '1127299', 'JOSE ANTONIO ENCINAS', 'Secundaria', 'Pública de gestión directa', '180210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2342, '1546381', '43157 SALINAS CHIVIRIA', 'Secundaria', 'Pública de gestión directa', '180210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2343, '1546399', 'JOSE ABELARDO QUIÑONES', 'Secundaria', 'Pública de gestión directa', '180210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2344, '1311307', 'QUERALA', 'Secundaria', 'Pública de gestión directa', '180210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2345, '1320399', '43148', 'Secundaria', 'Pública de gestión directa', '180210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2346, '1351006', 'FEDERICO VILLARREAL', 'Secundaria', 'Pública de gestión directa', '180210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2347, '1127257', 'JOSE MANUEL UBALDE ZEVALLOS', 'Secundaria', 'Pública de gestión directa', '180210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2348, '1369529', '43121 DANIEL ALCIDES CARRION', 'Secundaria', 'Pública de gestión directa', '180210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2349, '1732445', 'COALAQUE', 'Secundaria', 'Pública de gestión directa', '180210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2350, '0614834', 'ANDRES AVELINO CACERES', 'Secundaria', 'Pública de gestión directa', '180211', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2351, '1126226', 'SANTA MARIA REYNA', 'Secundaria', 'Privada', '180301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2352, '0310243', 'SAN LUIS', 'Secundaria', 'Privada', '180301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2353, '1125707', 'ALMTE. MIGUEL GRAU SEMINARIO', 'Secundaria', 'Pública de gestión directa', '180301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2354, '0309849', 'MERCEDES CABELLO DE CARBONERA', 'Secundaria', 'Pública de gestión directa', '180301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2355, '0614958', 'JORGE BASADRE GROHMANN', 'Secundaria', 'Pública de gestión directa', '180301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2356, '0309815', 'DANIEL BECERRA OCAMPO', 'Secundaria', 'Pública de gestión directa', '180301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2357, '1125665', 'CORONEL FRANCISCO BOLOGNESI CERVANTES', 'Secundaria', 'Pública de gestión directa', '180301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2358, '0310565', 'CARLOS A. VELASQUEZ', 'Secundaria', 'Pública de gestión directa', '180301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2359, '0310367', 'DANIEL BECERRA OCAMPO', 'Secundaria de Adultos', 'Pública de gestión directa', '180301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2360, '1210145', 'VICENTE LOPEZ DE OÑATE', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '180301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2361, '1210269', 'MANUEL GONZALEZ PRADA', 'Secundaria', 'Privada', '180301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2362, '1209949', 'CIRO ALEGRIA', 'Básica Alternativa-Avanzado', 'Privada', '180301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2363, '1209980', 'MARISCAL DOMINGO NIETO', 'Básica Alternativa-Avanzado', 'Privada', '180301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2364, '1209824', 'AUGUSTO SALAZAR BONDY', 'Secundaria de Adultos', 'Privada', '180301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2365, '1209782', 'JOSE PARDO Y BARREDA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '180301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2366, '1276120', 'FE Y ALEGRIA 52', 'Secundaria', 'Pública de gestión privada', '180301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2367, '1547264', 'SANTA ANITA', 'Secundaria', 'Privada', '180301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2368, '1547314', 'DOMINGO SAVIO', 'Secundaria', 'Privada', '180301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2369, '1547355', 'WILLIAM PRESCOTT', 'Secundaria', 'Privada', '180301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2370, '1547389', 'LEONAR EULER', 'Secundaria', 'Privada', '180301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2371, '1547397', 'JOSE PARDO Y BARREDA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '180301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2372, '1547421', 'LA CIENTIFICA', 'Secundaria', 'Privada', '180301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2373, '1321686', 'CRISTIANO BETESDA', 'Secundaria', 'Privada', '180301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2374, '1379254', 'BRYCE ILO', 'Secundaria', 'Privada', '180301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2375, '1523570', 'HIRAM BINGHAM', 'Secundaria', 'Privada', '180301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2376, '1534932', 'CIENCIAS APLICADAS', 'Secundaria', 'Privada', '180301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2377, '1668995', 'SAN FRANCISCO DE ASIS', 'Secundaria', 'Privada', '180301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2378, '1695733', 'VIRGENCITA DE COPACABANA', 'Básica Alternativa-Avanzado', 'Privada', '180301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2379, '1718519', 'CIUDAD ENERSUR', 'Secundaria', 'Pública de gestión directa', '180301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2380, '1719376', 'IMAGINA SCHOOL', 'Secundaria', 'Privada', '180301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2381, '1724780', 'VICENTE LOPEZ DE OÑATE', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '180301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2382, '1727106', 'VIRGENCITA DE COPACABANA', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '180301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2383, '1756618', 'SAN IGNACIO SCHOOL', 'Secundaria', 'Privada', '180301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2384, '1781491', 'STEPHEN HAWKING', 'Secundaria', 'Privada', '180301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2385, '3000783', 'EBENEZER SCHOOL', 'Secundaria', 'Privada', '180301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2386, '1210186', 'AMERICO GARIBALDI GHERSI', 'Secundaria', 'Pública de gestión directa', '180303', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2387, '0680835', 'ENRIQUE MEIGGS', 'Secundaria', 'Privada', '180303', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2388, '1260470', 'LITTLE ANGELS XXI', 'Secundaria', 'Privada', '180303', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2389, '1346790', 'COLEGIO MILITAR MARISCAL DOMINGO NIETO', 'Secundaria', 'Pública de gestión directa', '180303', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2390, '0230052', 'LA INMACULADA', 'Secundaria', 'Privada', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2391, '0239798', 'SAN JUAN BOSCO', 'Secundaria', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2392, '0239814', '45 EMILIO ROMERO PADILLA', 'Secundaria', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2393, '0239822', '32', 'Secundaria', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2394, '0240176', 'GRAN UNIDAD ESCOLAR SAN CARLOS', 'Secundaria', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2395, '0240184', 'GLORIOSO SAN CARLOS', 'Secundaria', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2396, '0240259', 'SANTA ROSA', 'Secundaria', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2397, '0240267', 'MARIA AUXILIADORA', 'Secundaria', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2398, '0474403', 'NUESTRA SEÑORA DE LA MERCED', 'Secundaria', 'Privada', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2399, '0578773', 'INDEPENDENCIA NACIONAL', 'Secundaria', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2400, '0578799', 'JOSE ANTONIO ENCINAS', 'Secundaria', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2401, '0578807', 'SAN JUAN BAUTISTA', 'Secundaria', 'Privada', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2402, '0578823', 'CARLOS RUBINA BURGOS', 'Secundaria', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2403, '0618447', 'POLITECNICO HUASCAR', 'Secundaria', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2404, '0660290', 'ADVENTISTA PUNO', 'Secundaria', 'Privada', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2405, '0701557', 'SAN JOSE', 'Secundaria', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2406, '1024033', 'JOSE CARLOS MARIATEGUI APLICACION UNA', 'Secundaria', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2407, '1024074', 'CARLOS DANTE NAVA', 'Secundaria', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2408, '1024157', 'DIVINO MAESTRO', 'Secundaria', 'Privada', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2409, '1024199', 'PRESCOTT', 'Secundaria', 'Privada', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2410, '1024272', 'CIENCIAS LEONARDO FIBONACI', 'Secundaria', 'Privada', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2411, '1029644', 'VILLA DEL LAGO', 'Secundaria', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2412, '1029974', 'SAN ANTONIO DE PADUA', 'Secundaria', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2413, '0239590', 'VILLA DE FATIMA', 'Secundaria', 'Privada', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2414, '0239624', 'GRAN UNIDAD ESCOLAR SAN CARLOS', 'Secundaria de Adultos', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2415, '0239632', 'GLORIOSO SAN CARLOS', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2416, '0239921', '45 EMILIO ROMERO PADILLA', 'Secundaria de Adultos', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2417, '0239947', '32', 'Secundaria de Adultos', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2418, '0240408', 'GRAN UNIDAD ESCOLAR SAN CARLOS', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2419, '0240523', 'GLORIOSO SAN CARLOS', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2420, '0578815', 'COMPLEJO EDUCATIVO AGROPECUARIO', 'Secundaria', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2421, '1024231', 'CARLOS ECHEGARAY LINARES', 'Secundaria', 'Privada', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2422, '1024314', 'SANTA ROSA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2423, '1024355', '70025 INDEPENDENCIA NACIONAL', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2424, '1024397', 'JOSE ANTONIO ENCINAS', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2425, '1025741', '70025 INDEPENDENCIA NACIONAL', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2426, '1025774', 'UROS CHULLUNI', 'Secundaria', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2427, '1153865', '70718', 'Secundaria de Adultos', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2428, '1154343', 'SAN IGNACIO DE LOYOLA', 'Secundaria', 'Privada', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2429, '0578781', '47092', 'Secundaria', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2430, '0701540', 'DANIEL ALCIDES CARRION', 'Secundaria', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2431, '1024116', 'ANDRES BELLO', 'Secundaria', 'Privada', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2432, '1025782', 'ALTO PUNO', 'Secundaria de Adultos', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2433, '1154780', 'IMAGINA SCHOOL', 'Secundaria', 'Privada', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2434, '1155381', 'EMMANUEL', 'Secundaria', 'Privada', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2435, '1154939', 'GERMAN TORRES HUMPIRI', 'Básica Alternativa-Avanzado', 'Privada', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2436, '1023845', 'DOMINGO SAVIO', 'Básica Alternativa-Avanzado', 'Privada', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2437, '1023779', 'DOMINGO SAVIO', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2438, '1571140', 'DIEGO J. THOMPSON', 'Secundaria', 'Privada', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2439, '1571157', 'CLAUDIO GALENO', 'Secundaria', 'Privada', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2440, '1571165', 'NUESTRA SEÑORA DE GUADALUPE', 'Secundaria', 'Privada', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2441, '1571207', 'DILEAA', 'Secundaria de Adultos', 'Privada', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2442, '1571249', 'CHAMPAGNAT DEL NIÑO DIVINO JESUS', 'Secundaria', 'Privada', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2443, '1571512', 'ALEXANDER FLEMING', 'Secundaria', 'Privada', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2444, '1306950', 'NOVUS ORDER', 'Secundaria', 'Privada', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2445, '1317569', 'JESUS OBRERO', 'Secundaria de Adultos', 'Privada', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2446, '1331883', 'VILLA DEL LAGO', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2447, '1360130', '45 EMILIO ROMERO PADILLA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2448, '1360148', 'JOSE ANTONIO ENCINAS', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2449, '1360155', '32', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2450, '1372879', 'INCA MANCO CAPAC', 'Secundaria', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2451, '1438753', 'MARIA TERESA DE CALCUTA', 'Secundaria', 'Privada', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2452, '1438779', 'CRAMER', 'Secundaria', 'Privada', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2453, '1561398', 'MARIANO SANTOS MATEOS', 'Secundaria', 'Privada', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2454, '1561380', 'JAMES BALDWIN', 'Secundaria', 'Privada', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2455, '1564590', 'APLICACION PEDAGOGICO PUNO', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2456, '1569201', 'LEONARD EULER', 'Secundaria', 'Privada', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2457, '1641562', 'EL BUEN PASTOR', 'Secundaria', 'Privada', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2458, '1645993', 'PRINSTON', 'Secundaria', 'Privada', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2459, '1702331', 'VIRGEN DE LA ASUNCION', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2460, '1721471', 'SAN SALVADOR', 'Secundaria', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2461, '1724855', 'GRAN UNIDAD ESCOLAR SAN CARLOS', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2462, '1731322', 'CRISTO REY', 'Secundaria', 'Privada', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2463, '1751296', 'COLVER', 'Secundaria', 'Privada', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2464, '1761295', 'YACHAY SCHOOL', 'Secundaria', 'Privada', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2465, '1764067', 'VIRGEN DE LA ASUNCION', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2466, '1791136', 'ESPIRITU SANTO', 'Secundaria', 'Privada', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2467, '3018579', '70092', 'Secundaria', 'Pública de gestión directa', '210101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2468, '0239897', 'COOPERATIVO', 'Secundaria', 'Privada', '210102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2469, '1025154', 'FRANCISCO BOLOGNESI CERVANTES', 'Secundaria', 'Pública de gestión directa', '210102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2470, '1260124', 'GILATAMARCA', 'Secundaria', 'Pública de gestión directa', '210102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2471, '0240341', 'ALFONSO TORRES LUNA', 'Secundaria', 'Pública de gestión directa', '210102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2472, '0474494', 'TUPAC AMARU II', 'Secundaria', 'Pública de gestión directa', '210102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2473, '0578971', 'ENRIQUE ENCINAS FRANCO', 'Secundaria', 'Pública de gestión directa', '210102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2474, '0706580', 'TUPAQ KATARI', 'Secundaria', 'Pública de gestión directa', '210102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2475, '0744441', 'THUNCO', 'Secundaria', 'Pública de gestión directa', '210102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2476, '1025113', 'AGROPECUARIO THUNUHUAYA', 'Secundaria', 'Pública de gestión directa', '210102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2477, '1025196', 'SAN JUAN', 'Secundaria', 'Pública de gestión directa', '210102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2478, '1025790', 'CRUCERO', 'Secundaria', 'Privada', '210102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2479, '1027820', 'TAIPICIRCA', 'Secundaria', 'Pública de gestión directa', '210102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2480, '0535864', 'RICARDO PALMA', 'Secundaria', 'Pública de gestión directa', '210102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2481, '0615351', 'JOSE ANTONIO ENCINAS', 'Secundaria', 'Pública de gestión directa', '210102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2482, '0579029', 'CARLOS DANTE NAVA', 'Secundaria', 'Pública de gestión directa', '210102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2483, '0660282', 'SIMON BOLIVAR', 'Secundaria', 'Pública de gestión directa', '210102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2484, '1029982', 'JUAN VELASCO ALVARADO', 'Secundaria', 'Pública de gestión directa', '210102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2485, '1572544', 'MANCO CAPAC', 'Secundaria', 'Pública de gestión directa', '210102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2486, '1571397', 'NUR', 'Secundaria', 'Privada', '210102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2487, '1385061', 'CCAPALLA', 'Secundaria', 'Pública de gestión directa', '210102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2488, '1418169', 'AYMARA', 'Secundaria', 'Pública de gestión directa', '210102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2489, '1541887', 'FLORENTINO AMEGHINO', 'Secundaria', 'Pública de gestión directa', '210102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2490, '0631135', 'MIGUEL GRAU', 'Secundaria', 'Pública de gestión directa', '210103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2491, '1025394', 'TAQUILE', 'Secundaria', 'Pública de gestión directa', '210103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2492, '0507533', 'SAN ANDRES', 'Secundaria', 'Pública de gestión directa', '210104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2493, '1376938', 'AMANECER QOLLA', 'Secundaria', 'Pública de gestión privada', '210104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2494, '1564608', 'SAN JOSE DE LLUNGO', 'Secundaria', 'Pública de gestión directa', '210104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2495, '0239723', 'ENRIQUE TORRES BELON', 'Secundaria', 'Pública de gestión directa', '210105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2496, '0474452', 'JOSE CARLOS MARIATEGUI', 'Secundaria', 'Pública de gestión directa', '210105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2497, '0578930', 'FRAY SAN MARTIN DE PORRES', 'Secundaria', 'Pública de gestión directa', '210105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2498, '1023365', 'JOSE OLAYA BALANDRA', 'Secundaria', 'Pública de gestión directa', '210105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2499, '1023407', 'CORAZON DE CRISTO', 'Secundaria', 'Pública de gestión directa', '210105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2500, '0578948', '70027', 'Secundaria', 'Pública de gestión directa', '210105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2501, '1154491', 'JOSE ABELARDO QUIÑONES', 'Secundaria', 'Pública de gestión directa', '210105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2502, '1364629', 'ISAÑURA', 'Secundaria', 'Pública de gestión directa', '210105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2503, '0240358', 'EMILIO ROMERO PADILLA', 'Secundaria', 'Pública de gestión directa', '210106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2504, '0521997', 'INCA GARCILAZO DE LA VEGA', 'Secundaria', 'Pública de gestión directa', '210106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2505, '1023480', 'INDEPENDENCIA NACIONAL', 'Secundaria', 'Pública de gestión directa', '210106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2506, '1023522', 'LEONCIO PRADO', 'Secundaria', 'Pública de gestión directa', '210106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2507, '1023563', 'MARIANO MELGAR VALDIVIESO', 'Secundaria', 'Pública de gestión directa', '210106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2508, '1023605', 'POTOJANI GRANDE', 'Secundaria', 'Pública de gestión directa', '210106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2509, '1400738', 'INCHUPALLA', 'Secundaria', 'Pública de gestión directa', '210106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2510, '1669753', 'COAR PUNO', 'Secundaria', 'Pública de gestión directa', '210106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2511, '0578955', 'SAN AGUSTIN', 'Secundaria', 'Pública de gestión directa', '210107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2512, '0578963', 'SORAZA', 'Secundaria', 'Pública de gestión directa', '210107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2513, '1023647', 'SAJANACACHI', 'Secundaria', 'Pública de gestión directa', '210107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2514, '1023688', 'TECNICO INDUSTRIAL TAHUANTINSUYO', 'Secundaria', 'Pública de gestión directa', '210107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2515, '0578922', '71557', 'Secundaria', 'Privada', '210107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2516, '0522193', 'SAN JUAN DE HUATTA', 'Secundaria', 'Pública de gestión directa', '210108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2517, '1753730', 'MIGUEL GRAU SEMINARIO', 'Secundaria', 'Pública de gestión directa', '210108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2518, '0474569', 'MAÑAZO', 'Secundaria', 'Pública de gestión directa', '210109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2519, '1023761', 'MAÑAZO', 'Secundaria de Adultos', 'Pública de gestión directa', '210109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2520, '1571470', 'SAN MIGUEL', 'Secundaria', 'Pública de gestión directa', '210109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2521, '1360122', 'MAÑAZO', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '210109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2522, '1372861', 'TECNICO AGROPECUARIO CHARAMAYA', 'Secundaria', 'Pública de gestión directa', '210109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2523, '1746304', 'INMACULADA CONCEPCION', 'Secundaria', 'Pública de gestión directa', '210109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2524, '0522292', 'TUPAC AMARU', 'Secundaria', 'Pública de gestión directa', '210110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2525, '1571587', 'CESAR VALLEJO', 'Secundaria', 'Pública de gestión directa', '210110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2526, '0489963', 'EDUARDO BENIGNO LUQUE ROMERO', 'Secundaria', 'Pública de gestión directa', '210111', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2527, '1023928', 'MARISCAL SUCRE', 'Secundaria', 'Pública de gestión directa', '210111', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2528, '1025808', 'HUACOCHULLO', 'Secundaria', 'Pública de gestión directa', '210111', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2529, '1025816', 'MIGUEL GRAU', 'Secundaria', 'Pública de gestión directa', '210111', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2530, '1573013', 'TITIRI', 'Secundaria', 'Pública de gestión directa', '210111', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2531, '1024082', 'GAMALIEL CHURATA', 'Secundaria', 'Pública de gestión directa', '210112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2532, '0474502', 'AGROINDUSTRIAL DE CCOTA', 'Secundaria', 'Pública de gestión directa', '210112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2533, '0474510', 'MANUEL ZUÑIGA CAMACHO', 'Secundaria', 'Pública de gestión directa', '210112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2534, '0521799', 'VICTOR RAUL HAYA DE LA TORRE', 'Secundaria', 'Pública de gestión directa', '210112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2535, '0615203', 'JULIO GONZALES RUIZ', 'Secundaria', 'Pública de gestión directa', '210112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2536, '0701581', 'FERNANDO A. STAHL', 'Secundaria', 'Privada', '210112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2537, '1024041', 'ANDRES AVELINO CACERES', 'Secundaria', 'Pública de gestión directa', '210112', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2538, '1024124', 'LOS ANDES', 'Secundaria', 'Pública de gestión directa', '210113', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2539, '1571439', 'SEÑOR DE HUANCA', 'Secundaria', 'Pública de gestión directa', '210113', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2540, '0536912', 'SAN FRANCISCO', 'Secundaria', 'Pública de gestión directa', '210114', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2541, '0631333', 'JUAN BUSTAMANTE DUEÑAS', 'Secundaria', 'Pública de gestión directa', '210115', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2542, '1569219', 'SAN JUAN DE MACHACMARCA', 'Secundaria', 'Pública de gestión directa', '210115', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2543, '0581413', 'AGROPECUARIO', 'Secundaria', 'Pública de gestión directa', '210201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2544, '1023985', 'JOSE REYES LUJAN', 'Secundaria', 'Pública de gestión directa', '210201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2545, '0239681', 'JOSE ANTONIO ENCINAS', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '210201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2546, '0240564', '72730', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '210201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2547, '0239731', 'INA 21 JOSE DOMINGO CHOQUEHUANCA', 'Secundaria', 'Pública de gestión directa', '210201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2548, '0478008', 'PEDRO VILCAPAZA', 'Secundaria', 'Pública de gestión directa', '210201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2549, '0581439', 'YAJCHATA', 'Secundaria', 'Pública de gestión directa', '210201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2550, '0612168', 'A 28 PERU BIRF', 'Secundaria', 'Pública de gestión directa', '210201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2551, '0693127', 'MACAYA', 'Secundaria', 'Pública de gestión directa', '210201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2552, '1023902', 'SAN CARLOS', 'Secundaria', 'Pública de gestión directa', '210201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2553, '1023936', 'LIZANDRO LUNA', 'Secundaria', 'Pública de gestión directa', '210201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2554, '1023944', 'AGROPECUARIO', 'Secundaria', 'Pública de gestión directa', '210201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2555, '1023977', 'APLICACION ISPA', 'Secundaria', 'Pública de gestión directa', '210201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2556, '1024181', 'ARTURO CARCAGNO', 'Secundaria', 'Privada', '210201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2557, '1259480', 'CARLOS DANTE NAVA', 'Secundaria', 'Privada', '210201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2558, '1573195', 'ALEXANDER VON HUMBOLDT', 'Secundaria', 'Privada', '210201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2559, '1355486', 'JOHANN MENDEL', 'Secundaria', 'Privada', '210201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2560, '1639236', 'CESAR VALLEJO', 'Secundaria', 'Privada', '210201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2561, '1724863', '72730', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '210201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2562, '1763150', 'CERMAT SCHOOL', 'Secundaria', 'Privada', '210201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2563, '1770940', 'ROSARIO HUANCARANI', 'Secundaria', 'Pública de gestión directa', '210201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2564, '3052222', 'HUELLAS DE LUPITA', 'Secundaria', 'Privada', '210201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2565, '0612499', 'AGROPECUARIO OCCORO', 'Secundaria', 'Pública de gestión directa', '210202', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2566, '0500645', 'SAN MIGUEL', 'Secundaria', 'Pública de gestión directa', '210202', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2567, '1024256', 'YANICO CUTURI', 'Secundaria', 'Pública de gestión directa', '210203', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2568, '1024132', 'VILLA BETANZOS', 'Secundaria', 'Pública de gestión directa', '210203', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2569, '0581538', 'IMPUCHI', 'Secundaria', 'Pública de gestión directa', '210203', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2570, '0581520', 'TUPAC AMARU', 'Secundaria', 'Pública de gestión directa', '210203', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2571, '0239475', 'ARAPA', 'Secundaria', 'Pública de gestión directa', '210203', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2572, '1355494', 'SHOOL PACIFIC CONRADO KRETS LENZ', 'Secundaria', 'Privada', '210203', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2573, '1025105', 'ASILLO COMERCIO', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '210204', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2574, '1024066', 'MANUEL SCORZA', 'Secundaria', 'Pública de gestión directa', '210204', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2575, '1024058', 'INDEPENDENCIA AMERICANA', 'Secundaria', 'Pública de gestión directa', '210204', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2576, '0581462', 'JOSE ANTONIO ENCINAS', 'Secundaria', 'Pública de gestión directa', '210204', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2577, '0240366', 'SAN JERONIMO', 'Secundaria', 'Pública de gestión directa', '210204', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2578, '1573211', 'NOBEL MARIO VARGAS LLOSA', 'Secundaria', 'Pública de gestión directa', '210204', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2579, '1360841', 'ASILLO COMERCIO', 'Básica Alternativa', 'Pública de gestión directa', '210204', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2580, '1579556', 'JUAN VELASCO ALVARADO', 'Secundaria', 'Pública de gestión directa', '210204', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2581, '0581496', 'CAMINACA', 'Secundaria', 'Pública de gestión directa', '210205', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2582, '0805010', 'SAN PEDRO', 'Secundaria', 'Pública de gestión directa', '210205', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2583, '1024173', 'JOSE ANTONIO ENCINAS FRANCO', 'Secundaria', 'Pública de gestión directa', '210206', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2584, '1024140', 'RICARDO PALMA', 'Secundaria', 'Pública de gestión directa', '210206', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2585, '0581546', 'CHOCCO', 'Secundaria', 'Pública de gestión directa', '210206', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2586, '0581553', 'CHUCAHUACAS', 'Secundaria', 'Pública de gestión directa', '210206', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2587, '0239756', '125', 'Secundaria', 'Pública de gestión directa', '210206', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2588, '1024108', 'SIMON BOLIVAR', 'Secundaria', 'Pública de gestión directa', '210207', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2589, '0239491', 'JOSE DOMINGO CHOQUEHUANCA', 'Secundaria', 'Pública de gestión directa', '210207', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2590, '1024017', 'EZEQUIEL URVIOLA Y RIVERO', 'Secundaria', 'Pública de gestión directa', '210208', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2591, '0548099', 'MUÑANI', 'Secundaria', 'Pública de gestión directa', '210208', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2592, '1261320', 'MUÑANI', 'Secundaria de Adultos', 'Pública de gestión directa', '210208', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2593, '1360833', 'MUÑANI', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '210208', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2594, '1671270', 'MIRASOL', 'Secundaria', 'Privada', '210208', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2595, '0632091', 'CARLOS GUTIERREZ ZAMORA', 'Secundaria', 'Pública de gestión directa', '210209', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2596, '0810622', 'POTONI', 'Secundaria', 'Pública de gestión directa', '210209', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2597, '1774553', '72118', 'Secundaria', 'Pública de gestión directa', '210209', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2598, '0612465', 'DANTE NAVA', 'Secundaria', 'Pública de gestión directa', '210210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2599, '0746289', 'SAN JUAN', 'Secundaria', 'Pública de gestión directa', '210210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2600, '0485763', 'SAN AGUSTIN', 'Secundaria', 'Pública de gestión directa', '210210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2601, '0700948', 'MANUEL NUÑEZ BUTRON', 'Secundaria', 'Pública de gestión directa', '210210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2602, '0729350', 'CAÑICUTO', 'Secundaria', 'Pública de gestión directa', '210211', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2603, '0547398', 'SAN ANTON', 'Secundaria', 'Pública de gestión directa', '210211', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2604, '1344043', 'JOSE MARIA ARGUEDAS ALTAMIRANO', 'Secundaria', 'Pública de gestión directa', '210211', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2605, '1544931', 'LOS ANGELES', 'Secundaria', 'Privada', '210211', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2606, '1699040', 'UNION AMERICANA', 'Secundaria', 'Pública de gestión directa', '210211', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2607, '1754795', 'SAN ANTON', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '210211', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2608, '3013471', 'JUAN FRANCISCO VELASCO ALVARADO', 'Secundaria', 'Pública de gestión directa', '210211', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2609, '0522508', 'SAN JOSE', 'Secundaria', 'Pública de gestión directa', '210212', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2610, '0612192', 'SOLLOCOTA', 'Secundaria', 'Pública de gestión directa', '210212', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2611, '0581421', 'JOSE CARLOS MARIATEGUI', 'Secundaria', 'Pública de gestión directa', '210213', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2612, '1024223', 'CCALLA', 'Secundaria', 'Privada', '210213', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2613, '1636232', 'SAN PEDRO DE CCALLA ESMERALDA', 'Secundaria', 'Pública de gestión directa', '210213', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `origin_schools` (`id`, `modular_code`, `name`, `d_niv_mod`, `management_type`, `ubigeo_code`, `created_at`, `updated_at`) VALUES
(2614, '0239855', 'SANTIAGO DE PUPUJA', 'Secundaria', 'Pública de gestión directa', '210214', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2615, '0612556', 'MATARO CHICO', 'Secundaria', 'Pública de gestión directa', '210214', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2616, '1024090', 'INDEPENDENCIA', 'Secundaria', 'Pública de gestión directa', '210214', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2617, '1573203', 'JOSE ANTONIO ENCINAS FRANCO', 'Secundaria', 'Pública de gestión directa', '210214', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2618, '0581504', 'TUPAC AMARU II', 'Secundaria', 'Pública de gestión directa', '210215', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2619, '1579564', 'PURINA', 'Secundaria', 'Pública de gestión directa', '210215', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2620, '0239509', '73002', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '210301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2621, '0240572', '73002', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '210301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2622, '0698043', 'JOSE MACEDO MENDOZA', 'Secundaria', 'Pública de gestión directa', '210301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2623, '1024629', 'PACAJE', 'Secundaria', 'Pública de gestión directa', '210301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2624, '1024645', 'JULIO ENRIQUE BARREDA ARAGON', 'Secundaria', 'Pública de gestión directa', '210301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2625, '1029883', 'JULIO GABANCHO ENRIQUEZ', 'Secundaria', 'Pública de gestión directa', '210301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2626, '1308790', 'POLITECNICO INDUSTRIAL MACUSANI', 'Secundaria', 'Pública de gestión directa', '210301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2627, '1311323', 'VIRGEN DE COPACABANA', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '210301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2628, '1317023', 'VIRGEN DE COPACABANA', 'Básica Alternativa-Avanzado', 'Privada', '210301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2629, '1759612', 'UNIVERSO KEPLER', 'Secundaria', 'Privada', '210301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2630, '1802008', 'JOHN DALTON', 'Secundaria', 'Privada', '210301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2631, '3054426', 'GAUSS', 'Secundaria', 'Privada', '210301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2632, '1024652', 'JOSE CARLOS MARIATEGUI', 'Secundaria', 'Pública de gestión directa', '210302', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2633, '0549188', 'AYAPATA', 'Secundaria', 'Pública de gestión directa', '210303', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2634, '1024660', 'KANA', 'Secundaria', 'Pública de gestión directa', '210303', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2635, '1025840', 'AYAPATA', 'Secundaria de Adultos', 'Privada', '210303', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2636, '1575109', 'TAYPE', 'Secundaria', 'Pública de gestión directa', '210303', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2637, '1575042', 'HANAC AYLLU', 'Secundaria', 'Pública de gestión directa', '210303', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2638, '1563931', 'PEDRO E. PAULET MOSTAJO', 'Secundaria', 'Pública de gestión directa', '210303', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2639, '1633320', 'MCA', 'Secundaria', 'Privada', '210303', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2640, '3016300', 'REMSON FABIO PRADO GONZALES', 'Secundaria', 'Pública de gestión directa', '210303', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2641, '0546598', 'TUPAC AMARU', 'Secundaria', 'Pública de gestión directa', '210304', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2642, '0810614', 'MIGUEL DE CERVANTES SAAVEDRA', 'Secundaria', 'Pública de gestión directa', '210304', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2643, '1155258', 'COAZA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '210304', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2644, '1155415', 'CARLOS DANTE NAVA SILVA', 'Secundaria', 'Pública de gestión directa', '210304', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2645, '1575083', 'AYUSUMA', 'Secundaria', 'Pública de gestión directa', '210304', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2646, '1436658', 'MARTIN JERONIMO CHAMBI JIMENEZ', 'Secundaria', 'Pública de gestión directa', '210304', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2647, '0612408', 'CORANI', 'Secundaria', 'Pública de gestión directa', '210305', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2648, '1024686', 'ISIVILLA', 'Secundaria', 'Pública de gestión directa', '210305', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2649, '1155050', 'SAN JUAN BOSCO', 'Secundaria', 'Pública de gestión directa', '210305', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2650, '1625383', 'QUELCAYA', 'Secundaria', 'Pública de gestión directa', '210305', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2651, '1625391', 'CHACACONIZA', 'Secundaria', 'Pública de gestión directa', '210305', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2652, '0546895', 'AGRO INDUSTRIAL', 'Secundaria', 'Pública de gestión directa', '210306', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2653, '1024983', 'AGRO - ARTESANAL', 'Secundaria', 'Pública de gestión directa', '210306', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2654, '1025006', 'SIERVOS DE DIOS', 'Secundaria', 'Pública de gestión directa', '210306', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2655, '1025014', 'OSCOROQUE', 'Secundaria', 'Pública de gestión directa', '210306', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2656, '1025022', 'ADVENTISTA LOS ANDES', 'Secundaria', 'Privada', '210306', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2657, '1320555', 'CRUCERO', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '210306', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2658, '1404946', 'DEL ALTIPLANO', 'Secundaria', 'Pública de gestión directa', '210306', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2659, '1653856', 'JOSE ANTONIO ENCINAS', 'Secundaria', 'Pública de gestión directa', '210306', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2660, '1771054', 'CUSQUI', 'Secundaria', 'Pública de gestión directa', '210306', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2661, '1024694', 'JOSE ANTONIO ENCINAS', 'Secundaria', 'Pública de gestión directa', '210307', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2662, '1024702', 'CESAR VALLEJO MENDOZA', 'Secundaria', 'Pública de gestión directa', '210307', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2663, '1025857', 'CESP ITUATA', 'Secundaria', 'Pública de gestión directa', '210307', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2664, '1154657', 'ITUATA', 'Secundaria', 'Pública de gestión directa', '210307', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2665, '1259407', 'INDEPENDENCIA AMERICANA', 'Secundaria', 'Pública de gestión directa', '210307', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2666, '1638428', 'JORGE BASADRE GROHMANN', 'Secundaria', 'Pública de gestión directa', '210307', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2667, '0568923', 'ANTONIO RAIMONDI', 'Secundaria', 'Pública de gestión directa', '210308', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2668, '1024728', 'JOSE MARIA ARGUEDAS', 'Secundaria', 'Pública de gestión directa', '210308', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2669, '1543149', 'PEDRO VILCAPAZA ALARCON', 'Secundaria', 'Pública de gestión directa', '210308', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2670, '1723402', 'PUMACHANCA', 'Secundaria', 'Pública de gestión directa', '210308', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2671, '1747203', 'MARIO VARGAS LLOSA', 'Secundaria', 'Pública de gestión directa', '210308', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2672, '3043833', 'COMUNIDAD OLLACHEA', 'Secundaria', 'Privada', '210308', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2673, '0612374', 'SAN GABAN', 'Secundaria', 'Pública de gestión directa', '210309', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2674, '1029735', 'CHACANEQUE', 'Secundaria', 'Pública de gestión directa', '210309', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2675, '1575117', 'VICTOR RAUL HAYA DE LA TORRE', 'Secundaria', 'Pública de gestión directa', '210309', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2676, '1347665', 'JOSE ANTONIO ENCINAS FRANCO', 'Secundaria', 'Pública de gestión directa', '210309', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2677, '1759620', 'ICACO', 'Secundaria', 'Pública de gestión directa', '210309', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2678, '3028024', 'CIENCIAS DE AMERICA', 'Secundaria', 'Pública de gestión directa', '210309', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2679, '0581512', 'LIBERTADOR SIMON BOLIVAR', 'Secundaria', 'Pública de gestión directa', '210310', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2680, '1575034', 'SALLACONI', 'Secundaria', 'Pública de gestión directa', '210310', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2681, '1621838', 'TECNICO AGROPECUARIO INDUSTRIAL', 'Secundaria', 'Pública de gestión directa', '210310', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2682, '1634138', 'SAGRADO CORAZON DE JESUS', 'Secundaria', 'Pública de gestión directa', '210310', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2683, '1781004', '72195', 'Secundaria', 'Pública de gestión directa', '210310', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2684, '1782770', '72181 GLORIOSO 827', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '210310', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2685, '0240275', 'MARIA ASUNCION GALINDO', 'Secundaria', 'Pública de gestión directa', '210401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2686, '1153451', 'ITAPALLUNI', 'Secundaria', 'Pública de gestión directa', '210401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2687, '1027929', 'CALLACAMI', 'Secundaria', 'Pública de gestión directa', '210401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2688, '0762021', 'TECNICO AGROPECUARIO SIVICANI', 'Secundaria', 'Pública de gestión directa', '210401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2689, '1027911', 'AGROPECUARIO SORAPA', 'Secundaria', 'Pública de gestión directa', '210401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2690, '0755314', 'CHALLAPAMPA', 'Secundaria', 'Pública de gestión directa', '210401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2691, '1027705', 'SANTIAGO', 'Secundaria', 'Pública de gestión directa', '210401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2692, '0744490', 'YARIHUANI', 'Secundaria', 'Pública de gestión directa', '210401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2693, '0578831', 'MOLINO', 'Secundaria', 'Pública de gestión directa', '210401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2694, '0240200', 'TELESFORO CATACORA', 'Secundaria', 'Pública de gestión directa', '210401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2695, '0537266', 'PERU BIRF INDUSTRIAL', 'Secundaria', 'Pública de gestión directa', '210401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2696, '0489955', 'TELESFORO CATACORA', 'Secundaria de Adultos', 'Pública de gestión directa', '210401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2697, '0240432', 'TELESFORO CATACORA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '210401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2698, '1027721', 'CASPA', 'Secundaria', 'Pública de gestión directa', '210401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2699, '1154269', 'JORGE FOX', 'Secundaria', 'Privada', '210401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2700, '1304054', 'LUPAKAS JULI', 'Secundaria', 'Pública de gestión directa', '210401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2701, '1311331', 'ADVENTISTA ALEJANDRO BULLON', 'Secundaria', 'Privada', '210401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2702, '1437920', 'PASIRI', 'Secundaria', 'Privada', '210401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2703, '1724871', 'TELESFORO CATACORA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '210401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2704, '1029362', 'TECNICO COMERCIAL', 'Secundaria', 'Pública de gestión directa', '210402', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2705, '0240333', 'TAWANTINSUYO', 'Secundaria', 'Pública de gestión directa', '210402', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2706, '0521492', 'TAHUANTINSUYO', 'Secundaria de Adultos', 'Pública de gestión directa', '210402', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2707, '1029370', 'ADVENTISTA LUCIANO CHAMBI', 'Secundaria', 'Privada', '210402', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2708, '0240440', 'BINACIONAL CENTRAL PATANI', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '210402', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2709, '0755355', 'THUNUHUAYA', 'Secundaria', 'Privada', '210402', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2710, '0755363', 'THUNCO', 'Secundaria', 'Pública de gestión directa', '210402', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2711, '1577261', 'INTERNACIONAL DESAGUADERO', 'Secundaria', 'Privada', '210402', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2712, '1727213', 'BINACIONAL CENTRAL PATANI', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '210402', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2713, '1029461', 'TEODORO MORALES ARCE', 'Secundaria', 'Pública de gestión directa', '210403', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2714, '0548206', 'HUACULLANI', 'Secundaria', 'Pública de gestión directa', '210403', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2715, '1029446', 'ADVENTISTA LACAHAQHI', 'Secundaria', 'Privada', '210403', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2716, '1154707', 'VILACHAVE', 'Secundaria', 'Pública de gestión directa', '210403', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2717, '0701375', 'TUPAC AMARU', 'Secundaria', 'Pública de gestión directa', '210403', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2718, '0660449', 'HUACULLANI', 'Secundaria de Adultos', 'Pública de gestión directa', '210403', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2719, '1029420', 'LACA LACA', 'Secundaria', 'Privada', '210403', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2720, '1577170', 'CHALLACOLLO', 'Secundaria', 'Pública de gestión directa', '210403', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2721, '1029438', 'AURINCOTA', 'Secundaria', 'Pública de gestión directa', '210403', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2722, '1577220', 'HUACASUMA', 'Secundaria', 'Privada', '210403', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2723, '1306422', 'HUACASUMA', 'Secundaria', 'Pública de gestión directa', '210403', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2724, '1029636', 'ADVENTISTA', 'Secundaria', 'Privada', '210404', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2725, '1029594', 'TULACOLLO', 'Secundaria', 'Pública de gestión directa', '210404', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2726, '1029602', 'CHACOCOLLO', 'Secundaria', 'Pública de gestión directa', '210404', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2727, '0579060', 'MANUEL A. ODRIA', 'Secundaria', 'Pública de gestión directa', '210404', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2728, '0631630', 'JUAN VELAZCO ALVARADO', 'Secundaria', 'Pública de gestión directa', '210404', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2729, '1577246', 'CARLOS DANTE NAVA', 'Secundaria', 'Pública de gestión directa', '210404', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2730, '0548305', 'PIZACOMA', 'Secundaria', 'Pública de gestión directa', '210405', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2731, '1029479', 'ADVENTISTA PIZACOMA', 'Secundaria', 'Privada', '210405', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2732, '1585504', 'CHAMBALAYA', 'Secundaria', 'Pública de gestión directa', '210405', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2733, '1577188', 'CORONEL FRANCISCO BOLOGNESI CERVANTES', 'Secundaria', 'Pública de gestión directa', '210405', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2734, '1788587', 'CORONEL FRANCISCO BOLOGNESI CERVANTES', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '210405', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2735, '1788793', 'CORONEL FRANCISCO BOLOGNESI CERVANTES', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '210405', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2736, '0701383', 'SIMON BOLIVAR', 'Secundaria', 'Pública de gestión directa', '210406', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2737, '1029503', 'POLITECNICO HUACANI', 'Secundaria', 'Pública de gestión directa', '210406', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2738, '1029487', 'COLLINI', 'Secundaria', 'Pública de gestión directa', '210406', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2739, '0239715', '73 AGROPECUARIA', 'Secundaria', 'Pública de gestión directa', '210406', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2740, '1029495', 'LLAQUEPA', 'Secundaria', 'Pública de gestión directa', '210406', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2741, '0615112', 'HUAPACA SANTIAGO', 'Secundaria', 'Pública de gestión directa', '210406', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2742, '0701391', 'HUAPACA SAN MIGUEL', 'Secundaria', 'Pública de gestión directa', '210406', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2743, '1028885', 'EMANUEL', 'Secundaria', 'Pública de gestión directa', '210406', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2744, '1029578', 'ALTO AYRIHUAS', 'Secundaria', 'Pública de gestión directa', '210407', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2745, '1029560', 'MARISCAL ANDRES DE SANTA CRUZ', 'Secundaria', 'Pública de gestión directa', '210407', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2746, '1029552', 'ILLECA MOLINO', 'Secundaria', 'Pública de gestión directa', '210407', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2747, '0546614', 'DANIEL ALCIDES CARRION', 'Secundaria', 'Pública de gestión directa', '210407', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2748, '0615179', 'JOSE ANTONIO ENCINAS', 'Secundaria', 'Pública de gestión directa', '210407', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2749, '0578872', 'MANUEL GONZALES PRADA', 'Secundaria', 'Pública de gestión directa', '210407', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2750, '0474536', 'ANDRES AVELINO CACERES', 'Secundaria', 'Pública de gestión directa', '210407', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2751, '0578989', 'JUAN VELASCO ALVARADO', 'Secundaria', 'Pública de gestión directa', '210407', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2752, '0579052', 'CESAR VALLEJO', 'Secundaria', 'Pública de gestión directa', '210407', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2753, '1155340', 'JOSE CARLOS MARIATEGUI', 'Secundaria', 'Pública de gestión directa', '210407', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2754, '1577121', 'ALTO PAVITA', 'Secundaria', 'Pública de gestión directa', '210407', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2755, '3013489', 'MANUEL ZUÑIGA CAMACHO', 'Secundaria', 'Pública de gestión directa', '210407', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2756, '3031051', 'SAN PEDRO - SAN PABLO', 'Secundaria', 'Pública de gestión directa', '210407', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2757, '0240465', 'SEÑOR DE LOS MILAGROS', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '210501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2758, '0239640', 'JOSE CARLOS MARIATEGUI', 'Secundaria de Adultos', 'Pública de gestión directa', '210501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2759, '1027077', 'JORGE FOX', 'Secundaria', 'Privada', '210501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2760, '0537365', 'MARISCAL CASTILLA', 'Secundaria', 'Privada', '210501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2761, '1027069', 'ANTONIO RAIMONDI', 'Secundaria', 'Privada', '210501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2762, '0578914', 'JORGE CHAVEZ', 'Secundaria', 'Pública de gestión directa', '210501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2763, '1027812', 'CHIJICHAYA', 'Secundaria', 'Pública de gestión directa', '210501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2764, '0240283', 'NUESTRA SEÑORA DEL CARMEN', 'Secundaria', 'Pública de gestión directa', '210501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2765, '0578880', 'SIRAYA', 'Secundaria', 'Pública de gestión directa', '210501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2766, '0579011', 'MARIANO MELGAR', 'Secundaria', 'Pública de gestión directa', '210501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2767, '0537464', 'PEDRO VILCAPAZA', 'Secundaria', 'Pública de gestión directa', '210501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2768, '1027804', 'SAN ANTONIO', 'Secundaria', 'Pública de gestión directa', '210501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2769, '0755280', 'CANGALLI', 'Secundaria', 'Pública de gestión directa', '210501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2770, '0578898', 'HORACIO ZEVALLOS GAMEZ', 'Secundaria', 'Pública de gestión directa', '210501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2771, '0755223', 'POLITECNICO REGIONAL DON BOSCO', 'Secundaria', 'Pública de gestión directa', '210501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2772, '0578906', 'AUGUSTO SALAZAR BONDY', 'Secundaria', 'Pública de gestión directa', '210501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2773, '0522490', 'JORGE BASADRE', 'Secundaria', 'Pública de gestión directa', '210501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2774, '0240218', 'JOSE CARLOS MARIATEGUI', 'Secundaria', 'Pública de gestión directa', '210501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2775, '1154988', 'ESPAÑA', 'Secundaria de Adultos', 'Privada', '210501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2776, '1576115', 'CARLOS DANTE NAVA', 'Secundaria', 'Pública de gestión directa', '210501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2777, '1576180', 'JOSE ANTONIO ENCINAS', 'Secundaria', 'Privada', '210501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2778, '1576156', 'CHRISTIAN BARNARD', 'Secundaria', 'Privada', '210501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2779, '0660431', 'YACANGO', 'Secundaria', 'Pública de gestión directa', '210501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2780, '0755199', 'ULLACACHI', 'Secundaria', 'Pública de gestión directa', '210501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2781, '1372895', 'SAN MIGUEL', 'Secundaria', 'Privada', '210501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2782, '1524545', 'PITAGORAS', 'Secundaria', 'Privada', '210501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2783, '1540228', 'JUAN VELASCO ALVARADO', 'Secundaria', 'Pública de gestión directa', '210501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2784, '1633809', 'SIMON BOLIVAR', 'Secundaria', 'Privada', '210501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2785, '1653476', 'TIUTIRI ANTAMARCA', 'Secundaria', 'Pública de gestión directa', '210501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2786, '1725878', 'SEÑOR DE LOS MILAGROS', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '210501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2787, '1732148', 'PALMER', 'Secundaria', 'Privada', '210501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2788, '1761857', 'PERU BIRF', 'Secundaria', 'Pública de gestión directa', '210501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2789, '1768050', 'GALENO', 'Secundaria', 'Privada', '210501', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2790, '0701573', 'SAN JOSE DE ANCOMARCA', 'Secundaria', 'Pública de gestión directa', '210502', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2791, '1027671', 'CAPASO', 'Secundaria', 'Pública de gestión directa', '210502', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2792, '0579037', 'TUPALA', 'Secundaria', 'Pública de gestión directa', '210502', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2793, '0578997', 'MARISCAL RAMON CASTILLA', 'Secundaria', 'Pública de gestión directa', '210503', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2794, '0579003', 'MANUEL GONZALES PRADA', 'Secundaria', 'Pública de gestión directa', '210503', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2795, '0701532', 'MICAELA BASTIDAS', 'Secundaria', 'Pública de gestión directa', '210503', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2796, '0522391', 'JOSE OLAYA', 'Secundaria', 'Pública de gestión directa', '210503', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2797, '0660423', 'ACCASO', 'Secundaria', 'Pública de gestión directa', '210503', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2798, '0474593', 'CESAR VALLEJO', 'Secundaria', 'Pública de gestión directa', '210503', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2799, '0573196', 'MIGUEL GRAU', 'Secundaria', 'Pública de gestión directa', '210503', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2800, '0500439', 'JOSE MARIA ARGUEDAS', 'Secundaria', 'Pública de gestión directa', '210504', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2801, '0533810', 'SANTA ROSA', 'Secundaria', 'Pública de gestión directa', '210504', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2802, '1633791', 'PROVIDENCIA', 'Secundaria', 'Pública de gestión directa', '210504', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2803, '0579045', 'TUPAC AMARU II', 'Secundaria', 'Pública de gestión directa', '210505', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2804, '1025865', 'LOS ANDES', 'Secundaria', 'Privada', '210601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2805, '1029941', 'MIGUEL GRAU', 'Secundaria', 'Pública de gestión directa', '210601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2806, '1025667', 'CUYURAYA', 'Secundaria', 'Pública de gestión directa', '210601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2807, '1025659', 'ALFONSO UGARTE', 'Secundaria', 'Pública de gestión directa', '210601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2808, '0754697', 'FRANCISCO BOLOGNESI YANAOCO', 'Secundaria', 'Pública de gestión directa', '210601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2809, '0701276', 'MILLIRAYA', 'Secundaria', 'Pública de gestión directa', '210601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2810, '0618009', 'JORGE BASADRE', 'Secundaria', 'Pública de gestión directa', '210601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2811, '0578641', 'LIBERTADOR JOSE DE SAN MARTIN', 'Secundaria', 'Pública de gestión directa', '210601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2812, '0578633', 'TUPAC AMARU II', 'Secundaria', 'Pública de gestión directa', '210601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2813, '0526129', 'JOSE CARLOS MARIATEGUI', 'Secundaria', 'Pública de gestión directa', '210601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2814, '0478081', 'CESAR VALLEJO', 'Secundaria', 'Pública de gestión directa', '210601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2815, '0478073', 'VARONES', 'Secundaria', 'Pública de gestión directa', '210601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2816, '0240580', 'HERIBERTO LUZA BRETEL', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '210601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2817, '0701268', 'ACOCOLLO', 'Secundaria', 'Pública de gestión directa', '210601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2818, '0755058', 'AGROPECUARIO', 'Secundaria', 'Pública de gestión directa', '210601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2819, '0616722', 'ALFONSO UGARTE', 'Secundaria', 'Privada', '210601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2820, '0617993', 'KAKACHI', 'Secundaria', 'Privada', '210601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2821, '0754663', 'JULIACA', 'Secundaria', 'Pública de gestión directa', '210601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2822, '0239673', 'HERIBERTO LUZA BRETEL', 'Secundaria de Adultos', 'Pública de gestión directa', '210601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2823, '1518059', 'SAN JUAN DE LA SALLE', 'Secundaria', 'Privada', '210601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2824, '1635069', 'APLICACION IESPPH', 'Secundaria', 'Pública de gestión directa', '210601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2825, '1724889', 'HERIBERTO LUZA BRETEL', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '210601', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2826, '0522110', 'COJATA', 'Secundaria', 'Pública de gestión directa', '210602', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2827, '1026103', 'SAN JUAN', 'Secundaria', 'Pública de gestión directa', '210602', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2828, '1722685', 'KANTATI URURI', 'Secundaria', 'Pública de gestión directa', '210602', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2829, '0485854', 'HUATASANI', 'Secundaria', 'Pública de gestión directa', '210603', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2830, '0754960', 'MUNAYPA', 'Secundaria', 'Pública de gestión directa', '210604', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2831, '0547786', 'INCHUPALLA', 'Secundaria', 'Pública de gestión directa', '210604', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2832, '0754630', 'TANANSAYA', 'Secundaria', 'Pública de gestión directa', '210604', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2833, '0547380', 'PUSI', 'Secundaria', 'Pública de gestión directa', '210605', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2834, '1154418', 'MUNI', 'Secundaria', 'Pública de gestión directa', '210605', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2835, '0754846', 'JOSE ANTONIO ENCINAS', 'Secundaria', 'Pública de gestión directa', '210606', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2836, '0754812', 'CAHUAYA', 'Secundaria', 'Pública de gestión directa', '210606', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2837, '0754903', 'QUELLO QUELLO', 'Secundaria', 'Pública de gestión directa', '210606', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2838, '0754879', 'TICANI CARIQUITA', 'Secundaria', 'Pública de gestión directa', '210606', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2839, '0502500', 'ROSASPATA', 'Secundaria', 'Pública de gestión directa', '210606', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2840, '1770833', 'HALLA', 'Secundaria', 'Pública de gestión directa', '210606', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2841, '0727172', 'HUANCOLLUSCO', 'Secundaria', 'Pública de gestión directa', '210607', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2842, '0239558', 'TARACO', 'Secundaria', 'Pública de gestión directa', '210607', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2843, '0634345', 'LEONCIO PRADO', 'Secundaria', 'Pública de gestión directa', '210607', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2844, '0634311', 'SACASCO', 'Secundaria', 'Pública de gestión directa', '210607', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2845, '0650143', 'EDUARDO PAREDES OCHOA', 'Secundaria', 'Pública de gestión directa', '210607', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2846, '1746379', 'SAN FRANCISCO DE BORJA', 'Secundaria', 'Pública de gestión directa', '210607', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2847, '0754754', 'HUIJIPATA', 'Secundaria', 'Pública de gestión directa', '210608', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2848, '0754721', 'ARTESANAL CULACHATA', 'Secundaria', 'Pública de gestión directa', '210608', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2849, '0618124', 'VICTOR ANDRES BELAUNDE', 'Secundaria', 'Pública de gestión directa', '210608', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2850, '0618116', 'NUEVA INDEPENDENCIA', 'Secundaria', 'Pública de gestión directa', '210608', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2851, '0578682', 'SICTA', 'Secundaria', 'Pública de gestión directa', '210608', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2852, '0618140', 'TIQUITIQUI', 'Secundaria', 'Pública de gestión directa', '210608', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2853, '0239780', '92', 'Secundaria', 'Pública de gestión directa', '210608', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2854, '0754788', 'YAPUTIRA', 'Secundaria', 'Pública de gestión directa', '210608', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2855, '0755116', 'SOLITARIO', 'Secundaria', 'Pública de gestión directa', '210608', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2856, '1025998', 'LA LIBERTAD', 'Secundaria', 'Privada', '210608', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2857, '1025980', 'SOMBRERONI', 'Secundaria', 'Privada', '210608', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2858, '0500710', 'TUPAC AMARU', 'Secundaria', 'Pública de gestión directa', '210701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2859, '1028133', 'POLITECNICO NACIONAL', 'Secundaria', 'Pública de gestión directa', '210701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2860, '0239525', 'JUAN BUSTAMANTE', 'Secundaria', 'Pública de gestión directa', '210701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2861, '1028125', 'JOSE CARLOS MARIATEGUI', 'Secundaria', 'Pública de gestión directa', '210701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2862, '0746248', 'DONATO PILCO PIZANO', 'Secundaria', 'Pública de gestión directa', '210701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2863, '0240499', '71010', 'Básica Alternativa', 'Pública de gestión directa', '210701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2864, '0500819', 'ENRIQUE TORRES BELON', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '210701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2865, '0537035', 'TUPAC AMARU', 'Secundaria de Adultos', 'Privada', '210701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2866, '1724897', 'ENRIQUE TORRES BELON', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '210701', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2867, '0616854', 'INAI CABANILLA', 'Secundaria', 'Pública de gestión directa', '210702', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2868, '0578724', 'CALAPUJA', 'Secundaria', 'Pública de gestión directa', '210703', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2869, '0474973', 'NICASIO', 'Secundaria', 'Pública de gestión directa', '210704', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2870, '0578732', 'LARO', 'Secundaria', 'Pública de gestión directa', '210704', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2871, '1030030', 'CARACARA', 'Secundaria', 'Pública de gestión directa', '210704', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2872, '0578740', 'OCUVIRI', 'Secundaria', 'Pública de gestión directa', '210705', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2873, '3002854', 'ANTONIO TICONA MAMANI', 'Secundaria', 'Pública de gestión directa', '210705', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2874, '0578625', 'HORACIO ZEVALLOS GAMEZ', 'Secundaria', 'Pública de gestión directa', '210706', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2875, '0805424', 'MANCO CAPAC', 'Secundaria', 'Pública de gestión directa', '210707', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2876, '1028166', 'CHILAHUITO', 'Secundaria', 'Pública de gestión directa', '210707', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2877, '1698497', 'EMILIO CAYLLAHUA', 'Secundaria', 'Privada', '210707', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2878, '3043601', 'LEGADOS DEL AYARACHI', 'Secundaria', 'Pública de gestión directa', '210707', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2879, '0751610', 'QQUEPA', 'Secundaria', 'Pública de gestión directa', '210708', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2880, '0239012', 'PUCARA', 'Secundaria', 'Pública de gestión directa', '210708', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2881, '0746255', 'MANUEL MORO SSOMO', 'Secundaria', 'Pública de gestión directa', '210709', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2882, '1028158', 'INDUSTRIAL SANTA LUCIA', 'Secundaria', 'Pública de gestión directa', '210709', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2883, '0548081', 'JOSE CARLOS MARIATEGUI', 'Secundaria', 'Pública de gestión directa', '210709', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2884, '0240481', '71009', 'Básica Alternativa', 'Pública de gestión directa', '210709', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2885, '0616888', 'ELEUTERIO TICONA JORDAN', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '210709', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2886, '1727205', 'ELEUTERIO TICONA JORDAN', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '210709', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2887, '3023454', 'ELEUTERIO TICONA JORDAN', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '210709', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2888, '0805416', 'PECUARIO ARTESANAL', 'Secundaria', 'Pública de gestión directa', '210710', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2889, '0239657', 'MARIANO MELGAR', 'Secundaria de Adultos', 'Pública de gestión directa', '210801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2890, '0239707', '72', 'Secundaria', 'Pública de gestión directa', '210801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2891, '0240507', 'AYAVIRI', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '210801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2892, '0478032', 'MARIANO MELGAR', 'Secundaria', 'Pública de gestión directa', '210801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2893, '0478040', 'NUESTRA SEÑORA DE ALTA GRACIA', 'Secundaria', 'Pública de gestión directa', '210801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2894, '0751636', 'NUESTRA SEÑORA DE ALTA GRACIA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '210801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2895, '0809848', 'PEDRO KALBERMATER', 'Secundaria', 'Privada', '210801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2896, '0809855', 'MANCO CAPAC', 'Secundaria de Adultos', 'Pública de gestión directa', '210801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2897, '1028349', 'ROQUE SAENZ PEÑA', 'Secundaria', 'Pública de gestión directa', '210801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2898, '1028422', 'CORAZON DE JESUS', 'Secundaria', 'Privada', '210801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2899, '1259522', 'CESAR VALLEJO', 'Secundaria', 'Privada', '210801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2900, '1029701', 'ANDRES BELLO', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '210801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2901, '1580075', 'ROSENDO HUIRSE', 'Secundaria', 'Privada', '210801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2902, '1580141', 'CORAZON DE JESUS', 'Secundaria', 'Privada', '210801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2903, '1360817', 'MARIANO MELGAR', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '210801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2904, '1360825', 'MANCO CAPAC', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '210801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2905, '1413962', 'SAN FRANCISCO DE ASIS', 'Secundaria', 'Pública de gestión privada', '210801', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2906, '0581470', 'ANTAUTA', 'Secundaria', 'Pública de gestión directa', '210802', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2907, '1024025', 'AGROPECUARIO LARIMAYO', 'Secundaria', 'Pública de gestión directa', '210802', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2908, '0612317', 'SAN RAFAEL', 'Secundaria', 'Privada', '210802', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2909, '1361534', 'SAN ISIDRO', 'Secundaria', 'Pública de gestión directa', '210802', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2910, '1540236', 'SAN JUAN', 'Secundaria', 'Pública de gestión directa', '210802', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2911, '0716720', 'AGROPECUARIO', 'Secundaria', 'Pública de gestión directa', '210803', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2912, '1722024', 'PEDRO VILCAPAZA', 'Secundaria', 'Pública de gestión directa', '210803', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2913, '0581454', 'MIGUEL GRAU', 'Secundaria', 'Pública de gestión directa', '210804', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2914, '1260603', 'JOSE MARIA ARGUEDAS', 'Secundaria', 'Pública de gestión directa', '210804', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2915, '0239905', '27 SANTA LUCIA FE Y ALEGRIA', 'Secundaria', 'Pública de gestión privada', '210805', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2916, '0581447', 'AGROPECUARIO HUAMANRURO', 'Secundaria', 'Pública de gestión directa', '210805', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2917, '0612432', 'AGROPECUARIO QUISHUARA', 'Secundaria', 'Pública de gestión directa', '210805', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2918, '0716860', '151', 'Secundaria', 'Pública de gestión directa', '210805', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2919, '1580042', 'AGROPECUARIO SANTA CRUZ', 'Secundaria', 'Pública de gestión directa', '210805', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2920, '1718725', '72754', 'Secundaria', 'Pública de gestión directa', '210805', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2921, '0239517', 'TUPAC AMARU', 'Secundaria', 'Pública de gestión directa', '210806', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2922, '0809731', 'NUÑOA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '210806', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2923, '1025055', 'TECNICO INDUSTRIAL NUÑOA', 'Secundaria', 'Pública de gestión directa', '210806', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2924, '1580646', 'PASANACOLLO', 'Secundaria', 'Pública de gestión directa', '210806', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2925, '1580182', 'DOMINGO SAVIO', 'Secundaria', 'Pública de gestión directa', '210806', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2926, '3044179', 'NUÑOA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '210806', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2927, '0548594', 'JOSE CARLOS MARIATEGUI', 'Secundaria', 'Pública de gestión directa', '210807', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2928, '0751628', 'ACLLAMAYO', 'Secundaria', 'Pública de gestión directa', '210807', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2929, '0809780', 'JOSE ANTONIO ENCINAS', 'Secundaria', 'Pública de gestión directa', '210807', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2930, '1154616', 'JORGE BASADRE', 'Secundaria', 'Pública de gestión directa', '210807', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2931, '0751644', 'ORURILLO', 'Secundaria de Adultos', 'Pública de gestión directa', '210807', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2932, '1580174', 'LUIS DALLE PERIER', 'Secundaria', 'Pública de gestión directa', '210807', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2933, '1580091', 'VILLA DE ORURILLO', 'Secundaria', 'Pública de gestión directa', '210807', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2934, '1329606', 'JOSE MARIA ARGUEDAS', 'Secundaria', 'Pública de gestión directa', '210807', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2935, '0239749', '108', 'Secundaria', 'Pública de gestión directa', '210808', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2936, '0530972', '73004 ROSENDO HUIRSE', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '210808', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2937, '0548891', '73004 ROSENDO HUIRSE', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '210808', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2938, '0809723', '110', 'Secundaria', 'Pública de gestión directa', '210808', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2939, '1024843', 'LA SALLE', 'Secundaria', 'Pública de gestión directa', '210808', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2940, '1028513', 'JUSTO JUEZ', 'Secundaria', 'Pública de gestión directa', '210808', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2941, '1153667', 'CESAR VALLEJO MENDOZA', 'Secundaria', 'Pública de gestión directa', '210808', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2942, '0522805', 'UMACHIRI', 'Secundaria', 'Pública de gestión directa', '210809', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2943, '0228056', 'JACANTAYA', 'Secundaria', 'Pública de gestión directa', '210901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2944, '0239533', 'JOSE ABELARDO QUIÑONES GONZALES', 'Secundaria', 'Pública de gestión directa', '210901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2945, '0240598', 'JOSE ABELARDO QUIÑONEZ', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '210901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2946, '0522011', 'UMUCHI', 'Secundaria', 'Pública de gestión directa', '210901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2947, '0578666', 'SAN JUAN BAUTISTA DE LA SALLE', 'Secundaria', 'Pública de gestión directa', '210901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2948, '0578674', 'AGROINDUSTRIAL POMAOCA', 'Secundaria', 'Pública de gestión directa', '210901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2949, '0618041', 'JOSE ABELARDO QUIÑONES GONZALES', 'Secundaria de Adultos', 'Pública de gestión directa', '210901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2950, '0618082', 'JACHA PARU', 'Secundaria', 'Pública de gestión directa', '210901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2951, '0635359', 'HUARAYA', 'Secundaria', 'Pública de gestión directa', '210901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2952, '1026186', 'TECNICO COMERCIAL', 'Secundaria', 'Pública de gestión directa', '210901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2953, '1026194', 'JIPATA JACHA JAA', 'Secundaria', 'Pública de gestión directa', '210901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2954, '1188952', 'NINANTAYA', 'Secundaria', 'Pública de gestión directa', '210901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2955, '1324995', 'MALLCUSUCA CENTRAL', 'Secundaria', 'Pública de gestión directa', '210901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2956, '1358316', 'LLAULLI', 'Secundaria', 'Pública de gestión directa', '210901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2957, '1724905', 'JOSE ABELARDO QUIÑONEZ', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '210901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2958, '1759166', 'JOSE CARLOS MARIATEGUI', 'Secundaria', 'Pública de gestión directa', '210901', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2959, '0239764', 'AGRO INDUSTRIAL 128', 'Secundaria', 'Pública de gestión directa', '210902', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2960, '1026228', 'INDUSTRIAL', 'Secundaria', 'Pública de gestión directa', '210902', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2961, '1026236', 'CONIMA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '210902', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2962, '0578658', 'LOS HEROES DEL CENEPA', 'Secundaria', 'Pública de gestión directa', '210903', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2963, '0618066', 'FRANCISCO BOLOGNESI', 'Secundaria', 'Pública de gestión directa', '210903', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2964, '1325000', 'HUALLATIRI', 'Secundaria', 'Pública de gestión directa', '210903', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2965, '1570951', 'ALTOS HUAYRAPATA', 'Secundaria', 'Pública de gestión directa', '210903', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2966, '1785948', 'SAN FRANCISO DE BORJA', 'Secundaria', 'Pública de gestión directa', '210903', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2967, '0475012', 'TILALI', 'Secundaria', 'Pública de gestión directa', '210904', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2968, '1026244', 'INDUSTRIAL TILALI', 'Secundaria', 'Privada', '210904', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2969, '0547893', '73005', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '211001', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2970, '0239483', 'SAN ANTONIO DE PADUA', 'Secundaria', 'Pública de gestión directa', '211001', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2971, '0584557', 'TECNICO AGROPECUARIO', 'Secundaria', 'Pública de gestión directa', '211001', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2972, '1026301', 'EL CENTENARIO', 'Secundaria', 'Pública de gestión directa', '211001', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2973, '1026293', 'AGROINDUSTRIAL', 'Secundaria', 'Pública de gestión directa', '211001', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2974, '0496489', '73005', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '211001', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2975, '1260009', 'JESUS DE NAZARET', 'Secundaria', 'Privada', '211001', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2976, '1324979', 'PEÑON NEGRO', 'Secundaria', 'Pública de gestión directa', '211001', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2977, '1324987', 'TARUCANI', 'Secundaria', 'Pública de gestión directa', '211001', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2978, '1377746', 'PICOTANI', 'Secundaria', 'Pública de gestión directa', '211001', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2979, '1703818', 'SAN MARTIN', 'Secundaria', 'Pública de gestión directa', '211001', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2980, '0581561', 'TECNICO INDUSTRIAL DE ANANEA', 'Secundaria', 'Pública de gestión directa', '211002', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `origin_schools` (`id`, `modular_code`, `name`, `d_niv_mod`, `management_type`, `ubigeo_code`, `created_at`, `updated_at`) VALUES
(2981, '1153501', 'SAGRADO CORAZON DE JESUS', 'Secundaria', 'Privada', '211002', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2982, '1030147', 'SAN FRANCISCO', 'Secundaria', 'Pública de gestión directa', '211002', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2983, '1450238', 'TRAPICHE', 'Secundaria', 'Pública de gestión directa', '211002', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2984, '1471028', 'ALBERT EINSTEIN', 'Secundaria', 'Privada', '211002', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2985, '1694256', 'LUZ ANDINO', 'Secundaria', 'Privada', '211002', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2986, '1749712', 'RAUL CASTILLO GAMARRA', 'Secundaria', 'Pública de gestión directa', '211002', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2987, '3050564', 'LOS ANGELES', 'Secundaria', 'Privada', '211002', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2988, '1026368', 'AGROINDUSTRIAL MAXIMO SAN ROMAN CACERES DE AJJATIRA', 'Secundaria', 'Pública de gestión directa', '211003', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2989, '0522904', 'GRAL. PEDRO VILCAPAZA ALARCON', 'Secundaria', 'Pública de gestión directa', '211003', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2990, '0618017', 'INDUSTRIAL DE QUILCAPUNCO', 'Secundaria', 'Pública de gestión directa', '211004', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2991, '1026350', 'JANANSAYA', 'Secundaria', 'Pública de gestión directa', '211004', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2992, '1026400', 'JAIME URIEL GIRONDA VARGAS', 'Secundaria', 'Pública de gestión directa', '211005', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2993, '1718527', '72482', 'Secundaria', 'Pública de gestión directa', '211005', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2994, '0478065', 'LAS MERCEDES', 'Secundaria', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2995, '0578591', 'MARIANO MELGAR', 'Secundaria', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2996, '1027184', 'INCA GARCILAZO DE LA VEGA', 'Secundaria', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2997, '0633941', 'TUPAC AMARU', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2998, '0239582', 'FRANCISCANO SAN ROMAN', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2999, '0239608', 'ELENA DE SANTA MARIA', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3000, '0239616', 'ADVENTISTA DEL TITICACA', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3001, '1026855', 'DANIELLE MITTERRAND', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3002, '1025618', 'NUESTRA SEÑORA DE LOURDES', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3003, '0239863', 'POLITECNICO REGIONAL LOS ANDES', 'Secundaria', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3004, '0746131', 'SAN FRANSISCO DE BORJA', 'Secundaria', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3005, '0746115', 'CESAR VALLEJO', 'Secundaria', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3006, '0727008', 'JOSE CARLOS MARIATEGUI', 'Secundaria', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3007, '0239699', '91 JOSE IGNACIO MIRANDA', 'Secundaria', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3008, '0239848', 'JOSE MARIA ARGUEDAS', 'Secundaria', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3009, '0239806', '32 MARIANO H. CORNEJO', 'Secundaria', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3010, '0804948', '70536', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3011, '0726992', '70550', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3012, '0578617', 'SANTA CATALINA', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3013, '0645655', 'LUZ ANDINA REYNA DE LAS AMERICAS', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3014, '1027168', 'PEDRO KALBERMATTER', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3015, '1026640', 'NUESTRA SEÑORA DEL CARMEN', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3016, '1026665', 'INTERNACIONAL DIVINO MAESTRO', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3017, '1029651', 'FERNANDO STAHL', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3018, '1026509', 'ANTONIO RAYMONDY', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3019, '1030097', 'MARISCAL ANDRES AVELINO CACERES', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3020, '0239665', 'JOSE ANTONIO ENCINAS', 'Secundaria', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3021, '1027226', 'SAN MARTIN', 'Secundaria', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3022, '1026756', 'BELEN', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3023, '1025360', 'SAGRADO CORAZON DE JESUS', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3024, '0746164', 'LOS ANDES', 'Secundaria de Adultos', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3025, '0239939', '32 MARIANO H. CORNEJO', 'Secundaria de Adultos', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3026, '0727024', '70547 MANCO CAPAC', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3027, '1027341', 'VICENTE MENDOZA DIAZ', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3028, '0490938', 'JOSE ANTONIO ENCINAS', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3029, '0530873', '71014 MANUEL NUÑEZ BUTRON', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3030, '0240549', '71015 SAN JUAN BOSCO', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3031, '1153709', 'ALFRED NOBEL', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3032, '0727016', 'LAS MERCEDES', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3033, '1027150', '70546', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3034, '0240531', '71014 MANUEL NUÑEZ BUTRON', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3035, '0804963', '70547 MANCO CAPAC', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3036, '0727032', 'AMERICANA', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3037, '1027218', 'ASOCIACION EDUCATIVA ADVENTISTA CRISTO REY', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3038, '1026582', 'MIGUEL GRAU', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3039, '1026624', 'JESUS DE LOS ANDES', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3040, '1026731', 'JOSE CARLOS MARIATEGUI', 'Secundaria de Adultos', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3041, '1027267', 'MARCELINO CHAMPAGNAT', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3042, '1029859', 'JULIACA', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3043, '1154053', 'ENRIQUE GUZMAN Y VALLE', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3044, '1154145', 'TRINOMIO', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3045, '1153782', 'MARIA JESUS', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3046, '1154095', 'SAN AGUSTIN DE HIPONA', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3047, '1154467', 'ADAM SMITH', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3048, '1154772', 'APLICACION UANCV', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3049, '1154509', 'EL PACIFICO', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3050, '0746099', '71018 CRAS', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3051, '0746123', 'AGROP. C.SATELITE', 'Secundaria', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3052, '1028729', 'SAN ROMAN', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3053, '1259647', 'LA SALLE', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3054, '1259969', 'LUZ Y CIENCIA', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3055, '1155464', 'SANTA ROSA DE LIMA', 'Secundaria', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3056, '1260280', 'ZIGMA E.I.R.L.', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3057, '1027481', 'SANTA ADRIANA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3058, '1581370', 'ANDRES BELLO', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3059, '1581404', 'BUEN PASTOR', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3060, '1261445', 'WILLIAM PRESCOTT', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3061, '1260520', 'GANIMEDES', 'Secundaria de Adultos', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3062, '1027507', 'SAN ROMAN', 'Básica Alternativa-Avanzado', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3063, '0216416', '71016 MARIA AUXILIADORA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3064, '1581438', 'COLIBRI', 'Secundaria', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3065, '1581495', 'JAMES BALDWIN', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3066, '1581529', 'CLAUDIO GALENO', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3067, '1581560', 'CIENCIAS DE AMERICA', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3068, '1581644', 'EDEN', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3069, '1581545', 'NUEVO HORIZONTE', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3070, '1581743', 'BRIGHAM YOUNG', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3071, '1581792', 'AUGUSTO SALAZAR BONDY', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3072, '1581859', 'PONTIFICIA CATOLICA SANTA MARIA', 'Básica Alternativa-Avanzado', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3073, '1581768', 'BRIGHAM YOUNG', 'Secundaria de Adultos', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3074, '1581917', 'SAN PABLO', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3075, '1581966', 'EUROAMERICANO', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3076, '1581974', 'EMANUEL', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3077, '1581990', 'ALFREDO BRYCE ECHENIQUE', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3078, '1582014', 'ALFREDO BRYCE ECHENIQUE', 'Secundaria de Adultos', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3079, '1582055', 'MARISCAL ANDRES AVELINO CACERES', 'Secundaria de Adultos', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3080, '1582097', 'BUENA SEMILLA', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3081, '1582121', 'ELIM', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3082, '1582238', 'PONTIFICIA CATOLICA SANTA MARIA', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3083, '1582279', 'PRESCOTT', 'Secundaria de Adultos', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3084, '1280932', 'FEDERICO MORE', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3085, '1320928', 'DAVID AUSUBEL', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3086, '1330133', 'SAN VICENTE DE PAUL', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3087, '1342419', 'ESCUELA MUNDIAL', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3088, '1349497', 'BERTOLT BRECHT', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3089, '1352327', 'GREGOR MENDEL', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3090, '1355775', 'ALEXANDER FLEMING', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3091, '1360783', 'POLITECNICO LOS ANDES', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3092, '1360791', '32 MARIANO H. CORNEJO', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3093, '1362599', 'MARTIN LUTERO', 'Secundaria', 'Pública de gestión privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3094, '1373661', 'DON BOSCO SALESIANOS', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3095, '1399518', 'PERUANO BRITANICO FRANCIS ASTON', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3096, '1633346', 'GANIMEDES', 'Básica Alternativa-Avanzado', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3097, '1639574', 'SAN JOSE LA ESPERANZA', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3098, '1645183', 'THOMAS ALVA EDISON', 'Secundaria', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3099, '1645191', 'SANTA MONICA', 'Secundaria', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3100, '1657246', 'RODOLFO DIESEL', 'Secundaria', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3101, '1661495', 'SACO OLIVEROS DE JULIACA', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3102, '1663251', 'JOSE OLAYA BALANDRA', 'Secundaria', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3103, '1663269', '20 DE ENERO', 'Secundaria', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3104, '1672732', 'LIDER', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3105, '1722032', 'TRINOMIO - LA DINASTIA', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3106, '1724913', '70550 VILLA HERMOZA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3107, '1727320', 'SAN IGNACIO DE RECALDE', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3108, '1729441', 'LAS MERCEDES', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3109, '1752450', 'GIORDANO LIVA', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3110, '1756071', 'INTERNATIONAL PERUVIAN SCHOOL', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3111, '1756519', 'RANGER\'S', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3112, '1756527', 'SCHOOL INTERNATIONAL INNOVA', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3113, '1758176', 'PEDRO PAULET', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3114, '1760180', 'WILLY\'S HOME CENTER', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3115, '1762921', 'RICARDO PALMA', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3116, '1767607', 'INTERNACIONAL NIVEL A', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3117, '1769306', 'REAL AMERICAN SCHOOL', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3118, '1779966', 'FE Y CIENCIA', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3119, '1785542', 'DANIEL ALCIDES CARRION', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3120, '1787845', 'BETHEL CHRISTIAN SCHOOL', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3121, '1789585', 'INNOVA SCHOOLS - JULIACA', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3122, '3000593', 'CORONEL BOLOGNESI', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3123, '1795202', 'PERUANO ESPAÑOL', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3124, '3009396', 'LAS AMERICAS', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3125, '1798156', 'GANIMEDES', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3126, '1798214', 'MONTESSORI SCHOOL', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3127, '1798420', 'SAN IGNACIO DE RECALDE', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3128, '1798438', 'SAN IGNACIO DE RECALDE', 'Básica Alternativa-Avanzado', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3129, '3016755', 'PAYEX', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3130, '3017373', 'JUAN RODOLFO SCHOOL', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3131, '1799030', 'PITAGORAS', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3132, '3002847', 'SAN GINES DE ARLES', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3133, '3020658', 'INGENIERITOS DEL PERU EDUARDO DE HABICH', 'Secundaria', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3134, '3023835', 'AMERICAN NOBEL', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3135, '3026036', 'EXCELENCIA ACADEMICA', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3136, '3026440', 'ALBORADA', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3137, '3026879', 'TALENTOS LIBER', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3138, '3038700', 'CORONEL ALFONSO UGARTE', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3139, '3038726', 'BENJAMIN CARSON', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3140, '3039294', 'NEWTON SCHOOL', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3141, '3040011', 'PAMER\'S', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3142, '3040185', 'THOMAS CRANMER', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3143, '3043494', 'NUESTRA SEÑORA DE MONSERRAT', 'Secundaria', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3144, '3043759', 'JOSE PORTUGAL CATACORA', 'Secundaria', 'Pública de gestión directa', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3145, '3046133', 'SISTEMAS SCHOOL', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3146, '3047487', 'LOS IMPARABLES FG', 'Secundaria', 'Privada', '211101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3147, '0746149', 'COLLANA', 'Secundaria', 'Pública de gestión directa', '211102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3148, '0578757', 'CABANA', 'Secundaria', 'Pública de gestión directa', '211102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3149, '1027036', 'TINCOPALCA', 'Secundaria', 'Pública de gestión directa', '211103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3150, '0616821', 'HUATAQUITA', 'Secundaria', 'Pública de gestión directa', '211103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3151, '0239566', 'CABANILLAS', 'Secundaria', 'Pública de gestión directa', '211103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3152, '0578609', 'SUCHIS', 'Secundaria', 'Pública de gestión directa', '211104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3153, '0746156', 'DANIEL ALCIDES CARRION', 'Secundaria', 'Pública de gestión directa', '211104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3154, '0578583', 'DOS DE MAYO', 'Secundaria', 'Pública de gestión directa', '211104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3155, '3018223', 'AMERICA\'S', 'Secundaria', 'Privada', '211104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3156, '3025939', 'JORGE RIVERA DEL MAR', 'Secundaria', 'Pública de gestión directa', '211104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3157, '0746107', 'PEDRO VILCAPAZA', 'Secundaria', 'Pública de gestión directa', '211105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3158, '0535252', 'PERU BIRF', 'Secundaria', 'Pública de gestión directa', '211105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3159, '1027200', 'SIMON BOLIVAR', 'Secundaria', 'Pública de gestión directa', '211105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3160, '1027325', 'PERU BIRF', 'Secundaria de Adultos', 'Pública de gestión directa', '211105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3161, '1155100', 'REAL CONVICTORIO', 'Secundaria', 'Privada', '211105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3162, '1260645', 'SIMON BOLIVAR', 'Secundaria de Adultos', 'Pública de gestión directa', '211105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3163, '1259720', 'SAN JOSE JULIACA', 'Secundaria', 'Privada', '211105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3164, '1581453', 'JOHN VENN EULER', 'Secundaria', 'Privada', '211105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3165, '1582063', 'HORACIO ZEVALLOS GAMEZ', 'Secundaria', 'Pública de gestión directa', '211105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3166, '1582311', 'NUEVO PERU', 'Secundaria', 'Privada', '211105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3167, '1360775', 'PERU BIRF', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '211105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3168, '1360809', 'SIMON BOLIVAR', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '211105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3169, '1630177', 'VIVA ESPERANZA', 'Secundaria', 'Privada', '211105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3170, '1663244', 'SAN ISIDRO DE CCACCACHI', 'Secundaria', 'Pública de gestión directa', '211105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3171, '1666148', 'CRISTO BLANCO', 'Secundaria', 'Privada', '211105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3172, '1672716', 'DANIEL GOLEMAN', 'Secundaria', 'Privada', '211105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3173, '1696061', 'SAN MIGUEL DE JULIACA', 'Secundaria', 'Privada', '211105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3174, '1727338', 'GALILEO GALILEI', 'Secundaria', 'Privada', '211105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3175, '1752443', 'ENSIL DE LAS AMERICAS JM', 'Secundaria', 'Privada', '211105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3176, '1774249', 'ALBERT EINSTEIN', 'Secundaria', 'Privada', '211105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3177, '1788421', 'EL ESCRITOR MIGUEL DE CERVANTES SAAVEDRA', 'Secundaria', 'Privada', '211105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3178, '3017167', 'CARLOS CONDORENA YUJRA', 'Secundaria', 'Pública de gestión directa', '211105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3179, '3017464', 'PENTAGONO INTERNACIONAL SCHOOL', 'Secundaria', 'Privada', '211105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3180, '3018637', 'JULIO VERNE SCHOOL', 'Secundaria', 'Privada', '211105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3181, '3025210', 'MARCO ANTONIO SAMILLAN SANGA', 'Secundaria', 'Pública de gestión directa', '211105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3182, '3025624', 'WALTER PAZ QUISPE SANTOS', 'Secundaria', 'Pública de gestión directa', '211105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3183, '3042397', 'CLORINDA MATTO DE TURNER', 'Secundaria', 'Pública de gestión directa', '211105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3184, '3043817', 'BOLIVARIANO DE LAS AMERICAS', 'Secundaria', 'Pública de gestión directa', '211105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3185, '0755587', 'JOSE CARLOS MARIATEGUI', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '211201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3186, '1028802', 'AGROPECUARIO MARIANO MELGAR SIMBA', 'Secundaria', 'Pública de gestión directa', '211201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3187, '0239541', 'JOSE CARLOS MARIATEGUI', 'Secundaria', 'Pública de gestión directa', '211201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3188, '1028794', 'AGROPECUARIO JOSE MARIA EGUREN', 'Secundaria', 'Pública de gestión directa', '211201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3189, '1153543', 'TECNICO AGROPECUARIO HUANCALUQUE', 'Secundaria', 'Pública de gestión directa', '211201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3190, '0755579', 'ADVENTISTA JOHN NEVINS ANDREWS DE SANDIA', 'Secundaria', 'Privada', '211201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3191, '1261049', 'AGROPECUARIO JOSE MARIA ARGUEDAS ALTAMIRANO', 'Secundaria', 'Pública de gestión directa', '211201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3192, '1583525', 'INDEPENDENCIA', 'Secundaria', 'Pública de gestión directa', '211201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3193, '1331875', 'JOSE CARLOS MARIATEGUI', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '211201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3194, '1028943', 'ALEXANDER FLEMING', 'Secundaria', 'Pública de gestión directa', '211202', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3195, '0547984', 'CARLOS OQUENDO DE AMAT', 'Secundaria', 'Pública de gestión directa', '211202', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3196, '1028174', 'CUYOCUYO', 'Secundaria de Adultos', 'Privada', '211202', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3197, '1259365', 'URA AYLLU', 'Secundaria', 'Pública de gestión directa', '211202', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3198, '1260041', 'ORIENTAL', 'Secundaria', 'Pública de gestión directa', '211202', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3199, '1457829', 'LUZ DE LOS ANDES', 'Secundaria', 'Pública de gestión directa', '211202', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3200, '0578765', 'SAN LUIS GONZAGA', 'Secundaria', 'Pública de gestión directa', '211203', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3201, '1154020', 'JOSE ANTONIO ENCINAS', 'Secundaria', 'Pública de gestión directa', '211203', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3202, '1781046', '72753', 'Secundaria', 'Pública de gestión directa', '211203', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3203, '1782788', '72445 GLORIOSO 835 SAN JUAN BOSCO', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '211203', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3204, '0633404', 'CENTENARIO PATAMBUCO', 'Secundaria', 'Pública de gestión directa', '211204', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3205, '1028950', 'DANTE NAVA', 'Secundaria', 'Pública de gestión directa', '211204', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3206, '1154863', 'AGROPECUARIO CHAUPI AYLLU', 'Secundaria', 'Pública de gestión directa', '211204', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3207, '0546697', 'PHARA', 'Secundaria', 'Pública de gestión directa', '211205', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3208, '1025444', 'CHEJANI', 'Secundaria', 'Pública de gestión directa', '211205', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3209, '0618330', 'FRANCISCO BOLOGNESI', 'Secundaria', 'Privada', '211205', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3210, '1155373', 'SAGRADO CORAZON DE JESUS', 'Secundaria', 'Pública de gestión directa', '211205', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3211, '1261205', 'DANIEL ALCIDES CARRION', 'Secundaria', 'Pública de gestión directa', '211205', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3212, '1776095', 'SAN MIGUEL', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '211205', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3213, '1023670', 'AGROPECUARIO UNTUCA', 'Secundaria', 'Pública de gestión directa', '211206', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3214, '0755751', 'AGROPECUARIO QUIACA', 'Secundaria', 'Pública de gestión directa', '211206', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3215, '0239772', 'AGROPECUARIO SAN JUAN DEL ORO', 'Secundaria', 'Pública de gestión directa', '211207', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3216, '0618181', 'JUAN VELASCO ALVARADO', 'Secundaria', 'Pública de gestión directa', '211207', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3217, '1028869', 'AGROINDUSTRIAL SANTA ANA', 'Secundaria', 'Pública de gestión directa', '211207', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3218, '0755645', 'ANTONIO RAYMONDI DELLACQUA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '211207', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3219, '0701284', 'SANTA MARIA DE LA PROVIDENCIA FE Y ALEGRIA 56', 'Secundaria', 'Pública de gestión privada', '211207', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3220, '0755637', 'ADVENTISTA TECNICO COMERCIAL', 'Secundaria', 'Privada', '211207', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3221, '1360858', 'ANTONIO RAYMONDI DELLACQUA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '211207', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3222, '0578708', 'YANAHUAYA', 'Secundaria', 'Pública de gestión directa', '211208', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3223, '1028810', 'AGROPECUARIO PAMPA YANAMAYO', 'Secundaria', 'Pública de gestión directa', '211209', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3224, '0618090', 'JORGE BASADRE GROHMANN', 'Secundaria', 'Pública de gestión directa', '211209', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3225, '1028828', 'AGROPECUARIO PACAYSUIZO', 'Secundaria', 'Pública de gestión directa', '211209', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3226, '0650481', 'AGROINDUSTRIAL CESAR VALLEJO', 'Secundaria', 'Pública de gestión directa', '211209', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3227, '1359447', 'SAN ANTONIO DE PADUA', 'Secundaria', 'Pública de gestión directa', '211209', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3228, '1411313', 'DANIEL ALCIDES CARRION', 'Secundaria', 'Pública de gestión directa', '211209', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3229, '1465897', 'SANTA ROSA', 'Secundaria', 'Pública de gestión directa', '211209', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3230, '1028877', 'AGROINDUSTRIAL SAN IGNACIO', 'Secundaria', 'Pública de gestión directa', '211210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3231, '1028893', 'SIMON BOLIVAR', 'Secundaria de Adultos', 'Pública de gestión directa', '211210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3232, '1229160', 'HORACIO ZEVALLOS GAMEZ', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '211210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3233, '0578716', 'SIMON BOLIVAR', 'Secundaria', 'Pública de gestión directa', '211210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3234, '1584135', 'VILLA CARMEN DE CHOCAL', 'Secundaria', 'Pública de gestión directa', '211210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3235, '1260884', 'SAN GABRIEL', 'Secundaria', 'Pública de gestión directa', '211210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3236, '1583517', 'TECNICO INDUSTRIAL LAS PALMERAS', 'Secundaria', 'Privada', '211210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3237, '1402031', 'SAN MIGUEL', 'Secundaria', 'Pública de gestión directa', '211210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3238, '1623917', 'FRANCISCO BOLOGNESI CERVANTES', 'Secundaria', 'Pública de gestión directa', '211210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3239, '1789858', 'PUERTO DE EDEN', 'Secundaria', 'Pública de gestión directa', '211210', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3240, '0572958', 'JOSE MARIA ARGUEDAS', 'Secundaria', 'Pública de gestión directa', '211301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3241, '1029230', 'FRANCISCO BOLOGNESI', 'Secundaria', 'Pública de gestión directa', '211301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3242, '0240192', 'JOSE GALVEZ', 'Secundaria', 'Pública de gestión directa', '211301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3243, '1029222', 'CESAR VALLEJO', 'Secundaria', 'Pública de gestión directa', '211301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3244, '0573071', 'JOSE ANTONIO ENCINAS FRANCO', 'Secundaria', 'Pública de gestión directa', '211301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3245, '0573014', 'DANIEL ALCIDES CARRION', 'Secundaria', 'Pública de gestión directa', '211301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3246, '0240457', '71006', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '211301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3247, '1029784', 'ALTO ALIANZA', 'Secundaria', 'Pública de gestión directa', '211301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3248, '1029271', 'MUNICIPAL DE YUNGUYO', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '211301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3249, '1029255', 'PANAMERICANA', 'Secundaria', 'Privada', '211301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3250, '1029263', 'JUAN VELASCO ALVARADO', 'Secundaria', 'Pública de gestión directa', '211301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3251, '0474411', 'MICAELA BASTIDAS', 'Secundaria', 'Pública de gestión directa', '211301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3252, '1435478', 'ISAAC NEWTON', 'Secundaria', 'Privada', '211301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3253, '0508135', 'ANAPIA', 'Secundaria', 'Pública de gestión directa', '211302', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3254, '1720291', 'SUANA', 'Secundaria', 'Pública de gestión directa', '211302', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3255, '1029321', 'JOSE CARLOS MARIATEGUI', 'Secundaria', 'Pública de gestión directa', '211303', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3256, '0573469', 'ANDRES BELLO', 'Secundaria', 'Pública de gestión directa', '211303', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3257, '1029347', 'RICARDO PALMA', 'Secundaria', 'Pública de gestión directa', '211304', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3258, '1029529', 'CHIMBO', 'Secundaria', 'Pública de gestión directa', '211304', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3259, '0572982', 'MIGUEL GRAU', 'Secundaria', 'Pública de gestión directa', '211305', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3260, '0660407', 'JOSE CARLOS MARIATEGUI', 'Secundaria', 'Pública de gestión directa', '211306', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3261, '0573105', 'SAN PEDRO', 'Secundaria', 'Pública de gestión directa', '211307', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3262, '0660415', 'ISCAYA', 'Secundaria', 'Pública de gestión directa', '211307', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3263, '1127232', '42019 LASTENIA REJAS DE CASTAÑON', 'Secundaria de Adultos', 'Pública de gestión directa', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3264, '0499970', 'FRANCISCO ANTONIO DE ZELA', 'Secundaria de Adultos', 'Pública de gestión directa', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3265, '0568675', 'CORONEL BOLOGNESI', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3266, '1126440', 'SANTA MARIA', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3267, '0216390', 'SAN MARTIN DE PORRES', 'Secundaria', 'Pública de gestión privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3268, '1126283', 'SAN JUAN BOSCO', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3269, '0744888', '42019 LASTENIA REJAS DE CASTAÑON', 'Secundaria', 'Pública de gestión directa', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3270, '0876441', 'SAN JOSE FE Y ALEGRIA 40', 'Secundaria', 'Pública de gestión privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3271, '0876490', 'SAN IGNACIO DE LOYOLA', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3272, '0876474', 'SAN AGUSTIN', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3273, '0568311', 'ADVENTISTA 28 DE JULIO', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3274, '1127117', 'ANTONIO RAIMONDI', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3275, '1127034', 'NUESTRA SEÑORA DE FATIMA', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3276, '0668764', '42217 NUESTROS HEROES DE LA GUERRA DEL PACIFICO', 'Secundaria', 'Pública de gestión directa', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3277, '1126994', '43008 JORGE MARTORELL FLORES', 'Secundaria', 'Pública de gestión directa', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3278, '0877308', '42006 SAN FRANCISCO DE ASIS', 'Secundaria', 'Pública de gestión privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3279, '0614966', '43006 MERCEDES INDACOCHEA', 'Secundaria', 'Pública de gestión directa', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3280, '0309773', 'CORONEL BOLOGNESI', 'Secundaria', 'Pública de gestión directa', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3281, '0309799', 'FRANCISCO ANTONIO DE ZELA', 'Secundaria', 'Pública de gestión directa', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3282, '0614933', '43003 CARLOS ARMANDO LAURA', 'Secundaria', 'Pública de gestión directa', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3283, '0614990', '42010 SANTISIMA NIÑA MARIA', 'Secundaria', 'Pública de gestión directa', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3284, '0568618', '42003 CORONEL GREGORIO ALBARRACIN', 'Secundaria', 'Pública de gestión directa', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3285, '0472423', 'SANTA ANA', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3286, '0310227', 'CRISTO REY', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3287, '1126952', '43005 MODESTO MOLINA', 'Secundaria', 'Pública de gestión directa', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3288, '0616938', '42195 WILMA SOTILLO DE BACIGALUPO', 'Secundaria', 'Pública de gestión directa', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3289, '0876383', '42022 DR. MODESTO MONTESINOS ZAMALLOA', 'Secundaria', 'Pública de gestión directa', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3290, '0568592', 'JORGE BASADRE GROHMANN', 'Secundaria', 'Pública de gestión directa', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3291, '0310516', 'MODESTO BASADRE', 'Secundaria', 'Pública de gestión directa', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3292, '0876417', '43009 MARIA UGARTECHE DE MACLEAN', 'Secundaria', 'Pública de gestión directa', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3293, '1127075', 'IMAGINA SCHOOL', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3294, '0877183', 'EL BUEN PASTOR', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3295, '0308528', '43001 HERMANOS BARRETO', 'Básica Alternativa', 'Pública de gestión directa', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3296, '1126929', 'DANIEL COMBONI', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3297, '0309831', 'PARROQUIAL CORAZON DE MARIA', 'Secundaria', 'Pública de gestión privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3298, '1126911', 'MIGUEL PRO', 'Secundaria', 'Pública de gestión privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3299, '0309823', 'CHAMPAGNAT', 'Secundaria', 'Pública de gestión directa', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3300, '0615088', 'CARLOS ARMANDO LAURA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3301, '0308510', '42195 WILMA SOTILLO DE BACIGALUPO', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3302, '0697748', '42195 WILMA SOTILLO DE BACIGALUPO', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3303, '1215532', '42241 HERMOGENES ARENAS YAÑEZ', 'Secundaria', 'Pública de gestión directa', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3304, '1215425', '42005 JOSE ROSA ARA', 'Secundaria', 'Pública de gestión directa', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3305, '1215813', 'HERMANAS BARCIA BONIFFATTI', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3306, '1215342', 'ARTURO JIMENEZ BORJA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3307, '1215417', 'ANDRES BELLO', 'Básica Alternativa-Avanzado', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3308, '1214865', '28 DE AGOSTO', 'Secundaria de Adultos', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3309, '1215177', 'SAN PEDRO', 'Básica Alternativa-Avanzado', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3310, '1215383', 'ARTURO JIMENEZ BORJA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3311, '0568642', 'ENRIQUE LOPEZ ALBUJAR', 'Secundaria de Adultos', 'Pública de gestión privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3312, '0645838', 'JORGE BASADRE GROHMANN', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3313, '0614875', 'EINSTEIN TACNA', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3314, '1595552', 'ALEXANDER FLEMING', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3315, '1595602', 'WILLIAM PRESCOTT', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3316, '1595669', 'PEDRO RUIZ GALLO', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3317, '1595883', 'MARISTA DE TACNA', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3318, '1596022', 'CRISTO SALVADOR', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3319, '1216092', 'SAN CARLOS', 'Básica Alternativa-Avanzado', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3320, '1595958', 'FEDERICO VILLARREAL', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3321, '1596147', 'RICARDO PALMA', 'Secundaria de Adultos', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3322, '1596279', 'AMERICANO', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3323, '1596303', 'CIMA', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3324, '1596345', 'COLIBRI - PNP', 'Secundaria de Adultos', 'Pública de gestión directa', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3325, '1596352', 'COLIBRI - PNP', 'Secundaria de Adultos', 'Pública de gestión privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3326, '1596378', 'SAINT GREGORY AMERICAN COLLEGE', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3327, '1127406', '28 DE AGOSTO', 'Secundaria de Adultos', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3328, '0308536', 'CORONEL BOLOGNESI', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3329, '0568568', 'SAN MARTIN DE PORRES', 'Secundaria de Adultos', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3330, '0672659', 'ISAAC NEWTON', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3331, '0668798', 'EVANGELICO ESPIRITU SANTO', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3332, '0716829', 'SAGRADOS CORAZONES', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3333, '1215623', 'TACNA', 'Secundaria de Adultos', 'Pública de gestión directa', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3334, '1308865', 'INDEPENDENCIA AMERICANA', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3335, '1596436', 'ALIPIO PONCE VASQUEZ', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3336, '1314582', 'SAN AGUSTIN', 'Básica Alternativa-Avanzado', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3337, '1360916', 'ENRIQUE LOPEZ ALBUJAR', 'Básica Alternativa', 'Pública de gestión privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3338, '1360924', 'CARLOS ARMANDO LAURA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3339, '1360932', 'JORGE BASADRE GROHMANN', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3340, '1369578', 'CORONEL GREGORIO ALBARRACIN LANCHIPA', 'Secundaria', 'Pública de gestión directa', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3341, '1377159', 'CRNL. JUAN VALER SANDOVAL', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3342, '1404987', 'SAN PABLO', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3343, '1406909', 'SAN SANTIAGO', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3344, '1415330', 'CRISTO REY DEL NIÑO Y ADOLESCENTE', 'Básica Alternativa-Avanzado', 'Pública de gestión privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3345, '1443753', 'NUESTRA SEÑORA DE GUADALUPE', 'Básica Alternativa', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3346, '1525138', 'PARADISE INTERNATIONAL COLLEGE', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3347, '1540863', 'CIENTIFICA SCHOOL', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3348, '1571959', 'INTERNACIONAL ELIM', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3349, '1577568', 'JUAN PABLO II', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3350, '1629146', 'PERUANO NORTEAMERICANO EDWARD KENNEDY', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3351, '1669548', 'COAR TACNA', 'Secundaria', 'Pública de gestión directa', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3352, '1694983', 'PERUANO BRITANICO', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3353, '1698273', 'INNOVA SCHOOLS - TACNA CEDROS', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `origin_schools` (`id`, `modular_code`, `name`, `d_niv_mod`, `management_type`, `ubigeo_code`, `created_at`, `updated_at`) VALUES
(3354, '1722958', 'INTERNATIONAL EDWARD SOCIEDAD ANONIMA', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3355, '1725308', 'CRISTO REY DEL NIÑO Y ADOLESCENTE', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3356, '1729458', 'ANDRES BELLO', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3357, '1748078', 'APOSTOL SAN SANTIAGO', 'Básica Alternativa-Avanzado', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3358, '1754811', 'SAN ANTONIO MARIA CLARET', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3359, '1771195', 'PRINCE', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3360, '1783588', 'LA TORRE', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3361, '1785617', 'STEVE JOBS COLLEGE', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3362, '3001880', 'MARIA REICHE', 'Básica Alternativa-Inicial e Intermedio', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3363, '3001898', 'MARIA REICHE', 'Básica Alternativa-Avanzado', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3364, '3015377', '449 EDUARDO PEREZ GAMBOA', 'Secundaria', 'Pública de gestión directa', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3365, '3019361', 'MI MEJOR AMIGO', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3366, '3042173', 'CIENCIAS - TACNA', 'Secundaria', 'Privada', '230101', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3367, '0568949', '42021 FORTUNATO ZORA CARVAJAL', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '230102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3368, '1127158', '42223 MANUEL DE MENDIBURU', 'Secundaria', 'Pública de gestión directa', '230102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3369, '0568915', '42021 FORTUNATO ZORA CARVAJAL', 'Secundaria', 'Pública de gestión directa', '230102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3370, '0876508', '42088 DON JOSE DE SAN MARTIN', 'Secundaria', 'Pública de gestión directa', '230102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3371, '0308544', '42021 FORTUNATO ZORA CARVAJAL', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '230102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3372, '0876524', '42198 VICTOR RAUL HAYA DE LA TORRE', 'Secundaria', 'Pública de gestión directa', '230102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3373, '0744870', 'GUILLERMO AUZA ARCE', 'Secundaria', 'Pública de gestión directa', '230102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3374, '0645804', '42198 VICTOR RAUL HAYA DE LA TORRE', 'Secundaria de Adultos', 'Pública de gestión directa', '230102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3375, '1336049', 'ADVENTISTA EL FARO', 'Secundaria', 'Privada', '230102', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3376, '0668913', '42025 AURELIA ARCE VILDOSO', 'Secundaria', 'Pública de gestión directa', '230103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3377, '0616961', '42023 VICTOR MAYURI CLAUSSEN', 'Secundaria', 'Pública de gestión directa', '230103', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3378, '1127273', '42218 MARISCAL CACERES', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '230104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3379, '0744896', '42218 MARISCAL CACERES', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '230104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3380, '0716886', '42218 MARISCAL CACERES', 'Secundaria', 'Pública de gestión directa', '230104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3381, '0876532', 'MANUEL A ODRIA', 'Secundaria', 'Pública de gestión directa', '230104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3382, '1216464', '42250 CESAR AUGUSTO COHAILA TAMAYO', 'Secundaria', 'Pública de gestión directa', '230104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3383, '1215102', 'CESAR AUGUSTO COHAILA TAMAYO', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '230104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3384, '1215144', 'CESAR AUGUSTO COHAILA TAMAYO', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '230104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3385, '1595842', '42251 SIMON BOLIVAR', 'Secundaria', 'Pública de gestión directa', '230104', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3386, '0876557', '42032 JOSE JOAQUIN INCLAN', 'Secundaria', 'Pública de gestión directa', '230105', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3387, '0615021', 'JOSE JOAQUIN INCLAN', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '230106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3388, '0472472', '42036 JUAN MARIA REJAS', 'Secundaria', 'Pública de gestión privada', '230106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3389, '1215698', 'JOSE JOAQUIN INCLAN', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '230106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3390, '0672725', '42038 MICAELA PAREDES REJAS', 'Secundaria', 'Pública de gestión directa', '230106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3391, '1597525', '42059', 'Secundaria', 'Pública de gestión directa', '230106', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3392, '0876565', '42064 ALFONSO UGARTE', 'Secundaria', 'Pública de gestión directa', '230107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3393, '1127190', '42068 FRANCISCO LASO', 'Secundaria', 'Pública de gestión directa', '230107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3394, '0877290', '42244 CESAR VALLEJO', 'Secundaria', 'Pública de gestión directa', '230107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3395, '1126689', '42246 JOSE OLAYA BALANDRA', 'Secundaria', 'Pública de gestión directa', '230107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3396, '1214899', 'PESA PALCA', 'Secundaria de Adultos', 'Pública de gestión directa', '230107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3397, '1126564', '42063 JOSE MARIA ARGUEDAS ALTAMIRANO', 'Secundaria', 'Pública de gestión directa', '230107', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3398, '0876599', 'MANUEL FLORES CALVO', 'Secundaria de Adultos', 'Pública de gestión directa', '230108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3399, '0309898', 'MANUEL FLORES CALVO', 'Secundaria', 'Pública de gestión directa', '230108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3400, '0646109', 'CORAZON DE JESUS', 'Secundaria', 'Privada', '230108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3401, '1215250', 'NOE MOISES DAVALOS YBAÑEZ', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '230108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3402, '1215292', 'NOE MOISES DAVALOS YBAÑEZ', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '230108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3403, '1595610', 'EL SHADDAI', 'Secundaria', 'Privada', '230108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3404, '1306497', 'SANTA MARIA EUFRASIA', 'Secundaria', 'Privada', '230108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3405, '0309856', 'FEDERICO BARRETO', 'Secundaria', 'Pública de gestión directa', '230108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3406, '1438530', 'MARIA DE LOS ANGELES', 'Secundaria', 'Privada', '230108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3407, '1631746', 'VERDAD Y VIDA - VERITAS ET VITA', 'Secundaria', 'Privada', '230108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3408, '1645209', '43505 GUSTAVO PONS MUZZO', 'Secundaria', 'Pública de gestión directa', '230108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3409, '1698240', 'FUTURA SCHOOLS', 'Secundaria', 'Privada', '230108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3410, '1778000', 'MARIA MONTESSORI', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '230108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3411, '1778992', 'MARIA MONTESSORI', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '230108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3412, '1788207', 'INNOVA SCHOOLS - TACNA - POCOLLAY', 'Secundaria', 'Privada', '230108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3413, '3009479', 'CUMBRES', 'Secundaria', 'Privada', '230108', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3414, '0568584', '42072 CAROLINA FREYRE ARIAS', 'Secundaria', 'Pública de gestión directa', '230109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3415, '1215904', 'SAN PEDRO', 'Secundaria de Adultos', 'Pública de gestión directa', '230109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3416, '1783778', '42207 SAN PEDRO', 'Secundaria', 'Pública de gestión directa', '230109', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3417, '0876433', '42237 JORGE CHAVEZ', 'Secundaria', 'Pública de gestión directa', '230110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3418, '0876409', '42238 ENRIQUE PAILLARDELLE', 'Secundaria', 'Pública de gestión directa', '230110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3419, '1215656', 'MARIA ROSARIO ARAOZ', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '230110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3420, '1215219', 'NILO VILDOSO GARCIA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '230110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3421, '1215052', 'MARIA ROSARIO ARAOZ', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '230110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3422, '1215730', 'NILO VILDOSO GARCIA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '230110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3423, '1216456', 'LUIS ALBERTO SANCHEZ', 'Secundaria', 'Pública de gestión directa', '230110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3424, '1595743', 'LUIS ALBERTO SANCHEZ', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '230110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3425, '1595750', 'LUIS ALBERTO SANCHEZ', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '230110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3426, '1618271', '42255 SANTA TERESITA DEL NIÑO JESUS', 'Secundaria', 'Pública de gestión directa', '230110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3427, '1596402', 'DR. JOSE ANTONIO ENCINAS FRANCO', 'Secundaria', 'Pública de gestión directa', '230110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3428, '1596410', 'SANTA CRUZ', 'Secundaria', 'Pública de gestión privada', '230110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3429, '0876466', 'ALEXANDER VON HUMBOLDT', 'Secundaria', 'Privada', '230110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3430, '1595446', '42253 GERARDO ARIAS COPAJA', 'Secundaria', 'Pública de gestión directa', '230110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3431, '1367663', 'HUGO MITCHELL', 'Secundaria', 'Privada', '230110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3432, '1458710', 'PEDRO PAULET MOSTAJO', 'Secundaria', 'Privada', '230110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3433, '1465657', 'SAN JOSE DE NAZARET', 'Secundaria', 'Privada', '230110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3434, '1628007', '42256 ESPERANZA MARTINEZ DE LOPEZ', 'Secundaria', 'Pública de gestión directa', '230110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3435, '1640150', '43508 PROCER MANUEL CALDERON DE LA BARCA', 'Secundaria', 'Pública de gestión directa', '230110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3436, '1735109', 'SEÑOR DE LOS MILAGROS', 'Secundaria', 'Privada', '230110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3437, '1783786', '456 EL CARMELO DE MARIA', 'Secundaria', 'Pública de gestión privada', '230110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3438, '1795129', '473 PEDRO QUINA CASTAÑON', 'Secundaria', 'Pública de gestión directa', '230110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3439, '3024767', 'SEÑOR DE LOS MILAGROS', 'Secundaria', 'Privada', '230110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3440, '3044203', 'ESTRELLITA DE BELEN', 'Secundaria', 'Privada', '230110', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3441, '0614909', '42199 JUAN VELASCO ALVARADO', 'Secundaria', 'Pública de gestión directa', '230111', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3442, '0568733', '42044 ALFONSO UGARTE', 'Secundaria', 'Pública de gestión directa', '230111', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3443, '0876375', '42211 ALFONSO EYZAGUIRRE TARA', 'Secundaria', 'Pública de gestión directa', '230111', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3444, '1331859', 'ALFONSO EYZAGUIRRE TARA', 'Básica Alternativa', 'Pública de gestión directa', '230111', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3445, '1331867', 'JUAN VELASCO ALVARADO', 'Básica Alternativa', 'Pública de gestión directa', '230111', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3446, '1406131', '43506 JUVENAL UBALDO ORDOÑEZ SALAZAR', 'Secundaria', 'Pública de gestión directa', '230111', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3447, '1126408', 'FORTUNATO ZORA CARVAJAL', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '230201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3448, '0876813', '42080 SIMON BOLIVAR', 'Secundaria', 'Pública de gestión directa', '230201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3449, '0876805', '42113 JOSE ANTONIO ENCINAS FRANCO', 'Secundaria', 'Pública de gestión directa', '230201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3450, '1126325', '42214 TECNICO AGROPECUARIO', 'Secundaria', 'Pública de gestión directa', '230201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3451, '1126366', 'FORTUNATO ZORA CARVAJAL', 'Secundaria', 'Pública de gestión directa', '230201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3452, '0876615', 'FORTUNATO ZORA CARVAJAL', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '230201', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3453, '0568741', '42075 SAN MARTIN DE PORRES', 'Secundaria', 'Pública de gestión directa', '230202', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3454, '1127208', '42221 RICARDO PALMA', 'Secundaria', 'Pública de gestión directa', '230202', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3455, '0594358', '42076 JOSE CARLOS MARIATEGUI', 'Secundaria', 'Pública de gestión directa', '230203', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3456, '1216613', 'VICTOR RAUL HAYA DE LA TORRE', 'Secundaria', 'Pública de gestión directa', '230203', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3457, '0616946', '42089 SAN AGUSTIN', 'Secundaria', 'Pública de gestión directa', '230205', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3458, '0669069', '42091 ENRIQUE DEMETRIO ESTRADA SERRANO', 'Secundaria', 'Pública de gestión directa', '230206', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3459, '1216530', '42127 LUCIA QUISPE NINA', 'Secundaria', 'Pública de gestión directa', '230206', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3460, '0309880', 'NUESTRO SEÑOR DE LOCUMBA', 'Secundaria', 'Pública de gestión directa', '230301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3461, '1127042', 'NUESTRO SEÑOR DE LOCUMBA', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '230301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3462, '1127000', 'NUESTRO SEÑOR DE LOCUMBA', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '230301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3463, '1311729', '42262 JUVENAL UBALDO ORDOÑEZ SALAZAR', 'Secundaria', 'Pública de gestión directa', '230301', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3464, '0876573', '42031 DANIEL ALCIDES CARRION', 'Secundaria', 'Pública de gestión directa', '230302', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3465, '0568808', '42028 MARISCAL GUILLERMO MILLER', 'Secundaria', 'Pública de gestión directa', '230302', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3466, '0672758', 'TUPAC AMARU II', 'Secundaria', 'Pública de gestión directa', '230302', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3467, '0472456', 'GUSTAVO ANTONIO PINTO ZEBALLOS', 'Secundaria', 'Pública de gestión directa', '230302', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3468, '0645986', '2794 MARISCAL RAMON CASTILLA', 'Secundaria', 'Privada', '230302', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3469, '0310250', 'FISCALIZADO TOQUEPALA', 'Secundaria', 'Privada', '230302', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3470, '0568774', '42030 LUIS BANCHERO ROSSI', 'Secundaria', 'Pública de gestión directa', '230302', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3471, '0549816', 'RICARDO PALMA', 'Secundaria', 'Privada', '230302', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3472, '1527068', 'ESCUELA ADVENTISTA MIRAVE', 'Secundaria', 'Privada', '230302', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3473, '0877274', '42206 MARISCAL ANDRES AVELINO CACERES', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '230303', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3474, '1127315', '42206 MARISCAL ANDRES AVELINO CACERES', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '230303', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3475, '0646042', '42054 JOSE CARLOS MARIATEGUI', 'Secundaria', 'Pública de gestión directa', '230303', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3476, '0646018', '42206 MARISCAL ANDRES AVELINO CACERES', 'Secundaria', 'Pública de gestión directa', '230303', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3477, '0308619', 'CORONEL GREGORIO ALBARRACIN', 'Básica Alternativa-Inicial e Intermedio', 'Pública de gestión directa', '230401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3478, '0876581', 'CORONEL GREGORIO ALBARRACIN', 'Secundaria', 'Pública de gestión directa', '230401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3479, '0309864', 'RAMON COPAJA', 'Secundaria', 'Pública de gestión directa', '230401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3480, '0537118', 'CORONEL GREGORIO ALBARRACIN', 'Básica Alternativa-Avanzado', 'Pública de gestión directa', '230401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3481, '1127166', '43010 HORACIO ZEBALLOS GAMEZ', 'Secundaria', 'Pública de gestión directa', '230401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3482, '1215136', '42231', 'Secundaria', 'Pública de gestión directa', '230401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3483, '0672782', 'RAMON CASTILLA', 'Secundaria', 'Pública de gestión directa', '230401', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3484, '0614842', '42086 HEROES ALBARRACIN', 'Secundaria', 'Pública de gestión directa', '230402', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3485, '0594267', '42094 HORACIO ZORA CARVAJAL', 'Secundaria', 'Pública de gestión directa', '230405', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3486, '0568683', '42096 MATEO PUMACAHUA', 'Secundaria', 'Pública de gestión directa', '230406', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3487, '0594291', '42099 MANUELA FLOR DE SILVA', 'Secundaria', 'Pública de gestión directa', '230407', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3488, '0614818', '42101 JORGE BASADRE GROHMANN', 'Secundaria', 'Pública de gestión directa', '230408', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payment_concepts`
--

CREATE TABLE `payment_concepts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(20) NOT NULL,
  `tupa_code` varchar(20) DEFAULT NULL,
  `description` varchar(200) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `concept_type` enum('enrollment','tuition','certificate','statement','fee','other') NOT NULL,
  `is_taxable` tinyint(1) NOT NULL DEFAULT 0,
  `tax_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `sunat_service_code` varchar(50) DEFAULT NULL,
  `is_mandatory` tinyint(1) NOT NULL DEFAULT 1,
  `discount_applicable` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `payment_concepts`
--

INSERT INTO `payment_concepts` (`id`, `code`, `tupa_code`, `description`, `amount`, `concept_type`, `is_taxable`, `tax_rate`, `sunat_service_code`, `is_mandatory`, `discount_applicable`, `status`, `created_at`, `updated_at`) VALUES
(1, 'MAT-REG', NULL, 'Matrícula Regular', 120.00, 'enrollment', 0, 0.00, NULL, 1, 0, 'active', '2025-11-26 18:45:42', '2025-11-26 18:45:42'),
(2, 'CERT-MOD', NULL, 'Certificado Modular', 50.00, 'certificate', 0, 0.00, NULL, 0, 0, 'active', '2025-11-26 18:45:42', '2025-11-26 18:45:42'),
(3, 'tsz-923', '17.30', 'Examen de Subsanación', 35.50, 'certificate', 0, 0.00, NULL, 0, 0, 'active', '2025-11-26 18:45:43', '2025-11-26 18:45:43'),
(4, 'mxq-211', '25.34', 'Certificado de Estudios', 25.00, 'fee', 0, 0.00, NULL, 0, 0, 'active', '2025-11-26 18:45:43', '2025-11-26 18:45:43'),
(5, 'xbm-775', '27.38', 'Examen de Subsanación', 25.00, 'certificate', 0, 0.00, NULL, 0, 0, 'active', '2025-11-26 18:45:43', '2025-11-26 18:45:43'),
(6, 'stg-190', '29.44', 'Examen de Subsanación', 50.00, 'fee', 0, 0.00, NULL, 0, 0, 'active', '2025-11-26 18:45:43', '2025-11-26 18:45:43'),
(7, 'hcg-426', '.3', 'Examen de Subsanación', 25.00, 'fee', 0, 0.00, NULL, 0, 0, 'active', '2025-11-26 18:45:43', '2025-11-26 18:45:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'gestionar-institucion', 'web', '2025-11-26 18:45:39', '2025-11-26 18:45:39'),
(2, 'gestionar-configuracion', 'web', '2025-11-26 18:45:39', '2025-11-26 18:45:39'),
(3, 'gestionar-estructura-academica', 'web', '2025-11-26 18:45:39', '2025-11-26 18:45:39'),
(4, 'gestionar-prerrequisitos', 'web', '2025-11-26 18:45:39', '2025-11-26 18:45:39'),
(5, 'gestionar-docentes', 'web', '2025-11-26 18:45:39', '2025-11-26 18:45:39'),
(6, 'gestionar-estudiantes', 'web', '2025-11-26 18:45:39', '2025-11-26 18:45:39'),
(7, 'gestionar-periodos', 'web', '2025-11-26 18:45:39', '2025-11-26 18:45:39'),
(8, 'gestionar-carga-academica', 'web', '2025-11-26 18:45:39', '2025-11-26 18:45:39'),
(9, 'gestionar-horarios', 'web', '2025-11-26 18:45:39', '2025-11-26 18:45:39'),
(10, 'aprobar-silabos', 'web', '2025-11-26 18:45:39', '2025-11-26 18:45:39'),
(11, 'registrar-notas', 'web', '2025-11-26 18:45:39', '2025-11-26 18:45:39'),
(12, 'registrar-asistencia', 'web', '2025-11-26 18:45:39', '2025-11-26 18:45:39'),
(13, 'subir-silabo', 'web', '2025-11-26 18:45:39', '2025-11-26 18:45:39'),
(14, 'matricularse', 'web', '2025-11-26 18:45:39', '2025-11-26 18:45:39'),
(15, 'entregar-actividades', 'web', '2025-11-26 18:45:39', '2025-11-26 18:45:39'),
(16, 'ver-mis-asistencias', 'web', '2025-11-26 18:45:39', '2025-11-26 18:45:39'),
(17, 'registrar-pagos', 'web', '2025-11-26 18:45:39', '2025-11-26 18:45:39'),
(18, 'gestionar-certificacion', 'web', '2025-11-26 18:45:39', '2025-11-26 18:45:39'),
(19, 'gestionar-biblioteca', 'web', '2025-11-26 18:45:39', '2025-11-26 18:45:39'),
(20, 'registrar-prestamos', 'web', '2025-11-26 18:45:39', '2025-11-26 18:45:39'),
(21, 'gestionar-admision', 'web', '2025-11-26 18:45:39', '2025-11-26 18:45:39'),
(22, 'gestionar-anuncios', 'web', '2025-11-26 18:45:39', '2025-11-26 18:45:39'),
(23, 'gestionar-cuadro-meritos', 'web', '2025-11-26 18:45:39', '2025-11-26 18:45:39'),
(24, 'revisar-entregas', 'web', '2025-11-26 18:45:39', '2025-11-26 18:45:39'),
(25, 'ver-reporte-asistencia', 'web', '2025-11-26 18:45:39', '2025-11-26 18:45:39'),
(26, 'descargar-acta-final', 'web', '2025-11-26 18:45:39', '2025-11-26 18:45:39'),
(27, 'ver-reporte-acumulativo-asistencia', 'web', '2025-11-26 18:45:39', '2025-11-26 18:45:39'),
(28, 'gestionar-sesiones-caja', 'web', '2025-11-26 18:45:39', '2025-11-26 18:45:39'),
(29, 'gestionar-correlativos', 'web', '2025-11-26 18:45:40', '2025-11-26 18:45:40'),
(30, 'anular-comprobantes', 'web', '2025-11-26 18:45:40', '2025-11-26 18:45:40'),
(31, 'registrar-tramites', 'web', '2025-11-26 18:45:40', '2025-11-26 18:45:40'),
(32, 'gestionar-actividades', 'web', '2025-11-26 18:45:40', '2025-11-26 18:45:40'),
(33, 'gestionar-reservas-matricula', 'web', '2025-11-29 01:33:02', '2025-11-29 01:33:02'),
(34, 'gestionar-reincorporaciones', 'web', '2025-11-29 01:58:27', '2025-11-29 01:58:27'),
(35, 'gestionar-matricula-regular', 'web', '2025-11-29 03:38:47', '2025-11-29 03:38:47'),
(36, 'gestionar-roles', 'web', '2025-11-29 21:16:18', '2025-11-29 21:16:18'),
(37, 'gestionar-usuarios', 'web', '2025-11-30 00:24:10', '2025-11-30 00:24:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prerequisites`
--

CREATE TABLE `prerequisites` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `didactic_unit_id` bigint(20) UNSIGNED NOT NULL,
  `prerequisite_unit_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('mandatory','recommended') NOT NULL DEFAULT 'mandatory',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registrations`
--

CREATE TABLE `registrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `enrollment_id` bigint(20) UNSIGNED NOT NULL,
  `teacher_assignment_id` bigint(20) UNSIGNED NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT '2025-11-26 18:44:20',
  `registration_type` enum('mandatory','elective','recovery') NOT NULL DEFAULT 'mandatory',
  `status` enum('enrolled','withdrawn','transferred') NOT NULL DEFAULT 'enrolled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Estudiante', 'web', '2025-11-26 18:45:40', '2025-11-26 18:45:40'),
(2, 'Docente', 'web', '2025-11-26 18:45:40', '2025-11-26 18:45:40'),
(3, 'Coordinador', 'web', '2025-11-26 18:45:40', '2025-11-26 18:45:40'),
(4, 'Secretario Academico', 'web', '2025-11-26 18:45:40', '2025-11-26 18:45:40'),
(5, 'Tesoreria', 'web', '2025-11-26 18:45:40', '2025-11-26 18:45:40'),
(6, 'Externo', 'web', '2025-11-26 18:45:41', '2025-11-26 18:45:41'),
(7, 'Administrador', 'web', '2025-11-26 18:45:41', '2025-11-26 18:45:41'),
(8, 'Asistente JUA', 'web', '2025-11-29 21:21:48', '2025-11-29 21:21:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 7),
(2, 7),
(3, 7),
(4, 3),
(4, 7),
(5, 7),
(5, 8),
(6, 4),
(6, 7),
(7, 4),
(7, 7),
(8, 4),
(8, 7),
(8, 8),
(9, 3),
(9, 7),
(9, 8),
(10, 3),
(10, 7),
(11, 2),
(11, 3),
(11, 7),
(12, 2),
(12, 3),
(12, 7),
(13, 2),
(13, 3),
(13, 7),
(14, 1),
(14, 7),
(15, 1),
(15, 7),
(16, 1),
(16, 7),
(17, 5),
(17, 7),
(18, 4),
(18, 7),
(19, 7),
(20, 7),
(21, 4),
(21, 7),
(22, 4),
(22, 7),
(22, 8),
(23, 4),
(23, 7),
(24, 2),
(24, 3),
(24, 7),
(25, 2),
(25, 3),
(25, 7),
(25, 8),
(26, 2),
(26, 3),
(26, 7),
(27, 2),
(27, 3),
(27, 7),
(27, 8),
(28, 5),
(28, 7),
(29, 7),
(30, 7),
(31, 5),
(31, 7),
(32, 2),
(32, 3),
(32, 7),
(32, 8),
(33, 4),
(33, 7),
(34, 4),
(34, 7),
(35, 4),
(35, 7),
(36, 7),
(37, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `schedules`
--

CREATE TABLE `schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `teacher_assignment_id` bigint(20) UNSIGNED NOT NULL,
  `classroom_resource_id` bigint(20) UNSIGNED DEFAULT NULL,
  `day_of_week` enum('monday','tuesday','wednesday','thursday','friday','saturday','sunday') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('EhSIic2fcbQioO71tNiDLNSFXPlJhaGwdqNfmEPY', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUURTOWdON05lMEMyQ3dndzRMS2MxczluWWwxNjlaNWxtTkpPR2NsSCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTg6Imh0dHA6Ly9lZHVjb24udGVzdCI7czo1OiJyb3V0ZSI7Tjt9fQ==', 1764450601),
('fAYDeIpgFr345oW0aPZim57Bds3ehmt4EYhGGkex', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiM2N5clZabFV6SWtEWmxSZlBCQWJWT1ZBdUV3NTgxVVVYdW9qWHhKQSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjQ6Imh0dHA6Ly9lZHVjb24udGVzdC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1764453057),
('n5jaflBR9bty8h8fvlOoskmAa4UdXelHUxRaRrkI', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZW1SSU82TEduZnd2MXVyRUpGYXU5Q2lyTVdXUEFnUGU1aWVEWlFWRCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjQ6Imh0dHA6Ly9lZHVjb24udGVzdC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1764453574),
('OX05rPgbFDBtUVu4y8Wp3AcgZpwjmTb6cGXaNk4x', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUUNsQTlEUDRWR1JUTEhZZ0VXUnNtem1qR0xVeUdQaEw3VXJYeTdzMiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTg6Imh0dHA6Ly9lZHVjb24udGVzdCI7czo1OiJyb3V0ZSI7Tjt9fQ==', 1764449400);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shifts`
--

CREATE TABLE `shifts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `shifts`
--

INSERT INTO `shifts` (`id`, `name`, `description`, `start_time`, `end_time`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Turno Mañana', NULL, '08:00:00', '12:30:00', 'active', '2025-11-26 18:45:42', '2025-11-26 18:45:42'),
(2, 'Turno Tarde', NULL, '13:30:00', '18:00:00', 'active', '2025-11-26 18:45:42', '2025-11-26 18:45:42'),
(3, 'Turno Noche', NULL, '18:30:00', '22:30:00', 'active', '2025-11-26 18:45:42', '2025-11-26 18:45:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `students`
--

CREATE TABLE `students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `career_id` bigint(20) UNSIGNED NOT NULL,
  `study_plan_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(20) NOT NULL,
  `current_semester` int(11) NOT NULL DEFAULT 1,
  `accumulated_credits` int(11) NOT NULL DEFAULT 0,
  `weighted_average` decimal(4,2) NOT NULL DEFAULT 0.00,
  `academic_status` enum('regular','irregular','graduated','withdrawn','enrollment_reserved') NOT NULL DEFAULT 'regular',
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `gender` enum('masculino','femenino') DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `ubigeo_birth_id` varchar(10) DEFAULT NULL,
  `admission_date` date NOT NULL,
  `graduation_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `origin_school_id` bigint(20) UNSIGNED DEFAULT NULL,
  `school_graduation_year` year(4) DEFAULT NULL,
  `photo_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `students`
--

INSERT INTO `students` (`id`, `user_id`, `career_id`, `study_plan_id`, `code`, `current_semester`, `accumulated_credits`, `weighted_average`, `academic_status`, `phone`, `address`, `gender`, `birthday`, `ubigeo_birth_id`, `admission_date`, `graduation_date`, `created_at`, `updated_at`, `deleted_at`, `origin_school_id`, `school_graduation_year`, `photo_url`) VALUES
(2, 62, 1, 1, 'E2025-0001', 1, 0, 0.00, 'regular', '987654321', 'Jr puno 123', 'masculino', '1988-07-28', '210101', '2025-11-26', NULL, '2025-11-26 20:07:04', '2025-11-26 20:07:04', NULL, 2394, '2020', 'applicants/9eOxQ1CLPSjX0UxHV7MBQzdRgaMUlFICKu0CVeOn.png'),
(5, 63, 1, 1, 'E2025-00002', 1, 0, 0.00, 'regular', '91234568', 'Jr altiplano 44', 'femenino', '1986-12-13', '210103', '2025-11-26', NULL, '2025-11-26 21:55:28', '2025-11-29 02:40:30', NULL, 142, '2021', 'applicants/vXVlDcS6uYdsc2qRJdvlJOvqKSoXi9us2g0V0Rln.png'),
(6, 64, 1, 1, 'E2025-00003', 1, 0, 0.00, 'regular', '912121212', 'je puno 12344', 'masculino', '1990-12-13', '210101', '2025-11-28', NULL, '2025-11-28 22:01:12', '2025-11-28 22:01:12', NULL, 867, '2020', 'applicants/NBe1UCz6jZ83lT5oF0Sy2uX00MgOxQIK2yVYQBx3.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `student_payments`
--

CREATE TABLE `student_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `payment_concept_id` bigint(20) UNSIGNED NOT NULL,
  `academic_period_id` bigint(20) UNSIGNED DEFAULT NULL,
  `registered_by_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `original_amount` decimal(8,2) NOT NULL,
  `discount_amount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `final_amount` decimal(8,2) NOT NULL,
  `due_date` date NOT NULL,
  `payment_date` datetime DEFAULT NULL,
  `transaction_number` varchar(50) DEFAULT NULL,
  `payment_method` enum('cash','bank_transfer','credit_card','debit_card') DEFAULT NULL,
  `status` enum('pending','paid','overdue','cancelled') NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `voucher_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `student_payments`
--

INSERT INTO `student_payments` (`id`, `student_id`, `payment_concept_id`, `academic_period_id`, `registered_by_user_id`, `original_amount`, `discount_amount`, `final_amount`, `due_date`, `payment_date`, `transaction_number`, `payment_method`, `status`, `notes`, `created_at`, `updated_at`, `voucher_id`) VALUES
(1, 2, 1, 1, 1, 120.00, 0.00, 120.00, '2025-12-01', '2025-11-26 15:20:00', '124', 'cash', 'paid', '', '2025-11-26 20:07:04', '2025-11-26 20:20:36', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `study_plans`
--

CREATE TABLE `study_plans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `career_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `version` varchar(10) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `total_credits` int(11) NOT NULL,
  `total_hours` int(11) NOT NULL,
  `approval_resolution` varchar(50) DEFAULT NULL,
  `status` enum('active','inactive','obsolete') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `study_plans`
--

INSERT INTO `study_plans` (`id`, `career_id`, `code`, `name`, `version`, `start_date`, `end_date`, `total_credits`, `total_hours`, `approval_resolution`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'APSTI-2021', 'Plan de Estudios APSTI', '2021', '2021-01-01', NULL, 114, 2880, 'R.D. 001-2021', 'active', '2025-11-26 18:45:43', '2025-11-26 18:45:43', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `syllabi`
--

CREATE TABLE `syllabi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `teacher_assignment_id` bigint(20) UNSIGNED NOT NULL,
  `general_competence` text DEFAULT NULL,
  `specific_competencies` text DEFAULT NULL,
  `terminal_capacities` text DEFAULT NULL,
  `evaluation_criteria` text DEFAULT NULL,
  `bibliography` text DEFAULT NULL,
  `status` enum('draft','pending_approval','approved','observed') NOT NULL DEFAULT 'draft',
  `observation_notes` text DEFAULT NULL,
  `approval_date` date DEFAULT NULL,
  `approved_by_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `file_url` varchar(255) DEFAULT NULL,
  `version` varchar(10) NOT NULL DEFAULT '1.0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `system_settings`
--

CREATE TABLE `system_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key_name` varchar(100) NOT NULL,
  `value` text NOT NULL,
  `description` text DEFAULT NULL,
  `data_type` enum('string','integer','decimal','boolean','date','json') NOT NULL DEFAULT 'string',
  `module` varchar(50) DEFAULT NULL,
  `is_editable` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `system_settings`
--

INSERT INTO `system_settings` (`id`, `key_name`, `value`, `description`, `data_type`, `module`, `is_editable`, `created_at`, `updated_at`) VALUES
(1, 'minimum_passing_grade', '13', 'Nota mínima aprobatoria.', 'integer', 'grades', 1, '2025-11-26 18:45:42', '2025-11-26 18:45:42'),
(2, 'minimum_attendance_percentage', '70', 'Porcentaje mínimo de asistencia.', 'integer', 'attendance', 1, '2025-11-26 18:45:42', '2025-11-26 18:45:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `teachers`
--

CREATE TABLE `teachers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `institution_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(20) NOT NULL,
  `academic_degree` varchar(100) DEFAULT NULL,
  `specialty` varchar(150) DEFAULT NULL,
  `professional_experience` text DEFAULT NULL,
  `cv_url` varchar(255) DEFAULT NULL,
  `contract_type` enum('permanent','contracted','hourly') NOT NULL DEFAULT 'contracted',
  `hire_date` date DEFAULT NULL,
  `preparation_day` enum('monday','tuesday','wednesday','thursday','friday','saturday') DEFAULT NULL,
  `status` enum('active','leave','terminated') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `teachers`
--

INSERT INTO `teachers` (`id`, `user_id`, `institution_id`, `code`, `academic_degree`, `specialty`, `professional_experience`, `cv_url`, `contract_type`, `hire_date`, `preparation_day`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, 1, 'T-54224', 'Lic.', 'Human Resources Assistant', NULL, NULL, 'contracted', '2021-08-23', NULL, 'active', '2025-11-26 18:45:44', '2025-11-26 18:45:44', NULL),
(2, 3, 1, 'T-81467', 'Dr.', 'Employment Interviewer', NULL, NULL, 'permanent', '2008-05-13', NULL, 'active', '2025-11-26 18:45:44', '2025-11-26 18:45:44', NULL),
(3, 4, 1, 'T-94576', 'Dr.', 'Heavy Equipment Mechanic', NULL, NULL, 'permanent', '1996-04-30', NULL, 'active', '2025-11-26 18:45:44', '2025-11-26 18:45:44', NULL),
(4, 5, 1, 'T-30074', 'Mag.', 'Fence Erector', NULL, NULL, 'permanent', '1975-09-14', NULL, 'active', '2025-11-26 18:45:44', '2025-11-26 18:45:44', NULL),
(5, 6, 1, 'T-65175', 'Mag.', 'Pipelaying Fitter', NULL, NULL, 'permanent', '1988-11-06', NULL, 'active', '2025-11-26 18:45:44', '2025-11-26 18:45:44', NULL),
(6, 7, 1, 'T-06759', 'Dr.', 'Professional Photographer', NULL, NULL, 'contracted', '1978-12-29', NULL, 'active', '2025-11-26 18:45:44', '2025-11-26 18:45:44', NULL),
(7, 8, 1, 'T-56251', 'Lic.', 'Food Cooking Machine Operators', NULL, NULL, 'permanent', '1988-04-02', NULL, 'active', '2025-11-26 18:45:44', '2025-11-26 18:45:44', NULL),
(8, 9, 1, 'T-59028', 'Dr.', 'Funeral Attendant', NULL, NULL, 'contracted', '1994-06-14', NULL, 'active', '2025-11-26 18:45:44', '2025-11-26 18:45:44', NULL),
(9, 10, 1, 'T-50021', 'Mag.', 'Office Clerk', NULL, NULL, 'contracted', '1973-12-19', NULL, 'active', '2025-11-26 18:45:44', '2025-11-26 18:45:44', NULL),
(10, 11, 1, 'T-31098', 'Lic.', 'Biologist', NULL, NULL, 'contracted', '2014-12-23', NULL, 'active', '2025-11-26 18:45:44', '2025-11-26 18:45:44', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `teacher_assignments`
--

CREATE TABLE `teacher_assignments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `didactic_unit_id` bigint(20) UNSIGNED NOT NULL,
  `academic_period_id` bigint(20) UNSIGNED NOT NULL,
  `shift_id` bigint(20) UNSIGNED NOT NULL,
  `section` varchar(5) NOT NULL DEFAULT 'A',
  `max_capacity` int(11) NOT NULL DEFAULT 30,
  `current_enrolled` int(11) NOT NULL DEFAULT 0,
  `status` enum('active','suspended','completed') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tutorings`
--

CREATE TABLE `tutorings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `tutoring_date` datetime NOT NULL,
  `tutoring_type` enum('academic','personal','vocational','group') NOT NULL,
  `reason` text NOT NULL,
  `session_development` text DEFAULT NULL,
  `agreements_commitments` text DEFAULT NULL,
  `follow_up_required` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('scheduled','completed','cancelled','rescheduled') NOT NULL DEFAULT 'scheduled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `document_number` varchar(20) DEFAULT NULL,
  `user_type` enum('administrator','teacher','student','applicant') NOT NULL DEFAULT 'student',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `current_team_id` bigint(20) UNSIGNED DEFAULT NULL,
  `profile_photo_path` varchar(2048) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `lastname`, `email`, `full_name`, `document_number`, `user_type`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `current_team_id`, `profile_photo_path`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', NULL, 'admin@educon.edu.pe', NULL, NULL, 'student', '2025-11-26 18:45:42', '$2y$12$nSX.90rPnRYm1nr1BIXo9.FaQ0Zm9cLIq7v329CUH8erECLgiPxO.', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-26 18:45:42', '2025-11-26 18:45:42'),
(2, 'Chester Rau', NULL, 'spencer.lonie@example.net', NULL, NULL, 'student', '2025-11-26 18:45:43', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'YV9pKxEuOT', NULL, NULL, '2025-11-26 18:45:44', '2025-11-26 18:45:44'),
(3, 'Dr. Bertrand Mertz MD', NULL, 'nyah58@example.net', NULL, NULL, 'student', '2025-11-26 18:45:44', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'wed8yzPmdK', NULL, NULL, '2025-11-26 18:45:44', '2025-11-26 18:45:44'),
(4, 'Prof. Braulio Torp', NULL, 'karson69@example.org', NULL, NULL, 'student', '2025-11-26 18:45:44', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, '0mMHoVxyQi', NULL, NULL, '2025-11-26 18:45:44', '2025-11-26 18:45:44'),
(5, 'Lucile Crist', NULL, 'georgette10@example.net', NULL, NULL, 'student', '2025-11-26 18:45:44', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'KdUiduOZjP', NULL, NULL, '2025-11-26 18:45:44', '2025-11-26 18:45:44'),
(6, 'Rachelle Balistreri', NULL, 'klein.manley@example.com', NULL, NULL, 'student', '2025-11-26 18:45:44', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'ynAoVyFyf1', NULL, NULL, '2025-11-26 18:45:44', '2025-11-26 18:45:44'),
(7, 'Ebba Willms', NULL, 'gorczany.drake@example.org', NULL, NULL, 'student', '2025-11-26 18:45:44', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'Nia1G2RqZQ', NULL, NULL, '2025-11-26 18:45:44', '2025-11-26 18:45:44'),
(8, 'Camille Casper', NULL, 'watsica.garth@example.net', NULL, NULL, 'student', '2025-11-26 18:45:44', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, '9tWw3NhDwg', NULL, NULL, '2025-11-26 18:45:44', '2025-11-26 18:45:44'),
(9, 'Jaiden Goyette', NULL, 'rosario18@example.org', NULL, NULL, 'student', '2025-11-26 18:45:44', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'aCOp0zVIgq', NULL, NULL, '2025-11-26 18:45:44', '2025-11-26 18:45:44'),
(10, 'Sydney Abbott', NULL, 'gorczany.easter@example.org', NULL, NULL, 'student', '2025-11-26 18:45:44', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'rQBIuY2TjJ', NULL, NULL, '2025-11-26 18:45:44', '2025-11-26 18:45:44'),
(11, 'Ms. Amya Fritsch', NULL, 'bruen.monica@example.org', NULL, NULL, 'student', '2025-11-26 18:45:44', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'xUKf1bOac5', NULL, NULL, '2025-11-26 18:45:44', '2025-11-26 18:45:44'),
(12, 'Prof. Yesenia Weber IV', NULL, 'dnader@example.org', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'C7CCJztLtY', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(13, 'Rasheed Franecki', NULL, 'fadel.estevan@example.net', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'gut8TnVFCP', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(14, 'Isidro Strosin', NULL, 'hilda06@example.com', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, '0xr3L4coCu', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(15, 'Keely Reichert', NULL, 'tristian.torphy@example.org', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'Is0QVnWetr', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(16, 'Raul Shields', NULL, 'stamm.korey@example.org', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'tqM8LnMe5U', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(17, 'Alicia Romaguera', NULL, 'bwill@example.org', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'QI6kJwICyV', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(18, 'Winifred Gutmann IV', NULL, 'kylee02@example.com', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$WgpZy8fFoYKbOwTJedyYG.Q76suvW21MnjQlwH7bVlRdfwQf6JQx6', NULL, NULL, NULL, 'T52jDywzO6IXgeGZFjgNrkc7nEg75K0zeijbudqZ4j2v9ZlY4iTK6Pb3jXJu', NULL, NULL, '2025-11-26 18:45:45', '2025-11-30 00:29:06'),
(19, 'Eriberto Johnson', NULL, 'okon.laurence@example.org', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'QeIzPebM5J', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(20, 'Wilbert Daniel', NULL, 'ftowne@example.com', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'GGqY1FFwcr', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(21, 'Rogelio Auer', NULL, 'lynch.natalia@example.net', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'BRUx1Fvbmi', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(22, 'Alec Orn', NULL, 'grayce.okeefe@example.net', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'TdRu2XEMgc', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(23, 'Miller Hickle', NULL, 'luisa.nikolaus@example.org', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, '3o7VnyNZh6', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(24, 'Kaden Rosenbaum', NULL, 'tkautzer@example.org', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, '3vzYTYbkfC', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(25, 'Dr. Jazlyn Gulgowski', NULL, 'shaniya.krajcik@example.net', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, '02RXI57NvP', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(26, 'Rachelle Kunze', NULL, 'arno.schumm@example.org', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'TTkSvarCYo', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(27, 'Palma Koch IV', NULL, 'deven61@example.com', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'R7HSS7xktD', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(28, 'Miss Joy Bartell MD', NULL, 'delfina62@example.net', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'NsXQWJIA6X', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(29, 'Brionna Feil PhD', NULL, 'corwin.joshuah@example.net', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'HiCvZifdLR', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(30, 'Aurore Gerlach Sr.', NULL, 'kcarroll@example.org', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'b0BejpqtqA', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(31, 'Samantha Harber MD', NULL, 'brett97@example.net', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'wzDIJ35AqA', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(32, 'Antonietta Beer IV', NULL, 'pdietrich@example.net', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'VGTdDjkEBk', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(33, 'Ada Rodriguez', NULL, 'panderson@example.com', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'c7P1rhVCjy', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(34, 'Sylvester Sauer', NULL, 'gilberto07@example.com', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'axw0h4rv7a', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(35, 'Dagmar Hickle Jr.', NULL, 'dicki.bethel@example.net', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'gqcTjEz4sW', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(36, 'Iva Hayes', NULL, 'darrell.hartmann@example.net', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'gjUO7qO0Mi', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(37, 'Cassandre Kilback', NULL, 'lubowitz.brionna@example.net', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, '8oFHfQ0kFr', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(38, 'Ms. Ana Streich III', NULL, 'hannah35@example.org', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'hJGgrTwznt', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(39, 'Meta Beatty', NULL, 'laron.friesen@example.com', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'DtIjzWi0qM', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(40, 'Hortense Durgan', NULL, 'kohler.gage@example.net', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'GPu7TAp5tO', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(41, 'Libbie Casper', NULL, 'gretchen63@example.net', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'slVqdRutUE', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(42, 'Mr. Hollis Nolan II', NULL, 'hzulauf@example.org', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'KdwehCq1ru', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(43, 'Kristy Predovic II', NULL, 'boehm.devonte@example.org', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'ZFFQ7xu0ky', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(44, 'Trudie Tremblay', NULL, 'evangeline.rice@example.net', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'FfmhyWEyZf', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(45, 'Lamar Emmerich IV', NULL, 'hill.elizabeth@example.org', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'pPLbJLsgEk', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(46, 'Janick Kohler I', NULL, 'jwintheiser@example.com', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'a4yrB6aOKa', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(47, 'Turner O\'Keefe V', NULL, 'enrique.balistreri@example.com', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'sEJdxY2LnQ', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(48, 'Miss Shyann Schowalter V', NULL, 'daugherty.beaulah@example.org', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'AWtpq4Uqr4', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(49, 'Prudence Bruen', NULL, 'terence50@example.org', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'TaXqnlYOVh', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(50, 'Everardo Heathcote', NULL, 'stanley.murphy@example.net', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'GsHuU5gmNk', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(51, 'Jody Terry V', NULL, 'pokeefe@example.com', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, '35oL54K59v', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(52, 'Golda Treutel', NULL, 'jazmyne72@example.com', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'girv936N4K', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(53, 'Agnes Bins', NULL, 'antoinette45@example.org', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'aKxIZnYeXq', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(54, 'Clint Little', NULL, 'qhickle@example.com', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, '0Wxed13k38', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(55, 'Sasha Halvorson', NULL, 'tgoyette@example.net', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'CLMizFTEG9', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(56, 'Bennett Pfeffer', NULL, 'ywest@example.net', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'Cjpaa0C3zv', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(57, 'Dr. Vito Bayer I', NULL, 'orland45@example.org', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, '8RxIbqKJ1b', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(58, 'Dr. Hallie Batz', NULL, 'fwalsh@example.net', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'Rl09AgnCDB', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(59, 'Floy Predovic II', NULL, 'elissa74@example.org', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'aG63ZKm9KA', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(60, 'Tyrel Dach', NULL, 'linda88@example.com', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, 'VEsistaFVK', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(61, 'Ward Prohaska V', NULL, 'jayda79@example.com', NULL, NULL, 'student', '2025-11-26 18:45:45', '$2y$12$s8NxCUqnFDmbmIY6dbsIFu1aoFMYKyrgv6zAAi6nvdkwqKZqQM0he', NULL, NULL, NULL, '86aGADpg71', NULL, NULL, '2025-11-26 18:45:45', '2025-11-26 18:45:45'),
(62, 'GERARDINO JUVENAL', 'CAUNA HUANCA', 'admin@admin.com', NULL, '45537302', 'student', NULL, '$2y$12$Bij.XSRnD3MMM/6vUcqGOeVFUqFkIs.ZFQQYrLt6hjR.7HGy6VuGK', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-26 19:54:57', '2025-11-26 19:54:57'),
(63, 'CELIA', 'CARI CALSIN', 'celia@admin.com', NULL, '43854482', 'student', NULL, '$2y$12$H50bLBI4cEjndZQvhKN8Q.in8mkoVheBuBDjL3vhM3O9ozWOYRhGW', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-26 20:32:39', '2025-11-26 20:32:39'),
(64, 'EDSON DENIS', 'ZANABRIA TICONA', 'edson@admin.com', NULL, '45899134', 'student', NULL, '$2y$12$U5dfgAQ6ciGqKgowXWF2muOB741yop/IAVPvM54KG2uAZAV5byZoq', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-28 21:56:32', '2025-11-28 21:56:32'),
(65, 'Gerardino Juvenal CAUNA HUANCA', NULL, 'gcauna@unap.edu.pe', NULL, NULL, 'student', NULL, '$2y$12$NFHGpmZvgSkea8WEFcvNe.P74VwYSuai34zJGZMfn2xp4vum24XjG', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-30 00:25:59', '2025-11-30 00:27:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vouchers`
--

CREATE TABLE `vouchers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cash_session_id` bigint(20) UNSIGNED NOT NULL,
  `issuer_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `voucher_type` varchar(20) NOT NULL,
  `series` varchar(8) NOT NULL,
  `number` bigint(20) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `transaction_code` varchar(100) DEFAULT NULL,
  `observations` text DEFAULT NULL,
  `status` enum('issued','annulled') NOT NULL DEFAULT 'issued',
  `issued_at` timestamp NOT NULL DEFAULT '2025-11-26 18:44:54',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `vouchers`
--

INSERT INTO `vouchers` (`id`, `cash_session_id`, `issuer_id`, `client_id`, `voucher_type`, `series`, `number`, `total_amount`, `payment_method`, `transaction_code`, `observations`, `status`, `issued_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 62, 'recibo', 'R25', 2, 120.00, 'cash', '124', 'Pago de deuda: Matrícula Regular', 'issued', '2025-11-26 20:20:00', '2025-11-26 20:20:36', '2025-11-26 20:20:36'),
(2, 1, 1, 62, 'recibo', 'R25', 3, 120.00, 'Efectivo', '', '', 'issued', '2025-11-26 20:22:08', '2025-11-26 20:22:08', '2025-11-26 20:22:08'),
(3, 1, 1, 63, 'recibo', 'R25', 4, 120.00, 'Efectivo', '', '', 'issued', '2025-11-26 21:45:41', '2025-11-26 21:45:41', '2025-11-26 21:45:41'),
(4, 1, 1, 64, 'recibo', 'R25', 5, 120.00, 'Efectivo', '', '', 'issued', '2025-11-28 22:00:20', '2025-11-28 22:00:20', '2025-11-28 22:00:20'),
(5, 1, 1, 63, 'recibo', 'R25', 6, 120.00, 'Efectivo', '', '', 'issued', '2025-11-29 02:08:54', '2025-11-29 02:08:54', '2025-11-29 02:08:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `voucher_items`
--

CREATE TABLE `voucher_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `voucher_id` bigint(20) UNSIGNED NOT NULL,
  `payment_concept_id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `voucher_items`
--

INSERT INTO `voucher_items` (`id`, `voucher_id`, `payment_concept_id`, `description`, `quantity`, `unit_price`, `total_price`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Matrícula Regular', 1, 120.00, 120.00, '2025-11-26 20:20:36', '2025-11-26 20:20:36'),
(2, 2, 1, 'Matrícula Regular', 1, 120.00, 120.00, '2025-11-26 20:22:08', '2025-11-26 20:22:08'),
(3, 3, 1, 'Matrícula Regular', 1, 120.00, 120.00, '2025-11-26 21:45:41', '2025-11-26 21:45:41'),
(4, 4, 1, 'Matrícula Regular', 1, 120.00, 120.00, '2025-11-28 22:00:20', '2025-11-28 22:00:20'),
(5, 5, 1, 'Matrícula Regular', 1, 120.00, 120.00, '2025-11-29 02:08:54', '2025-11-29 02:08:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `voucher_series`
--

CREATE TABLE `voucher_series` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `institution_id` bigint(20) UNSIGNED NOT NULL,
  `voucher_type` enum('boleta','factura','nota_credito','recibo') NOT NULL DEFAULT 'recibo',
  `series` varchar(8) NOT NULL,
  `current_number` bigint(20) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `voucher_series`
--

INSERT INTO `voucher_series` (`id`, `institution_id`, `voucher_type`, `series`, `current_number`, `status`) VALUES
(1, 1, 'recibo', 'R25', 6, 'active');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `academic_activities`
--
ALTER TABLE `academic_activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `academic_activities_teacher_assignment_id_foreign` (`teacher_assignment_id`);

--
-- Indices de la tabla `academic_periods`
--
ALTER TABLE `academic_periods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `academic_periods_institution_id_code_unique` (`institution_id`,`code`),
  ADD KEY `academic_periods_academic_year_id_foreign` (`academic_year_id`);

--
-- Indices de la tabla `academic_records`
--
ALTER TABLE `academic_records`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_academic_record` (`student_id`,`didactic_unit_id`,`academic_period_id`),
  ADD KEY `academic_records_didactic_unit_id_foreign` (`didactic_unit_id`),
  ADD KEY `academic_records_academic_period_id_foreign` (`academic_period_id`);

--
-- Indices de la tabla `academic_years`
--
ALTER TABLE `academic_years`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `academic_years_institution_id_year_unique` (`institution_id`,`year`);

--
-- Indices de la tabla `activity_submissions`
--
ALTER TABLE `activity_submissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `activity_submissions_academic_activity_id_registration_id_unique` (`academic_activity_id`,`registration_id`),
  ADD KEY `activity_submissions_registration_id_foreign` (`registration_id`),
  ADD KEY `activity_submissions_reviewed_by_user_id_foreign` (`reviewed_by_user_id`);

--
-- Indices de la tabla `admission_modalities`
--
ALTER TABLE `admission_modalities`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `admission_offerings`
--
ALTER TABLE `admission_offerings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_offering` (`academic_period_id`,`career_id`,`shift_id`),
  ADD KEY `admission_offerings_career_id_foreign` (`career_id`),
  ADD KEY `admission_offerings_shift_id_foreign` (`shift_id`);

--
-- Indices de la tabla `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `announcements_published_by_user_id_foreign` (`published_by_user_id`);

--
-- Indices de la tabla `applicants`
--
ALTER TABLE `applicants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `applicants_code_unique` (`code`),
  ADD KEY `applicants_user_id_foreign` (`user_id`),
  ADD KEY `applicants_ubigeo_birth_id_foreign` (`ubigeo_birth_id`),
  ADD KEY `applicants_origin_school_id_foreign` (`origin_school_id`),
  ADD KEY `applicants_admission_offering_id_foreign` (`admission_offering_id`),
  ADD KEY `applicants_admission_modality_id_foreign` (`admission_modality_id`),
  ADD KEY `applicants_financial_entity_id_foreign` (`financial_entity_id`);

--
-- Indices de la tabla `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_attendance` (`registration_id`,`schedule_id`,`class_date`),
  ADD KEY `attendances_schedule_id_foreign` (`schedule_id`),
  ADD KEY `attendances_registered_by_user_id_foreign` (`registered_by_user_id`);

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `careers`
--
ALTER TABLE `careers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `careers_institution_id_code_unique` (`institution_id`,`code`);

--
-- Indices de la tabla `cash_sessions`
--
ALTER TABLE `cash_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cash_sessions_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `certificates_code_unique` (`code`),
  ADD KEY `certificates_student_id_foreign` (`student_id`),
  ADD KEY `certificates_module_id_foreign` (`module_id`),
  ADD KEY `certificates_issued_by_user_id_foreign` (`issued_by_user_id`);

--
-- Indices de la tabla `classroom_resources`
--
ALTER TABLE `classroom_resources`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `classroom_resources_classroom_code_unique` (`classroom_code`);

--
-- Indices de la tabla `credit_notes`
--
ALTER TABLE `credit_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `credit_notes_voucher_id_foreign` (`voucher_id`),
  ADD KEY `credit_notes_user_id_foreign` (`user_id`),
  ADD KEY `credit_notes_cash_session_id_foreign` (`cash_session_id`);

--
-- Indices de la tabla `didactic_units`
--
ALTER TABLE `didactic_units`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `didactic_units_module_id_code_unique` (`module_id`,`code`);

--
-- Indices de la tabla `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `enrollments_student_id_academic_period_id_unique` (`student_id`,`academic_period_id`),
  ADD KEY `enrollments_academic_period_id_foreign` (`academic_period_id`);

--
-- Indices de la tabla `enrollment_reserves`
--
ALTER TABLE `enrollment_reserves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `enrollment_reserves_student_id_foreign` (`student_id`),
  ADD KEY `enrollment_reserves_academic_period_id_foreign` (`academic_period_id`);

--
-- Indices de la tabla `evaluation_types`
--
ALTER TABLE `evaluation_types`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `financial_entities`
--
ALTER TABLE `financial_entities`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `grades_registration_id_evaluation_type_id_unique` (`registration_id`,`evaluation_type_id`),
  ADD KEY `grades_evaluation_type_id_foreign` (`evaluation_type_id`),
  ADD KEY `grades_registered_by_user_id_foreign` (`registered_by_user_id`);

--
-- Indices de la tabla `graduation_processes`
--
ALTER TABLE `graduation_processes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `graduation_processes_student_id_foreign` (`student_id`),
  ADD KEY `graduation_processes_advisor_id_foreign` (`advisor_id`),
  ADD KEY `graduation_processes_jury_president_id_foreign` (`jury_president_id`),
  ADD KEY `graduation_processes_jury_secretary_id_foreign` (`jury_secretary_id`),
  ADD KEY `graduation_processes_jury_member_id_foreign` (`jury_member_id`);

--
-- Indices de la tabla `institutions`
--
ALTER TABLE `institutions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `institutions_code_unique` (`code`),
  ADD UNIQUE KEY `institutions_tax_id_unique` (`tax_id`);

--
-- Indices de la tabla `internships`
--
ALTER TABLE `internships`
  ADD PRIMARY KEY (`id`),
  ADD KEY `internships_student_id_foreign` (`student_id`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `library_loans`
--
ALTER TABLE `library_loans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `library_loans_library_resource_id_foreign` (`library_resource_id`),
  ADD KEY `library_loans_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `library_resources`
--
ALTER TABLE `library_resources`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `library_resources_code_unique` (`code`),
  ADD KEY `library_resources_institution_id_foreign` (`institution_id`),
  ADD KEY `library_resources_career_id_foreign` (`career_id`);
ALTER TABLE `library_resources` ADD FULLTEXT KEY `library_resources_title_author_description_fulltext` (`title`,`author`,`description`);

--
-- Indices de la tabla `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `locations_iddist_unique` (`iddist`),
  ADD KEY `locations_nombdep_index` (`nombdep`),
  ADD KEY `locations_nombprov_index` (`nombprov`);

--
-- Indices de la tabla `merit_rankings`
--
ALTER TABLE `merit_rankings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `merit_rankings_student_id_academic_period_id_module_id_unique` (`student_id`,`academic_period_id`,`module_id`),
  ADD KEY `merit_rankings_academic_period_id_foreign` (`academic_period_id`),
  ADD KEY `merit_rankings_module_id_foreign` (`module_id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indices de la tabla `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indices de la tabla `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `modules_study_plan_id_module_number_unique` (`study_plan_id`,`module_number`);

--
-- Indices de la tabla `origin_schools`
--
ALTER TABLE `origin_schools`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `origin_schools_modular_code_unique` (`modular_code`),
  ADD KEY `origin_schools_ubigeo_code_foreign` (`ubigeo_code`),
  ADD KEY `origin_schools_name_index` (`name`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `payment_concepts`
--
ALTER TABLE `payment_concepts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_concepts_code_unique` (`code`),
  ADD UNIQUE KEY `payment_concepts_tupa_code_unique` (`tupa_code`);

--
-- Indices de la tabla `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indices de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indices de la tabla `prerequisites`
--
ALTER TABLE `prerequisites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unit_prerequisite_unique` (`didactic_unit_id`,`prerequisite_unit_id`),
  ADD KEY `prerequisites_prerequisite_unit_id_foreign` (`prerequisite_unit_id`);

--
-- Indices de la tabla `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `registrations_enrollment_id_teacher_assignment_id_unique` (`enrollment_id`,`teacher_assignment_id`),
  ADD KEY `registrations_teacher_assignment_id_foreign` (`teacher_assignment_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indices de la tabla `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indices de la tabla `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schedules_teacher_assignment_id_foreign` (`teacher_assignment_id`),
  ADD KEY `schedules_classroom_resource_id_foreign` (`classroom_resource_id`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `shifts`
--
ALTER TABLE `shifts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `shifts_name_unique` (`name`);

--
-- Indices de la tabla `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `students_code_unique` (`code`),
  ADD KEY `students_user_id_foreign` (`user_id`),
  ADD KEY `students_career_id_foreign` (`career_id`),
  ADD KEY `students_study_plan_id_foreign` (`study_plan_id`),
  ADD KEY `students_ubigeo_birth_id_foreign` (`ubigeo_birth_id`),
  ADD KEY `students_origin_school_id_foreign` (`origin_school_id`);

--
-- Indices de la tabla `student_payments`
--
ALTER TABLE `student_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_payments_student_id_foreign` (`student_id`),
  ADD KEY `student_payments_payment_concept_id_foreign` (`payment_concept_id`),
  ADD KEY `student_payments_academic_period_id_foreign` (`academic_period_id`),
  ADD KEY `student_payments_registered_by_user_id_foreign` (`registered_by_user_id`),
  ADD KEY `student_payments_voucher_id_foreign` (`voucher_id`);

--
-- Indices de la tabla `study_plans`
--
ALTER TABLE `study_plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `study_plans_career_id_code_unique` (`career_id`,`code`);

--
-- Indices de la tabla `syllabi`
--
ALTER TABLE `syllabi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `syllabi_teacher_assignment_id_foreign` (`teacher_assignment_id`),
  ADD KEY `syllabi_approved_by_user_id_foreign` (`approved_by_user_id`);

--
-- Indices de la tabla `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `system_settings_key_name_unique` (`key_name`);

--
-- Indices de la tabla `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `teachers_user_id_institution_id_unique` (`user_id`,`institution_id`),
  ADD UNIQUE KEY `teachers_institution_id_code_unique` (`institution_id`,`code`);

--
-- Indices de la tabla `teacher_assignments`
--
ALTER TABLE `teacher_assignments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_assignment_period` (`teacher_id`,`didactic_unit_id`,`academic_period_id`,`section`),
  ADD KEY `teacher_assignments_didactic_unit_id_foreign` (`didactic_unit_id`),
  ADD KEY `teacher_assignments_academic_period_id_foreign` (`academic_period_id`),
  ADD KEY `teacher_assignments_shift_id_foreign` (`shift_id`);

--
-- Indices de la tabla `tutorings`
--
ALTER TABLE `tutorings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tutorings_student_id_foreign` (`student_id`),
  ADD KEY `tutorings_teacher_id_foreign` (`teacher_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indices de la tabla `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vouchers_series_number_unique` (`series`,`number`),
  ADD KEY `vouchers_cash_session_id_foreign` (`cash_session_id`),
  ADD KEY `vouchers_issuer_id_foreign` (`issuer_id`),
  ADD KEY `vouchers_client_id_foreign` (`client_id`);

--
-- Indices de la tabla `voucher_items`
--
ALTER TABLE `voucher_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `voucher_items_voucher_id_foreign` (`voucher_id`),
  ADD KEY `voucher_items_payment_concept_id_foreign` (`payment_concept_id`);

--
-- Indices de la tabla `voucher_series`
--
ALTER TABLE `voucher_series`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `voucher_series_institution_id_voucher_type_series_unique` (`institution_id`,`voucher_type`,`series`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `academic_activities`
--
ALTER TABLE `academic_activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `academic_periods`
--
ALTER TABLE `academic_periods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `academic_records`
--
ALTER TABLE `academic_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `academic_years`
--
ALTER TABLE `academic_years`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `activity_submissions`
--
ALTER TABLE `activity_submissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `admission_modalities`
--
ALTER TABLE `admission_modalities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `admission_offerings`
--
ALTER TABLE `admission_offerings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `applicants`
--
ALTER TABLE `applicants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `careers`
--
ALTER TABLE `careers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `cash_sessions`
--
ALTER TABLE `cash_sessions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `classroom_resources`
--
ALTER TABLE `classroom_resources`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `credit_notes`
--
ALTER TABLE `credit_notes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `didactic_units`
--
ALTER TABLE `didactic_units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `enrollment_reserves`
--
ALTER TABLE `enrollment_reserves`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `evaluation_types`
--
ALTER TABLE `evaluation_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `financial_entities`
--
ALTER TABLE `financial_entities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `grades`
--
ALTER TABLE `grades`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `graduation_processes`
--
ALTER TABLE `graduation_processes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `institutions`
--
ALTER TABLE `institutions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `internships`
--
ALTER TABLE `internships`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `library_loans`
--
ALTER TABLE `library_loans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `library_resources`
--
ALTER TABLE `library_resources`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `locations`
--
ALTER TABLE `locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1892;

--
-- AUTO_INCREMENT de la tabla `merit_rankings`
--
ALTER TABLE `merit_rankings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT de la tabla `modules`
--
ALTER TABLE `modules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `origin_schools`
--
ALTER TABLE `origin_schools`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6145;

--
-- AUTO_INCREMENT de la tabla `payment_concepts`
--
ALTER TABLE `payment_concepts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `prerequisites`
--
ALTER TABLE `prerequisites`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `registrations`
--
ALTER TABLE `registrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `shifts`
--
ALTER TABLE `shifts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `student_payments`
--
ALTER TABLE `student_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `study_plans`
--
ALTER TABLE `study_plans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `syllabi`
--
ALTER TABLE `syllabi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `teacher_assignments`
--
ALTER TABLE `teacher_assignments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tutorings`
--
ALTER TABLE `tutorings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT de la tabla `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `voucher_items`
--
ALTER TABLE `voucher_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `voucher_series`
--
ALTER TABLE `voucher_series`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `academic_activities`
--
ALTER TABLE `academic_activities`
  ADD CONSTRAINT `academic_activities_teacher_assignment_id_foreign` FOREIGN KEY (`teacher_assignment_id`) REFERENCES `teacher_assignments` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `academic_periods`
--
ALTER TABLE `academic_periods`
  ADD CONSTRAINT `academic_periods_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `academic_periods_institution_id_foreign` FOREIGN KEY (`institution_id`) REFERENCES `institutions` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `academic_records`
--
ALTER TABLE `academic_records`
  ADD CONSTRAINT `academic_records_academic_period_id_foreign` FOREIGN KEY (`academic_period_id`) REFERENCES `academic_periods` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `academic_records_didactic_unit_id_foreign` FOREIGN KEY (`didactic_unit_id`) REFERENCES `didactic_units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `academic_records_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `academic_years`
--
ALTER TABLE `academic_years`
  ADD CONSTRAINT `academic_years_institution_id_foreign` FOREIGN KEY (`institution_id`) REFERENCES `institutions` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `activity_submissions`
--
ALTER TABLE `activity_submissions`
  ADD CONSTRAINT `activity_submissions_academic_activity_id_foreign` FOREIGN KEY (`academic_activity_id`) REFERENCES `academic_activities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `activity_submissions_registration_id_foreign` FOREIGN KEY (`registration_id`) REFERENCES `registrations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `activity_submissions_reviewed_by_user_id_foreign` FOREIGN KEY (`reviewed_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `admission_offerings`
--
ALTER TABLE `admission_offerings`
  ADD CONSTRAINT `admission_offerings_academic_period_id_foreign` FOREIGN KEY (`academic_period_id`) REFERENCES `academic_periods` (`id`),
  ADD CONSTRAINT `admission_offerings_career_id_foreign` FOREIGN KEY (`career_id`) REFERENCES `careers` (`id`),
  ADD CONSTRAINT `admission_offerings_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`);

--
-- Filtros para la tabla `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_published_by_user_id_foreign` FOREIGN KEY (`published_by_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `applicants`
--
ALTER TABLE `applicants`
  ADD CONSTRAINT `applicants_admission_modality_id_foreign` FOREIGN KEY (`admission_modality_id`) REFERENCES `admission_modalities` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `applicants_admission_offering_id_foreign` FOREIGN KEY (`admission_offering_id`) REFERENCES `admission_offerings` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `applicants_financial_entity_id_foreign` FOREIGN KEY (`financial_entity_id`) REFERENCES `financial_entities` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `applicants_origin_school_id_foreign` FOREIGN KEY (`origin_school_id`) REFERENCES `origin_schools` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `applicants_ubigeo_birth_id_foreign` FOREIGN KEY (`ubigeo_birth_id`) REFERENCES `locations` (`iddist`) ON DELETE SET NULL,
  ADD CONSTRAINT `applicants_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `attendances`
--
ALTER TABLE `attendances`
  ADD CONSTRAINT `attendances_registered_by_user_id_foreign` FOREIGN KEY (`registered_by_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendances_registration_id_foreign` FOREIGN KEY (`registration_id`) REFERENCES `registrations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendances_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `careers`
--
ALTER TABLE `careers`
  ADD CONSTRAINT `careers_institution_id_foreign` FOREIGN KEY (`institution_id`) REFERENCES `institutions` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `cash_sessions`
--
ALTER TABLE `cash_sessions`
  ADD CONSTRAINT `cash_sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `certificates`
--
ALTER TABLE `certificates`
  ADD CONSTRAINT `certificates_issued_by_user_id_foreign` FOREIGN KEY (`issued_by_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `certificates_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `certificates_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `credit_notes`
--
ALTER TABLE `credit_notes`
  ADD CONSTRAINT `credit_notes_cash_session_id_foreign` FOREIGN KEY (`cash_session_id`) REFERENCES `cash_sessions` (`id`),
  ADD CONSTRAINT `credit_notes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `credit_notes_voucher_id_foreign` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`);

--
-- Filtros para la tabla `didactic_units`
--
ALTER TABLE `didactic_units`
  ADD CONSTRAINT `didactic_units_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_academic_period_id_foreign` FOREIGN KEY (`academic_period_id`) REFERENCES `academic_periods` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `enrollments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `enrollment_reserves`
--
ALTER TABLE `enrollment_reserves`
  ADD CONSTRAINT `enrollment_reserves_academic_period_id_foreign` FOREIGN KEY (`academic_period_id`) REFERENCES `academic_periods` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `enrollment_reserves_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `grades_evaluation_type_id_foreign` FOREIGN KEY (`evaluation_type_id`) REFERENCES `evaluation_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `grades_registered_by_user_id_foreign` FOREIGN KEY (`registered_by_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `grades_registration_id_foreign` FOREIGN KEY (`registration_id`) REFERENCES `registrations` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `graduation_processes`
--
ALTER TABLE `graduation_processes`
  ADD CONSTRAINT `graduation_processes_advisor_id_foreign` FOREIGN KEY (`advisor_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `graduation_processes_jury_member_id_foreign` FOREIGN KEY (`jury_member_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `graduation_processes_jury_president_id_foreign` FOREIGN KEY (`jury_president_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `graduation_processes_jury_secretary_id_foreign` FOREIGN KEY (`jury_secretary_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `graduation_processes_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `internships`
--
ALTER TABLE `internships`
  ADD CONSTRAINT `internships_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `library_loans`
--
ALTER TABLE `library_loans`
  ADD CONSTRAINT `library_loans_library_resource_id_foreign` FOREIGN KEY (`library_resource_id`) REFERENCES `library_resources` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `library_loans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `library_resources`
--
ALTER TABLE `library_resources`
  ADD CONSTRAINT `library_resources_career_id_foreign` FOREIGN KEY (`career_id`) REFERENCES `careers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `library_resources_institution_id_foreign` FOREIGN KEY (`institution_id`) REFERENCES `institutions` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `merit_rankings`
--
ALTER TABLE `merit_rankings`
  ADD CONSTRAINT `merit_rankings_academic_period_id_foreign` FOREIGN KEY (`academic_period_id`) REFERENCES `academic_periods` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `merit_rankings_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `merit_rankings_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `modules`
--
ALTER TABLE `modules`
  ADD CONSTRAINT `modules_study_plan_id_foreign` FOREIGN KEY (`study_plan_id`) REFERENCES `study_plans` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `origin_schools`
--
ALTER TABLE `origin_schools`
  ADD CONSTRAINT `origin_schools_ubigeo_code_foreign` FOREIGN KEY (`ubigeo_code`) REFERENCES `locations` (`iddist`) ON DELETE SET NULL;

--
-- Filtros para la tabla `prerequisites`
--
ALTER TABLE `prerequisites`
  ADD CONSTRAINT `prerequisites_didactic_unit_id_foreign` FOREIGN KEY (`didactic_unit_id`) REFERENCES `didactic_units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prerequisites_prerequisite_unit_id_foreign` FOREIGN KEY (`prerequisite_unit_id`) REFERENCES `didactic_units` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `registrations`
--
ALTER TABLE `registrations`
  ADD CONSTRAINT `registrations_enrollment_id_foreign` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `registrations_teacher_assignment_id_foreign` FOREIGN KEY (`teacher_assignment_id`) REFERENCES `teacher_assignments` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_classroom_resource_id_foreign` FOREIGN KEY (`classroom_resource_id`) REFERENCES `classroom_resources` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `schedules_teacher_assignment_id_foreign` FOREIGN KEY (`teacher_assignment_id`) REFERENCES `teacher_assignments` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_career_id_foreign` FOREIGN KEY (`career_id`) REFERENCES `careers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `students_origin_school_id_foreign` FOREIGN KEY (`origin_school_id`) REFERENCES `origin_schools` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `students_study_plan_id_foreign` FOREIGN KEY (`study_plan_id`) REFERENCES `study_plans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `students_ubigeo_birth_id_foreign` FOREIGN KEY (`ubigeo_birth_id`) REFERENCES `locations` (`iddist`) ON DELETE SET NULL,
  ADD CONSTRAINT `students_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `student_payments`
--
ALTER TABLE `student_payments`
  ADD CONSTRAINT `student_payments_academic_period_id_foreign` FOREIGN KEY (`academic_period_id`) REFERENCES `academic_periods` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `student_payments_payment_concept_id_foreign` FOREIGN KEY (`payment_concept_id`) REFERENCES `payment_concepts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_payments_registered_by_user_id_foreign` FOREIGN KEY (`registered_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `student_payments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_payments_voucher_id_foreign` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `study_plans`
--
ALTER TABLE `study_plans`
  ADD CONSTRAINT `study_plans_career_id_foreign` FOREIGN KEY (`career_id`) REFERENCES `careers` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `syllabi`
--
ALTER TABLE `syllabi`
  ADD CONSTRAINT `syllabi_approved_by_user_id_foreign` FOREIGN KEY (`approved_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `syllabi_teacher_assignment_id_foreign` FOREIGN KEY (`teacher_assignment_id`) REFERENCES `teacher_assignments` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `teachers_institution_id_foreign` FOREIGN KEY (`institution_id`) REFERENCES `institutions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `teachers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `teacher_assignments`
--
ALTER TABLE `teacher_assignments`
  ADD CONSTRAINT `teacher_assignments_academic_period_id_foreign` FOREIGN KEY (`academic_period_id`) REFERENCES `academic_periods` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `teacher_assignments_didactic_unit_id_foreign` FOREIGN KEY (`didactic_unit_id`) REFERENCES `didactic_units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `teacher_assignments_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `teacher_assignments_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tutorings`
--
ALTER TABLE `tutorings`
  ADD CONSTRAINT `tutorings_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tutorings_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `vouchers`
--
ALTER TABLE `vouchers`
  ADD CONSTRAINT `vouchers_cash_session_id_foreign` FOREIGN KEY (`cash_session_id`) REFERENCES `cash_sessions` (`id`),
  ADD CONSTRAINT `vouchers_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `vouchers_issuer_id_foreign` FOREIGN KEY (`issuer_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `voucher_items`
--
ALTER TABLE `voucher_items`
  ADD CONSTRAINT `voucher_items_payment_concept_id_foreign` FOREIGN KEY (`payment_concept_id`) REFERENCES `payment_concepts` (`id`),
  ADD CONSTRAINT `voucher_items_voucher_id_foreign` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `voucher_series`
--
ALTER TABLE `voucher_series`
  ADD CONSTRAINT `voucher_series_institution_id_foreign` FOREIGN KEY (`institution_id`) REFERENCES `institutions` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
