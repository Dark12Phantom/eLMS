-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 16, 2025 at 05:12 PM
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
  `course_id` varchar(30) DEFAULT NULL,
  `created_by` varchar(30) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `due_date` datetime DEFAULT NULL,
  `type` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activitiestable`
--

INSERT INTO `activitiestable` (`id`, `course_id`, `created_by`, `title`, `description`, `file_path`, `created_at`, `due_date`, `type`) VALUES
(16, 'AGRINCII', '2025T-00001', 'dagadg', 'adfgdsg', '../uploads/activities/Activity_dagadg_AGRINCII.txt', '2025-08-31 16:56:42', '2025-08-31 00:00:00', 'Activity'),
(17, 'AGRINCII', '2025T-00001', 'dasfdasfadsgfad', 'asdgafdgadfgadgfadfds', NULL, '2025-08-31 21:57:40', '2025-08-31 00:00:00', 'Exam');

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

--
-- Dumping data for table `announcementtable`
--

INSERT INTO `announcementtable` (`id`, `course_id`, `created_by`, `type`, `message`, `expires_at`, `created_at`) VALUES
(36, 3, NULL, 'announcement', 'WAS DONT', NULL, '2025-09-02 12:17:01');

-- --------------------------------------------------------

--
-- Table structure for table `assignedcourses`
--

CREATE TABLE `assignedcourses` (
  `id` int(11) NOT NULL,
  `course_id` varchar(30) NOT NULL,
  `trainer_id` varchar(30) NOT NULL,
  `trainerName` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assignedcourses`
--

