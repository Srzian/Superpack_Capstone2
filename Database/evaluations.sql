-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 01, 2025 at 09:48 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `employee_evaluations`
--

-- --------------------------------------------------------

--
-- Table structure for table `evaluations`
--

CREATE TABLE `evaluations` (
  `id` int(11) NOT NULL,
  `employee_name` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `evaluation_date` date NOT NULL,
  `evaluator_name` varchar(255) NOT NULL,
  `remarks` text DEFAULT NULL,
  `work_quality` int(11) DEFAULT NULL,
  `efficiency_productivity` int(11) DEFAULT NULL,
  `communication_skills` int(11) DEFAULT NULL,
  `teamwork_collaboration` int(11) DEFAULT NULL,
  `initiative_problem_solving` int(11) DEFAULT NULL,
  `adaptability_flexibility` int(11) DEFAULT NULL,
  `reliability_accountability` int(11) DEFAULT NULL,
  `attendance_punctuality` int(11) DEFAULT NULL,
  `professionalism_work_ethic` int(11) DEFAULT NULL,
  `overall_performance` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `evaluations`
--

INSERT INTO `evaluations` (`id`, `employee_name`, `position`, `evaluation_date`, `evaluator_name`, `remarks`, `work_quality`, `efficiency_productivity`, `communication_skills`, `teamwork_collaboration`, `initiative_problem_solving`, `adaptability_flexibility`, `reliability_accountability`, `attendance_punctuality`, `professionalism_work_ethic`, `overall_performance`) VALUES
(1, 'Ian Fabro', 'manager', '2025-04-01', 'Nancy', 'work good', 10, 10, 10, 10, 10, 10, 10, 10, 10, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
