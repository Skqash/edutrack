/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `employee_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `department` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_employee_id_unique` (`employee_id`),
  KEY `admins_user_id_foreign` (`user_id`),
  CONSTRAINT `admins_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `assessment_components`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `assessment_components` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `class_id` bigint unsigned NOT NULL,
  `teacher_id` bigint unsigned NOT NULL,
  `category` enum('Knowledge','Skills','Attitude') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'KSA Category',
  `subcategory` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type: Quiz, Exam, Output, Activity, etc',
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Display name for component',
  `max_score` int NOT NULL DEFAULT '100' COMMENT 'Maximum possible score',
  `weight` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Weight % within category',
  `passing_score` decimal(5,2) DEFAULT NULL,
  `order` int NOT NULL DEFAULT '0' COMMENT 'Display order',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Soft delete flag',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `assessment_components_teacher_id_foreign` (`teacher_id`),
  KEY `assessment_components_class_id_category_index` (`class_id`,`category`),
  KEY `assessment_components_class_id_teacher_id_index` (`class_id`,`teacher_id`),
  CONSTRAINT `assessment_components_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `assessment_components_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `assessment_ranges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `assessment_ranges` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `class_id` bigint unsigned NOT NULL,
  `subject_id` bigint unsigned DEFAULT NULL,
  `teacher_id` bigint unsigned NOT NULL,
  `quiz_1_max` int NOT NULL DEFAULT '20',
  `quiz_2_max` int NOT NULL DEFAULT '15',
  `quiz_3_max` int NOT NULL DEFAULT '25',
  `quiz_4_max` int NOT NULL DEFAULT '20',
  `quiz_5_max` int NOT NULL DEFAULT '20',
  `quiz_max` int DEFAULT '20',
  `prelim_exam_max` int NOT NULL DEFAULT '60',
  `midterm_exam_max` int NOT NULL DEFAULT '60',
  `final_exam_max` int NOT NULL DEFAULT '60',
  `exam_max` int DEFAULT '100',
  `output_max` int NOT NULL DEFAULT '100',
  `output_prelim` int NOT NULL DEFAULT '5',
  `output_midterm` int NOT NULL DEFAULT '5',
  `output_final` int NOT NULL DEFAULT '10',
  `class_participation_max` int NOT NULL DEFAULT '100',
  `class_participation_prelim` int NOT NULL DEFAULT '5',
  `class_participation_midterm` int NOT NULL DEFAULT '5',
  `class_participation_final` int NOT NULL DEFAULT '10',
  `activities_max` int NOT NULL DEFAULT '100',
  `activities_prelim` int NOT NULL DEFAULT '5',
  `activities_midterm` int NOT NULL DEFAULT '5',
  `activities_final` int NOT NULL DEFAULT '10',
  `assignments_max` int NOT NULL DEFAULT '100',
  `assignments_prelim` int NOT NULL DEFAULT '5',
  `assignments_midterm` int NOT NULL DEFAULT '5',
  `assignments_final` int NOT NULL DEFAULT '10',
  `behavior_max` int NOT NULL DEFAULT '100',
  `behavior_prelim` int NOT NULL DEFAULT '2',
  `behavior_midterm` int NOT NULL DEFAULT '3',
  `behavior_final` int NOT NULL DEFAULT '5',
  `awareness_max` int NOT NULL DEFAULT '100',
  `awareness_prelim` int NOT NULL DEFAULT '2',
  `awareness_midterm` int NOT NULL DEFAULT '3',
  `awareness_final` int NOT NULL DEFAULT '5',
  `attendance_max` int NOT NULL DEFAULT '100',
  `attendance_required` tinyint(1) NOT NULL DEFAULT '1',
  `attendance_min_percentage` int DEFAULT '75',
  `total_quiz_items` int NOT NULL DEFAULT '100',
  `num_quizzes` int NOT NULL DEFAULT '5',
  `equal_quiz_distribution` tinyint(1) NOT NULL DEFAULT '1',
  `quiz_distribution` json DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `assessment_ranges_class_id_subject_id_teacher_id_unique` (`class_id`,`subject_id`,`teacher_id`),
  KEY `assessment_ranges_subject_id_foreign` (`subject_id`),
  KEY `assessment_ranges_teacher_id_foreign` (`teacher_id`),
  CONSTRAINT `assessment_ranges_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `assessment_ranges_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `assessment_ranges_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `assignment_students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `assignment_students` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `assignment_id` bigint unsigned NOT NULL,
  `student_id` bigint unsigned NOT NULL,
  `status` enum('assigned','completed','dropped') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'assigned',
  `assigned_at` timestamp NOT NULL DEFAULT '2026-03-21 08:10:00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `assignment_students_assignment_id_student_id_unique` (`assignment_id`,`student_id`),
  KEY `assignment_students_student_id_status_index` (`student_id`,`status`),
  KEY `assignment_students_assignment_id_status_index` (`assignment_id`,`status`),
  CONSTRAINT `assignment_students_assignment_id_foreign` FOREIGN KEY (`assignment_id`) REFERENCES `teacher_assignments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `assignment_students_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `attendance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attendance` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `class_id` bigint unsigned NOT NULL,
  `date` date NOT NULL,
  `status` enum('Present','Absent','Late','Leave') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Present',
  `term` enum('Midterm','Final') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Midterm',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attendance_student_id_class_id_date_unique` (`student_id`,`class_id`,`date`),
  KEY `attendance_class_id_foreign` (`class_id`),
  CONSTRAINT `attendance_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attendance_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `class_students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `class_students` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `class_id` bigint unsigned NOT NULL,
  `student_id` bigint unsigned NOT NULL,
  `status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `class_students_class_id_student_id_unique` (`class_id`,`student_id`),
  KEY `class_students_student_id_foreign` (`student_id`),
  CONSTRAINT `class_students_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `class_students_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `classes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `class_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `class_level` int NOT NULL,
  `section` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` enum('1st','2nd','3rd','4th') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1st',
  `academic_year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `semester` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_students` int NOT NULL DEFAULT '60',
  `teacher_id` bigint unsigned DEFAULT NULL,
  `subject_id` bigint unsigned DEFAULT NULL,
  `course_id` bigint unsigned DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `units` int NOT NULL DEFAULT '3',
  `status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `campus` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `current_term` enum('midterm','final') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'midterm' COMMENT 'Term teacher is currently entering grades for',
  `total_meetings_midterm` int NOT NULL DEFAULT '17',
  `total_meetings_final` int NOT NULL DEFAULT '17',
  `attendance_percentage` decimal(5,2) NOT NULL DEFAULT '10.00',
  PRIMARY KEY (`id`),
  KEY `classes_teacher_id_foreign` (`teacher_id`),
  KEY `classes_subject_id_foreign` (`subject_id`),
  KEY `classes_course_id_foreign` (`course_id`),
  KEY `classes_campus_index` (`campus`),
  CONSTRAINT `classes_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `classes_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE SET NULL,
  CONSTRAINT `classes_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `colleges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `colleges` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `college_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `colleges_college_name_unique` (`college_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `component_averages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `component_averages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `class_id` bigint unsigned NOT NULL,
  `term` enum('midterm','final') COLLATE utf8mb4_unicode_ci NOT NULL,
  `knowledge_average` decimal(5,2) DEFAULT NULL COMMENT '0-100 average for Knowledge',
  `skills_average` decimal(5,2) DEFAULT NULL COMMENT '0-100 average for Skills',
  `attitude_average` decimal(5,2) DEFAULT NULL COMMENT '0-100 average for Attitude',
  `final_grade` decimal(5,2) DEFAULT NULL COMMENT 'Final computed grade',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `component_averages_student_id_class_id_term_unique` (`student_id`,`class_id`,`term`),
  KEY `component_averages_class_id_term_index` (`class_id`,`term`),
  CONSTRAINT `component_averages_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `component_averages_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `component_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `component_entries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `class_id` bigint unsigned NOT NULL,
  `component_id` bigint unsigned NOT NULL,
  `term` enum('midterm','final') COLLATE utf8mb4_unicode_ci NOT NULL,
  `raw_score` decimal(5,2) DEFAULT NULL COMMENT 'Raw input score',
  `normalized_score` decimal(5,2) DEFAULT NULL COMMENT 'Auto-calculated 0-100',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `component_entries_student_id_component_id_term_unique` (`student_id`,`component_id`,`term`),
  KEY `component_entries_component_id_foreign` (`component_id`),
  KEY `component_entries_class_id_term_index` (`class_id`,`term`),
  KEY `component_entries_student_id_term_index` (`student_id`,`term`),
  CONSTRAINT `component_entries_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `component_entries_component_id_foreign` FOREIGN KEY (`component_id`) REFERENCES `assessment_components` (`id`) ON DELETE CASCADE,
  CONSTRAINT `component_entries_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `course_access_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `course_access_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `teacher_id` bigint unsigned NOT NULL,
  `course_id` bigint unsigned NOT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `reason` text COLLATE utf8mb4_unicode_ci,
  `admin_note` text COLLATE utf8mb4_unicode_ci,
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `course_access_requests_teacher_id_course_id_unique` (`teacher_id`,`course_id`),
  KEY `course_access_requests_course_id_foreign` (`course_id`),
  KEY `course_access_requests_approved_by_foreign` (`approved_by`),
  CONSTRAINT `course_access_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  CONSTRAINT `course_access_requests_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `course_access_requests_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `course_instructors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `course_instructors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Instructor' COMMENT 'Course Lead, Co-Instructor, Coordinator, etc.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `course_instructors_course_id_user_id_role_unique` (`course_id`,`user_id`,`role`),
  KEY `course_instructors_user_id_foreign` (`user_id`),
  CONSTRAINT `course_instructors_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `course_instructors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `courses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `program_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'GEN',
  `program_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'General',
  `total_years` tinyint unsigned NOT NULL DEFAULT '4' COMMENT 'Total years in program (typically 4)',
  `department_id` bigint unsigned DEFAULT NULL,
  `program_head_id` bigint unsigned DEFAULT NULL,
  `college` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `course_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `duration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_students` int DEFAULT NULL,
  `instructor_id` bigint unsigned DEFAULT NULL,
  `head_id` bigint unsigned DEFAULT NULL,
  `status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `campus` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `credit_hours` int NOT NULL DEFAULT '3',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `courses_course_code_unique` (`course_code`),
  UNIQUE KEY `courses_department_code_unique` (`department_code`),
  KEY `courses_instructor_id_foreign` (`instructor_id`),
  KEY `courses_head_id_foreign` (`head_id`),
  KEY `courses_department_id_foreign` (`department_id`),
  KEY `courses_program_head_id_foreign` (`program_head_id`),
  KEY `courses_campus_index` (`campus`),
  CONSTRAINT `courses_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `courses_head_id_foreign` FOREIGN KEY (`head_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `courses_instructor_id_foreign` FOREIGN KEY (`instructor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `courses_program_head_id_foreign` FOREIGN KEY (`program_head_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `department_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `college_id` bigint unsigned NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `departments_department_name_unique` (`department_name`),
  KEY `departments_college_id_foreign` (`college_id`),
  CONSTRAINT `departments_college_id_foreign` FOREIGN KEY (`college_id`) REFERENCES `colleges` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `grade_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grade_entries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `class_id` bigint unsigned NOT NULL,
  `teacher_id` bigint unsigned DEFAULT NULL,
  `term` enum('midterm','final') COLLATE utf8mb4_unicode_ci NOT NULL,
  `exam_pr` decimal(5,2) DEFAULT NULL COMMENT 'Exam Preliminary Score',
  `exam_md` decimal(5,2) DEFAULT NULL COMMENT 'Exam Midterm Score',
  `exam_fn` decimal(5,2) DEFAULT NULL COMMENT 'Final Exam Score',
  `quiz_1` decimal(5,2) DEFAULT NULL,
  `quiz_2` decimal(5,2) DEFAULT NULL,
  `quiz_3` decimal(5,2) DEFAULT NULL,
  `quiz_4` decimal(5,2) DEFAULT NULL,
  `quiz_5` decimal(5,2) DEFAULT NULL,
  `exam` decimal(5,2) DEFAULT NULL,
  `output_1` decimal(5,2) DEFAULT NULL,
  `output_2` decimal(5,2) DEFAULT NULL,
  `output_3` decimal(5,2) DEFAULT NULL,
  `classpart_1` decimal(5,2) DEFAULT NULL,
  `classpart_2` decimal(5,2) DEFAULT NULL,
  `classpart_3` decimal(5,2) DEFAULT NULL,
  `activity_1` decimal(5,2) DEFAULT NULL,
  `activity_2` decimal(5,2) DEFAULT NULL,
  `activity_3` decimal(5,2) DEFAULT NULL,
  `assignment_1` decimal(5,2) DEFAULT NULL,
  `assignment_2` decimal(5,2) DEFAULT NULL,
  `assignment_3` decimal(5,2) DEFAULT NULL,
  `output` decimal(5,2) DEFAULT NULL,
  `class_participation` decimal(5,2) DEFAULT NULL,
  `activities` decimal(5,2) DEFAULT NULL,
  `behavior_1` decimal(5,2) DEFAULT NULL,
  `behavior_2` decimal(5,2) DEFAULT NULL,
  `behavior_3` decimal(5,2) DEFAULT NULL,
  `attendance_1` decimal(5,2) DEFAULT NULL,
  `attendance_2` decimal(5,2) DEFAULT NULL,
  `attendance_3` decimal(5,2) DEFAULT NULL,
  `awareness_1` decimal(5,2) DEFAULT NULL,
  `awareness_2` decimal(5,2) DEFAULT NULL,
  `awareness_3` decimal(5,2) DEFAULT NULL,
  `behavior` decimal(5,2) DEFAULT NULL,
  `awareness` decimal(5,2) DEFAULT NULL,
  `exam_average` decimal(5,2) DEFAULT NULL,
  `quiz_average` decimal(5,2) DEFAULT NULL,
  `knowledge_average` decimal(5,2) DEFAULT NULL,
  `output_average` decimal(5,2) DEFAULT NULL,
  `classpart_average` decimal(5,2) DEFAULT NULL,
  `activity_average` decimal(5,2) DEFAULT NULL,
  `assignment_average` decimal(5,2) DEFAULT NULL,
  `skills_average` decimal(5,2) DEFAULT NULL,
  `behavior_average` decimal(5,2) DEFAULT NULL,
  `attendance_average` decimal(5,2) DEFAULT NULL,
  `awareness_average` decimal(5,2) DEFAULT NULL,
  `attitude_average` decimal(5,2) DEFAULT NULL,
  `term_grade` decimal(5,2) DEFAULT NULL,
  `final_grade` decimal(5,2) DEFAULT NULL,
  `graded_at` timestamp NULL DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `grade_entries_student_id_class_id_term_unique` (`student_id`,`class_id`,`term`),
  KEY `grade_entries_class_id_foreign` (`class_id`),
  KEY `grade_entries_teacher_id_foreign` (`teacher_id`),
  CONSTRAINT `grade_entries_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `grade_entries_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `grade_entries_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `grades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grades` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `subject_id` bigint unsigned DEFAULT NULL,
  `class_id` bigint unsigned DEFAULT NULL,
  `term` enum('midterm','final') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'midterm',
  `q1` decimal(5,2) DEFAULT NULL,
  `q2` decimal(5,2) DEFAULT NULL,
  `q3` decimal(5,2) DEFAULT NULL,
  `q4` decimal(5,2) DEFAULT NULL,
  `q5` decimal(5,2) DEFAULT NULL,
  `q6` decimal(5,2) DEFAULT NULL,
  `q7` decimal(5,2) DEFAULT NULL,
  `q8` decimal(5,2) DEFAULT NULL,
  `q9` decimal(5,2) DEFAULT NULL,
  `q10` decimal(5,2) DEFAULT NULL,
  `prelim_exam` decimal(5,2) DEFAULT NULL,
  `midterm_exam` decimal(5,2) DEFAULT NULL,
  `final_exam` decimal(5,2) DEFAULT NULL,
  `output_1` decimal(5,2) DEFAULT NULL,
  `output_2` decimal(5,2) DEFAULT NULL,
  `output_3` decimal(5,2) DEFAULT NULL,
  `class_participation_1` decimal(5,2) DEFAULT NULL,
  `class_participation_2` decimal(5,2) DEFAULT NULL,
  `class_participation_3` decimal(5,2) DEFAULT NULL,
  `activities_1` decimal(5,2) DEFAULT NULL,
  `activities_2` decimal(5,2) DEFAULT NULL,
  `activities_3` decimal(5,2) DEFAULT NULL,
  `assignments_1` decimal(5,2) DEFAULT NULL,
  `assignments_2` decimal(5,2) DEFAULT NULL,
  `assignments_3` decimal(5,2) DEFAULT NULL,
  `behavior_1` decimal(5,2) DEFAULT NULL,
  `behavior_2` decimal(5,2) DEFAULT NULL,
  `behavior_3` decimal(5,2) DEFAULT NULL,
  `awareness_1` decimal(5,2) DEFAULT NULL,
  `awareness_2` decimal(5,2) DEFAULT NULL,
  `awareness_3` decimal(5,2) DEFAULT NULL,
  `teacher_id` bigint unsigned DEFAULT NULL,
  `marks_obtained` decimal(8,2) DEFAULT NULL,
  `total_marks` decimal(8,2) DEFAULT NULL,
  `grade` decimal(5,2) DEFAULT NULL COMMENT 'Numeric grade (0-100 scale) - Letter grades deprecated',
  `semester` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `academic_year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `knowledge_score` decimal(5,2) DEFAULT NULL COMMENT 'Knowledge (0-100)',
  `output_score` decimal(5,2) DEFAULT NULL,
  `class_participation_score` decimal(5,2) DEFAULT NULL,
  `activities_score` decimal(5,2) DEFAULT NULL,
  `assignments_score` decimal(5,2) DEFAULT NULL,
  `skills_score` decimal(5,2) DEFAULT NULL COMMENT 'Skills (0-100)',
  `behavior_score` decimal(5,2) DEFAULT NULL,
  `awareness_score` decimal(5,2) DEFAULT NULL,
  `attitude_score` decimal(5,2) DEFAULT NULL COMMENT 'Attitude (0-100)',
  `final_grade` decimal(5,2) DEFAULT NULL COMMENT 'Final Grade (0-100) = (K*0.3 + S*0.4 + A*0.3)',
  `grade_point` decimal(3,2) DEFAULT NULL COMMENT 'CHED Grade Point (4.0 scale)',
  `grade_letter` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `grading_period` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `assessment_period` enum('midterm','final') COLLATE utf8mb4_unicode_ci DEFAULT 'midterm',
  `exam_prelim` decimal(5,2) DEFAULT NULL COMMENT 'Preliminary Exam Score',
  `exam_midterm` decimal(5,2) DEFAULT NULL COMMENT 'Midterm Exam Score',
  `exam_final` decimal(5,2) DEFAULT NULL COMMENT 'Final Exam Score',
  `quiz_1` decimal(5,2) DEFAULT NULL,
  `quiz_2` decimal(5,2) DEFAULT NULL,
  `quiz_3` decimal(5,2) DEFAULT NULL,
  `quiz_4` decimal(5,2) DEFAULT NULL,
  `quiz_5` decimal(5,2) DEFAULT NULL,
  `knowledge_average` decimal(5,2) DEFAULT NULL COMMENT 'Average of Exams + Quizzes (40% weight)',
  `skills_average` decimal(5,2) DEFAULT NULL COMMENT 'Average of all Skills components (50% weight)',
  `attitude_average` decimal(5,2) DEFAULT NULL COMMENT 'Average of Behavior + Awareness (10% weight)',
  `midterm_grade` decimal(5,2) DEFAULT NULL COMMENT 'Midterm final grade',
  `final_grade_value` decimal(5,2) DEFAULT NULL COMMENT 'Final exam grade',
  `overall_grade` decimal(5,2) DEFAULT NULL COMMENT 'Overall grade (Midterm*0.40 + Final*0.60)',
  `letter_grade` enum('A+','A','A-','B+','B','B-','C+','C','C-','D+','D','D-','F','INC') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `output_total` decimal(5,2) DEFAULT NULL COMMENT 'Sum of output_1 + output_2 + output_3',
  `class_participation_total` decimal(5,2) DEFAULT NULL COMMENT 'Sum of class_participation_1 + 2 + 3',
  `activities_total` decimal(5,2) DEFAULT NULL COMMENT 'Sum of activities_1 + activities_2 + activities_3',
  `assignments_total` decimal(5,2) DEFAULT NULL COMMENT 'Sum of assignments_1 + assignments_2 + assignments_3',
  `behavior_total` decimal(5,2) DEFAULT NULL COMMENT 'Sum of behavior_1 + behavior_2 + behavior_3',
  `awareness_total` decimal(5,2) DEFAULT NULL COMMENT 'Sum of awareness_1 + awareness_2 + awareness_3',
  `decimal_grade` decimal(5,2) DEFAULT NULL COMMENT 'Decimal representation of final grade',
  `mid_exam_pr` decimal(5,2) DEFAULT NULL COMMENT 'Midterm Exam Preliminary Raw Score',
  `mid_exam_md` decimal(5,2) DEFAULT NULL COMMENT 'Midterm Exam Midterm Raw Score',
  `mid_quiz_1` decimal(5,2) DEFAULT NULL,
  `mid_quiz_2` decimal(5,2) DEFAULT NULL,
  `mid_quiz_3` decimal(5,2) DEFAULT NULL,
  `mid_quiz_4` decimal(5,2) DEFAULT NULL,
  `mid_quiz_5` decimal(5,2) DEFAULT NULL,
  `mid_output_1` decimal(5,2) DEFAULT NULL,
  `mid_output_2` decimal(5,2) DEFAULT NULL,
  `mid_output_3` decimal(5,2) DEFAULT NULL,
  `mid_classpart_1` decimal(5,2) DEFAULT NULL,
  `mid_classpart_2` decimal(5,2) DEFAULT NULL,
  `mid_classpart_3` decimal(5,2) DEFAULT NULL,
  `mid_activity_1` decimal(5,2) DEFAULT NULL,
  `mid_activity_2` decimal(5,2) DEFAULT NULL,
  `mid_activity_3` decimal(5,2) DEFAULT NULL,
  `mid_assignment_1` decimal(5,2) DEFAULT NULL,
  `mid_assignment_2` decimal(5,2) DEFAULT NULL,
  `mid_assignment_3` decimal(5,2) DEFAULT NULL,
  `mid_behavior_1` decimal(5,2) DEFAULT NULL,
  `mid_behavior_2` decimal(5,2) DEFAULT NULL,
  `mid_behavior_3` decimal(5,2) DEFAULT NULL,
  `mid_awareness_1` decimal(5,2) DEFAULT NULL,
  `mid_awareness_2` decimal(5,2) DEFAULT NULL,
  `mid_awareness_3` decimal(5,2) DEFAULT NULL,
  `final_exam_pr` decimal(5,2) DEFAULT NULL COMMENT 'Final Exam Preliminary Raw Score',
  `final_exam_md` decimal(5,2) DEFAULT NULL COMMENT 'Final Exam Midterm Raw Score',
  `final_quiz_1` decimal(5,2) DEFAULT NULL,
  `final_quiz_2` decimal(5,2) DEFAULT NULL,
  `final_quiz_3` decimal(5,2) DEFAULT NULL,
  `final_quiz_4` decimal(5,2) DEFAULT NULL,
  `final_quiz_5` decimal(5,2) DEFAULT NULL,
  `final_output_1` decimal(5,2) DEFAULT NULL,
  `final_output_2` decimal(5,2) DEFAULT NULL,
  `final_output_3` decimal(5,2) DEFAULT NULL,
  `final_classpart_1` decimal(5,2) DEFAULT NULL,
  `final_classpart_2` decimal(5,2) DEFAULT NULL,
  `final_classpart_3` decimal(5,2) DEFAULT NULL,
  `final_activity_1` decimal(5,2) DEFAULT NULL,
  `final_activity_2` decimal(5,2) DEFAULT NULL,
  `final_activity_3` decimal(5,2) DEFAULT NULL,
  `final_assignment_1` decimal(5,2) DEFAULT NULL,
  `final_assignment_2` decimal(5,2) DEFAULT NULL,
  `final_assignment_3` decimal(5,2) DEFAULT NULL,
  `final_behavior_1` decimal(5,2) DEFAULT NULL,
  `final_behavior_2` decimal(5,2) DEFAULT NULL,
  `final_behavior_3` decimal(5,2) DEFAULT NULL,
  `final_awareness_1` decimal(5,2) DEFAULT NULL,
  `final_awareness_2` decimal(5,2) DEFAULT NULL,
  `final_awareness_3` decimal(5,2) DEFAULT NULL,
  `mid_knowledge_average` decimal(5,2) DEFAULT NULL,
  `mid_skills_average` decimal(5,2) DEFAULT NULL,
  `mid_attitude_average` decimal(5,2) DEFAULT NULL,
  `mid_final_grade` decimal(5,2) DEFAULT NULL,
  `final_knowledge_average` decimal(5,2) DEFAULT NULL,
  `final_skills_average` decimal(5,2) DEFAULT NULL,
  `final_attitude_average` decimal(5,2) DEFAULT NULL,
  `final_final_grade` decimal(5,2) DEFAULT NULL,
  `grade_5pt_scale` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '5-point scale: 5.0, 4.0, 3.0, 2.0, 1.0, 0.0',
  `grade_remarks` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `grades_student_id_subject_id_semester_academic_year_unique` (`student_id`,`subject_id`,`semester`,`academic_year`),
  KEY `grades_subject_id_foreign` (`subject_id`),
  KEY `grades_class_id_foreign` (`class_id`),
  KEY `grades_teacher_id_foreign` (`teacher_id`),
  CONSTRAINT `grades_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE SET NULL,
  CONSTRAINT `grades_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `grades_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE SET NULL,
  CONSTRAINT `grades_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `grading_scale_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grading_scale_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `class_id` bigint unsigned NOT NULL,
  `teacher_id` bigint unsigned NOT NULL,
  `term` enum('midterm','final') COLLATE utf8mb4_unicode_ci NOT NULL,
  `knowledge_percentage` decimal(5,2) NOT NULL DEFAULT '40.00',
  `skills_percentage` decimal(5,2) NOT NULL DEFAULT '50.00',
  `attitude_percentage` decimal(5,2) NOT NULL DEFAULT '10.00',
  `is_locked` tinyint(1) NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `grading_scale_settings_class_id_term_unique` (`class_id`,`term`),
  KEY `grading_scale_settings_teacher_id_index` (`teacher_id`),
  KEY `grading_scale_settings_class_id_term_index` (`class_id`,`term`),
  CONSTRAINT `grading_scale_settings_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `grading_scale_settings_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ksa_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ksa_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `class_id` bigint unsigned NOT NULL,
  `teacher_id` bigint unsigned NOT NULL,
  `term` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'midterm',
  `knowledge_weight` decimal(5,2) NOT NULL DEFAULT '40.00',
  `skills_weight` decimal(5,2) NOT NULL DEFAULT '50.00',
  `attitude_weight` decimal(5,2) NOT NULL DEFAULT '10.00',
  `total_meetings` int NOT NULL DEFAULT '20',
  `attendance_weight` decimal(5,2) NOT NULL DEFAULT '10.00',
  `attendance_category` enum('knowledge','skills','attitude') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'skills',
  `grading_scale` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'percentage',
  `use_weighted_average` tinyint(1) NOT NULL DEFAULT '1',
  `round_final_grade` tinyint(1) NOT NULL DEFAULT '1',
  `decimal_places` int NOT NULL DEFAULT '2',
  `passing_grade` decimal(5,2) NOT NULL DEFAULT '75.00',
  `minimum_attendance` decimal(5,2) NOT NULL DEFAULT '75.00',
  `include_attendance_in_attitude` tinyint(1) NOT NULL DEFAULT '1',
  `auto_calculate` tinyint(1) NOT NULL DEFAULT '1',
  `custom_settings` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ksa_settings_class_id_term_unique` (`class_id`,`term`),
  KEY `ksa_settings_teacher_id_index` (`teacher_id`),
  KEY `ksa_settings_class_id_term_index` (`class_id`,`term`),
  CONSTRAINT `ksa_settings_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ksa_settings_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('info','warning','success','danger') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'info',
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_user_id_read_at_index` (`user_id`,`read_at`),
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `school_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `school_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `school_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `school_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `request_type` enum('school','subject','course','class') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'school',
  `related_id` bigint unsigned DEFAULT NULL,
  `related_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `school_requests_user_id_foreign` (`user_id`),
  CONSTRAINT `school_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `student_attendance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_attendance` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `class_id` bigint unsigned NOT NULL,
  `subject_id` bigint unsigned NOT NULL,
  `term` enum('midterm','final') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'midterm',
  `attendance_score` double(8,2) NOT NULL DEFAULT '0.00',
  `total_classes` int NOT NULL DEFAULT '0',
  `present_classes` int NOT NULL DEFAULT '0',
  `absent_classes` int NOT NULL DEFAULT '0',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_attendance_student_id_class_id_subject_id_term_unique` (`student_id`,`class_id`,`subject_id`,`term`),
  KEY `student_attendance_class_id_foreign` (`class_id`),
  KEY `student_attendance_subject_id_foreign` (`subject_id`),
  CONSTRAINT `student_attendance_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `student_attendance_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `student_attendance_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `students` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `class_id` bigint unsigned DEFAULT NULL,
  `student_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` int NOT NULL DEFAULT '1',
  `year_level` tinyint unsigned DEFAULT NULL COMMENT 'Alias for year field',
  `section` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gpa` decimal(4,2) NOT NULL DEFAULT '0.00',
  `status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `campus` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `students_student_id_unique` (`student_id`),
  KEY `students_user_id_foreign` (`user_id`),
  KEY `students_class_id_foreign` (`class_id`),
  KEY `idx_students_department_year_class` (`department`,`year`,`class_id`),
  KEY `students_campus_index` (`campus`),
  CONSTRAINT `students_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE SET NULL,
  CONSTRAINT `students_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `subject_instructors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subject_instructors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `subject_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Instructor' COMMENT 'Instructor, Co-Instructor, TA, etc.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subject_instructors_subject_id_user_id_role_unique` (`subject_id`,`user_id`,`role`),
  KEY `subject_instructors_user_id_foreign` (`user_id`),
  CONSTRAINT `subject_instructors_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subject_instructors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `subjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subjects` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `subject_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Core / General Ed / Major / Specialization',
  `semester` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `year_level` tinyint unsigned NOT NULL COMMENT 'Academic year level: 1-4',
  `credit_hours` int NOT NULL DEFAULT '3',
  `program_id` bigint unsigned DEFAULT NULL,
  `campus` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subjects_subject_code_unique` (`subject_code`),
  KEY `subjects_course_id_foreign` (`program_id`),
  KEY `subjects_campus_index` (`campus`),
  CONSTRAINT `subjects_course_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `super_admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `super_admins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `super_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `super_admins_super_id_unique` (`super_id`),
  UNIQUE KEY `super_admins_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `teacher_assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `teacher_assignments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `teacher_id` bigint unsigned NOT NULL,
  `class_id` bigint unsigned DEFAULT NULL,
  `subject_id` bigint unsigned DEFAULT NULL,
  `course_id` bigint unsigned DEFAULT NULL,
  `department` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `academic_year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '2024-2025',
  `semester` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'First',
  `status` enum('active','inactive','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `assigned_at` timestamp NOT NULL DEFAULT '2026-03-21 08:10:00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `teacher_assignments_subject_id_foreign` (`subject_id`),
  KEY `teacher_assignments_course_id_foreign` (`course_id`),
  KEY `teacher_assignments_teacher_id_status_index` (`teacher_id`,`status`),
  KEY `teacher_assignments_department_academic_year_index` (`department`,`academic_year`),
  KEY `teacher_assignments_class_id_subject_id_index` (`class_id`,`subject_id`),
  CONSTRAINT `teacher_assignments_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `teacher_assignments_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `teacher_assignments_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `teacher_assignments_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `teacher_subject`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `teacher_subject` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `teacher_id` bigint unsigned NOT NULL,
  `subject_id` bigint unsigned NOT NULL,
  `status` enum('active','inactive','pending') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `assigned_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `teacher_subject_teacher_id_subject_id_unique` (`teacher_id`,`subject_id`),
  KEY `teacher_subject_subject_id_foreign` (`subject_id`),
  CONSTRAINT `teacher_subject_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `teacher_subject_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `teachers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `teachers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `employee_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qualification` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `connected_school` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `campus` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `specialization` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `teachers_employee_id_unique` (`employee_id`),
  KEY `teachers_user_id_foreign` (`user_id`),
  CONSTRAINT `teachers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'student',
  `employee_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qualification` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `specialization` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `campus` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `campus_status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `campus_approved_at` timestamp NULL DEFAULT NULL,
  `connected_school` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `theme` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'light',
  `grading_scheme` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `grading_weights` json DEFAULT NULL,
  `campus_approved_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_employee_id_unique` (`employee_id`),
  KEY `users_campus_approved_by_foreign` (`campus_approved_by`),
  CONSTRAINT `users_campus_approved_by_foreign` FOREIGN KEY (`campus_approved_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'2014_10_12_000000_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'2014_10_12_100000_create_password_reset_tokens_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'2019_08_19_000000_create_failed_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2019_12_14_000001_create_personal_access_tokens_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2024_01_25_000001_add_theme_to_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2025_01_19_000000_create_super_admins_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2025_01_19_000003_update_users_table_add_role',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2026_01_20_032223_create_courses_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2026_01_20_032224_create_students_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2026_01_20_032225_create_teachers_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2026_01_20_032230_create_subjects_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2026_01_20_032231_create_classes_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2026_01_20_032237_create_class_students_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2026_01_20_032238_create_attendance_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2026_01_20_032239_create_departments_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2026_01_20_032240_create_grades_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17,'2026_01_20_171242_add_class_id_to_students_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18,'2026_01_21_000001_create_notifications_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19,'2026_01_21_000001_update_grades_table_for_ched_system',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20,'2026_01_21_000002_add_year_to_classes_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'2026_01_21_000003_create_assessment_ranges_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (22,'2026_01_21_000004_create_student_attendance_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (23,'2026_01_21_000006_add_flexible_quiz_columns_to_grades',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (24,'2026_01_21_000007_add_total_quiz_configuration',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (25,'2026_01_22_000001_add_grade_point_to_grades_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (26,'2026_01_22_000001_add_period_based_columns_to_assessment_ranges',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (27,'2026_01_22_000002_make_subject_id_nullable_in_assessment_ranges',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (28,'2026_01_24_update_students_table_structure',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (29,'2026_01_30_000001_drop_departments_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (30,'2026_01_30_000002_enhance_courses_table_with_department_fields',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (31,'2026_01_31_000001_extend_teachers_admins_with_profile_fields',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (32,'2026_01_31_000002_add_individual_ksa_entries_to_grades',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (33,'2026_02_06_000001_change_grade_column_to_decimal',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (34,'2026_02_10_000001_restructure_grades_for_midterm_final',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (35,'2026_02_11_000002_add_component_totals_to_grades',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (36,'2026_02_15_000001_add_grading_scheme_to_users',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (37,'2026_02_15_add_period_grades_to_grades_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (38,'2026_02_15_restructure_grades_tables',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (39,'2026_02_17_153822_add_subject_id_to_classes_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (40,'2026_03_04_062000_fix_course_structure',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (41,'2026_03_04_153211_create_teacher_subject_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (42,'2026_03_04_155017_add_units_to_classes_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (43,'2026_03_10_170546_create_teacher_assignments_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (44,'2026_03_10_170634_create_assignment_students_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (45,'2026_03_11_000001_add_status_to_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (46,'2026_03_15_000001_add_missing_columns_to_grade_entries',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (47,'2026_03_15_000002_create_school_requests_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (48,'2026_03_15_000003_add_connected_school_to_teachers_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (49,'2026_03_15_000004_add_campus_to_teachers_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (50,'2026_03_16_000000_add_school_department_to_students_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (51,'2026_03_16_000001_add_academic_year_to_classes_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (52,'2026_03_17_000001_create_dynamic_components_tables',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (53,'2026_03_17_000002_create_grading_scale_settings_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (54,'2026_03_18_000000_add_school_year_semester_to_subjects',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (55,'2026_03_18_220123_add_simplified_grade_columns_to_grade_entries_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (56,'2026_03_18_232448_create_ksa_settings_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (57,'2026_03_18_233636_add_passing_score_to_assessment_components_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (58,'2026_03_18_235611_remove_course_name_column_from_courses_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (59,'2026_03_18_restructure_classes_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (60,'2026_03_19_000000_add_year_to_subjects_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (61,'2026_03_19_000010_add_attendance_settings_to_classes',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (62,'2026_03_19_000011_update_attendance_table_for_terms',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (63,'2026_03_19_012618_add_attendance_fields_to_ksa_settings_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (64,'2026_03_19_064622_fix_semester_column_in_subjects_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (65,'2026_03_19_071930_add_subject_id_to_classes_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (66,'2026_03_19_create_colleges_departments_refactor',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (67,'2026_03_19_refactor_subjects_table_normalization',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (68,'2026_03_19_remove_limits_add_multiple_instructors',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (69,'2026_03_20_000000_repopulate_courses_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (70,'2026_03_20_000001_populate_subjects_years_2_3_4',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (71,'2026_03_20_000002_link_classes_to_programs',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (72,'2026_03_20_000003_cleanup_unassigned_classes',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (73,'2026_03_20_000006_fix_invalid_subject_program_ids',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (74,'2026_03_20_000007_add_profile_fields_to_users',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (75,'2026_03_20_000008_create_teacher_subject_pivot',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (76,'2026_03_21_000000_backfill_user_employee_ids',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (77,'2026_03_21_000000_update_teacher_subject_pivot_status',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (78,'2026_03_21_151438_add_campus_field_to_tables',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (79,'2026_03_22_000000_restore_teacher_assignments_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (80,'2026_03_22_000001_add_campus_approval_to_users',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (81,'2026_03_22_000001_make_program_id_nullable_in_subjects',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (82,'2026_03_22_000002_create_course_access_requests_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (83,'2026_04_01_000001_add_request_type_fields_to_school_requests',1);
