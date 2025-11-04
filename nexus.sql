-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 29/10/2025 às 00:18
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `nexus`
--
CREATE DATABASE IF NOT EXISTS `nexus` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `nexus`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `reservation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `route_id` int(11) NOT NULL,
  `attendance_date` date NOT NULL,
  `status` enum('present','absent','cancelled','late','early') NOT NULL,
  `check_in_time` timestamp NULL DEFAULT NULL,
  `check_out_time` timestamp NULL DEFAULT NULL,
  `notes` varchar(500) DEFAULT NULL,
  `recorded_by` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `feedback` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `maintenance_records`
--

CREATE TABLE `maintenance_records` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `maintenance_type` enum('preventive','corrective','inspection') NOT NULL,
  `description` varchar(500) NOT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `maintenance_date` date NOT NULL,
  `next_maintenance_date` date DEFAULT NULL,
  `technician` varchar(100) DEFAULT NULL,
  `status` enum('scheduled','in_progress','completed','cancelled') DEFAULT 'completed',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `message` varchar(500) NOT NULL,
  `type` enum('info','warning','success','reservation','cancellation','waiting_list','system') NOT NULL,
  `priority` enum('low','medium','high','urgent') DEFAULT 'low',
  `read_status` tinyint(1) DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `action_url` varchar(200) DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `qr_codes`
--

CREATE TABLE `qr_codes` (
  `id` int(11) NOT NULL,
  `reservation_id` int(11) NOT NULL,
  `code` varchar(100) NOT NULL,
  `generated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `status` enum('active','used','expired','cancelled') DEFAULT 'active',
  `scanned_by` int(11) DEFAULT NULL,
  `scan_location` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `route_id` int(11) NOT NULL,
  `seat_id` int(11) DEFAULT NULL,
  `reservation_date` date NOT NULL,
  `status` enum('active','cancelled','completed','waiting','no_show') NOT NULL DEFAULT 'active',
  `qr_code` varchar(100) DEFAULT NULL,
  `confirmation_code` varchar(10) DEFAULT NULL,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `check_in_time` timestamp NULL DEFAULT NULL,
  `cancellation_reason` varchar(200) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `reservations`
--

INSERT INTO `reservations` (`id`, `user_id`, `route_id`, `seat_id`, `reservation_date`, `status`, `qr_code`, `confirmation_code`, `confirmed_at`, `check_in_time`, `cancellation_reason`, `created_at`, `updated_at`) VALUES
(1, 2, 4, NULL, '2025-10-28', 'active', NULL, NULL, NULL, NULL, NULL, '2025-10-28 22:38:58', '2025-10-28 22:38:58'),
(2, 3, 5, NULL, '2025-10-28', 'active', NULL, NULL, NULL, NULL, NULL, '2025-10-28 22:38:58', '2025-10-28 22:38:58');

-- --------------------------------------------------------

--
-- Estrutura para tabela `routes`
--

CREATE TABLE `routes` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `origin` varchar(100) DEFAULT NULL,
  `destination` varchar(100) NOT NULL,
  `route_date` date NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `departure_time` time NOT NULL,
  `pickup_points` text NOT NULL,
  `max_capacity` int(11) NOT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `current_occupancy` int(11) DEFAULT 0,
  `status` enum('scheduled','in_progress','completed','cancelled') DEFAULT 'scheduled',
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `arrival_time` time DEFAULT NULL,
  `description` text DEFAULT NULL,
  `days_of_week` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `routes`
--

INSERT INTO `routes` (`id`, `name`, `origin`, `destination`, `route_date`, `schedule_id`, `departure_time`, `pickup_points`, `max_capacity`, `driver_id`, `current_occupancy`, `status`, `active`, `created_at`, `updated_at`, `arrival_time`, `description`, `days_of_week`) VALUES
(4, 'danoca', 'unoesc', 'são sebastião', '2025-10-27', 1, '21:00:00', 'Pontos de parada não informados', 40, 5, 0, 'scheduled', 1, '2025-10-26 18:45:34', '2025-10-28 22:37:38', '22:20:00', 'ygfds', 'mon,tue,wed,thu,fri'),
(5, 'Biologia', 'unoesc', 'são sebastião', '2025-10-27', 1, '12:30:00', 'Pontos de parada não informados', 40, 5, 0, 'scheduled', 1, '2025-10-27 17:57:25', '2025-10-28 22:37:38', '22:30:00', 'ygfds', 'mon,tue,fri,sun');