INSERT INTO `assignedcourses` (`id`, `course_id`, `trainer_id`, `trainerName`) VALUES
(1, 'AGRINCII', '2025T-00001', 'Harley David Son');

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
(1, 3, 'Participate in workplace communication '),
(2, 3, 'Work in a team environment'),
(3, 3, 'Solve/address general workplace problems '),
(4, 3, 'Develop career and life decisions '),
(5, 3, 'Contribute to workplace innovation '),
(6, 3, 'Present relevant information '),
(7, 3, 'Practice occupational health and safety procedures '),
(8, 3, 'Exercise efficient and effective sustainable practices in the workplace '),
(10, 3, 'Practice entrepreneurial skills in the workplace '),
(11, 4, 'Receive and respond to workplace communication'),
(12, 4, 'Work with others'),
(13, 4, 'Solve/address routine problems'),
(14, 4, 'Enhance self-management skills'),
(15, 4, 'Support innovation'),
(16, 4, 'Access and maintain information'),
(17, 4, 'Follow occupational safety and health policies and procedures'),
(18, 4, 'Apply environmental work standards'),
(19, 4, 'Adopt entrepreneurial mindset in the workplace'),
(20, 5, 'Participate in workplace communication'),
(21, 5, 'Work in a team environment'),
(23, 5, 'Solve/address general workplace problems'),
(24, 5, 'Develop career and life decisions'),
(25, 5, 'Contribute to workplace innovation'),
(26, 5, 'Present relevant information'),
(27, 5, 'Practice occupational health and safety procedures'),
(28, 5, 'Exercise efficient and effective sustainable practices in the workplace'),
(29, 5, 'Practice entrepreneurial skills in the workplace'),
(30, 6, 'Participate in workplace communication'),
(31, 6, 'Work in a team environment'),
(32, 6, 'Practice career professionalism'),
(33, 6, 'Practice occupational health and safety'),
(34, 7, 'Participate in workplace communication'),
(35, 7, 'Work in a team environment'),
(36, 7, 'Solve/address general workplace problems'),
(37, 7, 'Develop career and life decisions'),
(38, 7, 'Contribute to workplace innovation'),
(39, 7, 'Present relevant information'),
(40, 7, 'Practice occupational health and safety procedures'),
(41, 7, 'Exercise efficient and effective sustainable practices in the workplace'),
(42, 7, 'Practice entrepreneurial skills in the workplace'),
(43, 8, 'Participate in workplace communication'),
(44, 8, 'Work in a team environment'),
(45, 8, 'Practice career professionalism'),
(46, 8, 'Practice occupational health and safety'),
(47, 9, 'Participate in workplace communication'),
(48, 9, 'Work in a team environment '),
(49, 9, 'Solve/address general workplace problems'),
(50, 9, 'Develop career and life decisions'),
(51, 9, 'Contribute to workplace innovation'),
(52, 9, 'Present relevant information'),
(53, 9, 'Practice occupational health and safety procedures '),
(54, 9, 'Exercise efficient and effective sustainable practices in the workplace '),
(55, 9, 'Practice entrepreneurial skills in the workplace '),
(56, 10, 'Participate in workplace communication'),
(57, 10, 'Work in a team environment'),
(58, 10, 'Solve/address general workplace problems'),
(59, 10, 'Develop career and life decisions'),
(60, 10, 'Contribute to workplace innovation'),
(61, 10, 'Present relevant information'),
(62, 10, 'Practice occupational health and safety procedures'),
(63, 10, 'Exercise efficient and effective sustainable practices in the workplace'),
(64, 10, 'Practice entrepreneurial skills in the workplace');

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
(1, 3, 'Apply Safety Measures in Farm Operations '),
(2, 3, 'Use farm tools and equipment'),
(3, 3, 'Perform estimation and basic calculation '),
(4, 3, 'Process farm wastes'),
(5, 3, 'Perform record keeping'),
(6, 4, 'Validate vehicle specification'),
(7, 4, 'Move and Position Vehicle'),
(8, 4, 'Utilize automotive tools'),
(9, 4, 'Perform mensuration and calculation'),
(10, 4, 'Utilize workshop facilities and equipment'),
(11, 4, 'Prepare servicing parts and consumables'),
(12, 4, 'Prepare vehicle for servicing and releasing'),
(13, 5, 'Develop and Updated Industry Knowledge'),
(14, 5, 'Observe Workplace Hygiene Procedures'),
(15, 5, 'Perform Computer Operations'),
(16, 5, 'Perform Workplace and Safety Practices'),
(17, 5, 'Provide Effective Customer Service'),
(18, 6, 'Carry Out Measurements and Calculations'),
(19, 6, 'Apply Quality Standards'),
(20, 6, 'Perform Basic Maintenance'),
(21, 6, 'Set Up and Operate Machines'),
(22, 7, 'Maintain an effective relationship with clients/ customers '),
(23, 7, 'Manage own performance'),
(24, 7, 'Apply quality standards'),
(25, 7, 'Maintain a safe clean and efficient work environment'),
(26, 8, 'Introduce the general features of Japanese Culture'),
(27, 8, 'Introduce the Japanese Work Ethics'),
(28, 9, 'Apply appropriate sealant/adhesive'),
(29, 9, 'Perform shop maintenance'),
(30, 9, 'Read, Interpret and Apply Specifications and Manual'),
(31, 9, 'Use and apply lubricant/coolant'),
(32, 9, 'Perform Mensuration and Calculation'),
(33, 9, 'Move and Position Vehicle'),
(34, 10, 'Carry Out Measurements and Calculations'),
(35, 10, 'Apply Quality Standards'),
(36, 10, 'Perform Basic Maintenance'),
(37, 10, 'Set Up and Operate Machines');

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
(1, 3, 'Perform nursery operations'),
(2, 3, 'Plant crops'),
(3, 3, 'Care and maintain crops'),
(4, 3, 'Carry-out harvest and postharvest operations'),
(5, 4, 'Perform pre-delivery inspection'),
(6, 4, 'Perform periodic maintenance of automotive engine'),
(7, 4, 'Perform periodic maintenance of drive train'),
(8, 4, 'Perform periodic maintenance of brake system'),
(9, 4, 'Perform periodic maintenance of suspension system'),
(10, 4, 'Perform periodic maintenance of steering system'),
(11, 5, 'Prepare and produce bakery products'),
(12, 5, 'Prepare and produce pastry products'),
(13, 5, 'Prepare and present gateaux, tortes and cakes'),
(14, 5, 'Prepare and display petits fours'),
(15, 5, 'Prepare and serve other types of desserts'),
(16, 6, 'Draft and Cut Pattern for Casual Apparel'),
(17, 6, 'Prepare and Cut Materials for Casual Apparel'),
(18, 6, 'Sew Casual Apparel'),
(19, 6, 'Apply Finishing Touches on Casual Apparel'),
(20, 7, 'Perform pre and post hair care activities'),
(21, 7, 'Perform Hair and Scalp Treatment'),
(22, 7, 'Perform Basic hair coloring'),
(23, 7, 'Perform Basic Hair Bleaching'),
(24, 7, 'Perform Basic Hair Perming'),
(25, 7, 'Perform hair straightening'),
(26, 7, 'Perform basic haircutting'),
(27, 8, 'Practice the basic Japanese Writing System and their Alphabets'),
(28, 8, 'Practice the Japanese Sounds of Alphabets and Pronunciation'),
(29, 8, 'Practice the Japanese Grammar and Application in Sentence Construction'),
(30, 8, 'Apply the Japanese Counters in a conversation'),
(31, 8, 'Perform Japanese Greetings   of honorifics in a conversation'),
(32, 8, 'Participate in Ordinary Conversation'),
(33, 9, 'Carry out minor vehicle maintenance and servicing'),
(34, 9, 'Drive light vehicles'),
(35, 9, 'Observe traffic rules and regulations'),
(36, 9, 'Implement and coordinate accident/ emergency procedures'),
(37, 10, 'Draft and Cut Pattern for Casual Apparel'),
(38, 10, 'Prepare and Cut Materials for Casual Apparel'),
(39, 10, 'Sew Casual\r\nApparel\r\n'),
(40, 10, 'Apply Finishing Touches on Casual Apparel');

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
  `filePath` text NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coursestable`
--

INSERT INTO `coursestable` (`id`, `courseID`, `courseName`, `courseSchedule`, `description`, `filePath`, `status`) VALUES
(3, 'AGRINCII', 'Agricultural Crops Production NC II', 'M-F 1:00 - 3:00', 'Learn crop production, farm tools usage, and sustainable agriculture techniques.', 'uploads/images/agriculture.jpg', 'Offered'),
(4, 'ASNCI', 'Automotive Servicing NC I', 'T-Th 9:00 - 11:00', 'Get started with basic automotive maintenance and repair services.', 'uploads/images/automotive.jpg', 'Offered'),
(5, 'BAPPNCII', 'Bread and Pastry Production NC II', 'M-W 9:00 - 11:00', 'Master the fundamentals of baking and pastry preparation.', 'uploads/images/breadmaking.jpg', 'Offered'),
(6, 'DRSNCII', 'Dressmaking NC II', 'F-Sat 10:00 - 12:00', 'Learn how to design, measure, cut, and sew dresses professionally.', 'uploads/images/dressmaking.jpg', 'Offered'),
(7, 'HDSNCII', 'Hairdressing NC II', 'Th-F 9:00 -12:00', 'Gain skills in hair cutting, coloring, styling, and salon operations.', 'uploads/images/hairdressing.webp', 'Offered'),
(8, 'JLC', 'Japanese Language and Culture', 'W 1:00-4:00', 'Study basic Nihongo and understand essential aspects of Japanese culture.', 'uploads/images/japanese.jpg', 'Offered'),
(9, 'DRINCII', 'Driving NC II', 'Sat 8:00 - 12:00', 'Develop safe driving skills and gain vehicle operation knowledge.', 'uploads/images/driving.jpg', 'Not Offered'),
(10, 'TNCII', 'Tailoring NC II', 'T 9:00 - 12:00', 'Train in precision tailoring, pattern making, and garment construction.', 'uploads/images/tailoring.webp', 'Offered');

-- --------------------------------------------------------

--
-- Table structure for table `coursetracker`
--

CREATE TABLE `coursetracker` (
  `id` int(11) NOT NULL,
  `course_id` varchar(30) NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coursetracker`
