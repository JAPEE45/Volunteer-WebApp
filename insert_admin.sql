-- Add admin account back to the database
-- Run this in phpMyAdmin SQL tab

INSERT INTO `account` (`id`, `username`, `password`, `user_id`, `createdAt`, `user_type`, `lastLogin`) VALUES
(2, 'admin123', 'pass@123', 0, '2025-10-08', 'admin', '2025-12-09')
ON DUPLICATE KEY UPDATE 
    username = 'admin123',
    password = 'pass@123',
    user_type = 'admin',
    lastLogin = '2025-12-09';
