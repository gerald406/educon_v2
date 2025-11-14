-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-11-2025 a las 18:19:10
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
(1, 1, 2, '2025-I', 'Periodo Académico 2025-I', '2025-03-01', '2025-07-31', '2025-02-15', '2025-03-10', '2025-03-15', '2025-07-15', NULL, NULL, 'active', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(2, 1, 2, '2025-II', 'Perido Académico 2025-II', '2025-09-28', '2025-12-26', '2025-09-21', '2025-09-26', '2025-09-29', '2025-12-18', '2025-12-24 06:20:00', '2025-12-28 06:20:00', 'planned', '2025-11-12 16:21:13', '2025-11-12 16:21:13', NULL);

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
(1, 1, '2024', 'Año Académico 2024', '2024-01-01', '2024-12-31', 'closed', '2025-11-11 06:39:04', '2025-11-11 06:39:04'),
(2, 1, '2025', 'Año Académico 2025', '2025-01-01', '2025-12-31', 'active', '2025-11-11 06:39:04', '2025-11-11 06:39:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `activity_submissions`
--

CREATE TABLE `activity_submissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `academic_activity_id` bigint(20) UNSIGNED NOT NULL,
  `registration_id` bigint(20) UNSIGNED NOT NULL,
  `submission_date` timestamp NOT NULL DEFAULT '2025-11-11 08:17:55',
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

--
-- Volcado de datos para la tabla `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `content`, `announcement_type`, `target_audience`, `publish_date`, `expiration_date`, `attachment_url`, `published_by_user_id`, `is_featured`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Reunio', 'prueba', 'announcement', 'all', '2025-11-11 17:28:00', '2025-11-12 12:28:00', NULL, 1, 0, 'published', '2025-11-11 22:28:43', '2025-11-11 22:28:43', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `applicants`
--

CREATE TABLE `applicants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `career_id` bigint(20) UNSIGNED NOT NULL,
  `study_plan_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(20) NOT NULL,
  `admission_type` enum('regular','extraordinary','external_transfer','internal_transfer') NOT NULL DEFAULT 'regular',
  `exam_score` decimal(5,2) DEFAULT NULL,
  `merit_position` int(11) DEFAULT NULL,
  `application_status` enum('registered','evaluated','approved','no_vacancy','cancelled') NOT NULL DEFAULT 'registered',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `applicants`
--

INSERT INTO `applicants` (`id`, `user_id`, `career_id`, `study_plan_id`, `code`, `admission_type`, `exam_score`, `merit_position`, `application_status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 62, 1, 1, 'P2025-93766', 'regular', 17.32, NULL, 'registered', '2025-11-11 06:39:08', '2025-11-11 06:39:08', NULL),
(2, 63, 1, 1, 'P2025-91119', 'regular', 17.09, NULL, 'approved', '2025-11-11 06:39:08', '2025-11-11 08:01:16', NULL),
(3, 64, 1, 1, 'P2025-02969', 'regular', 14.56, NULL, 'approved', '2025-11-11 06:39:08', '2025-11-13 22:08:11', NULL),
(4, 65, 1, 1, 'P2025-90900', 'regular', 5.03, NULL, 'registered', '2025-11-11 06:39:08', '2025-11-11 06:39:08', NULL),
(5, 66, 1, 1, 'P2025-18193', 'regular', NULL, NULL, 'registered', '2025-11-11 06:39:08', '2025-11-11 06:39:08', NULL),
(6, 67, 1, 1, 'P2025-78065', 'regular', 7.94, NULL, 'registered', '2025-11-11 06:39:08', '2025-11-11 06:39:08', NULL),
(7, 68, 1, 1, 'P2025-77756', 'regular', 8.16, NULL, 'registered', '2025-11-11 06:39:08', '2025-11-11 06:39:08', NULL),
(8, 69, 1, 1, 'P2025-21209', 'regular', NULL, NULL, 'registered', '2025-11-11 06:39:08', '2025-11-11 06:39:08', NULL),
(9, 70, 1, 1, 'P2025-17827', 'regular', 5.65, NULL, 'registered', '2025-11-11 06:39:08', '2025-11-11 06:39:08', NULL),
(10, 71, 1, 1, 'P2025-56516', 'regular', 13.80, NULL, 'registered', '2025-11-11 06:39:08', '2025-11-11 06:39:08', NULL),
(11, 72, 1, 1, 'P2025-52206', 'regular', NULL, NULL, 'registered', '2025-11-11 06:39:08', '2025-11-11 06:39:08', NULL),
(12, 73, 1, 1, 'P2025-67041', 'regular', 18.90, NULL, 'approved', '2025-11-11 06:39:08', '2025-11-11 08:00:55', NULL),
(13, 74, 1, 1, 'P2025-39312', 'regular', 8.10, NULL, 'registered', '2025-11-11 06:39:08', '2025-11-11 06:39:08', NULL),
(14, 75, 1, 1, 'P2025-00593', 'regular', 5.57, NULL, 'registered', '2025-11-11 06:39:08', '2025-11-11 06:39:08', NULL),
(15, 76, 1, 1, 'P2025-89167', 'regular', NULL, NULL, 'registered', '2025-11-11 06:39:08', '2025-11-11 06:39:08', NULL),
(16, 78, 1, 1, 'p250001', 'regular', NULL, NULL, 'registered', '2025-11-11 06:47:12', '2025-11-11 06:47:12', NULL);

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
('laravel-cache-60e91c3a8dd294864aefe34dc2acddff', 'i:1;', 1763052329),
('laravel-cache-60e91c3a8dd294864aefe34dc2acddff:timer', 'i:1763052329;', 1763052329),
('laravel-cache-c67c8e3a6871ebdd8371c80afde32d48', 'i:1;', 1763053921),
('laravel-cache-c67c8e3a6871ebdd8371c80afde32d48:timer', 'i:1763053921;', 1763053921),
('laravel-cache-d9668a26c0eef2c1f5c9ab3f5bad64b3', 'i:1;', 1762882233),
('laravel-cache-d9668a26c0eef2c1f5c9ab3f5bad64b3:timer', 'i:1762882233;', 1762882233),
('laravel-cache-spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:24:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:21:\"gestionar-institucion\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:6;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:23:\"gestionar-configuracion\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:6;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:30:\"gestionar-estructura-academica\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:6;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:24:\"gestionar-prerrequisitos\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:3;i:1;i:6;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:18:\"gestionar-docentes\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:6;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:21:\"gestionar-estudiantes\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:4;i:1;i:6;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:18:\"gestionar-periodos\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:4;i:1;i:6;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:25:\"gestionar-carga-academica\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:4;i:1;i:6;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:18:\"gestionar-horarios\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:3;i:1;i:6;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:15:\"aprobar-silabos\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:3;i:1;i:6;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:15:\"registrar-notas\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:2;i:1;i:3;i:2;i:6;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:20:\"registrar-asistencia\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:2;i:1;i:3;i:2;i:6;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:12:\"subir-silabo\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:2;i:1;i:3;i:2;i:6;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:12:\"matricularse\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:6;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:15:\"registrar-pagos\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:5;i:1;i:6;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:23:\"gestionar-certificacion\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:4;i:1;i:6;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:20:\"gestionar-biblioteca\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:6;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:19:\"registrar-prestamos\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:6;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:18:\"gestionar-admision\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:4;i:1;i:6;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:21:\"gestionar-actividades\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:2;i:1;i:3;i:2;i:6;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:20:\"entregar-actividades\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:6;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:18:\"gestionar-anuncios\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:4;i:1;i:6;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:24:\"gestionar-cuadro-meritos\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:4;i:1;i:6;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:16:\"revisar-entregas\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:2;i:1;i:3;i:2;i:6;}}}s:5:\"roles\";a:6:{i:0;a:3:{s:1:\"a\";i:6;s:1:\"b\";s:13:\"Administrador\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:11:\"Coordinador\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:4;s:1:\"b\";s:20:\"Secretario Academico\";s:1:\"c\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:7:\"Docente\";s:1:\"c\";s:3:\"web\";}i:4;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:10:\"Estudiante\";s:1:\"c\";s:3:\"web\";}i:5;a:3:{s:1:\"a\";i:5;s:1:\"b\";s:9:\"Tesoreria\";s:1:\"c\";s:3:\"web\";}}}', 1763138672);

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
(1, 1, 'APSTI', 'Administración de Plataformas y Servicios de Tecnologías de Información', 6, 'Profesional Técnico en Administración de Plataformas y Servicios de TI', 'R.D. 001-2021', 'active', '2025-11-11 06:39:05', '2025-11-11 06:39:05', NULL);

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
(1, 'C-138', 'Laboratorio de Cómputo', 'Pabellón C', '2', 40, 1, 1, 30, 1, NULL, 'available', '2025-11-11 06:39:05', '2025-11-11 06:39:05'),
(2, 'A-128', 'Laboratorio de Cómputo', 'Pabellón A', '1', 40, 0, 1, 30, 0, NULL, 'available', '2025-11-11 06:39:05', '2025-11-11 06:39:05'),
(3, 'LAB-225', 'Laboratorio de Cómputo', 'Pabellón B', '3', 50, 1, 1, 30, 0, NULL, 'available', '2025-11-11 06:39:05', '2025-11-11 06:39:05'),
(4, 'B-218', 'Aula Común', 'Pabellón A', '3', 40, 1, 0, 0, 0, NULL, 'available', '2025-11-11 06:39:05', '2025-11-11 06:39:05'),
(5, 'LAB-230', 'Aula Común', 'Pabellón C', '2', 30, 0, 0, 0, 0, NULL, 'available', '2025-11-11 06:39:05', '2025-11-11 06:39:05'),
(6, 'B-189', 'Laboratorio de Cómputo', 'Pabellón B', '3', 30, 1, 1, 30, 0, NULL, 'available', '2025-11-11 06:39:05', '2025-11-11 06:39:05'),
(7, 'A-197', 'Laboratorio de Cómputo', 'Pabellón A', '1', 50, 0, 1, 30, 0, NULL, 'available', '2025-11-11 06:39:05', '2025-11-11 06:39:05'),
(8, 'LAB-144', 'Aula Común', 'Pabellón A', '2', 50, 1, 0, 0, 0, NULL, 'available', '2025-11-11 06:39:05', '2025-11-11 06:39:05'),
(9, 'B-276', 'Aula Común', 'Pabellón C', '1', 50, 1, 0, 0, 1, NULL, 'available', '2025-11-11 06:39:05', '2025-11-11 06:39:05'),
(10, 'B-289', 'Aula Común', 'Pabellón B', '2', 40, 0, 0, 0, 0, NULL, 'available', '2025-11-11 06:39:05', '2025-11-11 06:39:05');

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
(1, 1, 'MEI-I', 'Mantenimiento de equipos informáticos', 1, 8, 128, 5, 'career', NULL, NULL, 1, 'active', '2025-11-11 06:39:05', '2025-11-11 06:39:05', NULL),
(2, 1, 'ISO-I', 'Instalación de Sistemas operativos', 1, 5, 80, 3, 'career', NULL, NULL, 2, 'active', '2025-11-11 06:39:05', '2025-11-11 06:39:05', NULL),
(3, 1, 'ACPD-I', 'Administración de Centros de procesamiento de datos', 1, 4, 64, 3, 'career', NULL, NULL, 3, 'active', '2025-11-11 06:39:05', '2025-11-11 06:39:05', NULL),
(4, 1, 'DRC-I', 'Diseño de redes de comunicación', 1, 6, 96, 4, 'career', NULL, NULL, 4, 'active', '2025-11-11 06:39:05', '2025-11-11 06:39:05', NULL),
(5, 1, 'CE-I', 'Comunicación efectiva', 1, 4, 64, 3, 'transversal', NULL, NULL, 5, 'active', '2025-11-11 06:39:05', '2025-11-11 06:39:05', NULL),
(6, 1, 'OE-I', 'Ofimática empresarial', 1, 3, 48, 2, 'transversal', NULL, NULL, 6, 'active', '2025-11-11 06:39:05', '2025-11-11 06:39:05', NULL),
(7, 1, 'CE2-I', 'Comportamiento ético', 1, 3, 48, 2, 'transversal', NULL, NULL, 7, 'active', '2025-11-11 06:39:05', '2025-11-11 06:39:05', NULL),
(8, 1, 'SI-I', 'Seguridad informática', 1, 5, 80, 3, 'career', NULL, NULL, 8, 'active', '2025-11-11 06:39:05', '2025-11-11 06:39:05', NULL),
(9, 1, 'ADS-I', 'Análisis y diseño de sistemas', 1, 5, 80, 3, 'career', NULL, NULL, 9, 'active', '2025-11-11 06:39:05', '2025-11-11 06:39:05', NULL),
(10, 1, 'REC-II', 'Reparación de equipos de cómputo', 2, 8, 128, 5, 'career', NULL, NULL, 1, 'active', '2025-11-11 06:39:06', '2025-11-11 06:39:06', NULL),
(11, 1, 'ICRC-II', 'Instalación y configuración de redes de comunicación', 2, 7, 112, 4, 'career', NULL, NULL, 2, 'active', '2025-11-11 06:39:06', '2025-11-11 06:39:06', NULL),
(12, 1, 'ARC-II', 'Administración de redes de comunicación', 2, 5, 80, 3, 'career', NULL, NULL, 3, 'active', '2025-11-11 06:39:06', '2025-11-11 06:39:06', NULL),
(13, 2, 'PSE-III', 'Programación de software empresarial', 3, 8, 128, 5, 'career', NULL, NULL, 1, 'active', '2025-11-11 06:39:06', '2025-11-11 06:39:06', NULL),
(14, 2, 'ICO-III', 'Inglés para la comunicación oral', 3, 3, 48, 2, 'transversal', NULL, NULL, 3, 'active', '2025-11-11 06:39:06', '2025-11-11 06:39:06', NULL),
(15, 3, 'AW-V', 'Arquitectura web', 5, 9, 144, 5, 'career', NULL, NULL, 1, 'active', '2025-11-11 06:39:06', '2025-11-11 06:39:06', NULL),
(16, 3, 'PT-VI', 'Proyecto de Tesis', 6, 8, 128, 5, 'career', NULL, NULL, 1, 'active', '2025-11-11 06:39:06', '2025-11-11 06:39:06', NULL),
(17, 3, 'PP-VI', 'Prácticas Pre-profesionales', 6, 12, 192, 8, 'career', NULL, NULL, 2, 'active', '2025-11-11 06:39:06', '2025-11-11 06:39:06', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `enrollments`
--

CREATE TABLE `enrollments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `academic_period_id` bigint(20) UNSIGNED NOT NULL,
  `enrollment_date` timestamp NOT NULL DEFAULT '2025-11-11 06:38:48',
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
(1, 1, 1, '2025-11-11 06:38:48', 1, 'continuing', 0.00, 'pending', 'active', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(2, 2, 1, '2025-11-11 06:38:48', 1, 'continuing', 0.00, 'pending', 'active', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(3, 3, 1, '2025-11-11 06:38:48', 1, 'continuing', 0.00, 'pending', 'active', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(4, 4, 1, '2025-11-11 06:38:48', 1, 'continuing', 0.00, 'pending', 'active', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(5, 5, 1, '2025-11-11 06:38:48', 1, 'continuing', 0.00, 'pending', 'active', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(6, 6, 1, '2025-11-11 06:38:48', 1, 'continuing', 0.00, 'pending', 'active', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(7, 7, 1, '2025-11-11 06:38:48', 1, 'continuing', 0.00, 'pending', 'active', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(8, 8, 1, '2025-11-11 06:38:48', 1, 'continuing', 0.00, 'pending', 'active', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(9, 9, 1, '2025-11-11 06:38:48', 1, 'continuing', 0.00, 'pending', 'active', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(10, 10, 1, '2025-11-11 06:38:48', 1, 'continuing', 0.00, 'pending', 'active', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(11, 11, 1, '2025-11-11 06:38:48', 1, 'continuing', 0.00, 'pending', 'active', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(12, 12, 1, '2025-11-11 06:38:48', 1, 'continuing', 0.00, 'pending', 'active', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(13, 13, 1, '2025-11-11 06:38:48', 1, 'continuing', 0.00, 'pending', 'active', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(14, 14, 1, '2025-11-11 06:38:48', 1, 'continuing', 0.00, 'pending', 'active', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(15, 15, 1, '2025-11-11 06:38:48', 1, 'continuing', 0.00, 'pending', 'active', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(16, 16, 1, '2025-11-11 06:38:48', 1, 'continuing', 0.00, 'pending', 'active', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(17, 17, 1, '2025-11-11 06:38:48', 1, 'continuing', 0.00, 'pending', 'active', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(18, 18, 1, '2025-11-11 06:38:48', 1, 'continuing', 0.00, 'pending', 'active', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(19, 19, 1, '2025-11-11 06:38:48', 1, 'continuing', 0.00, 'pending', 'active', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(20, 20, 1, '2025-11-11 06:38:48', 1, 'continuing', 0.00, 'pending', 'active', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(21, 53, 1, '2025-11-11 06:38:48', 1, 'continuing', 0.00, 'paid', 'active', NULL, '2025-11-11 08:57:34', '2025-11-11 08:57:34', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `enrollment_reserves`
--

CREATE TABLE `enrollment_reserves` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `reason` text NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `supporting_document_url` varchar(255) DEFAULT NULL,
  `status` enum('active','expired','cancelled') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 'Evaluación Parcial', NULL, 30.00, 1, 1, 'active', '2025-11-11 06:39:05', '2025-11-11 06:39:05'),
(2, 'Evaluación Final', NULL, 40.00, 1, 3, 'active', '2025-11-11 06:39:05', '2025-11-11 06:39:05'),
(3, 'Evaluación Continua', NULL, 30.00, 1, 2, 'active', '2025-11-11 06:39:05', '2025-11-11 06:39:05');

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
(1, 'IESTP001', 'IESTP Educon (Sede Principal)', '20123456789', 'Av. Principal 123, Lima', NULL, NULL, NULL, NULL, 'active', '2025-11-11 06:39:04', '2025-11-11 06:39:04', NULL);

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
  `calculation_date` timestamp NOT NULL DEFAULT '2025-11-11 22:55:25',
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
(19, '2025_11_07_114340_create_applicants_table', 1),
(20, '2025_11_07_114351_create_students_table', 1),
(21, '2025_11_07_114359_create_enrollment_reserves_table', 1),
(22, '2025_11_07_115238_add_user_type_to_users_table', 1),
(23, '2025_11_07_225002_create_academic_periods_table', 1),
(24, '2025_11_07_225010_create_teacher_assignments_table', 1),
(25, '2025_11_07_225018_create_schedules_table', 1),
(26, '2025_11_08_224312_create_enrollments_table', 1),
(27, '2025_11_08_224335_create_registrations_table', 1),
(28, '2025_11_09_075448_create_grades_table', 1),
(29, '2025_11_09_075504_create_academic_records_table', 1),
(30, '2025_11_09_075517_create_attendances_table', 1),
(31, '2025_11_09_091059_create_student_payments_table', 1),
(32, '2025_11_09_154538_create_internships_table', 1),
(33, '2025_11_09_154624_create_graduation_processes_table', 1),
(34, '2025_11_09_154639_create_certificates_table', 1),
(35, '2025_11_10_100639_create_syllabi_table', 1),
(36, '2025_11_10_103939_add_observations_to_syllabi_table', 1),
(37, '2025_11_10_113202_create_library_resources_table', 1),
(38, '2025_11_10_113214_create_library_loans_table', 1),
(39, '2025_11_10_132213_create_permission_tables', 1),
(40, '2025_11_11_030309_create_tutorings_table', 2),
(41, '2025_11_11_031532_create_academic_activities_table', 3),
(42, '2025_11_11_031539_create_activity_submissions_table', 3),
(43, '2025_11_11_170210_create_announcements_table', 4),
(44, '2025_11_11_175313_create_merit_rankings_table', 5),
(45, '2025_11_12_110842_add_grade_entry_dates_to_academic_periods_table', 6);

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
(1, 'App\\Models\\User', 12),
(1, 'App\\Models\\User', 13),
(1, 'App\\Models\\User', 14),
(1, 'App\\Models\\User', 15),
(1, 'App\\Models\\User', 16),
(1, 'App\\Models\\User', 17),
(1, 'App\\Models\\User', 18),
(1, 'App\\Models\\User', 19),
(1, 'App\\Models\\User', 20),
(1, 'App\\Models\\User', 21),
(1, 'App\\Models\\User', 22),
(1, 'App\\Models\\User', 23),
(1, 'App\\Models\\User', 24),
(1, 'App\\Models\\User', 25),
(1, 'App\\Models\\User', 26),
(1, 'App\\Models\\User', 27),
(1, 'App\\Models\\User', 28),
(1, 'App\\Models\\User', 29),
(1, 'App\\Models\\User', 30),
(1, 'App\\Models\\User', 31),
(1, 'App\\Models\\User', 32),
(1, 'App\\Models\\User', 33),
(1, 'App\\Models\\User', 34),
(1, 'App\\Models\\User', 35),
(1, 'App\\Models\\User', 36),
(1, 'App\\Models\\User', 37),
(1, 'App\\Models\\User', 38),
(1, 'App\\Models\\User', 39),
(1, 'App\\Models\\User', 40),
(1, 'App\\Models\\User', 41),
(1, 'App\\Models\\User', 42),
(1, 'App\\Models\\User', 43),
(1, 'App\\Models\\User', 44),
(1, 'App\\Models\\User', 45),
(1, 'App\\Models\\User', 46),
(1, 'App\\Models\\User', 47),
(1, 'App\\Models\\User', 48),
(1, 'App\\Models\\User', 49),
(1, 'App\\Models\\User', 50),
(1, 'App\\Models\\User', 51),
(1, 'App\\Models\\User', 52),
(1, 'App\\Models\\User', 53),
(1, 'App\\Models\\User', 54),
(1, 'App\\Models\\User', 55),
(1, 'App\\Models\\User', 56),
(1, 'App\\Models\\User', 57),
(1, 'App\\Models\\User', 58),
(1, 'App\\Models\\User', 59),
(1, 'App\\Models\\User', 60),
(1, 'App\\Models\\User', 61),
(1, 'App\\Models\\User', 62),
(1, 'App\\Models\\User', 63),
(1, 'App\\Models\\User', 64),
(1, 'App\\Models\\User', 65),
(1, 'App\\Models\\User', 66),
(1, 'App\\Models\\User', 67),
(1, 'App\\Models\\User', 68),
(1, 'App\\Models\\User', 69),
(1, 'App\\Models\\User', 70),
(1, 'App\\Models\\User', 71),
(1, 'App\\Models\\User', 72),
(1, 'App\\Models\\User', 73),
(1, 'App\\Models\\User', 74),
(1, 'App\\Models\\User', 75),
(1, 'App\\Models\\User', 76),
(1, 'App\\Models\\User', 78),
(1, 'App\\Models\\User', 81),
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
(2, 'App\\Models\\User', 80),
(6, 'App\\Models\\User', 1);

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
(1, 1, 1, 'Soporte, mantenimiento y control de riesgos en sistemas informáticos', NULL, 25, 800, NULL, 1, 'active', '2025-11-11 06:39:05', '2025-11-11 06:39:05', NULL),
(2, 1, 2, 'Desarrollo de sistemas informáticos y gestión', NULL, 30, 768, NULL, 2, 'active', '2025-11-11 06:39:05', '2025-11-11 06:39:05', NULL),
(3, 1, 3, 'Arquitectura y proyectos TI', NULL, 35, 816, NULL, 3, 'active', '2025-11-11 06:39:05', '2025-11-11 06:39:05', NULL);

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
(1, 'MAT-REG', NULL, 'Matrícula Regular', 120.00, 'enrollment', 0, 0.00, NULL, 1, 0, 'active', '2025-11-11 06:39:05', '2025-11-11 06:39:05'),
(2, 'CERT-MOD', NULL, 'Certificado Modular', 50.00, 'certificate', 0, 0.00, NULL, 0, 0, 'active', '2025-11-11 06:39:05', '2025-11-11 06:39:05'),
(3, 'ljb-963', '.56', 'Certificado de Estudios', 25.00, 'fee', 0, 0.00, NULL, 0, 0, 'active', '2025-11-11 06:39:05', '2025-11-11 06:39:05'),
(4, 'cbd-392', '38.98', 'Certificado de Estudios', 50.00, 'fee', 0, 0.00, NULL, 0, 0, 'active', '2025-11-11 06:39:05', '2025-11-11 06:39:05'),
(5, 'pxk-249', '35.35', 'Examen de Subsanación', 25.00, 'fee', 0, 0.00, NULL, 0, 0, 'active', '2025-11-11 06:39:05', '2025-11-11 06:39:05'),
(6, 'aqn-185', '.76', 'Examen de Subsanación', 25.00, 'fee', 0, 0.00, NULL, 0, 0, 'active', '2025-11-11 06:39:05', '2025-11-11 06:39:05'),
(7, 'fvm-998', '.12', 'Certificado de Estudios', 50.00, 'fee', 0, 0.00, NULL, 0, 0, 'active', '2025-11-11 06:39:05', '2025-11-11 06:39:05');

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
(1, 'gestionar-institucion', 'web', '2025-11-11 06:39:04', '2025-11-11 06:39:04'),
(2, 'gestionar-configuracion', 'web', '2025-11-11 06:39:04', '2025-11-11 06:39:04'),
(3, 'gestionar-estructura-academica', 'web', '2025-11-11 06:39:04', '2025-11-11 06:39:04'),
(4, 'gestionar-prerrequisitos', 'web', '2025-11-11 06:39:04', '2025-11-11 06:39:04'),
(5, 'gestionar-docentes', 'web', '2025-11-11 06:39:04', '2025-11-11 06:39:04'),
(6, 'gestionar-estudiantes', 'web', '2025-11-11 06:39:04', '2025-11-11 06:39:04'),
(7, 'gestionar-periodos', 'web', '2025-11-11 06:39:04', '2025-11-11 06:39:04'),
(8, 'gestionar-carga-academica', 'web', '2025-11-11 06:39:04', '2025-11-11 06:39:04'),
(9, 'gestionar-horarios', 'web', '2025-11-11 06:39:04', '2025-11-11 06:39:04'),
(10, 'aprobar-silabos', 'web', '2025-11-11 06:39:04', '2025-11-11 06:39:04'),
(11, 'registrar-notas', 'web', '2025-11-11 06:39:04', '2025-11-11 06:39:04'),
(12, 'registrar-asistencia', 'web', '2025-11-11 06:39:04', '2025-11-11 06:39:04'),
(13, 'subir-silabo', 'web', '2025-11-11 06:39:04', '2025-11-11 06:39:04'),
(14, 'matricularse', 'web', '2025-11-11 06:39:04', '2025-11-11 06:39:04'),
(15, 'registrar-pagos', 'web', '2025-11-11 06:39:04', '2025-11-11 06:39:04'),
(16, 'gestionar-certificacion', 'web', '2025-11-11 06:39:04', '2025-11-11 06:39:04'),
(17, 'gestionar-biblioteca', 'web', '2025-11-11 06:39:04', '2025-11-11 06:39:04'),
(18, 'registrar-prestamos', 'web', '2025-11-11 06:39:04', '2025-11-11 06:39:04'),
(19, 'gestionar-admision', 'web', '2025-11-11 06:39:04', '2025-11-11 06:39:04'),
(20, 'gestionar-actividades', 'web', '2025-11-11 08:23:39', '2025-11-11 08:23:39'),
(21, 'entregar-actividades', 'web', '2025-11-11 08:45:55', '2025-11-11 08:45:55'),
(22, 'gestionar-anuncios', 'web', '2025-11-11 22:25:27', '2025-11-11 22:25:27'),
(23, 'gestionar-cuadro-meritos', 'web', '2025-11-12 15:20:50', '2025-11-12 15:20:50'),
(24, 'revisar-entregas', 'web', '2025-11-12 15:33:03', '2025-11-12 15:33:03');

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
  `registration_date` timestamp NOT NULL DEFAULT '2025-11-11 06:38:52',
  `registration_type` enum('mandatory','elective','recovery') NOT NULL DEFAULT 'mandatory',
  `status` enum('enrolled','withdrawn','transferred') NOT NULL DEFAULT 'enrolled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `registrations`
--

INSERT INTO `registrations` (`id`, `enrollment_id`, `teacher_assignment_id`, `registration_date`, `registration_type`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(2, 1, 2, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(3, 2, 1, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(4, 2, 2, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(5, 3, 1, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(6, 3, 2, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(7, 4, 1, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(8, 4, 2, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(9, 5, 1, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(10, 5, 2, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(11, 6, 1, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(12, 6, 2, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(13, 7, 1, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(14, 7, 2, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(15, 8, 1, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(16, 8, 2, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(17, 9, 1, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(18, 9, 2, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(19, 10, 1, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(20, 10, 2, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(21, 11, 1, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(22, 11, 2, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(23, 12, 1, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(24, 12, 2, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(25, 13, 1, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(26, 13, 2, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(27, 14, 1, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(28, 14, 2, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(29, 15, 1, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(30, 15, 2, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(31, 16, 1, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(32, 16, 2, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(33, 17, 1, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(34, 17, 2, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(35, 18, 1, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(36, 18, 2, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(37, 19, 1, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(38, 19, 2, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(39, 20, 1, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(40, 20, 2, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 06:39:09', '2025-11-11 06:39:09', NULL),
(41, 21, 1, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 08:57:34', '2025-11-11 08:57:34', NULL),
(42, 21, 2, '2025-11-11 06:38:52', 'mandatory', 'enrolled', '2025-11-11 08:57:34', '2025-11-11 08:57:34', NULL);

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
(1, 'Estudiante', 'web', '2025-11-11 06:39:04', '2025-11-11 06:39:04'),
(2, 'Docente', 'web', '2025-11-11 06:39:04', '2025-11-11 06:39:04'),
(3, 'Coordinador', 'web', '2025-11-11 06:39:04', '2025-11-11 06:39:04'),
(4, 'Secretario Academico', 'web', '2025-11-11 06:39:04', '2025-11-11 06:39:04'),
(5, 'Tesoreria', 'web', '2025-11-11 06:39:04', '2025-11-11 06:39:04'),
(6, 'Administrador', 'web', '2025-11-11 06:39:04', '2025-11-11 06:39:04');

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
(1, 6),
(2, 6),
(3, 6),
(4, 3),
(4, 6),
(5, 6),
(6, 4),
(6, 6),
(7, 4),
(7, 6),
(8, 4),
(8, 6),
(9, 3),
(9, 6),
(10, 3),
(10, 6),
(11, 2),
(11, 3),
(11, 6),
(12, 2),
(12, 3),
(12, 6),
(13, 2),
(13, 3),
(13, 6),
(14, 1),
(14, 6),
(15, 5),
(15, 6),
(16, 4),
(16, 6),
(17, 6),
(18, 6),
(19, 4),
(19, 6),
(20, 2),
(20, 3),
(20, 6),
(21, 1),
(21, 6),
(22, 4),
(22, 6),
(23, 4),
(23, 6),
(24, 2),
(24, 3),
(24, 6);

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

--
-- Volcado de datos para la tabla `schedules`
--

INSERT INTO `schedules` (`id`, `teacher_assignment_id`, `classroom_resource_id`, `day_of_week`, `start_time`, `end_time`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'monday', '08:00:00', '10:15:00', '2025-11-11 06:39:09', '2025-11-11 06:39:09'),
(2, 1, 1, 'wednesday', '08:00:00', '10:15:00', '2025-11-11 06:39:09', '2025-11-11 06:39:09'),
(3, 2, 1, 'tuesday', '10:30:00', '12:45:00', '2025-11-11 06:39:09', '2025-11-11 06:39:09');

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
('T0aRmXNnoZKlDchRTmTzEaAJh8rx8FX4tlNfl2b9', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZVoycFkwMDQyU2VaUmRZdEtWYW5wMzhLbnNUblNJVG9zTG53YlF5USI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjQ6Imh0dHA6Ly9lZHVjb24udGVzdC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1763054009);

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
(1, 'Turno Mañana', NULL, '08:00:00', '12:30:00', 'active', '2025-11-11 06:39:05', '2025-11-11 06:39:05'),
(2, 'Turno Tarde', NULL, '13:30:00', '18:00:00', 'active', '2025-11-11 06:39:05', '2025-11-11 06:39:05'),
(3, 'Turno Noche', NULL, '18:30:00', '22:30:00', 'active', '2025-11-11 06:39:05', '2025-11-11 06:39:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `students`
--

CREATE TABLE `students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `applicant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `career_id` bigint(20) UNSIGNED NOT NULL,
  `study_plan_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(20) NOT NULL,
  `current_semester` int(11) NOT NULL DEFAULT 1,
  `accumulated_credits` int(11) NOT NULL DEFAULT 0,
  `weighted_average` decimal(4,2) NOT NULL DEFAULT 0.00,
  `academic_status` enum('regular','irregular','graduated','withdrawn','enrollment_reserved') NOT NULL DEFAULT 'regular',
  `admission_date` date NOT NULL,
  `graduation_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `students`
--

INSERT INTO `students` (`id`, `user_id`, `applicant_id`, `career_id`, `study_plan_id`, `code`, `current_semester`, `accumulated_credits`, `weighted_average`, `academic_status`, `admission_date`, `graduation_date`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 12, NULL, 1, 1, 'E2025-63482', 6, 0, 0.00, 'regular', '2025-06-05', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(2, 13, NULL, 1, 1, 'E2025-65399', 3, 0, 0.00, 'regular', '2023-02-10', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(3, 14, NULL, 1, 1, 'E2025-36253', 2, 0, 0.00, 'regular', '2025-01-26', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(4, 15, NULL, 1, 1, 'E2025-40034', 1, 0, 0.00, 'regular', '2024-12-23', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(5, 16, NULL, 1, 1, 'E2025-52034', 5, 0, 0.00, 'regular', '2025-06-21', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(6, 17, NULL, 1, 1, 'E2025-05371', 2, 0, 0.00, 'regular', '2024-04-28', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(7, 18, NULL, 1, 1, 'E2025-53705', 3, 0, 0.00, 'regular', '2025-08-06', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(8, 19, NULL, 1, 1, 'E2025-93277', 1, 0, 0.00, 'regular', '2023-06-04', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(9, 20, NULL, 1, 1, 'E2025-11996', 6, 0, 0.00, 'regular', '2023-10-25', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(10, 21, NULL, 1, 1, 'E2025-04406', 1, 0, 0.00, 'regular', '2024-07-30', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(11, 22, NULL, 1, 1, 'E2025-76953', 6, 0, 0.00, 'regular', '2025-09-04', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(12, 23, NULL, 1, 1, 'E2025-96728', 6, 0, 0.00, 'regular', '2025-10-20', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(13, 24, NULL, 1, 1, 'E2025-18149', 3, 0, 0.00, 'regular', '2024-02-29', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(14, 25, NULL, 1, 1, 'E2025-06338', 6, 0, 0.00, 'regular', '2025-01-09', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(15, 26, NULL, 1, 1, 'E2025-36802', 6, 0, 0.00, 'regular', '2023-02-10', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(16, 27, NULL, 1, 1, 'E2025-40043', 2, 0, 0.00, 'regular', '2025-09-06', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(17, 28, NULL, 1, 1, 'E2025-41644', 6, 0, 0.00, 'regular', '2023-03-07', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(18, 29, NULL, 1, 1, 'E2025-03272', 3, 0, 0.00, 'regular', '2025-05-02', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(19, 30, NULL, 1, 1, 'E2025-13167', 5, 0, 0.00, 'regular', '2023-10-31', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(20, 31, NULL, 1, 1, 'E2025-51595', 6, 0, 0.00, 'regular', '2023-02-04', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(21, 32, NULL, 1, 1, 'E2025-99184', 6, 0, 0.00, 'regular', '2023-01-24', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(22, 33, NULL, 1, 1, 'E2025-01384', 4, 0, 0.00, 'regular', '2023-12-02', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(23, 34, NULL, 1, 1, 'E2025-51400', 1, 0, 0.00, 'regular', '2025-03-04', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(24, 35, NULL, 1, 1, 'E2025-13519', 4, 0, 0.00, 'regular', '2023-10-28', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(25, 36, NULL, 1, 1, 'E2025-17175', 6, 0, 0.00, 'regular', '2025-06-14', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(26, 37, NULL, 1, 1, 'E2025-07582', 2, 0, 0.00, 'regular', '2022-12-27', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(27, 38, NULL, 1, 1, 'E2025-28223', 5, 0, 0.00, 'regular', '2023-02-23', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(28, 39, NULL, 1, 1, 'E2025-73957', 2, 0, 0.00, 'regular', '2025-09-13', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(29, 40, NULL, 1, 1, 'E2025-74977', 1, 0, 0.00, 'regular', '2023-11-07', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(30, 41, NULL, 1, 1, 'E2025-35005', 5, 0, 0.00, 'regular', '2025-10-13', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(31, 42, NULL, 1, 1, 'E2025-74603', 2, 0, 0.00, 'regular', '2023-03-08', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(32, 43, NULL, 1, 1, 'E2025-56916', 3, 0, 0.00, 'regular', '2024-05-06', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(33, 44, NULL, 1, 1, 'E2025-62981', 6, 0, 0.00, 'regular', '2025-09-14', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(34, 45, NULL, 1, 1, 'E2025-62335', 1, 0, 0.00, 'regular', '2024-05-16', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(35, 46, NULL, 1, 1, 'E2025-98319', 6, 0, 0.00, 'regular', '2025-10-27', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(36, 47, NULL, 1, 1, 'E2025-75493', 4, 0, 0.00, 'regular', '2022-12-31', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(37, 48, NULL, 1, 1, 'E2025-50765', 4, 0, 0.00, 'regular', '2025-07-13', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(38, 49, NULL, 1, 1, 'E2025-79217', 3, 0, 0.00, 'regular', '2023-08-17', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(39, 50, NULL, 1, 1, 'E2025-69020', 6, 0, 0.00, 'regular', '2024-02-14', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(40, 51, NULL, 1, 1, 'E2025-93353', 6, 0, 0.00, 'regular', '2025-07-17', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(41, 52, NULL, 1, 1, 'E2025-98571', 3, 0, 0.00, 'regular', '2023-01-23', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(42, 53, NULL, 1, 1, 'E2025-48556', 6, 0, 0.00, 'regular', '2023-03-19', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(43, 54, NULL, 1, 1, 'E2025-87147', 4, 0, 0.00, 'regular', '2023-01-28', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(44, 55, NULL, 1, 1, 'E2025-35532', 5, 0, 0.00, 'regular', '2025-05-05', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(45, 56, NULL, 1, 1, 'E2025-45558', 3, 0, 0.00, 'regular', '2024-07-14', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(46, 57, NULL, 1, 1, 'E2025-68890', 6, 0, 0.00, 'regular', '2024-10-01', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(47, 58, NULL, 1, 1, 'E2025-82059', 3, 0, 0.00, 'regular', '2024-08-17', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(48, 59, NULL, 1, 1, 'E2025-82926', 5, 0, 0.00, 'regular', '2023-02-01', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(49, 60, NULL, 1, 1, 'E2025-94065', 2, 0, 0.00, 'regular', '2025-07-01', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(50, 61, NULL, 1, 1, 'E2025-35247', 2, 0, 0.00, 'regular', '2024-10-28', NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07', NULL),
(51, 73, 12, 1, 1, 'E2025-99185', 1, 0, 0.00, 'regular', '2025-11-11', NULL, '2025-11-11 08:00:55', '2025-11-11 08:00:55', NULL),
(52, 63, 2, 1, 1, 'E2025-99186', 1, 0, 0.00, 'regular', '2025-11-11', NULL, '2025-11-11 08:01:16', '2025-11-11 08:01:16', NULL),
(53, 81, NULL, 1, 1, 'es2500', 1, 0, 0.00, 'regular', '2025-11-10', NULL, '2025-11-11 08:56:55', '2025-11-11 08:56:55', NULL),
(54, 64, 3, 1, 1, 'E2025-99187', 1, 0, 0.00, 'regular', '2025-11-13', NULL, '2025-11-13 22:08:11', '2025-11-13 22:08:11', NULL);

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
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `student_payments`
--

INSERT INTO `student_payments` (`id`, `student_id`, `payment_concept_id`, `academic_period_id`, `registered_by_user_id`, `original_amount`, `discount_amount`, `final_amount`, `due_date`, `payment_date`, `transaction_number`, `payment_method`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, NULL, 120.00, 0.00, 120.00, '2025-03-10', NULL, NULL, NULL, 'pending', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09'),
(2, 2, 1, 1, NULL, 120.00, 0.00, 120.00, '2025-03-10', NULL, NULL, NULL, 'pending', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09'),
(3, 3, 1, 1, NULL, 120.00, 0.00, 120.00, '2025-03-10', NULL, NULL, NULL, 'pending', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09'),
(4, 4, 1, 1, NULL, 120.00, 0.00, 120.00, '2025-03-10', NULL, NULL, NULL, 'pending', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09'),
(5, 5, 1, 1, NULL, 120.00, 0.00, 120.00, '2025-03-10', NULL, NULL, NULL, 'pending', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09'),
(6, 6, 1, 1, NULL, 120.00, 0.00, 120.00, '2025-03-10', NULL, NULL, NULL, 'pending', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09'),
(7, 7, 1, 1, NULL, 120.00, 0.00, 120.00, '2025-03-10', NULL, NULL, NULL, 'pending', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09'),
(8, 8, 1, 1, NULL, 120.00, 0.00, 120.00, '2025-03-10', NULL, NULL, NULL, 'pending', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09'),
(9, 9, 1, 1, NULL, 120.00, 0.00, 120.00, '2025-03-10', NULL, NULL, NULL, 'pending', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09'),
(10, 10, 1, 1, NULL, 120.00, 0.00, 120.00, '2025-03-10', NULL, NULL, NULL, 'pending', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09'),
(11, 11, 1, 1, NULL, 120.00, 0.00, 120.00, '2025-03-10', NULL, NULL, NULL, 'pending', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09'),
(12, 12, 1, 1, NULL, 120.00, 0.00, 120.00, '2025-03-10', NULL, NULL, NULL, 'pending', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09'),
(13, 13, 1, 1, NULL, 120.00, 0.00, 120.00, '2025-03-10', NULL, NULL, NULL, 'pending', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09'),
(14, 14, 1, 1, NULL, 120.00, 0.00, 120.00, '2025-03-10', NULL, NULL, NULL, 'pending', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09'),
(15, 15, 1, 1, NULL, 120.00, 0.00, 120.00, '2025-03-10', NULL, NULL, NULL, 'pending', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09'),
(16, 16, 1, 1, NULL, 120.00, 0.00, 120.00, '2025-03-10', NULL, NULL, NULL, 'pending', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09'),
(17, 17, 1, 1, NULL, 120.00, 0.00, 120.00, '2025-03-10', NULL, NULL, NULL, 'pending', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09'),
(18, 18, 1, 1, NULL, 120.00, 0.00, 120.00, '2025-03-10', NULL, NULL, NULL, 'pending', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09'),
(19, 19, 1, 1, NULL, 120.00, 0.00, 120.00, '2025-03-10', NULL, NULL, NULL, 'pending', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09'),
(20, 20, 1, 1, NULL, 120.00, 0.00, 120.00, '2025-03-10', NULL, NULL, NULL, 'pending', NULL, '2025-11-11 06:39:09', '2025-11-11 06:39:09');

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
(1, 1, 'APSTI-2021', 'Plan de Estudios APSTI', '2021', '2021-01-01', NULL, 114, 2880, 'R.D. 001-2021', 'active', '2025-11-11 06:39:05', '2025-11-11 06:39:05', NULL);

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
(1, 'minimum_passing_grade', '13', 'Nota mínima aprobatoria.', 'integer', 'grades', 1, '2025-11-11 06:39:05', '2025-11-11 06:39:05'),
(2, 'minimum_attendance_percentage', '70', 'Porcentaje mínimo de asistencia.', 'integer', 'attendance', 1, '2025-11-11 06:39:05', '2025-11-11 06:39:05');

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
(1, 2, 1, 'T-12860', 'Mag.', 'Agricultural Manager', NULL, NULL, 'permanent', '2025-04-24', NULL, 'active', '2025-11-11 06:39:06', '2025-11-11 06:39:06', NULL),
(2, 3, 1, 'T-08078', 'Mag.', 'Military Officer', NULL, NULL, 'permanent', '2022-09-04', NULL, 'active', '2025-11-11 06:39:06', '2025-11-11 06:39:06', NULL),
(3, 4, 1, 'T-12643', 'Mag.', 'Well and Core Drill Operator', NULL, NULL, 'permanent', '2024-03-08', NULL, 'active', '2025-11-11 06:39:06', '2025-11-11 06:39:06', NULL),
(4, 5, 1, 'T-93146', 'Lic.', 'Welfare Eligibility Clerk', NULL, NULL, 'permanent', '2017-05-07', NULL, 'active', '2025-11-11 06:39:06', '2025-11-11 06:39:06', NULL),
(5, 6, 1, 'T-44103', 'Mag.', 'Interaction Designer', NULL, NULL, 'contracted', '1992-04-26', NULL, 'active', '2025-11-11 06:39:06', '2025-11-11 06:39:06', NULL),
(6, 7, 1, 'T-29155', 'Mag.', 'MARCOM Manager', NULL, NULL, 'permanent', '1985-08-07', NULL, 'active', '2025-11-11 06:39:06', '2025-11-11 06:39:06', NULL),
(7, 8, 1, 'T-53747', 'Lic.', 'Media and Communication Worker', NULL, NULL, 'permanent', '1990-01-21', NULL, 'active', '2025-11-11 06:39:06', '2025-11-11 06:39:06', NULL),
(8, 9, 1, 'T-65448', 'Lic.', 'MARCOM Director', NULL, NULL, 'contracted', '2014-11-29', NULL, 'active', '2025-11-11 06:39:06', '2025-11-11 06:39:06', NULL),
(9, 10, 1, 'T-32090', 'Dr.', 'Pastry Chef', NULL, NULL, 'permanent', '2004-03-10', NULL, 'active', '2025-11-11 06:39:06', '2025-11-11 06:39:06', NULL),
(10, 11, 1, 'T-35860', 'Lic.', 'Plating Operator OR Coating Machine Operator', NULL, NULL, 'contracted', '1979-11-04', NULL, 'active', '2025-11-11 06:39:06', '2025-11-11 06:39:06', NULL),
(11, 79, 1, 'd25001', 'Dr', 'Desarrollo de Software ', NULL, NULL, 'permanent', NULL, 'friday', 'active', '2025-11-11 08:31:21', '2025-11-11 08:31:21', NULL),
(12, 80, 1, 'D250011', 'Dr', 'Ingeniero', NULL, NULL, 'permanent', NULL, 'monday', 'active', '2025-11-11 08:37:51', '2025-11-11 08:37:51', NULL);

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

--
-- Volcado de datos para la tabla `teacher_assignments`
--

INSERT INTO `teacher_assignments` (`id`, `teacher_id`, `didactic_unit_id`, `academic_period_id`, `shift_id`, `section`, `max_capacity`, `current_enrolled`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 1, 1, 'A', 30, 21, 'active', '2025-11-11 06:39:09', '2025-11-11 08:57:34', NULL),
(2, 2, 2, 1, 1, 'A', 30, 21, 'active', '2025-11-11 06:39:09', '2025-11-11 08:57:34', NULL);

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

--
-- Volcado de datos para la tabla `tutorings`
--

INSERT INTO `tutorings` (`id`, `student_id`, `teacher_id`, `tutoring_date`, `tutoring_type`, `reason`, `session_development`, `agreements_commitments`, `follow_up_required`, `status`, `created_at`, `updated_at`) VALUES
(1, 5, 7, '2025-11-11 03:12:00', 'academic', 'csdcsc kjasdkajdfaf', '', '', 1, 'rescheduled', '2025-11-11 08:13:20', '2025-11-11 08:13:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
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

INSERT INTO `users` (`id`, `name`, `email`, `user_type`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `current_team_id`, `profile_photo_path`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', 'admin@educon.edu.pe', 'student', '2025-11-11 06:39:05', '$2y$12$8cy33Rm6g4EAVUDDEsHsJuDTCMeonnGD2NokUkoF6cc4atbY2dUFy', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-11 06:39:05', '2025-11-11 06:39:05'),
(2, 'Merle Haley', 'yfadel@example.com', 'student', '2025-11-11 06:39:06', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, '4Y3BGjoyxM', NULL, NULL, '2025-11-11 06:39:06', '2025-11-11 06:39:06'),
(3, 'Doug Becker', 'anthony29@example.com', 'student', '2025-11-11 06:39:06', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'CIqaHFkSh3', NULL, NULL, '2025-11-11 06:39:06', '2025-11-11 06:39:06'),
(4, 'Dr. Luther Reichert IV', 'christine.wyman@example.com', 'student', '2025-11-11 06:39:06', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 't559jTTgFh', NULL, NULL, '2025-11-11 06:39:06', '2025-11-11 06:39:06'),
(5, 'Betty Hermann', 'rico.beahan@example.net', 'student', '2025-11-11 06:39:06', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, '2T06MnduZQ', NULL, NULL, '2025-11-11 06:39:06', '2025-11-11 06:39:06'),
(6, 'Ms. Kirstin Volkman PhD', 'ggislason@example.net', 'student', '2025-11-11 06:39:06', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'xAOKfnFWKq', NULL, NULL, '2025-11-11 06:39:06', '2025-11-11 06:39:06'),
(7, 'Jeremie Kertzmann', 'jstroman@example.org', 'student', '2025-11-11 06:39:06', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'XAR1rkcALs', NULL, NULL, '2025-11-11 06:39:06', '2025-11-11 06:39:06'),
(8, 'Delbert McGlynn', 'dennis08@example.org', 'student', '2025-11-11 06:39:06', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'aYZzOqZYe2', NULL, NULL, '2025-11-11 06:39:06', '2025-11-11 06:39:06'),
(9, 'Oren Kuhic', 'meredith57@example.com', 'student', '2025-11-11 06:39:06', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'MzdomXNeYQ', NULL, NULL, '2025-11-11 06:39:06', '2025-11-11 06:39:06'),
(10, 'Sebastian Raynor', 'emarks@example.net', 'student', '2025-11-11 06:39:06', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'LK7icTs2Ai', NULL, NULL, '2025-11-11 06:39:06', '2025-11-11 06:39:06'),
(11, 'Reymundo Lesch', 'merl62@example.net', 'student', '2025-11-11 06:39:06', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'JB4c6u4FpJ', NULL, NULL, '2025-11-11 06:39:06', '2025-11-11 06:39:06'),
(12, 'Norma Simonis', 'wrodriguez@example.com', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'KotFmKrn3z', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(13, 'Mr. Dallas Crooks', 'lavada79@example.com', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'oTeGOVivtS', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(14, 'Avery Hettinger', 'rosetta.hartmann@example.org', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'GwDURKjtpJ', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(15, 'Dr. Orval Steuber IV', 'schoen.rigoberto@example.org', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'x4GsJ3JGen', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(16, 'Mrs. Alanna Carter', 'leannon.ricardo@example.net', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'NEerWeuLu4', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(17, 'Annette Torphy', 'meda87@example.com', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'wroCIszxFZ', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(18, 'Prof. Louisa Effertz', 'darion36@example.org', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'i4eA9anGap', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(19, 'Dr. Arno Rowe', 'quigley.reid@example.net', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'oz8lq7aSp0', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(20, 'Cleta Kassulke', 'okoss@example.org', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'TLldY0cMhY', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(21, 'Ms. Herminia Bode', 'mfeeney@example.org', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'SuEeu8M4FU', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(22, 'Gerhard Lebsack', 'gusikowski.jermey@example.net', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, '12VOkyyWxN', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(23, 'Baron Greenfelder', 'jhintz@example.net', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, '9uBfJzi245', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(24, 'Dr. Virgil Rogahn', 'rosamond24@example.net', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'bOHsrzdpoZ', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(25, 'Dr. Wilburn Williamson Jr.', 'paucek.rachael@example.org', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'YikJyscbo9', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(26, 'Xzavier Carroll', 'sanford34@example.com', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'nT381EAMpw', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(27, 'Weldon Sporer', 'rowe.annette@example.com', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'h6O8FDooAH', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(28, 'Ayden Boehm I', 'bdavis@example.org', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'yoPQXqlCud', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(29, 'Darion Anderson MD', 'blick.eriberto@example.com', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'RhyNhUnkxo', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(30, 'Alexys Heathcote', 'raphaelle37@example.org', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, '1BKaqYEjOlcBn0IUtV1gnf70llRz85UwCF0WsuDsYnULtYLLqkbVYa1097WY', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(31, 'Gay McGlynn', 'cdeckow@example.com', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'NDbehIMbmH', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(32, 'Willa Veum', 'bradtke.kacey@example.org', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'e8I4YELLkw', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(33, 'Elias Ortiz', 'kody28@example.com', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'J49Qm5SshF', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(34, 'Mrs. Nia Zemlak', 'tatyana.morissette@example.net', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, '2A1x6ilewu', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(35, 'Prof. Gianni Vandervort Jr.', 'nathen.kovacek@example.com', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'zpppZKyrI8', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(36, 'Edgardo Weimann', 'hamill.brad@example.com', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'wySgPPg0Zb', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(37, 'Mrs. Herta Kihn', 'lisette99@example.net', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'Ka9rIq9nnD', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(38, 'Dr. Reva Terry', 'rylee54@example.com', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'gdKJn4flza', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(39, 'Una Crist', 'gillian64@example.net', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'NrUrxapTbi', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(40, 'Dr. Riley O\'Conner IV', 'wolf.jaycee@example.com', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'okwjlnt2ID', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(41, 'Thora Goyette', 'angelita55@example.org', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'byKieaZIJQ', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(42, 'Marc Steuber', 'ygerlach@example.com', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'GWp3mSUmnN', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(43, 'Gardner Collins', 'corwin.adriana@example.net', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'G1Xe9r5Lmx', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(44, 'Lambert Dach', 'naomie.hermiston@example.org', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'vd4FhbU8hb', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(45, 'Megane West', 'oprohaska@example.org', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'UFFpcL0YsT', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(46, 'Mr. Willy Mohr V', 'assunta19@example.org', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'JGyaoamdY2', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(47, 'Alicia Jast II', 'xtillman@example.com', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'm4sy7efEVc', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(48, 'Elaina Gislason', 'isabell.buckridge@example.net', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'KKewHpNdN7', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(49, 'Josh Kshlerin', 'rhea.conroy@example.net', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'Anknu7Zq4a', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(50, 'Samson Gulgowski', 'ywilderman@example.com', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'jThyT2pJ5u', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(51, 'Manley Mosciski', 'agreen@example.com', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'qtYq4RfwhK', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(52, 'Keyon Ruecker', 'schuppe.ariane@example.org', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'MzSHWRHlQv', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(53, 'Brycen Pacocha', 'ophelia.dooley@example.com', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, '6bLkkxSfMu', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(54, 'Dejon Mosciski', 'hildegard77@example.org', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'zsXGsz1xAg', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(55, 'Freda Parisian', 'kjacobi@example.com', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'va4D6mbOaG', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(56, 'Ms. Katharina Steuber', 'bertrand64@example.com', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'fdp7TtxPZX', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(57, 'Mohammed Gottlieb', 'shany.halvorson@example.net', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, '91TwjHRqt9', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(58, 'Kylee Kozey', 'vheidenreich@example.com', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, '1bI9AhPDYg', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(59, 'Mr. Danial Volkman', 'dickinson.timothy@example.org', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'hFjpNvHkow', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(60, 'Rocky Bradtke', 'wbatz@example.net', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'WNdUknplaz', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(61, 'Stanley DuBuque IV', 'dietrich.lacey@example.com', 'student', '2025-11-11 06:39:07', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'E4OOJXZElX', NULL, NULL, '2025-11-11 06:39:07', '2025-11-11 06:39:07'),
(62, 'Sheridan Konopelski', 'josiane.barton@example.org', 'student', '2025-11-11 06:39:08', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'YOlKPVcVGs', NULL, NULL, '2025-11-11 06:39:08', '2025-11-11 06:39:08'),
(63, 'Casimer Cormier', 'jean.smitham@example.org', 'student', '2025-11-11 06:39:08', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'fXzz7M3s1D', NULL, NULL, '2025-11-11 06:39:08', '2025-11-11 06:39:08'),
(64, 'Kaylin Wintheiser', 'mfritsch@example.com', 'student', '2025-11-11 06:39:08', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'g07DMwJn9K', NULL, NULL, '2025-11-11 06:39:08', '2025-11-11 06:39:08'),
(65, 'Enoch Kulas', 'giovanny94@example.com', 'student', '2025-11-11 06:39:08', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, '0bhoqX4oF0', NULL, NULL, '2025-11-11 06:39:08', '2025-11-11 06:39:08'),
(66, 'Alfredo Macejkovic', 'ufahey@example.org', 'student', '2025-11-11 06:39:08', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'aA6AOEYvcu', NULL, NULL, '2025-11-11 06:39:08', '2025-11-11 06:39:08'),
(67, 'Prof. Camille White', 'hprohaska@example.net', 'student', '2025-11-11 06:39:08', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, '8NM7yF1LPR', NULL, NULL, '2025-11-11 06:39:08', '2025-11-11 06:39:08'),
(68, 'Jackeline Treutel II', 'adelbert.rosenbaum@example.com', 'student', '2025-11-11 06:39:08', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'VxBZZq8V40', NULL, NULL, '2025-11-11 06:39:08', '2025-11-11 06:39:08'),
(69, 'Birdie Wolf', 'xnolan@example.net', 'student', '2025-11-11 06:39:08', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'C187Y1h9Bh', NULL, NULL, '2025-11-11 06:39:08', '2025-11-11 06:39:08'),
(70, 'Prof. Travis Hamill II', 'leannon.oda@example.net', 'student', '2025-11-11 06:39:08', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'mI8wNlOCSm', NULL, NULL, '2025-11-11 06:39:08', '2025-11-11 06:39:08'),
(71, 'Zakary Ankunding', 'maximus.purdy@example.com', 'student', '2025-11-11 06:39:08', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'ddqSeguiLb', NULL, NULL, '2025-11-11 06:39:08', '2025-11-11 06:39:08'),
(72, 'Miles Berge', 'corwin.arch@example.com', 'student', '2025-11-11 06:39:08', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'bY4gVhhVbo', NULL, NULL, '2025-11-11 06:39:08', '2025-11-11 06:39:08'),
(73, 'Amber Wilderman', 'wilkinson.bessie@example.net', 'student', '2025-11-11 06:39:08', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'nXej9R5kML', NULL, NULL, '2025-11-11 06:39:08', '2025-11-11 06:39:08'),
(74, 'Andreanne Wyman', 'ybauch@example.com', 'student', '2025-11-11 06:39:08', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'jyg7OJlF3J', NULL, NULL, '2025-11-11 06:39:08', '2025-11-11 06:39:08'),
(75, 'Alanis Ondricka Sr.', 'ccarroll@example.com', 'student', '2025-11-11 06:39:08', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, 'WzsPKPJLad', NULL, NULL, '2025-11-11 06:39:08', '2025-11-11 06:39:08'),
(76, 'David Oberbrunner', 'brown97@example.net', 'student', '2025-11-11 06:39:08', '$2y$12$qg8PmtPQajpIaHiq61ORu.P.cxOuh8IfadUJceqSyD2883PV8s7fG', NULL, NULL, NULL, '5W4hVuCnTS', NULL, NULL, '2025-11-11 06:39:08', '2025-11-11 06:39:08'),
(78, 'Gerar', 'gcauna@unap.edu.pe', 'student', NULL, '$2y$12$ehWETgQdguBeU4C3K0O1nO25X15x52eSFdfzsALFkAmbj1i..ikxO', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-11 06:47:12', '2025-11-11 06:47:12'),
(79, 'Gerardino Cauna', 'gcaunah@unap.edu.pe', 'student', NULL, '$2y$12$Tl/8rvVuhxuE3FYuDGtvLeJ9gou.MBFCFpcanyFYEy5BQ82cvFpHy', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-11 08:31:21', '2025-11-11 08:31:21'),
(80, 'Gerardino Cauna', 'gcaunah406@unap.edu.pe', 'student', NULL, '$2y$12$py6P7kYvNlJqLhIce1lJreIUIeUr02iu9nov/QRp467fSXr9g6rj2', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-11 08:37:51', '2025-11-11 08:37:51'),
(81, 'celia cari', 'celia@admin.com', 'student', NULL, '$2y$12$kAN8tMzvcb.H2oSGzkYvYuIQcFgXIMpXSiWszTRd8.D9zrz2FzUHK', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-11 08:56:55', '2025-11-11 08:56:55');

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
  ADD KEY `applicants_career_id_foreign` (`career_id`),
  ADD KEY `applicants_study_plan_id_foreign` (`study_plan_id`);

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
  ADD KEY `enrollment_reserves_student_id_foreign` (`student_id`);

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
  ADD KEY `students_applicant_id_foreign` (`applicant_id`),
  ADD KEY `students_career_id_foreign` (`career_id`),
  ADD KEY `students_study_plan_id_foreign` (`study_plan_id`);

--
-- Indices de la tabla `student_payments`
--
ALTER TABLE `student_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_payments_student_id_foreign` (`student_id`),
  ADD KEY `student_payments_payment_concept_id_foreign` (`payment_concept_id`),
  ADD KEY `student_payments_academic_period_id_foreign` (`academic_period_id`),
  ADD KEY `student_payments_registered_by_user_id_foreign` (`registered_by_user_id`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
-- AUTO_INCREMENT de la tabla `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `applicants`
--
ALTER TABLE `applicants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

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
-- AUTO_INCREMENT de la tabla `didactic_units`
--
ALTER TABLE `didactic_units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `enrollment_reserves`
--
ALTER TABLE `enrollment_reserves`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT de la tabla `merit_rankings`
--
ALTER TABLE `merit_rankings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de la tabla `modules`
--
ALTER TABLE `modules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `payment_concepts`
--
ALTER TABLE `payment_concepts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `shifts`
--
ALTER TABLE `shifts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT de la tabla `student_payments`
--
ALTER TABLE `student_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `teacher_assignments`
--
ALTER TABLE `teacher_assignments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tutorings`
--
ALTER TABLE `tutorings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

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
-- Filtros para la tabla `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_published_by_user_id_foreign` FOREIGN KEY (`published_by_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `applicants`
--
ALTER TABLE `applicants`
  ADD CONSTRAINT `applicants_career_id_foreign` FOREIGN KEY (`career_id`) REFERENCES `careers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applicants_study_plan_id_foreign` FOREIGN KEY (`study_plan_id`) REFERENCES `study_plans` (`id`) ON DELETE CASCADE,
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
-- Filtros para la tabla `certificates`
--
ALTER TABLE `certificates`
  ADD CONSTRAINT `certificates_issued_by_user_id_foreign` FOREIGN KEY (`issued_by_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `certificates_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `certificates_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `students_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `students_career_id_foreign` FOREIGN KEY (`career_id`) REFERENCES `careers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `students_study_plan_id_foreign` FOREIGN KEY (`study_plan_id`) REFERENCES `study_plans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `students_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `student_payments`
--
ALTER TABLE `student_payments`
  ADD CONSTRAINT `student_payments_academic_period_id_foreign` FOREIGN KEY (`academic_period_id`) REFERENCES `academic_periods` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `student_payments_payment_concept_id_foreign` FOREIGN KEY (`payment_concept_id`) REFERENCES `payment_concepts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_payments_registered_by_user_id_foreign` FOREIGN KEY (`registered_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `student_payments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
