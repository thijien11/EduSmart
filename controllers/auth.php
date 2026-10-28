<?php
// controllers/auth.php (Updated version)
session_start();

// Database connection
$db = null;
try {
    $db = new PDO("mysql:host=localhost;dbname=edusmart", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    header('Location: ../index.php?error=Database+connection+failed');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';
    $education_level = $_POST['education_level'] ?? '';
    $current_class = $_POST['current_class'] ?? '';
    
    // Validate required fields
    if (empty($email) || empty($password) || empty($role)) {
        header('Location: ../index.php?error=All+required+fields+must+be+filled');
        exit();
    }
    
    // For students, validate education level and class
    if ($role === 'student') {
        if (empty($education_level) || empty($current_class)) {
            header('Location: ../index.php?error=Please+select+your+education+level+and+current+class');
            exit();
        }
    }
    
    try {
        // Prepare query based on role
        if ($role === 'student') {
            // For students, also check education level and class
            $stmt = $db->prepare("SELECT * FROM users WHERE email = ? AND role = ? AND education_level = ? AND current_class = ? AND status = 'active'");
            $stmt->execute([$email, $role, $education_level, $current_class]);
        } else {
            // For teachers and admins
            $stmt = $db->prepare("SELECT * FROM users WHERE email = ? AND role = ? AND (status = 'active' OR status = 'approved')");
            $stmt->execute([$email, $role]);
        }
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            // Login successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['education_level'] = $user['education_level'] ?? '';
            $_SESSION['current_class'] = $user['current_class'] ?? '';
            $_SESSION['country'] = $user['country'] ?? '';
            
            // Redirect to appropriate dashboard
            switch ($user['role']) {
                case 'admin':
                    header('Location: ../dashboards/admin/dashboard.php');
                    break;
                case 'teacher':
                    header('Location: ../dashboards/teacher/dashboard.php');
                    break;
                case 'student':
                    header('Location: ../dashboards/student/dashboard.php');
                    break;
                default:
                    header('Location: ../dashboard.php');
            }
            exit();
        } else {
            // Login failed
            header('Location: ../index.php?error=Invalid+credentials+or+account+not+active');
            exit();
        }
        
    } catch(PDOException $e) {
        header('Location: ../index.php?error=Database+error+' . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Not a POST request
    header('Location: ../index.php');
    exit();
}
?>