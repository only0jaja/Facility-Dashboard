-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 28, 2026 at 08:20 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `facility-dashboard`
--

-- --------------------------------------------------------

--
-- Table structure for table `access_log`
--

CREATE TABLE `access_log` (
  `Log_id` int(11) NOT NULL,
  `User_id` int(11) DEFAULT NULL,
  `Rfid_tag` varchar(50) NOT NULL,
  `Room_id` int(11) NOT NULL,
  `Schedule_id` int(11) DEFAULT NULL,
  `Access_time` datetime DEFAULT current_timestamp(),
  `Access_type` enum('Entry','Exit') NOT NULL,
  `Status` enum('granted','denied') DEFAULT 'denied'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `access_log`
--

INSERT INTO `access_log` (`Log_id`, `User_id`, `Rfid_tag`, `Room_id`, `Schedule_id`, `Access_time`, `Access_type`, `Status`) VALUES
(1, NULL, '1,82 04 10 01', 1, NULL, '2025-10-09 15:38:44', 'Entry', 'denied'),
(2, NULL, '2,82 04 10 01', 2, NULL, '2025-10-09 15:38:56', 'Entry', 'denied'),
(3, NULL, '2,82 04 10 01', 2, NULL, '2025-10-09 15:38:59', 'Entry', 'denied'),
(4, NULL, '1,82 04 10 01', 1, NULL, '2025-10-09 15:40:14', 'Entry', 'denied'),
(5, NULL, '2,82 04 10 01', 2, NULL, '2025-10-09 15:40:19', 'Entry', 'denied'),
(6, NULL, '2,82 04 10 01', 2, NULL, '2025-10-09 15:40:27', 'Entry', 'denied'),
(7, NULL, '2,82 04 10 01', 2, NULL, '2025-10-09 15:45:18', 'Entry', 'denied'),
(8, 1, '82 04 10 01', 1, 3, '2025-10-09 15:48:46', 'Entry', 'granted'),
(9, 2, 'D3 CB B1 38', 1, NULL, '2025-10-09 15:49:41', 'Entry', 'denied'),
(10, NULL, '2,D3 CB B1 38', 2, NULL, '2025-10-09 15:49:47', 'Entry', 'denied'),
(11, NULL, '2,D3 CB B1 38', 2, NULL, '2025-10-09 15:49:51', 'Entry', 'denied'),
(12, 1, '82 04 10 01', 1, 3, '2025-10-09 15:49:54', 'Entry', 'granted'),
(13, 1, '82 04 10 01', 1, 3, '2025-10-09 15:50:13', 'Entry', 'granted'),
(14, 1, '82 04 10 01', 1, 3, '2025-10-09 15:50:43', 'Entry', 'granted'),
(15, NULL, 'A4 12 3D 05', 1, NULL, '2025-10-09 15:51:02', 'Entry', 'denied'),
(16, 8, 'A4 12 3D 05', 1, NULL, '2025-10-09 15:51:26', 'Exit', 'granted'),
(17, 1, '82 04 10 01', 1, 3, '2025-10-09 15:51:33', 'Entry', 'granted'),
(18, 8, 'A4 12 3D 05', 1, NULL, '2025-10-09 15:51:40', 'Exit', 'granted'),
(19, 1, '82 04 10 01', 1, NULL, '2025-10-09 15:52:05', 'Entry', 'denied'),
(20, 1, '82 04 10 01', 1, NULL, '2025-10-09 15:52:16', 'Entry', 'denied'),
(21, 1, '82 04 10 01', 1, NULL, '2025-10-09 15:52:21', 'Entry', 'denied'),
(22, NULL, '2,82 04 10 01', 2, NULL, '2025-10-09 15:53:03', 'Entry', 'denied'),
(23, NULL, '2,82 04 10 01', 2, NULL, '2025-10-09 15:53:08', 'Entry', 'denied'),
(24, 1, '82 04 10 01', 1, 3, '2025-10-09 15:53:13', 'Entry', 'granted'),
(25, 8, 'A4 12 3D 05', 1, NULL, '2025-10-09 15:53:35', 'Exit', 'granted'),
(26, 8, 'A4 12 3D 05', 1, NULL, '2025-10-09 15:53:37', 'Entry', 'granted'),
(27, NULL, '2,A4 12 3D 05', 2, NULL, '2025-10-09 15:53:46', 'Entry', 'denied'),
(28, NULL, '2,82 04 10 01', 2, NULL, '2025-10-09 16:13:01', 'Entry', 'denied'),
(29, 1, '82 04 10 01', 1, 3, '2025-10-09 16:13:22', 'Entry', 'granted'),
(30, 1, '82 04 10 01', 2, 3, '2025-10-09 16:14:30', 'Entry', 'granted'),
(31, 1, '82 04 10 01', 1, 3, '2025-10-09 16:14:34', 'Entry', 'granted'),
(32, 1, '82 04 10 01', 2, 3, '2025-10-09 16:15:53', 'Entry', 'granted'),
(33, 8, 'A4 12 3D 05', 2, NULL, '2025-10-09 16:15:59', 'Exit', 'granted'),
(34, 2, 'D3 CB B1 38', 2, NULL, '2025-10-09 16:16:03', 'Entry', 'denied'),
(35, 2, 'D3 CB B1 38', 2, 1, '2025-10-09 16:19:29', 'Entry', 'granted'),
(36, 2, 'D3 CB B1 38', 1, 1, '2025-10-09 16:19:32', 'Entry', 'granted'),
(37, 8, 'A4 12 3D 05', 1, NULL, '2025-10-09 16:22:40', 'Exit', 'granted'),
(38, 8, 'A4 12 3D 05', 1, NULL, '2025-10-09 16:22:43', 'Entry', 'granted'),
(39, 8, 'A4 12 3D 05', 1, NULL, '2025-10-09 16:22:51', 'Exit', 'granted'),
(40, 2, 'D3 CB B1 38', 1, NULL, '2025-10-09 16:22:55', 'Entry', 'denied'),
(41, 2, 'D3 CB B1 38', 2, 1, '2025-10-09 16:23:00', 'Entry', 'granted'),
(42, 1, '82 04 10 01', 2, NULL, '2025-10-09 16:23:09', 'Entry', 'denied'),
(43, 1, '82 04 10 01', 1, NULL, '2025-10-09 16:23:12', 'Entry', 'denied'),
(44, 8, 'A4 12 3D 05', 1, NULL, '2025-10-09 16:23:46', 'Entry', 'granted'),
(45, 8, 'A4 12 3D 05', 1, NULL, '2025-10-09 16:23:53', 'Exit', 'granted'),
(46, 2, 'D3 CB B1 38', 1, NULL, '2025-10-09 16:23:57', 'Entry', 'denied'),
(47, 2, 'D3 CB B1 38', 2, 1, '2025-10-09 16:24:04', 'Entry', 'granted'),
(48, 2, 'D3 CB B1 38', 2, 1, '2025-10-09 16:24:20', 'Entry', 'granted'),
(49, 2, 'D3 CB B1 38', 2, 1, '2025-10-09 16:24:28', 'Entry', 'granted'),
(50, 1, '82 04 10 01', 2, NULL, '2025-10-09 16:24:50', 'Entry', 'denied'),
(51, 1, '82 04 10 01', 1, 3, '2025-10-09 16:25:01', 'Entry', 'granted'),
(52, 1, '82 04 10 01', 1, 3, '2025-10-09 16:26:30', 'Entry', 'granted'),
(53, 1, '82 04 10 01', 2, NULL, '2025-10-09 16:26:34', 'Entry', 'denied'),
(54, 1, '82 04 10 01', 2, NULL, '2025-10-09 16:28:41', 'Entry', 'denied'),
(55, 1, '82 04 10 01', 2, NULL, '2025-10-09 16:28:43', 'Entry', 'denied'),
(56, 1, '82 04 10 01', 1, 3, '2025-10-09 16:28:45', 'Entry', 'granted'),
(57, 2, 'D3 CB B1 38', 2, 1, '2025-10-09 16:28:52', 'Entry', 'granted'),
(58, 2, 'D3 CB B1 38', 1, NULL, '2025-10-09 16:28:55', 'Entry', 'denied'),
(59, 2, 'D3 CB B1 38', 2, NULL, '2025-10-10 10:28:40', 'Entry', 'denied'),
(60, 2, 'D3 CB B1 38', 2, NULL, '2025-10-10 10:30:37', 'Entry', 'denied'),
(61, 1, '82 04 10 01', 2, NULL, '2025-10-10 10:30:41', 'Entry', 'denied'),
(62, 1, '82 04 10 01', 1, NULL, '2025-10-10 10:39:08', 'Entry', 'denied'),
(63, 1, '82 04 10 01', 1, NULL, '2025-10-10 10:39:11', 'Entry', 'denied'),
(64, 1, '82 04 10 01', 1, NULL, '2025-10-10 10:39:14', 'Entry', 'denied'),
(65, 1, '82 04 10 01', 1, NULL, '2025-10-10 10:39:20', 'Entry', 'denied'),
(66, 1, '82 04 10 01', 1, NULL, '2025-10-10 10:39:23', 'Entry', 'denied'),
(67, 2, 'D3 CB B1 38', 1, NULL, '2025-10-10 10:40:18', 'Entry', 'denied'),
(68, 2, 'D3 CB B1 38', 1, 1, '2025-10-10 10:41:06', 'Entry', 'granted'),
(69, 2, 'D3 CB B1 38', 2, 1, '2025-10-10 10:41:11', 'Entry', 'granted'),
(70, 2, 'D3 CB B1 38', 2, 1, '2025-10-10 10:41:19', 'Entry', 'granted'),
(71, 1, '82 04 10 01', 2, 3, '2025-10-10 10:41:27', 'Entry', 'granted'),
(72, 1, '82 04 10 01', 1, 3, '2025-10-10 10:41:31', 'Entry', 'granted'),
(73, 2, 'D3 CB B1 38', 2, 1, '2025-10-10 10:44:08', 'Entry', 'granted'),
(74, 2, 'D3 CB B1 38', 2, 1, '2025-10-10 10:45:11', 'Entry', 'granted'),
(75, 1, '82 04 10 01', 2, NULL, '2025-10-10 10:45:47', 'Entry', 'denied'),
(76, 1, '82 04 10 01', 2, NULL, '2025-10-10 10:45:52', 'Entry', 'denied'),
(77, 2, 'D3 CB B1 38', 2, 1, '2025-10-10 10:45:56', 'Entry', 'granted'),
(78, 2, 'D3 CB B1 38', 1, NULL, '2025-10-10 10:46:13', 'Entry', 'denied'),
(79, 2, 'D3 CB B1 38', 2, 1, '2025-10-10 10:46:17', 'Entry', 'granted'),
(80, 1, '82 04 10 01', 1, 3, '2025-10-10 10:46:23', 'Entry', 'granted'),
(81, 1, '82 04 10 01', 2, NULL, '2025-10-10 10:46:28', 'Entry', 'denied'),
(82, 2, 'D3 CB B1 38', 2, 1, '2025-10-10 10:48:03', 'Entry', 'granted'),
(83, 2, 'D3 CB B1 38', 2, 1, '2025-10-10 10:48:26', 'Entry', 'granted'),
(84, 2, 'D3 CB B1 38', 2, 1, '2025-10-10 10:48:33', 'Entry', 'granted'),
(85, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 10:48:41', 'Exit', 'granted'),
(86, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 10:48:48', 'Entry', 'granted'),
(87, 1, '82 04 10 01', 2, NULL, '2025-10-10 10:48:56', 'Entry', 'denied'),
(88, 1, '82 04 10 01', 2, NULL, '2025-10-10 10:48:58', 'Entry', 'denied'),
(89, 1, '82 04 10 01', 2, NULL, '2025-10-10 10:49:01', 'Entry', 'denied'),
(90, 1, '82 04 10 01', 2, NULL, '2025-10-10 10:49:04', 'Entry', 'denied'),
(91, 1, '82 04 10 01', 1, 3, '2025-10-10 10:49:06', 'Entry', 'granted'),
(92, 2, 'D3 CB B1 38', 2, 1, '2025-10-10 10:49:10', 'Entry', 'granted'),
(93, 2, 'D3 CB B1 38', 1, NULL, '2025-10-10 10:49:16', 'Entry', 'denied'),
(94, 2, 'D3 CB B1 38', 1, NULL, '2025-10-10 10:49:18', 'Entry', 'denied'),
(95, 2, 'D3 CB B1 38', 2, 1, '2025-10-10 11:02:20', 'Entry', 'granted'),
(96, 2, 'D3 CB B1 38', 2, 1, '2025-10-10 11:02:31', 'Entry', 'granted'),
(97, 2, 'D3 CB B1 38', 2, 1, '2025-10-10 11:02:45', 'Entry', 'granted'),
(98, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:05:31', 'Exit', 'granted'),
(99, 1, '82 04 10 01', 2, NULL, '2025-10-10 11:05:35', 'Entry', 'denied'),
(100, 1, '82 04 10 01', 2, NULL, '2025-10-10 11:05:38', 'Entry', 'denied'),
(101, 1, '82 04 10 01', 2, NULL, '2025-10-10 11:05:47', 'Entry', 'denied'),
(102, 1, '82 04 10 01', 2, NULL, '2025-10-10 11:05:49', 'Entry', 'denied'),
(103, 2, 'D3 CB B1 38', 2, 1, '2025-10-10 11:05:55', 'Entry', 'granted'),
(104, 2, 'D3 CB B1 38', 2, 1, '2025-10-10 11:06:02', 'Entry', 'granted'),
(105, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:06:11', 'Exit', 'granted'),
(106, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:06:17', 'Entry', 'granted'),
(107, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:06:24', 'Exit', 'granted'),
(108, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:06:31', 'Entry', 'granted'),
(109, 1, '82 04 10 01', 2, NULL, '2025-10-10 11:07:24', 'Entry', 'denied'),
(110, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:07:28', 'Exit', 'granted'),
(111, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:07:32', 'Entry', 'granted'),
(112, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:07:40', 'Exit', 'granted'),
(113, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:07:43', 'Entry', 'granted'),
(114, 2, 'D3 CB B1 38', 2, 1, '2025-10-10 11:09:03', 'Entry', 'granted'),
(115, 2, 'D3 CB B1 38', 2, 1, '2025-10-10 11:09:23', 'Entry', 'granted'),
(116, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:09:30', 'Exit', 'granted'),
(117, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:09:33', 'Entry', 'granted'),
(118, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:09:41', 'Exit', 'granted'),
(119, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:10:28', 'Entry', 'granted'),
(120, 8, 'A4 12 3D 05', 1, NULL, '2025-10-10 11:10:36', 'Exit', 'granted'),
(121, 8, 'A4 12 3D 05', 1, NULL, '2025-10-10 11:10:40', 'Entry', 'granted'),
(122, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:13:13', 'Exit', 'granted'),
(123, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:13:15', 'Entry', 'granted'),
(124, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:13:51', 'Exit', 'granted'),
(125, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:13:55', 'Entry', 'granted'),
(126, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:14:04', 'Exit', 'granted'),
(127, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:14:09', 'Entry', 'granted'),
(128, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:14:18', 'Exit', 'granted'),
(129, NULL, '61 DE 6A 05', 1, NULL, '2025-10-13 08:09:23', 'Entry', 'denied'),
(130, NULL, '61 DE 6A 05', 1, NULL, '2025-10-13 08:09:26', 'Entry', 'denied'),
(131, NULL, '61 DE 6A 05', 1, NULL, '2025-10-13 08:09:29', 'Entry', 'denied'),
(132, NULL, '44 22 95 04', 2, NULL, '2025-10-13 08:11:25', 'Entry', 'denied'),
(133, NULL, '44 22 95 04', 2, NULL, '2025-10-13 08:11:28', 'Entry', 'denied'),
(134, NULL, '44 22 95 04', 2, NULL, '2025-10-13 08:11:30', 'Entry', 'denied'),
(135, 1, '44 22 95 04', 1, NULL, '2025-10-13 08:13:49', 'Entry', 'denied'),
(136, 8, '61 DE 6A 05', 1, NULL, '2025-10-13 08:13:54', 'Exit', 'granted'),
(137, 8, '61 DE 6A 05', 1, NULL, '2025-10-13 08:14:01', 'Entry', 'granted'),
(138, 8, '61 DE 6A 05', 2, NULL, '2025-10-13 08:14:34', 'Entry', 'granted'),
(139, 1, '44 22 95 04', 1, NULL, '2025-10-13 08:14:40', 'Entry', 'denied'),
(140, 1, '44 22 95 04', 2, NULL, '2025-10-13 08:14:43', 'Entry', 'denied'),
(141, 8, '61 DE 6A 05', 2, NULL, '2025-10-13 08:14:46', 'Exit', 'granted'),
(142, 8, '61 DE 6A 05', 1, NULL, '2025-10-13 08:14:56', 'Exit', 'granted'),
(143, 8, '61 DE 6A 05', 1, NULL, '2025-10-13 08:15:24', 'Entry', 'granted'),
(144, 8, '61 DE 6A 05', 1, NULL, '2025-10-13 08:15:33', 'Exit', 'granted'),
(145, 8, '61 DE 6A 05', 1, NULL, '2025-10-13 08:16:24', 'Entry', 'granted'),
(146, 8, '61 DE 6A 05', 1, NULL, '2025-10-13 08:16:32', 'Exit', 'granted'),
(147, 8, '61 DE 6A 05', 2, NULL, '2025-10-13 08:16:48', 'Entry', 'granted'),
(148, 8, '61 DE 6A 05', 2, NULL, '2025-10-13 08:16:55', 'Exit', 'granted'),
(149, 8, '61 DE 6A 05', 2, NULL, '2025-10-13 08:16:58', 'Entry', 'granted'),
(150, 8, '61 DE 6A 05', 2, NULL, '2025-10-13 08:17:05', 'Exit', 'granted'),
(151, 8, '61 DE 6A 05', 1, NULL, '2025-10-13 08:18:26', 'Entry', 'granted'),
(152, 8, '61 DE 6A 05', 1, NULL, '2025-10-13 08:18:35', 'Exit', 'granted'),
(153, 8, '61 DE 6A 05', 2, NULL, '2025-10-13 08:18:42', 'Entry', 'granted'),
(154, 8, '61 DE 6A 05', 2, NULL, '2025-10-13 08:18:57', 'Exit', 'granted'),
(155, 8, '61 DE 6A 05', 1, NULL, '2025-10-13 08:19:03', 'Entry', 'granted'),
(156, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 01:55:37', 'Exit', 'granted'),
(157, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 01:55:40', 'Entry', 'granted'),
(158, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 01:56:24', 'Exit', 'granted'),
(159, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 01:56:27', 'Entry', 'granted'),
(160, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 01:56:35', 'Exit', 'granted'),
(161, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 01:56:38', 'Entry', 'granted'),
(162, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 01:56:48', 'Exit', 'granted'),
(163, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 01:58:10', 'Entry', 'granted'),
(164, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 01:58:25', 'Exit', 'granted'),
(165, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:00:24', 'Entry', 'granted'),
(166, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:00:31', 'Exit', 'granted'),
(167, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:00:36', 'Entry', 'granted'),
(168, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:01:12', 'Exit', 'granted'),
(169, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:01:16', 'Entry', 'granted'),
(170, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:01:23', 'Exit', 'granted'),
(171, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:01:25', 'Entry', 'granted'),
(172, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:01:33', 'Exit', 'granted'),
(173, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:01:38', 'Entry', 'granted'),
(174, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:01:45', 'Exit', 'granted'),
(175, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:01:50', 'Entry', 'granted'),
(176, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:01:57', 'Exit', 'granted'),
(177, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:04:05', 'Entry', 'granted'),
(178, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:04:16', 'Exit', 'granted'),
(179, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:04:22', 'Entry', 'granted'),
(180, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:06:12', 'Exit', 'granted'),
(181, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:06:20', 'Entry', 'granted'),
(182, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:06:29', 'Exit', 'granted'),
(183, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:07:09', 'Entry', 'granted'),
(184, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:07:15', 'Exit', 'granted'),
(185, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:07:22', 'Entry', 'granted'),
(186, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:07:58', 'Exit', 'granted'),
(187, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:08:04', 'Entry', 'granted'),
(188, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:08:11', 'Exit', 'granted'),
(189, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:08:43', 'Entry', 'granted'),
(190, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:09:59', 'Exit', 'granted'),
(191, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:10:07', 'Entry', 'granted'),
(192, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:10:59', 'Exit', 'granted'),
(193, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:11:01', 'Entry', 'granted'),
(194, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:11:37', 'Exit', 'granted'),
(195, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:11:58', 'Entry', 'granted'),
(196, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:12:20', 'Exit', 'granted'),
(197, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:12:22', 'Entry', 'granted'),
(198, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:13:20', 'Exit', 'granted'),
(199, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:13:23', 'Entry', 'granted'),
(200, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:13:46', 'Exit', 'granted'),
(201, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:13:48', 'Entry', 'granted'),
(202, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:14:29', 'Exit', 'granted'),
(203, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:14:31', 'Entry', 'granted'),
(204, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:14:38', 'Exit', 'granted'),
(205, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:15:13', 'Entry', 'granted'),
(206, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:15:19', 'Exit', 'granted'),
(207, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:15:25', 'Entry', 'granted'),
(208, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:17:13', 'Exit', 'granted'),
(209, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:17:20', 'Entry', 'granted'),
(210, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:17:27', 'Exit', 'granted'),
(211, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:17:33', 'Entry', 'granted'),
(212, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:17:40', 'Exit', 'granted'),
(213, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:17:56', 'Entry', 'granted'),
(214, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:18:04', 'Exit', 'granted'),
(215, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:18:07', 'Entry', 'granted'),
(216, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:18:13', 'Exit', 'granted'),
(217, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:18:18', 'Entry', 'granted'),
(218, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:18:25', 'Exit', 'granted'),
(219, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:18:28', 'Entry', 'granted'),
(220, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:19:17', 'Exit', 'granted'),
(221, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:19:22', 'Entry', 'granted'),
(222, NULL, '\0', 1, NULL, '2025-10-14 02:19:52', 'Entry', 'denied'),
(223, NULL, '82 04 10 01', 1, NULL, '2025-10-16 22:41:25', 'Entry', 'denied'),
(224, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:04:01', 'Exit', 'granted'),
(225, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:04:06', 'Entry', 'granted'),
(226, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:05:15', 'Exit', 'granted'),
(227, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:05:18', 'Entry', 'granted'),
(228, NULL, 'A4 12 3D 05', 1, NULL, '2025-10-17 12:05:27', 'Entry', 'denied'),
(229, 2, 'D3 CB B1 38', 1, NULL, '2025-10-17 12:05:34', 'Entry', 'denied'),
(230, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:05:39', 'Exit', 'granted'),
(231, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:06:05', 'Entry', 'granted'),
(232, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:06:11', 'Exit', 'granted'),
(233, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:06:18', 'Entry', 'granted'),
(234, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:06:27', 'Exit', 'granted'),
(235, NULL, 'A4 12 3D 05', 1, NULL, '2025-10-17 12:07:17', 'Entry', 'denied'),
(236, NULL, 'A4 12 3D 05', 1, NULL, '2025-10-17 12:07:19', 'Entry', 'denied'),
(237, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:07:22', 'Entry', 'granted'),
(238, NULL, 'A4 12 3D 05', 1, NULL, '2025-10-17 12:08:17', 'Entry', 'denied'),
(239, 2, 'D3 CB B1 38', 1, NULL, '2025-10-17 12:08:22', 'Entry', 'denied'),
(240, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:08:25', 'Exit', 'granted'),
(241, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:18:36', 'Entry', 'granted'),
(242, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:38:27', 'Exit', 'granted'),
(243, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:38:32', 'Entry', 'granted'),
(244, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:38:39', 'Exit', 'granted'),
(245, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:38:52', 'Entry', 'granted'),
(246, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:39:03', 'Exit', 'granted'),
(247, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:41:24', 'Entry', 'granted'),
(248, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:43:04', 'Exit', 'granted'),
(249, NULL, '82 04 10 01', 1, NULL, '2025-10-17 12:45:15', 'Entry', 'denied'),
(250, NULL, '82 04 10 01', 1, NULL, '2025-10-17 12:45:20', 'Entry', 'denied'),
(251, NULL, '82 04 10 01', 1, NULL, '2025-10-17 12:45:24', 'Entry', 'denied'),
(252, 2, 'D3 CB B1 38', 1, NULL, '2025-10-17 12:45:39', 'Entry', 'denied'),
(253, NULL, 'A4 12 3D 05', 1, NULL, '2025-10-17 12:45:43', 'Entry', 'denied'),
(254, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:45:59', 'Entry', 'granted'),
(255, 2, 'D3 CB B1 38', 1, NULL, '2025-10-17 12:47:24', 'Entry', 'denied'),
(256, NULL, 'A4 12 3D 05', 1, NULL, '2025-10-17 12:47:43', 'Entry', 'denied'),
(257, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:47:46', 'Exit', 'granted'),
(258, NULL, '82 04 10 01', 1, NULL, '2025-10-17 12:47:52', 'Entry', 'denied'),
(259, 2, 'D3 CB B1 38', 1, NULL, '2025-10-17 12:47:55', 'Entry', 'denied'),
(260, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:47:57', 'Entry', 'granted'),
(261, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:49:37', 'Exit', 'granted'),
(262, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:49:41', 'Entry', 'granted'),
(263, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:50:41', 'Exit', 'granted'),
(264, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:50:46', 'Entry', 'granted'),
(265, 2, 'D3 CB B1 38', 1, NULL, '2025-10-17 13:10:46', 'Entry', 'denied'),
(266, NULL, '82 04 10 01', 1, NULL, '2025-10-17 13:10:50', 'Entry', 'denied'),
(267, NULL, 'A4 12 3D 05', 1, NULL, '2025-10-17 13:10:53', 'Entry', 'denied'),
(268, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:10:58', 'Exit', 'granted'),
(269, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:11:19', 'Entry', 'granted'),
(270, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:11:34', 'Exit', 'granted'),
(271, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:11:38', 'Entry', 'granted'),
(272, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:11:44', 'Exit', 'granted'),
(273, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:11:58', 'Entry', 'granted'),
(274, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:14:33', 'Exit', 'granted'),
(275, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:14:41', 'Entry', 'granted'),
(276, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:15:32', 'Exit', 'granted'),
(277, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:15:36', 'Entry', 'granted'),
(278, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:15:47', 'Exit', 'granted'),
(279, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:15:52', 'Entry', 'granted'),
(280, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:20:01', 'Exit', 'granted'),
(281, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:20:09', 'Entry', 'granted'),
(282, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:23:11', 'Exit', 'granted'),
(283, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:23:16', 'Entry', 'granted'),
(284, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:24:02', 'Exit', 'granted'),
(285, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:24:10', 'Entry', 'granted'),
(286, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:24:39', 'Exit', 'granted'),
(287, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:24:43', 'Entry', 'granted'),
(288, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:27:47', 'Exit', 'granted'),
(289, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:27:52', 'Entry', 'granted'),
(290, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:31:42', 'Exit', 'granted'),
(291, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:31:48', 'Entry', 'granted'),
(292, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:34:46', 'Exit', 'granted'),
(293, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:34:51', 'Entry', 'granted'),
(294, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:37:05', 'Exit', 'granted'),
(295, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:37:11', 'Entry', 'granted'),
(296, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:37:28', 'Exit', 'granted'),
(297, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:37:32', 'Entry', 'granted'),
(298, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:38:12', 'Exit', 'granted'),
(299, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:38:15', 'Entry', 'granted'),
(300, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:57:10', 'Exit', 'granted'),
(301, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:57:14', 'Entry', 'granted'),
(302, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 14:00:33', 'Exit', 'granted'),
(303, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 14:00:41', 'Entry', 'granted'),
(304, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 14:01:59', 'Exit', 'granted'),
(305, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 14:02:25', 'Entry', 'granted'),
(306, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 14:02:44', 'Exit', 'granted'),
(307, NULL, 'A4 12 3D 05', 1, NULL, '2025-10-17 16:46:24', 'Entry', 'denied'),
(308, NULL, '82 04 10 01', 1, NULL, '2025-10-17 16:46:31', 'Entry', 'denied'),
(309, NULL, 'A4 12 3D 05', 1, NULL, '2025-10-17 16:46:35', 'Entry', 'denied'),
(310, 2, 'D3 CB B1 38', 1, NULL, '2025-10-17 16:46:39', 'Entry', 'denied'),
(311, 2, 'D3 CB B1 38', 1, NULL, '2025-10-17 16:46:41', 'Entry', 'denied'),
(312, NULL, 'A4 12 3D 05', 1, NULL, '2025-10-17 16:46:46', 'Entry', 'denied'),
(313, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 16:46:49', 'Entry', 'granted'),
(314, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 16:48:46', 'Exit', 'granted'),
(315, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 16:48:50', 'Entry', 'granted'),
(316, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 16:51:16', 'Exit', 'granted'),
(317, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 16:51:20', 'Entry', 'granted'),
(318, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 16:52:10', 'Exit', 'granted'),
(319, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 16:52:18', 'Entry', 'granted'),
(320, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 16:52:26', 'Exit', 'granted'),
(321, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 16:52:56', 'Entry', 'granted'),
(322, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 16:53:19', 'Exit', 'granted'),
(323, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 16:53:58', 'Entry', 'granted'),
(324, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 16:54:15', 'Exit', 'granted'),
(325, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 16:54:18', 'Entry', 'granted'),
(326, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 17:00:10', 'Exit', 'granted'),
(327, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 17:00:34', 'Entry', 'granted'),
(328, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 17:01:14', 'Exit', 'granted'),
(329, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 17:01:16', 'Entry', 'granted'),
(330, NULL, 'RFID Ready - Waiting for Card...', 1, NULL, '2025-10-21 16:20:08', 'Entry', 'denied'),
(331, NULL, 'D3CBB138', 1, NULL, '2025-10-21 16:20:13', 'Entry', 'denied'),
(332, NULL, 'ACCESS DENIED', 1, NULL, '2025-10-21 16:20:13', 'Entry', 'denied'),
(333, NULL, '82041001', 1, NULL, '2025-10-21 16:20:20', 'Entry', 'denied'),
(334, NULL, 'ACCESS DENIED', 1, NULL, '2025-10-21 16:20:20', 'Entry', 'denied'),
(335, NULL, 'A4123D05', 1, NULL, '2025-10-21 16:20:25', 'Entry', 'denied'),
(336, NULL, 'ACCESS DENIED', 1, NULL, '2025-10-21 16:20:25', 'Entry', 'denied'),
(337, NULL, 'RFID Ready - Waiting for Card...', 1, NULL, '2025-10-21 16:20:45', 'Entry', 'denied'),
(338, NULL, 'A4123D05', 1, NULL, '2025-10-21 16:20:48', 'Entry', 'denied'),
(339, NULL, 'ACCESS DENIED', 1, NULL, '2025-10-21 16:20:48', 'Entry', 'denied'),
(340, 2, 'D3 CB B1 38', 1, NULL, '2025-10-21 16:26:45', 'Entry', 'denied'),
(341, NULL, 'A4 12 3D 05', 1, NULL, '2025-10-21 16:26:52', 'Entry', 'denied'),
(342, NULL, '82 04 10 01', 1, NULL, '2025-10-21 16:26:55', 'Entry', 'denied'),
(343, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 16:26:58', 'Exit', 'granted'),
(344, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 16:27:01', 'Entry', 'granted'),
(345, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 16:27:39', 'Exit', 'granted'),
(346, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 16:27:44', 'Entry', 'granted'),
(347, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 16:27:56', 'Exit', 'granted'),
(348, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 16:27:58', 'Entry', 'granted'),
(349, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 16:28:27', 'Exit', 'granted'),
(350, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 16:28:31', 'Entry', 'granted'),
(351, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:09:44', 'Exit', 'granted'),
(352, NULL, '82 04 10 01', 1, NULL, '2025-10-21 19:09:48', 'Entry', 'denied'),
(353, 2, 'D3 CB B1 38', 1, NULL, '2025-10-21 19:09:52', 'Entry', 'denied'),
(354, NULL, 'A4 12 3D 05', 1, NULL, '2025-10-21 19:09:55', 'Entry', 'denied'),
(355, NULL, 'A4 12 3D 05', 1, NULL, '2025-10-21 19:10:03', 'Entry', 'denied'),
(356, NULL, 'A4 12 3D 05', 1, NULL, '2025-10-21 19:10:06', 'Entry', 'denied'),
(357, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:10:08', 'Entry', 'granted'),
(358, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:10:15', 'Exit', 'granted'),
(359, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:10:19', 'Entry', 'granted'),
(360, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:13:51', 'Exit', 'granted'),
(361, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:13:54', 'Entry', 'granted'),
(362, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:14:23', 'Exit', 'granted'),
(363, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:14:30', 'Entry', 'granted'),
(364, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:14:37', 'Exit', 'granted'),
(365, NULL, '61DE6A05', 1, NULL, '2025-10-21 19:25:57', 'Entry', 'denied'),
(366, NULL, '❌ Access DENIED', 1, NULL, '2025-10-21 19:25:59', 'Entry', 'denied'),
(367, NULL, '❌ Access DENIED', 1, NULL, '2025-10-21 19:26:02', 'Entry', 'denied'),
(368, NULL, '❌ Access DENIED', 1, NULL, '2025-10-21 19:26:04', 'Entry', 'denied'),
(369, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:30:17', 'Entry', 'granted'),
(370, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:31:00', 'Exit', 'granted'),
(371, NULL, '❌ Access DENIED', 1, NULL, '2025-10-21 19:31:06', 'Entry', 'denied'),
(372, NULL, '❌ Access DENIED', 1, NULL, '2025-10-21 19:31:08', 'Entry', 'denied'),
(373, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:33:52', 'Entry', 'granted'),
(374, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:35:00', 'Exit', 'granted'),
(375, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:36:19', 'Entry', 'granted'),
(376, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:38:14', 'Exit', 'granted'),
(377, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:40:04', 'Entry', 'granted'),
(378, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:40:12', 'Exit', 'granted'),
(379, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:40:19', 'Entry', 'granted'),
(380, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:41:31', 'Exit', 'granted'),
(381, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:41:37', 'Entry', 'granted'),
(382, 8, '61 DE 6A 05', 1, NULL, '2025-10-22 23:09:39', 'Exit', 'granted'),
(383, 8, '61 DE 6A 05', 1, NULL, '2025-10-22 23:09:42', 'Entry', 'granted'),
(384, 8, '61 DE 6A 05', 1, NULL, '2025-10-22 23:10:10', 'Exit', 'granted'),
(385, 8, '61 DE 6A 05', 1, NULL, '2025-10-22 23:10:12', 'Entry', 'granted'),
(386, NULL, '82 04 10 01', 1, NULL, '2025-10-22 23:10:55', 'Entry', 'denied'),
(387, 8, '61 DE 6A 05', 1, NULL, '2025-10-22 23:11:13', 'Exit', 'granted'),
(388, 8, '61 DE 6A 05', 1, NULL, '2025-10-22 23:11:19', 'Entry', 'granted'),
(389, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 13:58:27', 'Exit', 'granted'),
(390, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 13:58:35', 'Entry', 'granted'),
(391, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 13:59:25', 'Exit', 'granted'),
(392, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 13:59:27', 'Entry', 'granted'),
(393, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 13:59:45', 'Exit', 'granted'),
(394, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 13:59:47', 'Entry', 'granted'),
(395, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 13:59:53', 'Exit', 'granted'),
(396, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 14:00:31', 'Entry', 'granted'),
(397, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 14:03:41', 'Exit', 'granted'),
(398, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 14:03:43', 'Entry', 'granted'),
(399, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 14:04:56', 'Exit', 'granted'),
(400, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 14:04:58', 'Entry', 'granted'),
(401, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 14:05:16', 'Exit', 'granted'),
(402, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 14:05:18', 'Entry', 'granted'),
(403, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 14:05:34', 'Exit', 'granted'),
(404, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 14:06:42', 'Entry', 'granted'),
(405, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 14:06:49', 'Exit', 'granted'),
(406, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 14:11:13', 'Entry', 'granted'),
(407, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 14:11:31', 'Exit', 'granted'),
(408, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 14:11:33', 'Entry', 'granted');

-- --------------------------------------------------------

--
-- Table structure for table `classrooms`
--

CREATE TABLE `classrooms` (
  `Room_id` int(11) NOT NULL,
  `Room_code` varchar(50) NOT NULL,
  `Status` enum('Occupied','Unoccupied') DEFAULT 'Unoccupied',
  `Classroom_type` varchar(255) DEFAULT NULL,
  `Capacity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classrooms`
--

INSERT INTO `classrooms` (`Room_id`, `Room_code`, `Status`, `Classroom_type`, `Capacity`) VALUES
(1, 'ROOM101', 'Occupied', 'CLASSROOM', 50),
(2, 'ROOM102', 'Unoccupied', 'CLASSROOM', 30);

-- --------------------------------------------------------

--
-- Table structure for table `course_section`
--

CREATE TABLE `course_section` (
  `CourseSection_id` int(11) NOT NULL,
  `CourseSection` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_section`
--

INSERT INTO `course_section` (`CourseSection_id`, `CourseSection`) VALUES
(2, 'BSCS 1-21'),
(1, 'BSIT 1-11'),
(3, 'BSIT 2-11'),
(111, 'BSOA 1-11'),
(121, 'BSOA 1-21'),
(131, 'BSOA 1-31'),
(141, 'BSOA 1-41');

-- --------------------------------------------------------

--
-- Table structure for table `rfid_reader`
--

CREATE TABLE `rfid_reader` (
  `Reader_id` int(11) NOT NULL,
  `Room_id` int(11) NOT NULL,
  `Port_name` varchar(50) NOT NULL,
  `Status` enum('Active','Inactive') DEFAULT 'Active',
  `Last_online` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rfid_reader`
--

INSERT INTO `rfid_reader` (`Reader_id`, `Room_id`, `Port_name`, `Status`, `Last_online`) VALUES
(1, 1, 'COM5', 'Active', NULL),
(2, 2, 'COM10', 'Active', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `Schedule_id` int(11) NOT NULL,
  `Subject_id` int(11) NOT NULL,
  `Room_id` int(11) NOT NULL,
  `Faculty_id` int(11) NOT NULL,
  `Day` enum('Mon','Tue','Wed','Thu','Fri','Sat','Sun') NOT NULL,
  `Start_time` time NOT NULL,
  `End_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`Schedule_id`, `Subject_id`, `Room_id`, `Faculty_id`, `Day`, `Start_time`, `End_time`) VALUES
(1, 1, 2, 6, 'Fri', '10:00:00', '18:00:00'),
(2, 1, 1, 6, 'Mon', '10:00:00', '12:00:00'),
(3, 2, 1, 8, 'Fri', '10:00:00', '18:00:00'),
(4, 3, 2, 6, 'Wed', '16:00:00', '19:00:00'),
(5, 4, 1, 6, 'Tue', '13:00:00', '16:00:00'),
(6, 5, 1, 6, 'Fri', '18:00:00', '21:00:00'),
(7, 6, 1, 6, 'Fri', '15:00:00', '18:00:00'),
(8, 7, 2, 7, 'Mon', '07:00:00', '10:00:00'),
(9, 8, 2, 7, 'Mon', '13:00:00', '16:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `schedule_access`
--

CREATE TABLE `schedule_access` (
  `Rule_id` int(11) NOT NULL,
  `Schedule_id` int(11) NOT NULL,
  `CourseSection_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule_access`
--

INSERT INTO `schedule_access` (`Rule_id`, `Schedule_id`, `CourseSection_id`) VALUES
(1, 1, 2),
(2, 2, 2),
(3, 3, 1),
(4, 4, 2),
(5, 5, 2),
(6, 6, 131),
(7, 7, 131),
(8, 8, 131),
(9, 9, 131);

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `Subject_id` int(11) NOT NULL,
  `Code` varchar(50) NOT NULL,
  `Description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`Subject_id`, `Code`, `Description`) VALUES
(1, 'IT101', 'Introduction to Information Technology'),
(2, 'ITP311', 'Human Computer Interaction'),
(3, 'GE304', 'SCIENCE TECHNOLOGYY ENGINEERING'),
(4, 'asdawdasdawd ajshdgawj ', 'awdasd'),
(5, 'OAC310', 'Business Law'),
(6, 'OAE301', 'Human Anatomy and Physiology'),
(7, 'GEE303', 'GE Elective 3- Business Logic'),
(8, 'OAC309', 'Customer Relations');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `User_id` int(11) NOT NULL,
  `Rfid_tag` varchar(50) NOT NULL,
  `F_name` varchar(100) NOT NULL,
  `L_name` varchar(100) NOT NULL,
  `CourseSection_id` int(11) DEFAULT NULL,
  `Role` enum('Student','Faculty','Admin') DEFAULT 'Student',
  `Status` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`User_id`, `Rfid_tag`, `F_name`, `L_name`, `CourseSection_id`, `Role`, `Status`) VALUES
(1, '44 22 95 04', 'John', 'Doe', 1, 'Student', 'Active'),
(2, 'D3 CB B1 38', 'Jane', 'Smith', 2, 'Student', 'Active'),
(3, 'STU3944', 'Mark', 'Reyes', 3, 'Student', 'Active'),
(6, 'FAC001', 'Anna', 'Cruz', NULL, 'Faculty', 'Active'),
(7, 'FAC002', 'Paul', 'Santos', NULL, 'Faculty', 'Active'),
(8, '61 DE 6A 05', 'Michael', 'Tan', NULL, 'Admin', 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access_log`
--
ALTER TABLE `access_log`
  ADD PRIMARY KEY (`Log_id`),
  ADD KEY `User_id` (`User_id`),
  ADD KEY `Room_id` (`Room_id`),
  ADD KEY `Schedule_id` (`Schedule_id`);

--
-- Indexes for table `classrooms`
--
ALTER TABLE `classrooms`
  ADD PRIMARY KEY (`Room_id`),
  ADD UNIQUE KEY `Room_code` (`Room_code`);

--
-- Indexes for table `course_section`
--
ALTER TABLE `course_section`
  ADD PRIMARY KEY (`CourseSection_id`),
  ADD UNIQUE KEY `CourseSection` (`CourseSection`);

--
-- Indexes for table `rfid_reader`
--
ALTER TABLE `rfid_reader`
  ADD PRIMARY KEY (`Reader_id`),
  ADD KEY `Room_id` (`Room_id`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`Schedule_id`),
  ADD KEY `Subject_id` (`Subject_id`),
  ADD KEY `Room_id` (`Room_id`),
  ADD KEY `Faculty_id` (`Faculty_id`);

--
-- Indexes for table `schedule_access`
--
ALTER TABLE `schedule_access`
  ADD PRIMARY KEY (`Rule_id`),
  ADD KEY `Schedule_id` (`Schedule_id`),
  ADD KEY `CourseSection_id` (`CourseSection_id`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`Subject_id`),
  ADD UNIQUE KEY `Code` (`Code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`User_id`),
  ADD UNIQUE KEY `Rfid_tag` (`Rfid_tag`),
  ADD KEY `CourseSection_id` (`CourseSection_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access_log`
--
ALTER TABLE `access_log`
  MODIFY `Log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=409;

--
-- AUTO_INCREMENT for table `classrooms`
--
ALTER TABLE `classrooms`
  MODIFY `Room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `course_section`
--
ALTER TABLE `course_section`
  MODIFY `CourseSection_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

--
-- AUTO_INCREMENT for table `rfid_reader`
--
ALTER TABLE `rfid_reader`
  MODIFY `Reader_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `Schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `schedule_access`
--
ALTER TABLE `schedule_access`
  MODIFY `Rule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `Subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `User_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `access_log`
--
ALTER TABLE `access_log`
  ADD CONSTRAINT `fk_access_room` FOREIGN KEY (`Room_id`) REFERENCES `classrooms` (`Room_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_access_schedule` FOREIGN KEY (`Schedule_id`) REFERENCES `schedule` (`Schedule_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_access_user` FOREIGN KEY (`User_id`) REFERENCES `users` (`User_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `rfid_reader`
--
ALTER TABLE `rfid_reader`
  ADD CONSTRAINT `rfid_reader_ibfk_1` FOREIGN KEY (`Room_id`) REFERENCES `classrooms` (`Room_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `schedule`
--
ALTER TABLE `schedule`
  ADD CONSTRAINT `fk_schedule_faculty` FOREIGN KEY (`Faculty_id`) REFERENCES `users` (`User_id`),
  ADD CONSTRAINT `fk_schedule_room` FOREIGN KEY (`Room_id`) REFERENCES `classrooms` (`Room_id`),
  ADD CONSTRAINT `fk_schedule_subject` FOREIGN KEY (`Subject_id`) REFERENCES `subject` (`Subject_id`);

--
-- Constraints for table `schedule_access`
--
ALTER TABLE `schedule_access`
  ADD CONSTRAINT `fk_schedule_access_course` FOREIGN KEY (`CourseSection_id`) REFERENCES `course_section` (`CourseSection_id`),
  ADD CONSTRAINT `fk_schedule_access_schedule` FOREIGN KEY (`Schedule_id`) REFERENCES `schedule` (`Schedule_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_course` FOREIGN KEY (`CourseSection_id`) REFERENCES `course_section` (`CourseSection_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
