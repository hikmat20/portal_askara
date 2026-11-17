-- =====================================================
-- Tambah kolom app_code pada tabel apps & buat tabel app_login_logs
-- Database: portal_db
-- =====================================================

ALTER TABLE `apps`
  ADD COLUMN `app_code` VARCHAR(100) DEFAULT NULL AFTER `name`,
  ADD INDEX `idx_apps_code` (`app_code`);

CREATE TABLE IF NOT EXISTS `app_login_logs` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `app_id` INT(11) NOT NULL,
  `user_id` VARCHAR(100) DEFAULT NULL,
  `username` VARCHAR(100) NOT NULL,
  `full_name` VARCHAR(150) DEFAULT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `user_agent` VARCHAR(500) DEFAULT NULL,
  `login_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_app_login_at` (`app_id`, `login_at`),
  KEY `idx_app_user` (`app_id`, `username`),
  KEY `idx_login_at` (`login_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `portal_settings` (
  `setting_key` VARCHAR(100) NOT NULL,
  `setting_value` TEXT DEFAULT NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `portal_settings` (`setting_key`, `setting_value`) VALUES
('api_endpoint_url', 'http://localhost/portal_askara/index.php/api/log_login'),
('api_key', 'ASKARA_PORTAL_KEY_2026'),
('api_enabled', '1');
