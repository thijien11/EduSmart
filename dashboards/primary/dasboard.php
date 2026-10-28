<?php
require_once(__DIR__ . '/config/config.php');
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

// Get user info
$user_name = htmlspecialchars($_SESSION['user_name']);
$user_email = htmlspecialchars($_SESSION['user_email']);
$user_role = htmlspecialchars($_SESSION['user_role']);
$education_level = $_SESSION['education_level'] ?? '';
$current_class = $_SESSION['current_class'] ?? '';
$country = $_SESSION['country'] ?? '';

// Education level labels
$education_labels = [
    'primary' => 'Primary School',
    'secondary' => 'Secondary School',
    'university' => 'University/College',
    'vocational' => 'Vocational Training'
];

// Country labels
$country_labels = [
    'KE' => 'Kenya',
    'UG' => 'Uganda',
    'TZ' => 'Tanzania',
    'RW' => 'Rwanda',
    'SS' => 'South Sudan'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - EduSmart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
        }
        
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .user-info h1 {
            color: #4361ee;
            margin-bottom: 10px;
        }
        
        .user-info p {
            color: #64748b;
        }
        
        .logout-btn {
            padding: 12px 24px;
            background: #ef4444;
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        
        .dashboard-content {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 30px;
        }
        
        .user-profile {
            background: #f8fafc;
            padding: 30px;
            border-radius: 16px;
            border: 2px solid #e2e8f0;
        }
        
        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .profile-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 40px;
            margin: 0 auto 20px;
        }
        
        .profile-details div {
            margin-bottom: 15px;
            padding: 12px;
            background: white;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }
        
        .profile-details strong {
            color: #4361ee;
            display: block;
            margin-bottom: 5px;
        }
        
        .profile-details span {
            color: #1e293b;
            font-weight: 500;
        }
        
        .dashboard-widgets {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .widget {
            background: white;
            padding: 25px;
            border-radius: 16px;
            border: 2px solid #e2e8f0;
            transition: transform 0.3s;
        }
        
        .widget:hover {
            transform: translateY(-5px);
            border-color: #4361ee;
        }
        
        .widget-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .widget-icon {
            width: 50px;
            height: 50px;
            background: #e0e7ff;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4361ee;
            font-size: 24px;
        }
        
        .widget h3 {
            color: #1e293b;
            margin: 0;
        }
        
        .widget-content ul {
            list-style: none;
        }
        
        .widget-content li {
            padding: 10px 0;
            border-bottom: 1px solid #e2e8f0;
            color: #64748b;
        }
        
        .widget-content li:last-child {
            border-bottom: none;
        }
        
        @media (max-width: 768px) {
            .dashboard-content {
                grid-template-columns: 1fr;
            }
            
            .dashboard-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <div class="user-info">
                <h1>Welcome, <?php echo $user_name; ?>!</h1>
                <p><?php echo $user_email; ?></p>
            </div>
            <a href="../controllers/logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
        
        <div class="dashboard-content">
            <div class="user-profile">
                <div class="profile-header">
                    <div class="profile-icon">
                        <?php if ($user_role === 'student'): ?>
                            <i class="fas fa-user-graduate"></i>
                        <?php elseif ($user_role === 'teacher'): ?>
                            <i class="fas fa-chalkboard-teacher"></i>
                        <?php else: ?>
                            <i class="fas fa-user-shield"></i>
                        <?php endif; ?>
                    </div>
                    <h2><?php echo ucfirst($user_role); ?> Profile</h2>
                </div>
                
                <div class="profile-details">
                    <?php if ($education_level): ?>
                        <div>
                            <strong>Education Level</strong>
                            <span><?php echo $education_labels[$education_level] ?? $education_level; ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($current_class): ?>
                        <div>
                            <strong>Current Class</strong>
                            <span><?php echo $current_class; ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($country): ?>
                        <div>
                            <strong>Country</strong>
                            <span><?php echo $country_labels[$country] ?? $country; ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <div>
                        <strong>Account Type</strong>
                        <span><?php echo ucfirst($user_role); ?></span>
                    </div>
                </div>
            </div>
            
            <div class="dashboard-widgets">
                <div class="widget">
                    <div class="widget-header">
                        <div class="widget-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <h3>Learning Materials</h3>
                    </div>
                    <div class="widget-content">
                        <ul>
                            <li><i class="fas fa-play-circle"></i> Video Lessons</li>
                            <li><i class="fas fa-file-pdf"></i> Study Notes</li>
                            <li><i class="fas fa-question-circle"></i> Practice Questions</li>
                            <li><i class="fas fa-tasks"></i> Assignments</li>
                        </ul>
                    </div>
                </div>
                
                <div class="widget">
                    <div class="widget-header">
                        <div class="widget-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3>Progress Tracking</h3>
                    </div>
                    <div class="widget-content">
                        <ul>
                            <li><i class="fas fa-trophy"></i> Overall Score: 85%</li>
                            <li><i class="fas fa-clock"></i> Study Time: 45 hours</li>
                            <li><i class="fas fa-check-circle"></i> Completed Lessons: 24</li>
                            <li><i class="fas fa-star"></i> Achievements: 8</li>
                        </ul>
                    </div>
                </div>
                
                <?php if ($user_role === 'student'): ?>
                <div class="widget">
                    <div class="widget-header">
                        <div class="widget-icon">
                            <i class="fas fa-robot"></i>
                        </div>
                        <h3>AI Tutor</h3>
                    </div>
                    <div class="widget-content">
                        <ul>
                            <li><i class="fas fa-comments"></i> Ask Questions 24/7</li>
                            <li><i class="fas fa-lightbulb"></i> Get Explanations</li>
                            <li><i class="fas fa-puzzle-piece"></i> Solve Problems</li>
                            <li><i class="fas fa-chart-bar"></i> Personalized Learning</li>
                        </ul>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($user_role === 'teacher'): ?>
                <div class="widget">
                    <div class="widget-header">
                        <div class="widget-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3>Teaching Tools</h3>
                    </div>
                    <div class="widget-content">
                        <ul>
                            <li><i class="fas fa-plus-circle"></i> Create Assignments</li>
                            <li><i class="fas fa-chalkboard"></i> Virtual Classroom</li>
                            <li><i class="fas fa-chart-pie"></i> Student Analytics</li>
                            <li><i class="fas fa-file-upload"></i> Upload Materials</li>
                        </ul>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>