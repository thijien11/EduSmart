<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_GET['action'])) {
    die('Invalid request');
}

$action = $_GET['action'];

if ($action === 'login') {

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ../auth/login.php');
        exit;
    }

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = 'Email and password are required';
        header('Location: ../auth/login.php');
        exit;
    }

    // 1️⃣ Check students
    $stmt = $db->prepare("SELECT * FROM students WHERE email = ? AND status = 'active' LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = 'student';
        $_SESSION['education_level'] = $user['education_level'];

        // Redirect by education level
        if ($user['education_level'] === 'primary') {
            header('Location: ../dashboards/student/primary.php');
        } elseif ($user['education_level'] === 'secondary') {
            header('Location: ../dashboards/student/secondary.php');
        } else {
            header('Location: ../dashboards/student/university.php');
        }
        exit;
    }

    // 2️⃣ Check teachers
    $stmt = $db->prepare("SELECT * FROM teachers WHERE email = ? AND status = 'active' LIMIT 1");
    $stmt->execute([$email]);
    $teacher = $stmt->fetch();

    if ($teacher && password_verify($password, $teacher['password'])) {
        $_SESSION['user_id'] = $teacher['id'];
        $_SESSION['role'] = 'teacher';
        header('Location: ../dashboards/teacher/dashboard.php');
        exit;
    }

    // 3️⃣ Check admins
    $stmt = $db->prepare("SELECT * FROM admins WHERE email = ? AND status = 'active' LIMIT 1");
    $stmt->execute([$email]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['user_id'] = $admin['id'];
        $_SESSION['role'] = 'admin';
        header('Location: ../dashboards/admin/dashboard.php');
        exit;
    }

    // ❌ Login failed
    $_SESSION['error'] = 'Invalid email or password';
    header('Location: ../auth/login.php');
    exit;
}
