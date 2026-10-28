<?php
session_start();

// Initialize message variables
$error = '';
$success = '';

// Database connection
$db = null;
try {
    $db = new PDO("mysql:host=localhost", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec("USE edusmart_db");
    
} catch(PDOException $e) {
    $error = "Note: Running without database connection. " . $e->getMessage();
    $db = null;
}

// Redirect logged in users
if (isset($_SESSION['user_id'])) {
    header('Location: views/dashboard.php');
    exit();
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'student_signup') {
        // Process student registration
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $education_level = $_POST['education_level'] ?? '';
        $current_class = $_POST['current_class'] ?? '';
        $country = $_POST['country'] ?? '';
        
        // Basic validation
        if (empty($name) || empty($email) || empty($password) || empty($education_level) || empty($country)) {
            $error = "All required fields must be filled";
        } elseif (strlen($password) < 8) {
            $error = "Password must be at least 8 characters";
        } elseif (!$db) {
            $error = "Database connection failed. Please try again later.";
        } else {
            try {
                // Check if email exists
                $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$email]);
                
                if ($stmt->fetch()) {
                    $error = "Email already exists";
                } else {
                    // Insert new student
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $db->prepare("INSERT INTO users (name, email, password, role, education_level, current_class, country, status) VALUES (?, ?, ?, 'student', ?, ?, ?, 'active')");
                    $stmt->execute([$name, $email, $hashed_password, $education_level, $current_class, $country]);
                    
                    $success = "Student account created successfully! You can now login.";
                }
            } catch(PDOException $e) {
                $error = "Database error: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - EduAccess AI-Powered Learning Platform</title>
    
    <!-- CSS Links -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <style>
        /* ===== CSS VARIABLES ===== */
:root {
    --primary: #4361ee;
    --primary-dark: #3a56d4;
    --secondary: #7209b7;
    --accent: #4cc9f0;
    --success: #4ade80;
    --warning: #f59e0b;
    --danger: #ef4444;
    --info: #06b6d4;
    
    --gradient-primary: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
    --gradient-secondary: linear-gradient(135deg, #7209b7 0%, #3a0ca3 100%);
    --gradient-success: linear-gradient(135deg, #10b981 0%, #059669 100%);
    --gradient-warning: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    --gradient-info: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
    --gradient-dark: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    
    --bg-primary: #ffffff;
    --bg-secondary: #f8fafc;
    --bg-tertiary: #f1f5f9;
    --bg-card: #ffffff;
    --bg-glass: rgba(255, 255, 255, 0.9);
    --bg-dark: #0f172a;
    --bg-dark-card: #1e293b;
    
    --text-primary: #1e293b;
    --text-secondary: #64748b;
    --text-tertiary: #94a3b8;
    --text-light: #ffffff;
    --text-dark: #f1f5f9;
    
    --border-color: #e2e8f0;
    --border-dark: #475569;
    
    --radius-sm: 8px;
    --radius: 12px;
    --radius-lg: 16px;
    --radius-xl: 24px;
    --radius-full: 9999px;
    
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    --shadow-xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    --shadow-2xl: 0 50px 100px -20px rgba(0, 0, 0, 0.3);
    
    --spacing-xs: 0.5rem;
    --spacing-sm: 1rem;
    --spacing-md: 1.5rem;
    --spacing-lg: 2rem;
    --spacing-xl: 3rem;
    --spacing-2xl: 4rem;
    
    --font-heading: 'Poppins', sans-serif;
    --font-body: 'Inter', sans-serif;
    
    --transition: 300ms cubic-bezier(0.4, 0, 0.2, 1);
    --transition-bounce: 500ms cubic-bezier(0.68, -0.55, 0.265, 1.55);
    
    --glass-blur: blur(20px);
}

[data-theme="dark"] {
    --bg-primary: #0f172a;
    --bg-secondary: #1e293b;
    --bg-tertiary: #334155;
    --bg-card: #1e293b;
    --bg-glass: rgba(30, 41, 59, 0.9);
    --text-primary: #f1f5f9;
    --text-secondary: #cbd5e1;
    --text-tertiary: #94a3b8;
    --border-color: #475569;
}

/* ===== BASE STYLES ===== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html {
    scroll-behavior: smooth;
}

body {
    font-family: var(--font-body);
    background: var(--bg-primary);
    color: var(--text-primary);
    line-height: 1.6;
    transition: background-color var(--transition), color var(--transition);
    overflow-x: hidden;
    min-height: 100vh;
    background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-primary) 100%);
}

h1, h2, h3, h4, h5, h6 {
    font-family: var(--font-heading);
    font-weight: 600;
    line-height: 1.2;
}

a {
    color: var(--primary);
    text-decoration: none;
    transition: color var(--transition);
}

a:hover {
    color: var(--primary-dark);
}

.container {
    width: 100%;
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 var(--spacing-md);
}

/* ===== HEADER STYLES ===== */
.header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background: var(--bg-glass);
    backdrop-filter: var(--glass-blur);
    -webkit-backdrop-filter: var(--glass-blur);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    z-index: 1000;
    transition: all var(--transition);
}

.nav-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.25rem var(--spacing-md);
    max-width: 1400px;
    margin: 0 auto;
}

.logo {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    text-decoration: none;
}

.logo-icon {
    width: 40px;
    height: 40px;
    background: var(--gradient-primary);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.logo-text {
    font-family: var(--font-heading);
    font-size: 1.75rem;
    font-weight: 800;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.nav-links {
    display: flex;
    gap: var(--spacing-lg);
    list-style: none;
    align-items: center;
}

.nav-links a {
    text-decoration: none;
    color: var(--text-secondary);
    font-weight: 500;
    font-size: 1rem;
    padding: 0.5rem 1rem;
    border-radius: var(--radius-full);
    transition: all var(--transition);
}

.nav-links a:hover {
    color: var(--primary);
    background: rgba(67, 97, 238, 0.1);
}

.nav-actions {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
}

/* ===== BUTTON STYLES ===== */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    padding: 0.875rem 2rem;
    border-radius: var(--radius-full);
    font-family: var(--font-heading);
    font-weight: 600;
    font-size: 1rem;
    text-decoration: none;
    cursor: pointer;
    transition: all var(--transition-bounce);
    border: none;
    outline: none;
    position: relative;
    overflow: hidden;
}

.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.1);
    opacity: 0;
    transition: opacity var(--transition);
}

.btn:hover::before {
    opacity: 1;
}

.btn-primary {
    background: var(--gradient-primary);
    color: white;
    box-shadow: 0 10px 20px rgba(67, 97, 238, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 30px rgba(67, 97, 238, 0.4);
}

.btn-secondary {
    background: transparent;
    border: 2px solid var(--primary);
    color: var(--primary);
}

.btn-secondary:hover {
    background: var(--primary);
    color: white;
    transform: translateY(-2px);
}

.btn-sm {
    padding: 0.625rem 1.5rem;
    font-size: 0.875rem;
}

/* ===== LOGIN CONTAINER ===== */
.login-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 120px var(--spacing-md) var(--spacing-2xl);
}

.login-wrapper {
    display: grid;
    grid-template-columns: 1fr 1.2fr;
    gap: var(--spacing-xl);
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    background: var(--bg-card);
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: var(--shadow-2xl);
    border: 1px solid rgba(255, 255, 255, 0.2);
    backdrop-filter: var(--glass-blur);
    -webkit-backdrop-filter: var(--glass-blur);
}

.login-left {
    padding: var(--spacing-2xl);
    background: var(--gradient-dark);
    color: white;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.login-logo-container {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-xl);
}

.login-logo {
    width: 64px;
    height: 64px;
    background: white;
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: var(--primary);
}

.login-welcome-text {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: var(--spacing-md);
    line-height: 1.1;
    background: linear-gradient(135deg, #fff 0%, #94a3b8 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.login-welcome-subtext {
    font-size: 1.125rem;
    opacity: 0.9;
    margin-bottom: var(--spacing-xl);
    line-height: 1.6;
}

.login-features-list {
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
}

.login-features-list li {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    font-size: 1rem;
}

.login-features-list i {
    color: var(--primary);
    background: rgba(255, 255, 255, 0.1);
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

.login-right {
    padding: var(--spacing-2xl);
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.login-header {
    margin-bottom: var(--spacing-xl);
}

.login-header h2 {
    font-size: 2.25rem;
    font-weight: 700;
    margin-bottom: var(--spacing-sm);
    color: var(--text-primary);
}

.login-header p {
    color: var(--text-secondary);
    font-size: 1.125rem;
}

/* ===== FORM STYLES ===== */
.form-group {
    margin-bottom: var(--spacing-lg);
}

.form-label {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    margin-bottom: 0.5rem;
    color: var(--text-primary);
    font-weight: 600;
    font-size: 0.875rem;
}

.form-label i {
    color: var(--primary);
    width: 20px;
}

.required::after {
    content: '*';
    color: var(--danger);
    margin-left: 4px;
}

.form-input, .form-select {
    width: 100%;
    padding: 1rem 1.25rem;
    border: 2px solid var(--border-color);
    border-radius: var(--radius-lg);
    background: var(--bg-primary);
    color: var(--text-primary);
    font-size: 1rem;
    font-family: var(--font-body);
    transition: all var(--transition);
}

.form-input:focus, .form-select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
}

.form-input::placeholder {
    color: var(--text-tertiary);
}

/* ===== ALERT STYLES ===== */
.alert {
    padding: 1rem 1.25rem;
    border-radius: var(--radius);
    margin-bottom: var(--spacing-lg);
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    animation: slideInDown 0.3s ease;
}

@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.alert-danger {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.2);
    color: var(--danger);
}

.alert-success {
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid rgba(16, 185, 129, 0.2);
    color: var(--success);
}

/* ===== LOGIN FOOTER ===== */
.login-footer {
    margin-top: var(--spacing-xl);
    text-align: center;
    color: var(--text-secondary);
    font-size: 0.875rem;
}

.login-footer p {
    margin-bottom: 0.5rem;
}

.login-footer a {
    color: var(--primary);
    font-weight: 600;
}

.login-footer a:hover {
    text-decoration: underline;
}

/* ===== MODAL STYLES ===== */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2000;
    animation: fadeIn 0.3s ease;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    display: none;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.modal-content {
    background: var(--bg-card);
    backdrop-filter: var(--glass-blur);
    -webkit-backdrop-filter: var(--glass-blur);
    border-radius: var(--radius-xl);
    padding: var(--spacing-2xl);
    max-width: 600px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    animation: slideInRight 0.4s ease;
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: var(--shadow-2xl);
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--spacing-xl);
}

.modal-header h3 {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
}

.modal-close {
    background: none;
    border: none;
    font-size: 2rem;
    color: var(--text-secondary);
    cursor: pointer;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: var(--radius);
    transition: all var(--transition);
}

.modal-close:hover {
    background: var(--bg-tertiary);
    color: var(--text-primary);
}

/* ===== SIGNUP OPTION STYLES ===== */
.signup-option-card {
    display: flex;
    align-items: center;
    gap: var(--spacing-lg);
    padding: var(--spacing-xl);
    border: 2px solid var(--border-color);
    border-radius: var(--radius-lg);
    margin-bottom: var(--spacing-lg);
    cursor: pointer;
    transition: all var(--transition-bounce);
    position: relative;
    overflow: hidden;
    background: var(--bg-card);
}

.signup-option-card:hover {
    border-color: var(--primary);
    transform: translateY(-5px) scale(1.02);
    box-shadow: var(--shadow-xl);
}

.signup-option-icon {
    width: 64px;
    height: 64px;
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 28px;
    flex-shrink: 0;
    box-shadow: var(--shadow-lg);
}

.signup-option-content {
    flex: 1;
}

.signup-option-content h5 {
    margin: 0 0 8px 0;
    color: var(--text-primary);
    font-size: 1.25rem;
    font-weight: 700;
}

.signup-option-content p {
    margin: 0;
    color: var(--text-secondary);
    font-size: 0.9375rem;
    line-height: 1.5;
}

.signup-note {
    display: inline-block;
    padding: 6px 12px;
    background: var(--gradient-warning);
    color: white;
    border-radius: var(--radius-full);
    font-size: 0.75rem;
    font-weight: 700;
    margin-top: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.signup-option-action {
    color: var(--primary);
    font-size: 1.25rem;
}

/* ===== TEACHER REQUEST INFO ===== */
.teacher-request-info {
    background: rgba(245, 158, 11, 0.1);
    border: 1px solid rgba(245, 158, 11, 0.2);
    border-radius: var(--radius-lg);
    padding: var(--spacing-lg);
    margin-bottom: var(--spacing-xl);
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 1024px) {
    .login-wrapper {
        grid-template-columns: 1fr;
    }
    
    .login-left {
        padding: var(--spacing-xl);
    }
    
    .login-right {
        padding: var(--spacing-xl);
    }
    
    .login-welcome-text {
        font-size: 2.5rem;
    }
}

@media (max-width: 768px) {
    .nav-container {
        flex-direction: column;
        gap: var(--spacing-md);
        padding: 1rem;
    }
    
    .nav-links {
        gap: var(--spacing-sm);
    }
    
    .nav-links a {
        padding: 0.5rem;
        font-size: 0.875rem;
    }
    
    .login-container {
        padding: 100px var(--spacing-sm) var(--spacing-xl);
    }
    
    .login-welcome-text {
        font-size: 2rem;
    }
    
    .login-header h2 {
        font-size: 1.75rem;
    }
    
    .modal-content {
        padding: var(--spacing-xl);
        width: 95%;
    }
}

@media (max-width: 480px) {
    .logo-text {
        font-size: 1.5rem;
    }
    
    .btn {
        padding: 0.75rem 1.5rem;
        font-size: 0.875rem;
    }
    
    .login-left, .login-right {
        padding: var(--spacing-lg);
    }
    
    .login-welcome-text {
        font-size: 1.75rem;
    }
    
    .signup-option-card {
        flex-direction: column;
        text-align: center;
        gap: var(--spacing-md);
    }
    
    .signup-option-icon {
        width: 48px;
        height: 48px;
        font-size: 20px;
    }
}
    </style>
    
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- HEADER -->
    <header class="header">
        <div class="container nav-container">
            <a href="index.php" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="logo-text">EduAccess</div>
            </a>
            
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="#features">Features</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
            
            <div class="nav-actions">
                <button class="btn btn-primary btn-sm" onclick="window.location.href='index.php'">
                    <i class="fas fa-sign-in-alt"></i>
                    Login
                </button>
            </div>
        </div>
    </header>

    <!-- REGISTRATION CONTAINER -->
    <div class="login-container">
        <div class="login-wrapper">
            <!-- Left Side: Welcome & Info -->
            <div class="login-left">
                <div class="login-logo-container">
                    <div class="login-logo">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div>
                        <div style="font-size: 36px; font-weight: 800; font-family: 'Poppins', sans-serif;">EduAccess</div>
                        <div style="font-size: 14px; opacity: 0.9;">AI-Powered Learning Platform</div>
                    </div>
                </div>
                
                <h1 class="login-welcome-text">Join EduAccess<br>Today</h1>
                <p class="login-welcome-subtext">
                    Create your account to access personalized learning, AI-powered tutoring, and track your educational journey.
                </p>
                
                <ul class="login-features-list">
                    <li><i class="fas fa-rocket"></i> Get started in minutes</li>
                    <li><i class="fas fa-brain"></i> AI-powered learning assistant</li>
                    <li><i class="fas fa-chart-line"></i> Track your progress</li>
                    <li><i class="fas fa-users"></i> Join learning communities</li>
                </ul>
            </div>
            
            <!-- Right Side: Registration Options -->
            <div class="login-right">
                <div class="login-header">
                    <h2>Join EduAccess</h2>
                    <p>Choose your account type to get started</p>
                </div>
                
                <!-- Display Messages -->
                <?php if (isset($error) && !empty($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <?php if (isset($success) && !empty($success)): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>
                
                <!-- Student Registration Option -->
                <div class="signup-option-card" onclick="showStudentRegistration()" style="cursor: pointer;">
                    <div class="signup-option-icon" style="background: var(--gradient-primary);">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="signup-option-content">
                        <h5>Student Account</h5>
                        <p>Self-register and get instant access to learning materials, AI tutor, and progress tracking.</p>
                        <span class="signup-note">Instant Access</span>
                    </div>
                    <div class="signup-option-action">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
                
                <!-- Teacher/Lecturer Registration Option -->
                <div class="signup-option-card" onclick="showTeacherRequestForm()" style="cursor: pointer;">
                    <div class="signup-option-icon" style="background: var(--gradient-warning);">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="signup-option-content">
                        <h5>Teacher/Lecturer Account</h5>
                        <p>Request access with institutional verification. Admin approval required for security.</p>
                        <span class="signup-note">Verification Required</span>
                    </div>
                    <div class="signup-option-action">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
                
                <div class="login-footer">
                    <p>
                        Already have an account? 
                        <a href="index.php">Sign in here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- STUDENT REGISTRATION MODAL -->
    <div id="studentRegistrationModal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Student Registration</h3>
                <button class="modal-close" onclick="closeStudentRegistrationModal()">×</button>
            </div>
            
            <div style="margin-bottom: var(--spacing-md);">
                <?php if (isset($error) && !empty($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <?php if (isset($success) && !empty($success)): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>
                
                <form method="POST" action="" id="studentRegistrationForm">
                    <input type="hidden" name="action" value="student_signup">
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-lg);">
                        <!-- Full Name -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-user"></i>
                                Full Name
                                <span class="required"></span>
                            </label>
                            <input type="text" id="studentName" name="name" class="form-input" 
                                   placeholder="Your full name" required>
                        </div>
                        
                        <!-- Email -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-envelope"></i>
                                Email Address
                                <span class="required"></span>
                            </label>
                            <input type="email" id="studentEmail" name="email" class="form-input" 
                                   placeholder="student@email.com" required>
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-lg);">
                        <!-- Password -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-lock"></i>
                                Password
                                <span class="required"></span>
                            </label>
                            <input type="password" id="studentPassword" name="password" class="form-input" 
                                   placeholder="Minimum 8 characters" required minlength="8">
                        </div>
                        
                        <!-- Education Level -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-graduation-cap"></i>
                                Education Level
                                <span class="required"></span>
                            </label>
                            <select id="educationLevel" name="education_level" class="form-input" required onchange="updateClassOptionsRegistration()">
                                <option value="">Select Level</option>
                                <option value="primary">Primary School</option>
                                <option value="secondary">Secondary School</option>
                                <option value="university">University/College</option>
                                <option value="vocational">Vocational Training</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Current Class -->
                    <div class="form-group" id="classFieldRegistration">
                        <label class="form-label">
                            <i class="fas fa-user-graduate"></i>
                            Current Class/Form
                            <span class="required"></span>
                        </label>
                        <select id="currentClass" name="current_class" class="form-input">
                            <option value="">Select Class</option>
                        </select>
                    </div>
                    
                    <!-- Country -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-globe-africa"></i>
                            Country
                            <span class="required"></span>
                        </label>
                        <select id="country" name="country" class="form-input" required>
                            <option value="">Select Country</option>
                            <option value="KE">Kenya</option>
                            <option value="UG">Uganda</option>
                            <option value="TZ">Tanzania</option>
                            <option value="RW">Rwanda</option>
                            <option value="SS">South Sudan</option>
                        </select>
                    </div>
                    
                    <!-- Terms -->
                    <div class="form-group">
                        <label style="display: flex; align-items: flex-start; gap: var(--spacing-sm); cursor: pointer; padding: var(--spacing-sm); background: var(--bg-tertiary); border-radius: var(--radius);">
                            <input type="checkbox" id="studentTerms" name="terms" style="margin-top: 3px; flex-shrink: 0;" required>
                            <span style="font-size: 0.9rem; color: var(--text-secondary);">
                                I agree to the <a href="#" style="color: var(--primary);">Terms of Service</a> and <a href="#" style="color: var(--primary);">Privacy Policy</a>
                            </span>
                        </label>
                    </div>
                    
                    <!-- Submit -->
                    <div style="display: flex; gap: var(--spacing-md); margin-top: var(--spacing-xl);">
                        <button type="button" class="btn btn-secondary" onclick="closeStudentRegistrationModal()" style="flex: 1;">
                            <i class="fas fa-times"></i>
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary" style="flex: 2;">
                            <i class="fas fa-user-plus"></i>
                            Create Student Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- TEACHER REQUEST MODAL -->
    <div id="teacherRequestModal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Teacher Account Request</h3>
                <button class="modal-close" onclick="closeTeacherRequestModal()">×</button>
            </div>
            
            <div style="margin-bottom: var(--spacing-md);">
                <div class="teacher-request-info">
                    <h4 style="color: var(--warning); margin: 0 0 var(--spacing-sm) 0; display: flex; align-items: center; gap: var(--spacing-sm);">
                        <i class="fas fa-exclamation-triangle"></i>
                        Verification Required
                    </h4>
                    <p style="color: var(--text-secondary); margin: 0; font-size: 0.95rem;">
                        Teacher accounts require institutional verification. Please contact administration at admin@edusmart.com
                    </p>
                </div>
                
                <div style="text-align: center; padding: var(--spacing-xl) 0;">
                    <i class="fas fa-envelope" style="font-size: 48px; color: var(--primary); margin-bottom: var(--spacing-md);"></i>
                    <h4>Contact Administration</h4>
                    <p style="color: var(--text-secondary); margin-bottom: var(--spacing-xl);">
                        Send your institutional credentials to: admin@edusmart.com
                    </p>
                    <a href="mailto:admin@edusmart.com?subject=Teacher%20Account%20Request" class="btn btn-primary">
                        <i class="fas fa-envelope"></i>
                        Send Email Request
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="js/main.js"></script>
    <script>
        // Class options for different education levels
        const classOptions = {
            primary: ['Primary'],
            secondary: ['Secondary'],
            university: ['Year 1', 'Year 2', 'Year 3', 'Year 4', 'Year 5', 'Postgraduate'],
            vocational: ['Certificate Level', 'Diploma Level', 'Advanced Diploma']
        };

        // Update class options for registration form
        function updateClassOptionsRegistration() {
            const educationLevel = document.getElementById('educationLevel').value;
            const classSelect = document.getElementById('currentClass');
            
            // Clear existing options
            classSelect.innerHTML = '<option value="">Select Class</option>';
            
            // Add new options
            if (classOptions[educationLevel]) {
                classOptions[educationLevel].forEach(className => {
                    const option = document.createElement('option');
                    option.value = className;
                    option.textContent = className;
                    classSelect.appendChild(option);
                });
            }
            
            // Show/hide class field
            const classField = document.getElementById('classFieldRegistration');
            classField.style.display = educationLevel ? 'block' : 'none';
        }

        // Modal functions
        function showStudentRegistration() {
            document.getElementById('studentRegistrationModal').style.display = 'flex';
        }

        function closeStudentRegistrationModal() {
            document.getElementById('studentRegistrationModal').style.display = 'none';
        }

        function showTeacherRequestForm() {
            document.getElementById('teacherRequestModal').style.display = 'flex';
        }

        function closeTeacherRequestModal() {
            document.getElementById('teacherRequestModal').style.display = 'none';
        }

        // Student registration form validation
        document.getElementById('studentRegistrationForm').addEventListener('submit', function(e) {
            const password = document.getElementById('studentPassword').value;
            
            if (password.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long');
                return false;
            }
            
            if (!document.getElementById('studentTerms').checked) {
                e.preventDefault();
                alert('You must agree to the Terms of Service and Privacy Policy');
                return false;
            }
            
            return true;
        });

        // Close modals when clicking outside
        document.addEventListener('click', function(event) {
            const modals = document.querySelectorAll('.modal-overlay');
            modals.forEach(modal => {
                if (modal === event.target) {
                    modal.style.display = 'none';
                }
            });
        });

        // Initialize class options for registration form
        document.addEventListener('DOMContentLoaded', function() {
            const educationLevelSelect = document.getElementById('educationLevel');
            if (educationLevelSelect) {
                educationLevelSelect.addEventListener('change', updateClassOptionsRegistration);
            }
        });
    </script>
</body>
</html>