-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 25, 2024 at 07:28 PM
-- Server version: 10.5.25-MariaDB-cll-lve
-- PHP Version: 8.1.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `evef9533_Eventreg`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` varchar(5) NOT NULL,
  `event_name` varchar(150) NOT NULL,
  `event_status` varchar(50) NOT NULL,
  `event_date` date NOT NULL,
  `event_time` time NOT NULL,
  `location` varchar(200) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `curr_participants` int(11) NOT NULL,
  `max_participants` int(11) DEFAULT NULL,
  `banner_name` varchar(70) NOT NULL,
  `banner_url` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `event_name`, `event_status`, `event_date`, `event_time`, `location`, `description`, `curr_participants`, `max_participants`, `banner_name`, `banner_url`) VALUES
('E0001', 'EcoFuture Summit: Sustainability for a Greener Tomorrow', 'open', '2024-10-27', '16:00:08', 'New York', 'A conference focused on environmental innovations and steps towards a sustainable future. It brings together companies, environmental activists, and government leaders.', 2, 120, 'gambar.jpg', 'gambar.jpg'),
('E0006', 'Tech and Innovation Summit', 'open', '2024-10-31', '12:34:00', 'London', 'This Summit will focus on convening the UK tech sector with UK Government and policy professionals to explore the future of UK innovation, identifying policy barriers hindering technological transform', 9, 123, 'gambar2.jpg', 'gambar2.jpg'),
('E0007', 'FoodieFest: A Celebration of Culinary Arts', 'open', '2024-11-30', '08:03:00', 'Senayan Park', 'A food festival that gathers local and international chefs to celebrate the diversity of culinary arts from around the world. Featuring cooking demos, tastings, and cooking competitions.', 1, 500, 'images.jpg', 'images.jpg'),
('E0008', 'HealthTech Expo: Transforming Healthcare Through Technology', 'open', '2024-11-07', '08:00:00', 'Los Angels', 'An exhibition showcasing the latest innovations in healthcare technology such as AI in medicine, health tech wearables, and medical apps.', 1, 100, 'img.jpeg', 'img.jpeg'),
('E0009', 'Startup Showcase: Igniting the Next Wave of Innovation', 'open', '2024-11-11', '12:00:00', 'Dubai', 'An event focusing on tech startups with pitch sessions, panel discussions, and mentoring from top investors. It helps startups grow through networking and collaboration.', 2, 500, 'Startup-Showcase-KSA-1.jpg', 'Startup-Showcase-KSA-1.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

CREATE TABLE `registrations` (
  `User_id` varchar(5) NOT NULL,
  `Event_id` varchar(5) NOT NULL,
  `Tnggal_daftar` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registrations`
--

INSERT INTO `registrations` (`User_id`, `Event_id`, `Tnggal_daftar`) VALUES
('U0004', 'E0006', '2024-10-24 23:40:08'),
('U0005', 'E0001', '2024-10-25 01:07:13'),
('U0005', 'E0006', '2024-10-25 01:07:56'),
('U0005', 'E0007', '2024-10-25 01:07:29'),
('U0005', 'E0008', '2024-10-25 01:07:23'),
('U0005', 'E0009', '2024-10-25 01:07:32'),
('U0006', 'E0001', '2024-10-25 03:45:29'),
('U0006', 'E0006', '2024-10-25 03:45:54'),
('U0006', 'E0009', '2024-10-25 03:46:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` varchar(5) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(500) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
('U0003', 'admin', 'admin@admin.com', '$2y$10$96JZvXjvw4PKzm4nf0bNLuCFPQNAzugbZb7aQLtaHYBluMH.SrULe', 'admin', '2024-10-23 18:37:30'),
('U0004', '1234', '1234@123.com', '$2y$10$WdyWkBGPTydAxExarZ2ahOCruqHZRexGfX.FO1ndhItQl09MvQq2q', 'user', '2024-10-24 22:56:13'),
('U0005', 'joshua', 'joshua@gmail.com', '$2y$10$EnDVUfR9WhvinH3Zx/6hfeJafOJdI2jAvBMNPYb2nM9vLuiMKMRXi', 'user', '2024-10-25 01:08:29'),
('U0006', 'FajarChresty', 'fajarchresty11112005@gmail.com', '$2y$10$Dy.yxUpXJrbwpmnJNfK8deFPCRR4pPIXZd2zkqocIsuRtyRzVo4.K', 'user', '2024-10-25 03:44:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`User_id`,`Event_id`),
  ADD KEY `registrations_ibfk_2` (`Event_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `email_2` (`email`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `registrations`
--
ALTER TABLE `registrations`
  ADD CONSTRAINT `registrations_ibfk_1` FOREIGN KEY (`User_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `registrations_ibfk_2` FOREIGN KEY (`Event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
