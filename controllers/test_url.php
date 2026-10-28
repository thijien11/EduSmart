<?php
echo "<h2>URL Debug Information</h2>";

echo "Server Name: " . $_SERVER['SERVER_NAME'] . "<br>";
echo "Server Port: " . $_SERVER['SERVER_PORT'] . "<br>";
echo "Request URI: " . $_SERVER['REQUEST_URI'] . "<br>";
echo "Script Name: " . $_SERVER['SCRIPT_NAME'] . "<br>";
echo "PHP Self: " . $_SERVER['PHP_SELF'] . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Current File: " . __FILE__ . "<br>";

echo "<h3>Test Links:</h3>";
echo '<a href="index.php">index.php</a><br>';
echo '<a href="/EduSmart/index.php">/EduSmart/index.php</a><br>';
echo '<a href="http://localhost/EduSmart/index.php">http://localhost/EduSmart/index.php</a><br>';
?>