-- --------------------------------------------------------

--
-- Estrutura para tabela `schedules`
--

CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `departure_time` time NOT NULL,
  `return_time` time NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `max_capacity_multiplier` decimal(3,2) DEFAULT 1.00,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `schedules`
--

INSERT INTO `schedules` (`id`, `name`, `departure_time`, `return_time`, `description`, `max_capacity_multiplier`, `active`, `created_at`) VALUES
(1, 'Manhã', '07:00:00', '12:00:00', 'Turno da manhã', 1.00, 1, '2025-10-26 18:03:33'),
(2, 'Tarde', '13:00:00', '18:00:00', 'Turno da tarde', 1.00, 1, '2025-10-26 18:03:33'),
(3, 'Noite', '19:00:00', '23:00:00', 'Turno da noite', 1.00, 1, '2025-10-26 18:03:33');

-- --------------------------------------------------------

--
-- Estrutura para tabela `seats`
--

CREATE TABLE `seats` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `seat_number` varchar(5) NOT NULL,
  `position_row` int(11) NOT NULL,
  `position_column` char(1) NOT NULL,
  `seat_type` enum('regular','priority','disabled') DEFAULT 'regular',
  `x_position` int(11) DEFAULT NULL,
  `y_position` int(11) DEFAULT NULL,
  `available` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `system_logs`
--

CREATE TABLE `system_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `table_affected` varchar(50) DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(500) DEFAULT NULL,
  `severity` enum('debug','info','warning','error','critical') DEFAULT 'info',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `matricula` varchar(20) DEFAULT NULL,
  `curso` varchar(50) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `emergency_contact` varchar(15) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `user_type` enum('student','driver','admin') NOT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `email_verified` tinyint(1) DEFAULT 0,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `matricula`, `curso`, `phone`, `address`, `emergency_contact`, `password_hash`, `user_type`, `profile_photo`, `email_verified`, `active`, `created_at`, `updated_at`, `last_login`) VALUES
(1, 'Administrador', 'admin@nexus.com', NULL, NULL, NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NULL, 0, 1, '2025-10-26 18:03:33', '2025-10-27 23:04:33', '2025-10-27 23:04:33'),
(2, 'Biologia', 'bchksdsbhsb@gmail.coms', '121212', 'Arquitetura e Urbanismo', '95658152656', 'sxdcfvgbhn, 123', NULL, '$2y$10$qOcp52v7Wf.5eUd1lzRmqOxIZx6sPkbiNKeqAz1ETwhyxY1mXfOiC', 'student', NULL, 0, 1, '2025-10-27 17:49:16', '2025-10-27 17:50:09', '2025-10-27 17:50:09'),
(3, 'Biologia', 'admin@gmail.com', NULL, NULL, '95658152656', NULL, NULL, '$2y$10$anYuY695/P/jAucd9qtzB.ql04NBdPiLul31AKm0f6Ns0yxqFPaO.', 'admin', NULL, 1, 1, '2025-10-27 18:36:01', '2025-10-27 22:24:36', '2025-10-27 22:24:36'),
(4, 'Biologia', 'bchksdsbhsb@gmail.com', '12345', 'Arquitetura e Urbanismo', '95658152656', 'sxdcfvgbhn, 123', NULL, '$2y$10$aVDhihtPzHHGggy7MIfEcedF6VrAnqYDsnbAHGpHbMlsyw/F4F3Fq', 'student', NULL, 0, 1, '2025-10-27 22:32:19', '2025-10-28 22:50:18', '2025-10-28 22:50:18'),
(5, 'jose pereira', 'jose123@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$10$8YsMqgChVim5p/rQZ7pFN.MBb6DfzmyEaZ.HW.b2dzIaKumvNb51.', 'driver', NULL, 0, 1, '2025-10-27 22:37:33', '2025-10-28 22:24:53', '2025-10-28 22:24:53');

-- --------------------------------------------------------

--
-- Estrutura para tabela `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `plate` varchar(10) NOT NULL,
  `model` varchar(50) NOT NULL,
  `brand` varchar(50) NOT NULL,
  `year` int(11) NOT NULL,
  `capacity` int(11) NOT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `mileage` int(11) DEFAULT 0,
  `fuel_type` varchar(20) DEFAULT NULL,
  `status` enum('Ativo','Inativo','Em Manutenção') DEFAULT 'Ativo',
  `last_maintenance` date NOT NULL,
  `next_maintenance` date DEFAULT NULL,
  `chassis_number` varchar(17) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `vehicles`
