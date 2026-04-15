-- =============================================
-- APP PORTAL - Database Setup
-- =============================================

CREATE DATABASE IF NOT EXISTS `portal_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `portal_db`;

CREATE TABLE IF NOT EXISTS `apps` (
  `id`          INT(11) NOT NULL AUTO_INCREMENT,
  `name`        VARCHAR(100) NOT NULL,
  `url`         VARCHAR(500) NOT NULL,
  `description` VARCHAR(255) DEFAULT NULL,
  `logo`        VARCHAR(500) DEFAULT NULL,
  `color`       VARCHAR(20) DEFAULT '#6366f1',
  `category`    VARCHAR(100) DEFAULT 'General',
  `is_active`   TINYINT(1) DEFAULT 1,
  `sort_order`  INT(11) DEFAULT 0,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample Data
INSERT INTO `apps` (`name`, `url`, `description`, `logo`, `color`, `category`) VALUES
('Gmail',       'https://mail.google.com',   'Email & komunikasi',      NULL, '#EA4335', 'Produktivitas'),
('Google Drive','https://drive.google.com',  'Penyimpanan cloud',        NULL, '#4285F4', 'Produktivitas'),
('WhatsApp Web','https://web.whatsapp.com',  'Pesan & chat tim',         NULL, '#25D366', 'Komunikasi'),
('Notion',      'https://notion.so',         'Manajemen dokumen',        NULL, '#000000', 'Produktivitas'),
('Figma',       'https://figma.com',         'Desain & prototyping',     NULL, '#F24E1E', 'Desain'),
('GitHub',      'https://github.com',        'Version control & code',   NULL, '#181717', 'Developer');
