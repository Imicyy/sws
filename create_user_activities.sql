-- Create user_activities table for storing user activity logs
CREATE TABLE IF NOT EXISTS `user_activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `activity_type` varchar(100) NOT NULL,
  `activity_description` text NOT NULL,
  `metadata` json DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `created_at` (`created_at`),
  KEY `activity_type` (`activity_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert sample data
INSERT INTO user_activities (user_id, activity_type, activity_description, created_at) VALUES
(5, 'login', 'User logged in successfully', NOW()),
(5, 'view_dashboard', 'Viewed superadmin dashboard', NOW()),
(5, 'view_users', 'Viewed user management page', NOW()),
(7, 'login', 'User logged in successfully', NOW()),
(7, 'add_pwd', 'Added new PWD record', NOW())
ON DUPLICATE KEY UPDATE id = id;