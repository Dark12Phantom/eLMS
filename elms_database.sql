-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 19, 2025 at 10:32 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `elms_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `activitiestable`
--

CREATE TABLE `activitiestable` (
  `id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `due_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `announcementtable`
--

CREATE TABLE `announcementtable` (
  `id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `type` enum('notice','announcement') DEFAULT NULL,
  `message` text DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coursestable`
--

CREATE TABLE `coursestable` (
  `id` int(11) NOT NULL,
  `courseID` varchar(20) DEFAULT NULL,
  `courseName` varchar(100) DEFAULT NULL,
  `courseSchedule` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `enrollmenttable`
--

CREATE TABLE `enrollmenttable` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `status` enum('pending','approved','denied') DEFAULT NULL,
  `enrolled_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gradestable`
--

CREATE TABLE `gradestable` (
  `id` int(11) NOT NULL,
  `submission_id` int(11) DEFAULT NULL,
  `grade` decimal(5,2) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `graded_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `modulestable`
--

CREATE TABLE `modulestable` (
  `id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `studentprogress`
--

CREATE TABLE `studentprogress` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `progress` decimal(5,2) NOT NULL DEFAULT 0.00,
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ;

-- --------------------------------------------------------

--
-- Table structure for table `submissionstable`
--

CREATE TABLE `submissionstable` (
  `id` int(11) NOT NULL,
  `activity_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `submitted_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trackingtable`
--

CREATE TABLE `trackingtable` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `action_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `userstable`
--

CREATE TABLE `userstable` (
  `id` int(11) NOT NULL,
  `userID` varchar(20) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `middleName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `suffix` varchar(10) DEFAULT NULL,
  `gender` enum('M','F','Other') NOT NULL,
  `age` int(11) NOT NULL,
  `birthDate` date NOT NULL,
  `bio` text NOT NULL,
  `role` enum('admin','trainer','trainee') NOT NULL,
  `mobileNumber` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `education` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userstable`
--

INSERT INTO `userstable` (`id`, `userID`, `firstName`, `middleName`, `lastName`, `suffix`, `gender`, `age`, `birthDate`, `bio`, `role`, `mobileNumber`, `email`, `password`, `education`) VALUES
(21, '2025S-000001', 'Erick', 'Cat', 'Gaceta', '', 'M', 22, '2025-08-28', 'Student of Benguet Technical School', 'trainee', '+639201555544', 'gacetaerick124@gmail.com', '$2y$10$vpy.68.Y/QDm6WLf2btzzuIA2I9tHHQy3Zixc5eQPkzAuZICs.GRq', 'College');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activitiestable`
--
ALTER TABLE `activitiestable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `announcementtable`
--
ALTER TABLE `announcementtable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `coursestable`
--
ALTER TABLE `coursestable`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `courseID` (`courseID`);

--
-- Indexes for table `enrollmenttable`
--
ALTER TABLE `enrollmenttable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `gradestable`
--
ALTER TABLE `gradestable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `submission_id` (`submission_id`);

--
-- Indexes for table `modulestable`
--
ALTER TABLE `modulestable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `studentprogress`
--
ALTER TABLE `studentprogress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `submissionstable`
--
ALTER TABLE `submissionstable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_id` (`activity_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `trackingtable`
--
ALTER TABLE `trackingtable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `userstable`
--
ALTER TABLE `userstable`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `userID` (`userID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activitiestable`
--
ALTER TABLE `activitiestable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `announcementtable`
--
ALTER TABLE `announcementtable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coursestable`
--
ALTER TABLE `coursestable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `enrollmenttable`
--
ALTER TABLE `enrollmenttable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gradestable`
--
ALTER TABLE `gradestable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `modulestable`
--
ALTER TABLE `modulestable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `studentprogress`
--
ALTER TABLE `studentprogress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `submissionstable`
--
ALTER TABLE `submissionstable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trackingtable`
--
ALTER TABLE `trackingtable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `userstable`
--
ALTER TABLE `userstable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activitiestable`
--
ALTER TABLE `activitiestable`
  ADD CONSTRAINT `activitiestable_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `coursestable` (`id`),
  ADD CONSTRAINT `activitiestable_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `userstable` (`id`);

--
-- Constraints for table `announcementtable`
--
ALTER TABLE `announcementtable`
  ADD CONSTRAINT `announcementtable_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `coursestable` (`id`),
  ADD CONSTRAINT `announcementtable_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `userstable` (`id`);

--
-- Constraints for table `coursestable`
--
ALTER TABLE `coursestable`
  ADD CONSTRAINT `coursestable_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `userstable` (`id`);

--
-- Constraints for table `enrollmenttable`
--
ALTER TABLE `enrollmenttable`
  ADD CONSTRAINT `enrollmenttable_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `userstable` (`id`),
  ADD CONSTRAINT `enrollmenttable_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `userstable` (`id`),
  ADD CONSTRAINT `enrollmenttable_ibfk_3` FOREIGN KEY (`course_id`) REFERENCES `coursestable` (`id`);

--
-- Constraints for table `gradestable`
--
ALTER TABLE `gradestable`
  ADD CONSTRAINT `gradestable_ibfk_1` FOREIGN KEY (`submission_id`) REFERENCES `submissionstable` (`id`);

--
-- Constraints for table `modulestable`
--
ALTER TABLE `modulestable`
  ADD CONSTRAINT `modulestable_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `coursestable` (`id`);

--
-- Constraints for table `studentprogress`
--
ALTER TABLE `studentprogress`
  ADD CONSTRAINT `studentprogress_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `userstable` (`id`),
  ADD CONSTRAINT `studentprogress_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `coursestable` (`id`);

--
-- Constraints for table `submissionstable`
--
ALTER TABLE `submissionstable`
  ADD CONSTRAINT `submissionstable_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activitiestable` (`id`),
  ADD CONSTRAINT `submissionstable_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `userstable` (`id`);

--
-- Constraints for table `trackingtable`
--
ALTER TABLE `trackingtable`
  ADD CONSTRAINT `trackingtable_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `userstable` (`id`),
  ADD CONSTRAINT `trackingtable_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `coursestable` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
