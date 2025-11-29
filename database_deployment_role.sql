-- Add role column to deployment table
-- Run this SQL in phpMyAdmin or MySQL command line

-- Add role column if it doesn't exist
ALTER TABLE `deployment` 
ADD COLUMN IF NOT EXISTS `role` VARCHAR(100) DEFAULT NULL AFTER `event_id`;

-- Sample volunteer roles for Red Cross operations
-- These are the common roles that volunteers can be assigned to:
-- - First Aider
-- - Ambulance Driver
-- - Logistics Support
-- - Communications Officer
-- - Search and Rescue
-- - Medical Assistant
-- - Crowd Control
-- - Documentation
-- - Relief Distribution
-- - Psychosocial Support
