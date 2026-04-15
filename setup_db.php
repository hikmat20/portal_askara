<?php
// Simple database setup script
$config = include 'application/config/database.php';

try {
    $pdo = new PDO(
        "mysql:host={$config['default']['hostname']};dbname={$config['default']['database']}",
        $config['default']['username'],
        $config['default']['password']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Database connection successful!<br>";

    // Check if table exists
    $result = $pdo->query("SHOW TABLES LIKE 'apps'");
    if ($result->rowCount() > 0) {
        echo "Table 'apps' exists.<br>";
    } else {
        echo "Table 'apps' does not exist. Creating...<br>";

        $sql = "
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
        ";

        $pdo->exec($sql);
        echo "Table 'apps' created successfully!<br>";

        // Insert sample data
        $sampleData = [
            ['Gmail', 'https://mail.google.com', 'Email & komunikasi', NULL, '#EA4335', 'Produktivitas'],
            ['Google Drive', 'https://drive.google.com', 'Penyimpanan cloud', NULL, '#4285F4', 'Produktivitas'],
            ['WhatsApp Web', 'https://web.whatsapp.com', 'Pesan & chat tim', NULL, '#25D366', 'Komunikasi'],
            ['Notion', 'https://notion.so', 'Manajemen dokumen', NULL, '#000000', 'Produktivitas'],
            ['Figma', 'https://figma.com', 'Desain & prototyping', NULL, '#F24E1E', 'Desain'],
            ['GitHub', 'https://github.com', 'Version control & code', NULL, '#181717', 'Developer']
        ];

        $stmt = $pdo->prepare("INSERT INTO apps (name, url, description, logo, color, category) VALUES (?, ?, ?, ?, ?, ?)");
        foreach ($sampleData as $app) {
            $stmt->execute($app);
        }
        echo "Sample data inserted successfully!<br>";
    }

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "<br>";
}
?>