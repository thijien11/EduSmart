<?php
session_start();

// IMPORTANT: Remove or comment out debug lines above before using this

// Include config
require_once(__DIR__ . '/config/config.php');

// Check if this is a login request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = "Invalid request method";
    header('Location: ../index.php');
    exit();
}

// Get form data
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$role = trim($_POST['role'] ?? 'student');

// Basic validation
if (empty($email) || empty($password) || empty($role)) {
    $_SESSION['error'] = "All fields are required";
    header('Location: ../index.php');
    exit();
}

try {
    // Query the user
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user exists
    if (!$user) {
        $_SESSION['error'] = "Invalid email or password";
        header('Location: ../index.php');
        exit();
    }

    // Verify password
    if (!password_verify($password, $user['password'])) {
        $_SESSION['error'] = "Invalid email or password";
        header('Location: ../index.php');
        exit();
    }

    // Verify role matches
    if ($user['role'] !== $role) {
        $_SESSION['error'] = "You selected '$role' but your account is '" . $user['role'] . "'. Please select the correct role.";
        header('Location: ../index.php');
        exit();
    }

    // Check account status
    $status = $user['status'] ?? 'pending';
    
    // Define allowed statuses for each role
    $allowedStatuses = [
        'student' => ['active'],
        'teacher' => ['approved', 'active'],
        'admin' => ['active']
    ];

    if (!in_array($status, $allowedStatuses[$user['role']] ?? ['active'])) {
        $message = "Account not active";
        if ($status === 'pending') {
            $message = "Your account is pending approval";
        } elseif ($status === 'rejected') {
            $message = "Your account has been rejected";
        }
        $_SESSION['error'] = $message . " (Status: $status)";
        header('Location: ../index.php');
        exit();
    }

    // Login successful - store session data
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['education_level'] = $user['education_level'] ?? null;
    $_SESSION['current_class'] = $user['current_class'] ?? null;
    $_SESSION['country'] = $user['country'] ?? null;
    $_SESSION['logged_in'] = true;
    $_SESSION['login_time'] = time();

    // Redirect based on role - USING RELATIVE PATHS
    switch($user['role']) {
        case 'admin':
            // Check if file exists before redirecting
            $adminDashboard = '../dashboards/admin/dashboard.php';
            if (!file_exists($adminDashboard)) {
                $_SESSION['error'] = "Admin dashboard not found at: $adminDashboard";
                header('Location: ../index.php');
                exit();
            }
            header('Location: ' . $adminDashboard);
            break;
            
        case 'teacher':
            $teacherDashboard = '../dashboards/teacher/dashboard.php';
            if (!file_exists($teacherDashboard)) {
                $_SESSION['error'] = "Teacher dashboard not found at: $teacherDashboard";
                header('Location: ../index.php');
                exit();
            }
            header('Location: ' . $teacherDashboard);
            break;
            
        case 'student':
            $studentDashboard = '../dashboards/student/dashboard.php';
            if (!file_exists($studentDashboard)) {
                $_SESSION['error'] = "Student dashboard not found at: $studentDashboard";
                header('Location: ../index.php');
                exit();
            }
            header('Location: ' . $studentDashboard);
            break;
            
        default:
            $_SESSION['error'] = "Unknown user role: " . $user['role'];
            header('Location: ../index.php');
    }
    exit();

} catch (PDOException $e) {
    // Log error for debugging
    error_log("Login error: " . $e->getMessage());
    
    $_SESSION['error'] = "Database error occurred. Please try again.";
    header('Location: ../index.php');
    exit();
}