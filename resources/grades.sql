-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 06, 2026 at 06:27 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `edutrack_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `subject_id` bigint UNSIGNED DEFAULT NULL,
  `class_id` bigint UNSIGNED DEFAULT NULL,
  `teacher_id` bigint UNSIGNED DEFAULT NULL,
  `marks_obtained` decimal(8,2) DEFAULT NULL,
  `total_marks` decimal(8,2) DEFAULT NULL,
  `grade` enum('A+','A','B','C','D','F') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `semester` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `academic_year` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `q1` decimal(5,2) DEFAULT NULL COMMENT 'Quiz 1',
  `q2` decimal(5,2) DEFAULT NULL COMMENT 'Quiz 2',
  `q3` decimal(5,2) DEFAULT NULL COMMENT 'Quiz 3',
  `q4` decimal(5,2) DEFAULT NULL COMMENT 'Quiz 4',
  `q5` decimal(5,2) DEFAULT NULL COMMENT 'Quiz 5',
  `midterm_exam` decimal(5,2) DEFAULT NULL,
  `final_exam` decimal(5,2) DEFAULT NULL,
  `knowledge_score` decimal(5,2) DEFAULT NULL COMMENT 'Calculated Knowledge Score (0-100)',
  `output_1` decimal(5,2) DEFAULT NULL,
  `output_2` decimal(5,2) DEFAULT NULL,
  `output_3` decimal(5,2) DEFAULT NULL,
  `output_score` decimal(5,2) DEFAULT NULL COMMENT 'Average of output entries',
  `class_participation_1` decimal(5,2) DEFAULT NULL,
  `class_participation_2` decimal(5,2) DEFAULT NULL,
  `class_participation_3` decimal(5,2) DEFAULT NULL,
  `class_participation_score` decimal(5,2) DEFAULT NULL COMMENT 'Average of class participation entries',
  `activities_1` decimal(5,2) DEFAULT NULL,
  `activities_2` decimal(5,2) DEFAULT NULL,
  `activities_3` decimal(5,2) DEFAULT NULL,
  `activities_score` decimal(5,2) DEFAULT NULL COMMENT 'Average of activities entries',
  `assignments_1` decimal(5,2) DEFAULT NULL,
  `assignments_2` decimal(5,2) DEFAULT NULL,
  `assignments_3` decimal(5,2) DEFAULT NULL,
  `assignments_score` decimal(5,2) DEFAULT NULL COMMENT 'Average of assignments entries',
  `skills_score` decimal(5,2) DEFAULT NULL COMMENT 'Calculated Skills Score (0-100)',
  `behavior_1` decimal(5,2) DEFAULT NULL,
  `behavior_2` decimal(5,2) DEFAULT NULL,
  `behavior_3` decimal(5,2) DEFAULT NULL,
  `behavior_score` decimal(5,2) DEFAULT NULL COMMENT 'Average of behavior entries',
  `awareness_1` decimal(5,2) DEFAULT NULL,
  `awareness_2` decimal(5,2) DEFAULT NULL,
  `awareness_3` decimal(5,2) DEFAULT NULL,
  `awareness_score` decimal(5,2) DEFAULT NULL COMMENT 'Average of awareness entries',
  `attitude_score` decimal(5,2) DEFAULT NULL COMMENT 'Calculated Attitude Score (0-100)',
  `final_grade` decimal(5,2) DEFAULT NULL COMMENT 'Final Grade = (K*0.40 + S*0.50 + A*0.10)',
  `grade_point` decimal(3,2) DEFAULT NULL,
  `total_quiz` int DEFAULT '5',
  `term` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` longtext COLLATE utf8mb4_unicode_ci,
  `grading_period` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `grades`
--

INSERT INTO `grades` (`id`, `student_id`, `subject_id`, `class_id`, `teacher_id`, `marks_obtained`, `total_marks`, `grade`, `semester`, `academic_year`, `q1`, `q2`, `q3`, `q4`, `q5`, `midterm_exam`, `final_exam`, `knowledge_score`, `output_1`, `output_2`, `output_3`, `output_score`, `class_participation_1`, `class_participation_2`, `class_participation_3`, `class_participation_score`, `activities_1`, `activities_2`, `activities_3`, `activities_score`, `assignments_1`, `assignments_2`, `assignments_3`, `assignments_score`, `skills_score`, `behavior_1`, `behavior_2`, `behavior_3`, `behavior_score`, `awareness_1`, `awareness_2`, `awareness_3`, `awareness_score`, `attitude_score`, `final_grade`, `grade_point`, `total_quiz`, `term`, `remarks`, `grading_period`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 2, NULL, NULL, NULL, '1st Semester', '2025-2026', '85.00', '88.00', '90.00', '87.00', '92.00', '88.00', NULL, NULL, '85.00', '88.00', '90.00', NULL, '90.00', '88.00', '92.00', NULL, '85.00', '80.00', '88.00', NULL, '90.00', '92.00', '88.00', NULL, NULL, '88.00', '90.00', '85.00', NULL, '92.00', '90.00', '88.00', NULL, NULL, NULL, NULL, 5, 'midterm', 'Excellent performance, consistent participation', 'Midterm', '2026-02-06 05:04:33', '2026-02-06 05:04:33'),
(2, 2, 1, 1, 2, NULL, NULL, NULL, '1st Semester', '2025-2026', '82.00', '85.00', '87.00', '84.00', '89.00', '86.00', NULL, NULL, '82.00', '85.00', '88.00', NULL, '85.00', '82.00', '87.00', NULL, '80.00', '78.00', '85.00', NULL, '87.00', '85.00', '83.00', NULL, NULL, '85.00', '87.00', '82.00', NULL, '88.00', '85.00', '83.00', NULL, NULL, NULL, NULL, 5, 'midterm', 'Good performance, needs more participation', 'Midterm', '2026-02-06 05:04:33', '2026-02-06 05:04:33'),
(3, 3, 1, 1, 2, NULL, NULL, NULL, '1st Semester', '2025-2026', '90.00', '92.00', '94.00', '91.00', '95.00', '92.00', NULL, NULL, '92.00', '94.00', '95.00', NULL, '94.00', '92.00', '95.00', NULL, '90.00', '92.00', '94.00', NULL, '95.00', '93.00', '92.00', NULL, NULL, '92.00', '94.00', '90.00', NULL, '95.00', '93.00', '92.00', NULL, NULL, NULL, NULL, 5, 'midterm', 'Outstanding performance, excellent attitude', 'Midterm', '2026-02-06 05:04:33', '2026-02-06 05:04:33'),
(4, 4, 1, 1, 2, NULL, NULL, NULL, '1st Semester', '2025-2026', '78.00', '80.00', '82.00', '79.00', '84.00', '81.00', NULL, NULL, '80.00', '82.00', '85.00', NULL, '80.00', '78.00', '82.00', NULL, '75.00', '73.00', '80.00', NULL, '82.00', '80.00', '78.00', NULL, NULL, '80.00', '82.00', '78.00', NULL, '83.00', '80.00', '78.00', NULL, NULL, NULL, NULL, 5, 'midterm', 'Average performance, needs improvement in activities', 'Midterm', '2026-02-06 05:04:33', '2026-02-06 05:04:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_grade_entry` (`student_id`,`subject_id`,`semester`,`academic_year`),
  ADD KEY `idx_student_id` (`student_id`),
  ADD KEY `idx_subject_id` (`subject_id`),
  ADD KEY `idx_class_id` (`class_id`),
  ADD KEY `idx_teacher_id` (`teacher_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `grades_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `grades_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `grades_ibfk_3` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `grades_ibfk_4` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
