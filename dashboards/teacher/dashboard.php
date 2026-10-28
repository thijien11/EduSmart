<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'teacher') {
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
    <title>Teacher Dashboard - EduSmart</title>
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
            background: #f59e0b;
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
            <div class="welcome">Welcome, Teacher <?php echo htmlspecialchars($user_name); ?>!</div>
            <p>Teacher Dashboard - Manage your classes and students</p>
            <a href="../../controllers/logout.php" class="btn btn-logout">Logout</a>
        </div>
        
        <div class="card">
            <h2>Teacher Features</h2>
            <button class="btn">Manage Classes</button>
            <button class="btn">Create Assignments</button>
            <button class="btn">Grade Submissions</button>
        </div>
    </div>
</body>
</html><?php
require_once(__DIR__ . '/config/config.php');
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'teacher') {
    header('Location: ../index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard - EduAccess</title>
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
        .btn-logout {
            background: #ef4444;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="welcome">Welcome, Teacher <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</div>
            <p>Teacher ID: <?php echo $_SESSION['user_id']; ?></p>
            <a href="../controllers/auth.php?logout=true" class="btn btn-logout">Logout</a>
        </div>
        
        <div class="card">
            <h2>Manage Courses</h2>
            <p>Create and manage your courses and assignments.</p>
            <button class="btn">Manage Courses</button>
        </div>
        
        <div class="card">
            <h2>Student Progress</h2>
            <p>Track and monitor student performance.</p>
            <button class="btn">View Analytics</button>
        </div>
    </div>
</body>
</html>