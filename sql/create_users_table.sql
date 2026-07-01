-- =====================================================
-- CREATE TABLE users untuk fitur login admin
-- Database: portal_db
-- =====================================================

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `full_name` VARCHAR(150) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- =====================================================
-- INSERT default admin user
-- Username: admin
-- Password: admin123 (hashed with password_hash)
-- =====================================================

INSERT INTO `users` (`username`, `password`, `full_name`, `created_at`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', NOW());

-- NOTE: Default password = "password" (bcrypt hash di atas dari Laravel factory)
-- Untuk keamanan, gunakan script PHP di bawah untuk generate hash yang benar.
-- Atau jalankan file setup_admin.php di browser.
