<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
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
    <title>Admin Dashboard - EduSmart</title>
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
            background: #ef4444;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-logout {
            background: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="welcome">Welcome, Admin <?php echo htmlspecialchars($user_name); ?>!</div>
            <p>Admin Dashboard - Manage system settings and users</p>
            <a href="../../controllers/logout.php" class="btn btn-logout">Logout</a>
        </div>
        
        <div class="card">
            <h2>Admin Features</h2>
            <button class="btn">Manage Users</button>
            <button class="btn">System Settings</button>
            <button class="btn">Teacher Approvals</button>
        </div>
    </div>
</body>
</html>