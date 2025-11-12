<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "e_notes_db"; // 👈 ye wahi naam hona chahiye jisme "users" table hai

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>