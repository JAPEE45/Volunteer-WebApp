-- SQL to add duration and end_date fields to events table
-- Also adds event status to track active/completed events
-- Run this in phpMyAdmin on volunteer-web database

-- Step 1: Add duration column (in hours) - run this first
ALTER TABLE `events` ADD COLUMN `duration` INT NOT NULL DEFAULT 8 AFTER `date`;

-- Step 2: Add end_date column
ALTER TABLE `events` ADD COLUMN `end_date` DATETIME DEFAULT NULL AFTER `duration`;

-- Step 3: Add status column
ALTER TABLE `events` ADD COLUMN `status` VARCHAR(20) NOT NULL DEFAULT 'upcoming' AFTER `end_date`;

-- Step 4: Update existing events to calculate end_date (8 hours default)
UPDATE `events` SET `end_date` = DATE_ADD(`date`, INTERVAL 8 HOUR) WHERE `end_date` IS NULL;

-- Step 5: Update status based on current date
UPDATE `events` SET `status` = 'completed' WHERE `end_date` < NOW();
UPDATE `events` SET `status` = 'active' WHERE `date` <= NOW() AND `end_date` >= NOW();
UPDATE `events` SET `status` = 'upcoming' WHERE `date` > NOW();
