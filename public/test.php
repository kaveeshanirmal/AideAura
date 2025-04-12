<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "PHP is working!";

// Test file access
if (file_exists("../app/views/HeroSection.view.php")) {
    echo "<br>Found HeroSection.view.php";
} else {
    echo "<br>Could NOT find HeroSection.view.php - path might be wrong";
}
?>