--

INSERT INTO `coursetracker` (`id`, `course_id`, `status`) VALUES
(1, 'AGRINCII', 'enabled');

-- --------------------------------------------------------

--
-- Table structure for table `enrolledtable`
--

CREATE TABLE `enrolledtable` (
  `id` int(11) NOT NULL,
  `course_id` varchar(30) NOT NULL,
  `user_id` varchar(30) NOT NULL,
  `enrollment_id` int(11) NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrolledtable`
--

INSERT INTO `enrolledtable` (`id`, `course_id`, `user_id`, `enrollment_id`, `status`) VALUES
(8, 'AGRINCII', '2025S-000001', 34, 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `enrollmenttable`
--

CREATE TABLE `enrollmenttable` (
  `id` int(11) NOT NULL,
  `user_id` varchar(30) DEFAULT NULL,
  `course_id` varchar(30) DEFAULT NULL,
  `teacher_id` varchar(30) DEFAULT NULL,
  `status` enum('pending','approved','denied') DEFAULT NULL,
  `enrolled_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollmenttable`
--

INSERT INTO `enrollmenttable` (`id`, `user_id`, `course_id`, `teacher_id`, `status`, `enrolled_at`) VALUES
(34, '2025S-000001', 'AGRINCII', NULL, 'approved', '2025-09-14 16:46:13');

-- --------------------------------------------------------

--
-- Table structure for table `finalgradestable`
--

CREATE TABLE `finalgradestable` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `total_grade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gradestable`
--

CREATE TABLE `gradestable` (
  `id` int(11) NOT NULL,
  `submission_id` int(11) DEFAULT NULL,
  `studentID` varchar(30) NOT NULL,
  `grade` decimal(5,2) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `graded_at` datetime DEFAULT current_timestamp(),
  `remarks` text NOT NULL,
  `score` int(11) NOT NULL,
  `totalItems` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `modulestable`
--

CREATE TABLE `modulestable` (
  `id` int(11) NOT NULL,
  `course_id` varchar(30) DEFAULT NULL,
  `trainerID` varchar(30) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `modulestable`
--

INSERT INTO `modulestable` (`id`, `course_id`, `trainerID`, `title`, `description`, `file_path`, `created_at`) VALUES
(14, 'AGRINCII', '2025T-00001', 'PERFOMING NURSERY OPERATION', 'Competency-based Learning Material', '../uploads/modules/Module_PERFOMING NURSERY OPERATION_AGRINCII.pdf', '2025-09-16 21:51:41');

-- --------------------------------------------------------

--
-- Table structure for table `studentprogress`
--

CREATE TABLE `studentprogress` (
  `id` int(11) NOT NULL,
  `studentID` varchar(30) NOT NULL,
  `course_id` varchar(30) NOT NULL,
  `courseName` text NOT NULL,
  `trackingID` int(11) NOT NULL,
  `submittedActivity` int(11) NOT NULL,
  `submittedExam` int(11) NOT NULL,
  `submittedProjects` int(11) NOT NULL,
  `progress` decimal(5,2) NOT NULL DEFAULT 0.00,
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `studentprogress`
--

INSERT INTO `studentprogress` (`id`, `studentID`, `course_id`, `courseName`, `trackingID`, `submittedActivity`, `submittedExam`, `submittedProjects`, `progress`, `last_updated`) VALUES
(28, '2025S-000001', 'AGRINCII', 'Agricultural Crops Production NC II', 1, 1, 0, 0, 0.36, '2025-09-14 16:47:41');

--
-- Triggers `studentprogress`
--
DELIMITER $$
CREATE TRIGGER `update_student_status` AFTER INSERT ON `studentprogress` FOR EACH ROW BEGIN
    DECLARE course_count INT;
    
    SELECT COUNT(*) INTO course_count 
    FROM studentprogress 
    WHERE studentID = NEW.studentID;
    
    IF course_count > 0 THEN
        UPDATE traineestable 
        SET status = 'Ongoing' 
        WHERE studentID = NEW.studentID;
    ELSE
        UPDATE traineestable 
        SET status = 'Idle' 
        WHERE studentID = NEW.studentID;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_student_status_delete` AFTER DELETE ON `studentprogress` FOR EACH ROW BEGIN
    DECLARE course_count INT;
    
    SELECT COUNT(*) INTO course_count 
    FROM studentprogress 
    WHERE studentID = OLD.studentID;
    
    IF course_count > 0 THEN
        UPDATE traineestable 
        SET status = 'Ongoing' 
        WHERE studentID = OLD.studentID;
    ELSE
        UPDATE traineestable 
        SET status = 'Idle' 
        WHERE studentID = OLD.studentID;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `submissionstable`
--

CREATE TABLE `submissionstable` (
  `id` int(11) NOT NULL,
  `activity_id` int(11) DEFAULT NULL,
  `student_id` varchar(30) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `submitted_at` datetime DEFAULT current_timestamp(),
  `type` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `submissionstable`
--

INSERT INTO `submissionstable` (`id`, `activity_id`, `student_id`, `file_path`, `submitted_at`, `type`) VALUES
(1, 16, '2025S-000001', 'uploads/submissions/2025S-000001_16_1756652858.txt', '2025-08-31 23:07:38', '');

-- --------------------------------------------------------

--
-- Table structure for table `timetracker`
--

CREATE TABLE `timetracker` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trackingtable`
--

CREATE TABLE `trackingtable` (
  `id` int(11) NOT NULL,
  `course_id` varchar(30) DEFAULT NULL,
  `totalActivity` int(11) NOT NULL,
  `totalExam` int(11) NOT NULL,
  `totalProjects` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trackingtable`
--

INSERT INTO `trackingtable` (`id`, `course_id`, `totalActivity`, `totalExam`, `totalProjects`) VALUES
(1, 'AGRINCII', 92, 6, 2),
(2, 'TNCII', 78, 4, 3),
(3, 'JLC', 91, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `traineestable`
--

CREATE TABLE `traineestable` (
  `id` int(11) NOT NULL,
  `studentID` varchar(30) NOT NULL,
  `studentName` text NOT NULL,
  `status` text NOT NULL,
  `enrolledDate` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `traineestable`
--

INSERT INTO `traineestable` (`id`, `studentID`, `studentName`, `status`, `enrolledDate`) VALUES
(11, '2025S-00003', 'Ruby Xander Cube', 'Idle', '2025-09-02');

-- --------------------------------------------------------

--
-- Table structure for table `trainercourses`
--

CREATE TABLE `trainercourses` (
  `id` int(11) NOT NULL,
  `trainerID` varchar(50) NOT NULL,
  `courseID` varchar(50) NOT NULL,
  `courseName` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trainercourses`
--

INSERT INTO `trainercourses` (`id`, `trainerID`, `courseID`, `courseName`) VALUES
(4, '2025T-00001', 'AGRINCII', 'Agricultural Crops Production NC II');

-- --------------------------------------------------------

--
-- Table structure for table `trainerstable`
--

CREATE TABLE `trainerstable` (
  `id` int(11) NOT NULL,
  `trainerID` varchar(30) NOT NULL,
  `trainerName` text NOT NULL,
  `status` text NOT NULL,
  `assignedDate` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trainerstable`
--

INSERT INTO `trainerstable` (`id`, `trainerID`, `trainerName`, `status`, `assignedDate`) VALUES
(29, '2025T-00001', 'Harley David Son', 'active', '2025-08-25');

-- --------------------------------------------------------

--
-- Stand-in structure for view `trainers_view`
-- (See below for the actual view)
--
CREATE TABLE `trainers_view` (
`trainerID` int(11)
,`trainerName` varchar(163)
);

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
  `role` enum('guest','admin','trainer','trainee') NOT NULL,
  `mobileNumber` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `education` text NOT NULL,
  `profileImage` text NOT NULL,
  `dateCreated` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userstable`
--

INSERT INTO `userstable` (`id`, `userID`, `firstName`, `middleName`, `lastName`, `suffix`, `gender`, `age`, `birthDate`, `bio`, `role`, `mobileNumber`, `email`, `password`, `education`, `profileImage`, `dateCreated`) VALUES
(21, '2025S-000001', 'Erick', 'Cats', 'Gaceta', '', 'M', 22, '2025-08-28', 'Student of Benguet Technical School', 'guest', '+639201555544', 'gacetaerick124@gmail.com', '$2y$10$vpy.68.Y/QDm6WLf2btzzuIA2I9tHHQy3Zixc5eQPkzAuZICs.GRq', 'College', 'uploads/profiles/user_21_1755881782.png', '2025-08-23'),
(31, '2025A-000006', 'Anne', 'Sacramento', 'Thesia', '', 'F', 45, '1980-06-18', 'Graduate of Doctor of Philosophy (PhD) in Administration and Supervision', 'admin', '+639201551234', 'annesthesia@bts.gov.ph', '$2y$10$FF7v5rny92ODpf4jahUAxe0f5.u8P6UfmcoqNNlI3BCyBN5AWeA36', 'Graduate', 'uploads/profiles/user_31_1756211718.jpg', '2025-08-23'),
(32, '2025S-00002', 'Dre', 'Santos', 'Maker', 'JR', 'M', 26, '1999-03-23', 'Student of Benguet Technical School', 'guest', '+639692012345', 'dre.ss.maker@gmail.com', '$2y$10$YiBnAu5mAwNi9n55zjT94eshUvQfxetlZUhzuQSUMoq/xSKp4A3p2', 'SHS', '', '2025-08-23'),
(57, '2025T-00001', 'Harley', 'David', 'Son', '', 'M', 0, '2025-08-26', '', 'trainer', '+639201555544', 'harley.son@bts.gov.ph', '$2y$10$/rtkNK9mJSIwt/alWtq4euZeCcoxAAD6QeQpHdOx4eg1zZL0owXoq', 'Bachelor\'s Degree', '', '2025-08-25'),
(59, '2025S-00003', 'Ruby', 'Xander', 'Cube', '', 'F', 23, '2002-02-05', 'Senior High School', 'trainee', '+639019283786', 'ruby.cube@bts.gov.ph', '$2y$10$YnXr4wqrQlvLHgVnr/Gmh.XlDDR.lZ23hRCcQ53hdx.GVCNVdhFN6', 'SHS', '', '2025-09-02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activitiestable`
--
ALTER TABLE `activitiestable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `announcementtable`
--
ALTER TABLE `announcementtable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `announcementtable_ibfk_1` (`course_id`),
  ADD KEY `announcementtable_ibfk_2` (`created_by`);

--
-- Indexes for table `assignedcourses`
--
ALTER TABLE `assignedcourses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `trainer_id` (`trainer_id`);

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
-- Indexes for table `coursetracker`
--
ALTER TABLE `coursetracker`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `enrolledtable`
--
ALTER TABLE `enrolledtable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `enrollment_id` (`enrollment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `enrollmenttable`
--
ALTER TABLE `enrollmenttable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `finalgradestable`
--
ALTER TABLE `finalgradestable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `gradestable`
--
ALTER TABLE `gradestable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `submission_id` (`submission_id`),
  ADD KEY `studentID` (`studentID`);

--
-- Indexes for table `modulestable`
--
ALTER TABLE `modulestable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `trainerID` (`trainerID`);

--
-- Indexes for table `studentprogress`
--
ALTER TABLE `studentprogress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trackingID` (`trackingID`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `studentprogress_ibfk_5` (`studentID`);

--
-- Indexes for table `submissionstable`
--
ALTER TABLE `submissionstable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `activity_id` (`activity_id`);

--
-- Indexes for table `timetracker`
--
ALTER TABLE `timetracker`
  ADD PRIMARY KEY (`id`),
  ADD KEY `timetracker_ibfk_1` (`user_id`);

--
-- Indexes for table `trackingtable`
--
ALTER TABLE `trackingtable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `traineestable`
--
ALTER TABLE `traineestable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `studentID` (`studentID`);

--
-- Indexes for table `trainercourses`
--
ALTER TABLE `trainercourses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trainerID` (`trainerID`),
  ADD KEY `courseID` (`courseID`);

--
-- Indexes for table `trainerstable`
--
ALTER TABLE `trainerstable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trainerBind` (`trainerID`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `announcementtable`
--
ALTER TABLE `announcementtable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `assignedcourses`
--
ALTER TABLE `assignedcourses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `basiccompetency`
--
ALTER TABLE `basiccompetency`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `commoncompetency`
--
ALTER TABLE `commoncompetency`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `corecompetency`
--
ALTER TABLE `corecompetency`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `coursestable`
--
ALTER TABLE `coursestable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `coursetracker`
--
ALTER TABLE `coursetracker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `enrolledtable`
--
ALTER TABLE `enrolledtable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `enrollmenttable`
--
ALTER TABLE `enrollmenttable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `finalgradestable`
--
ALTER TABLE `finalgradestable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gradestable`
--
ALTER TABLE `gradestable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `modulestable`
--
ALTER TABLE `modulestable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `studentprogress`
--
ALTER TABLE `studentprogress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `submissionstable`
--
ALTER TABLE `submissionstable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `timetracker`
--
ALTER TABLE `timetracker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trackingtable`
--
ALTER TABLE `trackingtable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `traineestable`
--
ALTER TABLE `traineestable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `trainercourses`
--
ALTER TABLE `trainercourses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `trainerstable`
--
ALTER TABLE `trainerstable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `userstable`
--
ALTER TABLE `userstable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

-- --------------------------------------------------------

--
-- Structure for view `trainers_view`
--
DROP TABLE IF EXISTS `trainers_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `trainers_view`  AS SELECT `userstable`.`id` AS `trainerID`, concat(`userstable`.`firstName`,' ',ifnull(`userstable`.`middleName`,''),' ',`userstable`.`lastName`,' ',ifnull(`userstable`.`suffix`,'')) AS `trainerName` FROM `userstable` WHERE `userstable`.`role` = 'trainer' ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activitiestable`
--
ALTER TABLE `activitiestable`
  ADD CONSTRAINT `activitiestable_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `userstable` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `activitiestable_ibfk_3` FOREIGN KEY (`course_id`) REFERENCES `coursestable` (`courseID`);

--
-- Constraints for table `announcementtable`
--
ALTER TABLE `announcementtable`
  ADD CONSTRAINT `announcementtable_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `coursestable` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `announcementtable_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `userstable` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `assignedcourses`
--
ALTER TABLE `assignedcourses`
  ADD CONSTRAINT `assignedcourses_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `coursestable` (`courseID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `assignedcourses_ibfk_2` FOREIGN KEY (`trainer_id`) REFERENCES `trainerstable` (`trainerID`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Constraints for table `corecompetency`
--
ALTER TABLE `corecompetency`
  ADD CONSTRAINT `corecompetency_ibfk_1` FOREIGN KEY (`courseID`) REFERENCES `coursestable` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `coursetracker`
--
ALTER TABLE `coursetracker`
  ADD CONSTRAINT `coursetracker_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `coursestable` (`courseID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `enrolledtable`
--
ALTER TABLE `enrolledtable`
  ADD CONSTRAINT `enrolledtable_ibfk_2` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollmenttable` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `enrolledtable_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `userstable` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `enrolledtable_ibfk_4` FOREIGN KEY (`course_id`) REFERENCES `coursestable` (`courseID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `enrollmenttable`
--
ALTER TABLE `enrollmenttable`
  ADD CONSTRAINT `enrollmenttable_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `userstable` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `enrollmenttable_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `trainercourses` (`courseID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `enrollmenttable_ibfk_3` FOREIGN KEY (`teacher_id`) REFERENCES `trainerstable` (`trainerID`);

--
-- Constraints for table `finalgradestable`
--
ALTER TABLE `finalgradestable`
  ADD CONSTRAINT `finalgradestable_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `userstable` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `finalgradestable_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `coursestable` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `gradestable`
--
ALTER TABLE `gradestable`
  ADD CONSTRAINT `gradestable_ibfk_1` FOREIGN KEY (`submission_id`) REFERENCES `submissionstable` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `gradestable_ibfk_2` FOREIGN KEY (`studentID`) REFERENCES `userstable` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `modulestable`
--
ALTER TABLE `modulestable`
  ADD CONSTRAINT `modulestable_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `coursestable` (`courseID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `modulestable_ibfk_2` FOREIGN KEY (`trainerID`) REFERENCES `userstable` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `studentprogress`
--
ALTER TABLE `studentprogress`
  ADD CONSTRAINT `studentprogress_ibfk_3` FOREIGN KEY (`trackingID`) REFERENCES `trackingtable` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `studentprogress_ibfk_4` FOREIGN KEY (`course_id`) REFERENCES `coursestable` (`courseID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `studentprogress_ibfk_5` FOREIGN KEY (`studentID`) REFERENCES `userstable` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `submissionstable`
--
ALTER TABLE `submissionstable`
  ADD CONSTRAINT `submissionstable_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `userstable` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `submissionstable_ibfk_2` FOREIGN KEY (`activity_id`) REFERENCES `activitiestable` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `timetracker`
--
ALTER TABLE `timetracker`
  ADD CONSTRAINT `timetracker_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `userstable` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `trackingtable`
--
ALTER TABLE `trackingtable`
  ADD CONSTRAINT `trackingtable_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `coursestable` (`courseID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `traineestable`
--
ALTER TABLE `traineestable`
  ADD CONSTRAINT `traineestable_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `userstable` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `trainercourses`
--
ALTER TABLE `trainercourses`
  ADD CONSTRAINT `trainercourses_ibfk_1` FOREIGN KEY (`trainerID`) REFERENCES `trainerstable` (`trainerID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `trainercourses_ibfk_2` FOREIGN KEY (`courseID`) REFERENCES `coursestable` (`courseID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `trainerstable`
--
ALTER TABLE `trainerstable`
  ADD CONSTRAINT `trainerBind` FOREIGN KEY (`trainerID`) REFERENCES `userstable` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
