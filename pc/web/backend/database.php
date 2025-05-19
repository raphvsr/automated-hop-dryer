<?php

$dotenv = parse_ini_file('C:\xampp\htdocs\skl-project\raspberry_pi\web\backend\.env', true);
$host = $dotenv['DB_HOST'];
$db = $dotenv['DB_DATABASE_PC'];
$user = $dotenv['DB_USERNAME'];
$pass = $dotenv['DB_PASSWORD'];

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
