<?php
echo "<h2>Testing Paths</h2>";

// Current location
echo "Current file: " . __FILE__ . "<br>";
echo "Current directory: " . __DIR__ . "<br><br>";

// Test relative paths from controllers folder
echo "<h3>From controllers/ folder:</h3>";
$paths = [
    '../dashboards/student/dashboard.php' => 'Student Dashboard',
    '../dashboards/teacher/dashboard.php' => 'Teacher Dashboard',
    '../dashboards/admin/dashboard.php' => 'Admin Dashboard',
    '../index.php' => 'Main Index'
];

foreach ($paths as $path => $name) {
    if (file_exists($path)) {
        echo "<span style='color: green;'>✓ $name exists at: $path</span><br>";
    } else {
        echo "<span style='color: red;'>✗ $name NOT FOUND at: $path</span><br>";
        echo "&nbsp;&nbsp;Full path: " . realpath(dirname(__FILE__) . '/' . $path) . "<br>";
    }
}

// Check if dashboards folder exists
echo "<h3>Checking dashboards folder structure:</h3>";
$base = __DIR__ . '/../dashboards';
if (is_dir($base)) {
    echo "✓ dashboards folder exists<br>";
    
    $subfolders = ['admin', 'teacher', 'student'];
    foreach ($subfolders as $folder) {
        $path = $base . '/' . $folder;
        if (is_dir($path)) {
            echo "&nbsp;&nbsp;✓ $folder/ folder exists<br>";
            
            // Check for dashboard.php
            $dashboard = $path . '/dashboard.php';
            if (file_exists($dashboard)) {
                echo "&nbsp;&nbsp;&nbsp;&nbsp;✓ dashboard.php exists<br>";
            } else {
                echo "&nbsp;&nbsp;&nbsp;&nbsp;✗ dashboard.php NOT FOUND<br>";
            }
        } else {
            echo "&nbsp;&nbsp;✗ $folder/ folder NOT FOUND<br>";
        }
    }
} else {
    echo "✗ dashboards folder NOT FOUND at: $base<br>";
}

echo "<h3>Test Login Redirects:</h3>";
echo '<a href="../index.php">Go to Login Page</a><br>';
echo 'Test with: primary@demo.com / password123 (role: student)';
?>