--

INSERT INTO `vehicles` (`id`, `type`, `plate`, `model`, `brand`, `year`, `capacity`, `driver_id`, `mileage`, `fuel_type`, `status`, `last_maintenance`, `next_maintenance`, `chassis_number`, `notes`, `active`, `created_at`, `updated_at`) VALUES
(1, 'Van', 'LLL1234', 'asdfg', 'asdfcghj', 2011, 40, 5, 121212, 'Diesel', 'Ativo', '2025-02-25', '2027-02-25', '9BWZZZ377VT004251', '', 1, '2025-10-27 18:07:26', '2025-10-28 22:36:30');

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `v_active_reservations`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `v_active_reservations` (
`id` int(11)
,`student_name` varchar(100)
,`email` varchar(100)
,`matricula` varchar(20)
,`curso` varchar(50)
,`phone` varchar(15)
,`route_name` varchar(100)
,`origin` varchar(100)
,`destination` varchar(100)
,`schedule_name` varchar(50)
,`departure_time` time
,`return_time` time
,`seat_number` varchar(5)
,`reservation_date` date
,`status` enum('active','cancelled','completed','waiting','no_show')
,`confirmation_code` varchar(10)
,`created_at` timestamp
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `v_admin_dashboard`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `v_admin_dashboard` (
`total_students` bigint(21)
,`total_drivers` bigint(21)
,`total_vehicles` bigint(21)
,`today_routes` bigint(21)
,`today_reservations` bigint(21)
,`today_cancellations` bigint(21)
,`avg_occupancy_week` decimal(21,8)
,`pending_maintenance` bigint(21)
,`unread_notifications_today` bigint(21)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `v_attendance_stats`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `v_attendance_stats` (
`user_id` int(11)
,`name` varchar(100)
,`matricula` varchar(20)
,`curso` varchar(50)
,`total_reservations` bigint(21)
,`present_count` decimal(22,0)
,`absent_count` decimal(22,0)
,`late_count` decimal(22,0)
,`cancelled_count` decimal(22,0)
,`attendance_percentage` decimal(5,2)
,`average_rating` decimal(7,6)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `v_route_occupancy`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `v_route_occupancy` (
`route_id` int(11)
,`route_name` varchar(100)
,`origin` varchar(100)
,`destination` varchar(100)
,`route_date` date
,`schedule_name` varchar(50)
,`departure_time` time
,`vehicle_plate` varchar(10)
,`vehicle_model` varchar(50)
,`max_capacity` int(11)
,`current_occupancy` int(11)
,`occupancy_percentage` decimal(5,2)
,`available_seats` bigint(12)
,`status` enum('scheduled','in_progress','completed','cancelled')
);

-- --------------------------------------------------------

--
-- Estrutura para tabela `waiting_list`
--

CREATE TABLE `waiting_list` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `route_id` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `priority_score` int(11) DEFAULT 0,
  `notification_sent` tinyint(1) DEFAULT 0,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para view `v_active_reservations`
--
DROP TABLE IF EXISTS `v_active_reservations`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_active_reservations`  AS SELECT `r`.`id` AS `id`, `u`.`name` AS `student_name`, `u`.`email` AS `email`, `u`.`matricula` AS `matricula`, `u`.`curso` AS `curso`, `u`.`phone` AS `phone`, `rt`.`name` AS `route_name`, `rt`.`origin` AS `origin`, `rt`.`destination` AS `destination`, `s`.`name` AS `schedule_name`, `s`.`departure_time` AS `departure_time`, `s`.`return_time` AS `return_time`, `st`.`seat_number` AS `seat_number`, `r`.`reservation_date` AS `reservation_date`, `r`.`status` AS `status`, `r`.`confirmation_code` AS `confirmation_code`, `r`.`created_at` AS `created_at` FROM ((((`reservations` `r` join `users` `u` on(`r`.`user_id` = `u`.`id`)) join `routes` `rt` on(`r`.`route_id` = `rt`.`id`)) join `schedules` `s` on(`rt`.`schedule_id` = `s`.`id`)) left join `seats` `st` on(`r`.`seat_id` = `st`.`id`)) WHERE `r`.`status` in ('active','waiting') AND `rt`.`active` = 1 AND `u`.`active` = 1 ;

-- --------------------------------------------------------

--
-- Estrutura para view `v_admin_dashboard`
--
DROP TABLE IF EXISTS `v_admin_dashboard`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_admin_dashboard`  AS SELECT (select count(0) from `users` where `users`.`user_type` = 'student' and `users`.`active` = 1) AS `total_students`, (select count(0) from `users` where `users`.`user_type` = 'driver' and `users`.`active` = 1) AS `total_drivers`, (select count(0) from `vehicles` where `vehicles`.`status` = 'Ativo') AS `total_vehicles`, (select count(0) from `routes` where `routes`.`route_date` = curdate() and `routes`.`active` = 1) AS `today_routes`, (select count(0) from `reservations` where `reservations`.`reservation_date` = curdate() and `reservations`.`status` = 'active') AS `today_reservations`, (select count(0) from `reservations` where `reservations`.`reservation_date` = curdate() and `reservations`.`status` = 'cancelled') AS `today_cancellations`, (select avg(cast(`routes`.`current_occupancy` as decimal(10,0)) / cast(`routes`.`max_capacity` as decimal(10,0)) * 100) from `routes` where `routes`.`route_date` >= curdate() - interval 7 day and `routes`.`active` = 1) AS `avg_occupancy_week`, (select count(0) from `maintenance_records` where `maintenance_records`.`status` in ('scheduled','in_progress')) AS `pending_maintenance`, (select count(0) from `notifications` where `notifications`.`read_status` = 0 and `notifications`.`created_at` >= current_timestamp() - interval 1 day) AS `unread_notifications_today` ;

-- --------------------------------------------------------

--
-- Estrutura para view `v_attendance_stats`
--
DROP TABLE IF EXISTS `v_attendance_stats`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_attendance_stats`  AS SELECT `u`.`id` AS `user_id`, `u`.`name` AS `name`, `u`.`matricula` AS `matricula`, `u`.`curso` AS `curso`, count(0) AS `total_reservations`, sum(case when `a`.`status` = 'present' then 1 else 0 end) AS `present_count`, sum(case when `a`.`status` = 'absent' then 1 else 0 end) AS `absent_count`, sum(case when `a`.`status` = 'late' then 1 else 0 end) AS `late_count`, sum(case when `a`.`status` = 'cancelled' then 1 else 0 end) AS `cancelled_count`, cast(sum(case when `a`.`status` = 'present' then 1 else 0 end) * 100.0 / nullif(count(0),0) as decimal(5,2)) AS `attendance_percentage`, avg(cast(`a`.`rating` as decimal(3,2))) AS `average_rating` FROM ((`users` `u` join `reservations` `r` on(`u`.`id` = `r`.`user_id`)) join `attendance` `a` on(`r`.`id` = `a`.`reservation_id`)) WHERE `u`.`user_type` = 'student' GROUP BY `u`.`id`, `u`.`name`, `u`.`matricula`, `u`.`curso` ;

-- --------------------------------------------------------

--
-- Estrutura para view `v_route_occupancy`
--
DROP TABLE IF EXISTS `v_route_occupancy`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_route_occupancy`  AS SELECT `r`.`id` AS `route_id`, `r`.`name` AS `route_name`, `r`.`origin` AS `origin`, `r`.`destination` AS `destination`, `r`.`route_date` AS `route_date`, `s`.`name` AS `schedule_name`, `s`.`departure_time` AS `departure_time`, `v`.`plate` AS `vehicle_plate`, `v`.`model` AS `vehicle_model`, `v`.`capacity` AS `max_capacity`, `r`.`current_occupancy` AS `current_occupancy`, cast(`r`.`current_occupancy` * 100.0 / `v`.`capacity` as decimal(5,2)) AS `occupancy_percentage`, `v`.`capacity`- `r`.`current_occupancy` AS `available_seats`, `r`.`status` AS `status` FROM ((`routes` `r` join `schedules` `s` on(`r`.`schedule_id` = `s`.`id`)) left join `vehicles` `v` on(`r`.`id` = `v`.`id`)) WHERE `r`.`route_date` >= curdate() AND `r`.`status` in ('scheduled','in_progress') AND `r`.`active` = 1 ;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reservation_id` (`reservation_id`),
  ADD KEY `route_id` (`route_id`),
  ADD KEY `recorded_by` (`recorded_by`),
  ADD KEY `IX_attendance_date` (`attendance_date`),
  ADD KEY `IX_attendance_user` (`user_id`),
  ADD KEY `IX_attendance_status` (`status`);

--
-- Índices de tabela `maintenance_records`
--
ALTER TABLE `maintenance_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Índices de tabela `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IX_notifications_user_read` (`user_id`,`read_status`),
  ADD KEY `IX_notifications_created` (`created_at`);

--
-- Índices de tabela `qr_codes`
--
ALTER TABLE `qr_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `reservation_id` (`reservation_id`),
  ADD KEY `scanned_by` (`scanned_by`);

--
-- Índices de tabela `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UQ_UserRouteDate` (`user_id`,`route_id`,`reservation_date`),
  ADD UNIQUE KEY `qr_code` (`qr_code`),
  ADD KEY `seat_id` (`seat_id`),
  ADD KEY `IX_reservations_user` (`user_id`),
  ADD KEY `IX_reservations_route` (`route_id`),
  ADD KEY `IX_reservations_date` (`reservation_date`),
  ADD KEY `IX_reservations_status` (`status`),
  ADD KEY `IX_reservations_qr_code` (`qr_code`);

--
-- Índices de tabela `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IX_routes_date` (`route_date`),
  ADD KEY `IX_routes_schedule` (`schedule_id`),
  ADD KEY `IX_routes_status` (`status`),
  ADD KEY `IX_routes_active` (`active`),
  ADD KEY `driver_id` (`driver_id`);

--
-- Índices de tabela `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Índices de tabela `seats`
--
ALTER TABLE `seats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UQ_VehicleSeat` (`vehicle_id`,`seat_number`);

--
-- Índices de tabela `system_logs`
--
ALTER TABLE `system_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Índices de tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `matricula` (`matricula`),
  ADD KEY `IX_users_email` (`email`),
  ADD KEY `IX_users_matricula` (`matricula`),
  ADD KEY `IX_users_type` (`user_type`),
  ADD KEY `IX_users_active` (`active`);

--
-- Índices de tabela `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `plate` (`plate`),
  ADD KEY `driver_id` (`driver_id`);

--
-- Índices de tabela `waiting_list`
--
ALTER TABLE `waiting_list`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UQ_UserRouteWaiting` (`user_id`,`route_id`),
  ADD KEY `IX_waiting_list_route` (`route_id`),
  ADD KEY `IX_waiting_list_position` (`route_id`,`position`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `maintenance_records`
--
ALTER TABLE `maintenance_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `qr_codes`
--
ALTER TABLE `qr_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `routes`
--
ALTER TABLE `routes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `seats`
--
ALTER TABLE `seats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `system_logs`
--
ALTER TABLE `system_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `waiting_list`
--
ALTER TABLE `waiting_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`),
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `attendance_ibfk_3` FOREIGN KEY (`route_id`) REFERENCES `routes` (`id`),
  ADD CONSTRAINT `attendance_ibfk_4` FOREIGN KEY (`recorded_by`) REFERENCES `users` (`id`);

--
-- Restrições para tabelas `maintenance_records`
--
ALTER TABLE `maintenance_records`
  ADD CONSTRAINT `maintenance_records_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`);

--
-- Restrições para tabelas `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Restrições para tabelas `qr_codes`
--
ALTER TABLE `qr_codes`
  ADD CONSTRAINT `qr_codes_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`),
  ADD CONSTRAINT `qr_codes_ibfk_2` FOREIGN KEY (`scanned_by`) REFERENCES `users` (`id`);

--
-- Restrições para tabelas `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`route_id`) REFERENCES `routes` (`id`),
  ADD CONSTRAINT `reservations_ibfk_3` FOREIGN KEY (`seat_id`) REFERENCES `seats` (`id`);

--
-- Restrições para tabelas `routes`
--
ALTER TABLE `routes`
  ADD CONSTRAINT `routes_ibfk_1` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`),
  ADD CONSTRAINT `routes_ibfk_2` FOREIGN KEY (`driver_id`) REFERENCES `users` (`id`);

--
-- Restrições para tabelas `seats`
--
ALTER TABLE `seats`
  ADD CONSTRAINT `seats_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `system_logs`
--
ALTER TABLE `system_logs`
  ADD CONSTRAINT `system_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Restrições para tabelas `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `vehicles_ibfk_1` FOREIGN KEY (`driver_id`) REFERENCES `users` (`id`);

--
-- Restrições para tabelas `waiting_list`
--
ALTER TABLE `waiting_list`
  ADD CONSTRAINT `waiting_list_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `waiting_list_ibfk_2` FOREIGN KEY (`route_id`) REFERENCES `routes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
