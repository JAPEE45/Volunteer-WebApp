-- SQL to create evaluations table for volunteer assessment
-- Run this in phpMyAdmin to add the table to volunteer-web database

CREATE TABLE IF NOT EXISTS `evaluations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `evaluator_id` int(11) NOT NULL,
  `physical_fitness` decimal(2,1) NOT NULL DEFAULT 0.0,
  `communication_skills` decimal(2,1) NOT NULL DEFAULT 0.0,
  `teamwork` decimal(2,1) NOT NULL DEFAULT 0.0,
  `reliability` decimal(2,1) NOT NULL DEFAULT 0.0,
  `overall_rating` decimal(2,1) NOT NULL DEFAULT 0.0,
  `comments` text DEFAULT NULL,
  `status` enum('passed','failed') NOT NULL DEFAULT 'passed',
  `evaluation_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `evaluator_id` (`evaluator_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
