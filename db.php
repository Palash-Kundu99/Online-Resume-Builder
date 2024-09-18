<?php
$servername = "localhost";
$username = "root"; // your database username
$password = ""; // your database password
$database = "resume_builder";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
