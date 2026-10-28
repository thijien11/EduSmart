<?php
// Site Configuration
define('SITE_NAME', 'EduSmart');
define('SITE_URL', 'http://localhost/EduSmart/');
define('SITE_ROOT', __DIR__ . '/../');

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'edusmart_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Session Configuration
define('SESSION_TIMEOUT', 3600); // 1 hour

// Debug Settings
define('DEBUG_MODE', true);
error_reporting(DEBUG_MODE ? E_ALL : 0);
ini_set('display_errors', DEBUG_MODE ? 1 : 0);

// ==============================================
// DATABASE CONNECTION
// ==============================================
try {
    // Create PDO connection
    $db = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
    
    // Optional: Set timezone for database
    $db->exec("SET time_zone = '+03:00'");
    
    // Connection successful
    // You can add a log here if needed
    
} catch (PDOException $e) {
    // Handle connection error
    if (DEBUG_MODE) {
        die("Database Connection Failed: " . $e->getMessage());
    } else {
        // Log the error and show user-friendly message
        error_log("Database connection error: " . $e->getMessage());
        die("System temporarily unavailable. Please try again later.");
    }
}
?>
