-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 21, 2025 at 09:09 AM
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
-- Table structure for table `basiccompetency`
--

CREATE TABLE `basiccompetency` (
  `id` int(11) NOT NULL,
  `courseID` int(30) NOT NULL,
  `basicPoints` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `basiccompetency`
--

INSERT INTO `basiccompetency` (`id`, `courseID`, `basicPoints`) VALUES
(1, 3, 'Receive and respond to workplace communication'),
(2, 3, 'Work with others'),
(3, 3, 'Demonstrate work values'),
(4, 4, 'Participate in workplace communication'),
(5, 4, 'Work in team environment'),
(6, 5, 'Receive and respond to workplace communication'),
(7, 5, 'Work with others'),
(8, 5, 'Demonstrate work values'),
(9, 6, 'Receive and respond to workplace communication'),
(10, 6, 'Work with others'),
(11, 7, 'Participate in workplace communication'),
(12, 7, 'Work in team environment'),
(13, 8, 'Participate in workplace communication'),
(14, 8, 'Work in team environment'),
(15, 9, 'Observe road safety'),
(16, 9, 'Work in team environment'),
(17, 10, 'Participate in workplace communication'),
(18, 10, 'Work with others');

-- --------------------------------------------------------

--
-- Table structure for table `commoncompetency`
--

CREATE TABLE `commoncompetency` (
  `id` int(11) NOT NULL,
  `courseID` int(30) NOT NULL,
  `commonPoints` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `commoncompetency`
--

INSERT INTO `commoncompetency` (`id`, `courseID`, `commonPoints`) VALUES
(1, 3, 'Apply safety measures\r\n'),
(2, 3, 'Use farm tools and equipment'),
(3, 4, 'Use automotive hand tools'),
(4, 4, 'Perform mensuration and calculations'),
(5, 5, 'Use of tools, equipment and facilities'),
(6, 5, 'Perform mensuration and calculations'),
(7, 6, 'Use sewing tools and equipment'),
(8, 6, 'Draft and cut patterns'),
(9, 7, 'Use hairdressing tools'),
(10, 7, 'Practice personal hygiene'),
(11, 8, 'Demonstrate cultural sensitivity'),
(12, 8, 'Practice correct pronunciation'),
(13, 9, 'Check and inspect vehicles'),
(14, 9, 'Perform basic troubleshooting'),
(15, 10, 'Use of tools and equipment'),
(16, 10, 'Practice proper cutting techniques');

-- --------------------------------------------------------

--
-- Table structure for table `corecompetency`
--

CREATE TABLE `corecompetency` (
  `id` int(11) NOT NULL,
  `courseID` int(30) NOT NULL,
  `corePoints` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `corecompetency`
--

INSERT INTO `corecompetency` (`id`, `courseID`, `corePoints`) VALUES
(1, 3, 'Prepare land for agricultural crop production'),
(2, 3, 'Care and manage crops'),
(3, 4, 'Perform under-chassis preventive maintenance'),
(4, 4, 'Service battery and ignition system'),
(5, 5, 'Prepare bakery products'),
(6, 5, 'Prepare pastry products'),
(7, 6, 'Sew casual dresses'),
(8, 6, 'Sew skirts and blouses'),
(9, 7, 'Perform hair cutting'),
(10, 7, 'Perform hair coloring'),
(11, 7, 'Perform hair styling'),
(12, 8, 'Speak basic Nihongo'),
(13, 8, 'Understand Japanese customs and etiquette'),
(14, 9, 'Operate light vehicles'),
(15, 9, 'Perform defensive driving techniques'),
(16, 10, 'Construct men’s trousers'),
(17, 10, 'Construct polo shirts and barong');

-- --------------------------------------------------------

--
-- Table structure for table `coursestable`
--

CREATE TABLE `coursestable` (
  `id` int(11) NOT NULL,
  `courseID` varchar(20) DEFAULT NULL,
  `courseName` varchar(100) DEFAULT NULL,
  `courseSchedule` varchar(100) DEFAULT NULL,
  `description` text NOT NULL,
  `filePath` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coursestable`
--

INSERT INTO `coursestable` (`id`, `courseID`, `courseName`, `courseSchedule`, `description`, `filePath`) VALUES
(3, 'AGRINCII', 'Agricultural Crops Production NC II', 'M-F 1:00 - 3:00', 'Learn crop production, farm tools usage, and sustainable agriculture techniques.', 'uploads/images/agriculture.jpg'),
(4, 'ASNCI', 'Automotive Servicing NC I', 'T-Th 9:00 - 11:00', 'Get started with basic automotive maintenance and repair services.', 'uploads/images/automotive.jpg'),
(5, 'BAPPNCII', 'Bread and Pastry Production NC II', 'M-W 9:00 - 11:00', 'Master the fundamentals of baking and pastry preparation.', 'uploads/images/breadmaking.jpg'),
(6, 'DRSNCII', 'Dressmaking NC II', 'F-Sat 10:00 - 12:00', 'Learn how to design, measure, cut, and sew dresses professionally.', 'uploads/images/dressmaking.webp'),
(7, 'HDSNCII', 'Hairdressing NC II', 'Th-F 9:00 -12:00', 'Gain skills in hair cutting, coloring, styling, and salon operations.', 'uploads/images/hairdressing.webp'),
(8, 'JLC', 'Japanese Language and Culture', 'W 1:00-4:00', 'Study basic Nihongo and understand essential aspects of Japanese culture.', 'uploads/images/japanese.jpg'),
(9, 'DRINCII', 'Driving NC II', 'Sat 8:00 - 12:00', 'Develop safe driving skills and gain vehicle operation knowledge.', 'uploads/images/driving.webp'),
(10, 'TNCII', 'Tailoring NC II', 'T 9:00 - 12:00', 'Train in precision tailoring, pattern making, and garment construction.', 'uploads/images/tailoring.webp');

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
-- Table structure for table `trainerstable`
--

CREATE TABLE `trainerstable` (
  `id` int(11) NOT NULL,
  `courseID` int(30) NOT NULL,
  `trainerID` varchar(30) NOT NULL,
  `assignedDate` datetime NOT NULL DEFAULT current_timestamp()
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
  `education` text NOT NULL,
  `profileImage` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userstable`
--

INSERT INTO `userstable` (`id`, `userID`, `firstName`, `middleName`, `lastName`, `suffix`, `gender`, `age`, `birthDate`, `bio`, `role`, `mobileNumber`, `email`, `password`, `education`, `profileImage`) VALUES
(21, '2025S-000001', 'Erick', 'Cats', 'Gaceta', '', 'M', 22, '2025-08-28', 'Student of Benguet Technical School', 'trainee', '+639201555544', 'gacetaerick124@gmail.com', '$2y$10$vpy.68.Y/QDm6WLf2btzzuIA2I9tHHQy3Zixc5eQPkzAuZICs.GRq', 'College', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activitiestable`
--
ALTER TABLE `activitiestable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activitiestable_ibfk_1` (`course_id`),
  ADD KEY `activitiestable_ibfk_2` (`created_by`);

--
-- Indexes for table `announcementtable`
--
ALTER TABLE `announcementtable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `announcementtable_ibfk_1` (`course_id`),
  ADD KEY `announcementtable_ibfk_2` (`created_by`);

--
-- Indexes for table `basiccompetency`
--
ALTER TABLE `basiccompetency`
  ADD PRIMARY KEY (`id`),
  ADD KEY `courseID` (`courseID`);

--
-- Indexes for table `commoncompetency`
--
ALTER TABLE `commoncompetency`
  ADD PRIMARY KEY (`id`),
  ADD KEY `courseID` (`courseID`);

--
-- Indexes for table `corecompetency`
--
ALTER TABLE `corecompetency`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_fetcher_core` (`courseID`);

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
  ADD KEY `modulestable_ibfk_1` (`course_id`);

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
-- Indexes for table `trainerstable`
--
ALTER TABLE `trainerstable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trainerBind` (`trainerID`),
  ADD KEY `courseID` (`courseID`);

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
-- AUTO_INCREMENT for table `basiccompetency`
--
ALTER TABLE `basiccompetency`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `commoncompetency`
--
ALTER TABLE `commoncompetency`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `corecompetency`
--
ALTER TABLE `corecompetency`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `coursestable`
--
ALTER TABLE `coursestable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
-- AUTO_INCREMENT for table `trainerstable`
--
ALTER TABLE `trainerstable`
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
  ADD CONSTRAINT `activitiestable_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `coursestable` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `activitiestable_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `userstable` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `announcementtable`
--
ALTER TABLE `announcementtable`
  ADD CONSTRAINT `announcementtable_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `coursestable` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `announcementtable_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `userstable` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `basiccompetency`
--
ALTER TABLE `basiccompetency`
  ADD CONSTRAINT `basiccompetency_ibfk_1` FOREIGN KEY (`courseID`) REFERENCES `coursestable` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `commoncompetency`
--
ALTER TABLE `commoncompetency`
  ADD CONSTRAINT `commoncompetency_ibfk_1` FOREIGN KEY (`courseID`) REFERENCES `coursestable` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `modulestable_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `coursestable` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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

--
-- Constraints for table `trainerstable`
--
ALTER TABLE `trainerstable`
  ADD CONSTRAINT `trainerBind` FOREIGN KEY (`trainerID`) REFERENCES `userstable` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `trainerstable_ibfk_1` FOREIGN KEY (`courseID`) REFERENCES `coursestable` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
