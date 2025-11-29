-- SQL query to create the reports table

CREATE TABLE IF NOT EXISTS `reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `deployment_id` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `report_title` varchar(255) NOT NULL,
  `report_description` text DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_size` bigint(20) DEFAULT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `status` enum('pending','reviewed','approved','rejected') DEFAULT 'pending',
  `submission_date` datetime NOT NULL DEFAULT current_timestamp(),
  `review_date` datetime DEFAULT NULL,
  `reviewed_by` int(11) DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `deployment_id` (`deployment_id`),
  KEY `event_id` (`event_id`),
  KEY `status` (`status`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`deployment_id`) REFERENCES `deployment`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`event_id`) REFERENCES `events`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Add indexes for better query performance
CREATE INDEX idx_submission_date ON reports(submission_date DESC);
CREATE INDEX idx_user_status ON reports(user_id, status);
