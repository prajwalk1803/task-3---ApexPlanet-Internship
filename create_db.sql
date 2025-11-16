CREATE DATABASE IF NOT EXISTS task3_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE task3_db;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('user','admin') DEFAULT 'user',
  profile_picture VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed admin user (password: Admin@123)
INSERT INTO users (name, email, password, role) VALUES
('Admin', 'admin@example.com', '$2y$10$wHk9Jj.3b7vQ8qK6AW3V6e6fH8xH3b3k0GZL1Q7k2k8c6fO5vL6uW', 'admin');
-- NOTE: The above hash corresponds to password 'Admin@123' using password_hash PHP default.
