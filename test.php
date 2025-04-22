<?php
$connection = mysqli_connect('localhost', 'root', '', 'aideaura');

if ($connection) {
    echo "✅ Connected to the 'aideaura' database successfully!";
} else {
    echo "❌ Database connection failed: " . mysqli_connect_error();
}
?>
