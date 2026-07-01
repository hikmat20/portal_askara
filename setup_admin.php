<?php
/**
 * Setup Admin User
 * ================
 * Jalankan file ini SEKALI di browser untuk:
 * 1. Membuat tabel 'users' jika belum ada
 * 2. Membuat user admin default (username: admin, password: admin123)
 *
 * Akses: http://localhost/portal_askara/setup_admin.php
 * HAPUS FILE INI SETELAH SETUP SELESAI!
 */

// Load CI bootstrap
define('BASEPATH', __DIR__ . '/system/');
define('APPPATH', __DIR__ . '/application/');
define('ENVIRONMENT', 'development');

// Load database config
require_once APPPATH . 'config/database.php';

$conn = new mysqli(
    $db['default']['hostname'],
    $db['default']['username'],
    $db['default']['password'],
    $db['default']['database']
);

if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

$conn->set_charset('utf8');

echo "<h2>Setup Admin — ISO Platform Askara Group</h2>";

// 1. Create table
$sql_create = "
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `full_name` VARCHAR(150) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
";

if ($conn->query($sql_create)) {
    echo "<p>✅ Tabel <code>users</code> berhasil dibuat (atau sudah ada).</p>";
} else {
    echo "<p>❌ Gagal membuat tabel: " . $conn->error . "</p>";
}

// 2. Check if admin already exists
$check = $conn->query("SELECT id FROM users WHERE username = 'admin'");
if ($check && $check->num_rows > 0) {
    echo "<p>⚠️ User <code>admin</code> sudah ada. Tidak perlu membuat ulang.</p>";
} else {
    // Create admin with password: admin123
    $hashed_password = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, password, full_name, created_at) VALUES (?, ?, ?, NOW())");
    $full_name = 'Administrator';
    $username = 'admin';
    $stmt->bind_param('sss', $username, $hashed_password, $full_name);

    if ($stmt->execute()) {
        echo "<p>✅ User admin berhasil dibuat!</p>";
        echo "<p><strong>Username:</strong> admin</p>";
        echo "<p><strong>Password:</strong> admin123</p>";
    } else {
        echo "<p>❌ Gagal membuat user admin: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

$conn->close();

echo "<hr>";
echo "<p style='color:red;font-weight:bold;'>⚠️ HAPUS FILE INI (setup_admin.php) SETELAH SETUP SELESAI!</p>";
echo "<p><a href='" . dirname($_SERVER['SCRIPT_NAME']) . "/'>→ Buka Portal</a></p>";
