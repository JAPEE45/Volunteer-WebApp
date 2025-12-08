-- Activity Reports Table
-- This table stores volunteer activity reports submitted after deployments
-- Run this SQL in phpMyAdmin or MySQL command line

CREATE TABLE IF NOT EXISTS `activity_reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deployment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `hours_worked` decimal(5,2) NOT NULL,
  `date_of_activity` date NOT NULL,
  `activities_performed` text NOT NULL,
  `challenges_faced` text DEFAULT NULL,
  `outcomes_achieved` text DEFAULT NULL,
  `recommendations` text DEFAULT NULL,
  `supporting_documents` text DEFAULT NULL COMMENT 'JSON array of file paths',
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `reviewed_by` int(11) DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL,
  `submitted_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_deployment` (`deployment_id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_event` (`event_id`),
  KEY `idx_status` (`status`),
  KEY `idx_submitted_at` (`submitted_at`),
  KEY `idx_user_deployment` (`user_id`, `deployment_id`),
  KEY `idx_event_status` (`event_id`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add foreign key constraints (optional, but recommended)
-- Uncomment if you want referential integrity

-- ALTER TABLE `activity_reports`
--   ADD CONSTRAINT `fk_activity_reports_deployment` FOREIGN KEY (`deployment_id`) REFERENCES `deployment` (`id`) ON DELETE CASCADE,
--   ADD CONSTRAINT `fk_activity_reports_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
--   ADD CONSTRAINT `fk_activity_reports_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
--   ADD CONSTRAINT `fk_activity_reports_reviewer` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

-- Add unique constraint to prevent duplicate reports per deployment
ALTER TABLE `activity_reports`
  ADD UNIQUE KEY `unique_deployment_report` (`deployment_id`);
