<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    header('Location: ../../index.php');
    exit();
}

$user_name = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - EduSmart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .welcome {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }
        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .btn {
            background: #4361ee;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-logout {
            background: #ef4444;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="welcome">Welcome, <?php echo htmlspecialchars($user_name); ?>!</div>
            <p>Student Dashboard - You are logged in as a student</p>
            <a href="../../controllers/logout.php" class="btn btn-logout">Logout</a>
        </div>
        
        <div class="card">
            <h2>Student Features</h2>
            <p>Access your courses, assignments, and learning materials.</p>
            <button class="btn">View Courses</button>
            <button class="btn">AI Tutor</button>
            <button class="btn">Assignments</button>
        </div>
    </div>
</body>
</html><?php
require_once(__DIR__ . '/config/config.php');
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    header('Location: ../index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - EduAccess</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .welcome {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }
        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .btn {
            background: #4361ee;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-right: 10px;
        }
        .btn:hover {
            background: #3a56d4;
        }
        .btn-logout {
            background: #ef4444;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="welcome">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</div>
            <p>Student ID: <?php echo $_SESSION['user_id']; ?></p>
            <p>Email: <?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
            <a href="../controllers/auth.php?logout=true" class="btn btn-logout">Logout</a>
        </div>
        
        <div class="card">
            <h2>Your Courses</h2>
            <p>Access your learning materials and track progress.</p>
            <button class="btn">View Courses</button>
        </div>
        
        <div class="card">
            <h2>AI Tutor</h2>
            <p>Get personalized assistance from our AI learning assistant.</p>
            <button class="btn">Start Chat</button>
        </div>
    </div>
</body>
</html>