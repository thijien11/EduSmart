<?php
// Define base URL
define('BASE_URL', 'http://localhost/your-project-folder/');

// Define paths
define('APP_ROOT', dirname(dirname(__FILE__)));
define('VIEWS_PATH', APP_ROOT . '/views/');
define('CONTROLLERS_PATH', APP_ROOT . '/controllers/');
define('MODELS_PATH', APP_ROOT . '/models/');
define('ASSETS_PATH', BASE_URL . 'assets/');

// Database constants
define('DB_HOST', 'localhost');
define('DB_NAME', 'auth_system');
define('DB_USER', 'root');
define('DB_PASS', '');

// Application settings
define('APP_NAME', 'Auth System');
define('APP_VERSION', '1.0.0');
define('MAX_LOGIN_ATTEMPTS', 5);
define('SESSION_TIMEOUT', 3600); // 1 hour

// Email settings
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'your-email@gmail.com');
define('SMTP_PASS', 'your-password');

// Security settings
define('PASSWORD_MIN_LENGTH', 6);
define('PASSWORD_MAX_LENGTH', 255);
define('CSRF_TOKEN_EXPIRE', 1800); // 30 minutes

// Error reporting
define('DEBUG_MODE', true);
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Timezone
date_default_timezone_set('America/New_York');
?>