<?php
$dotenv = parse_ini_file(__DIR__ . '/.env', true);
$host = $dotenv['DB_HOST'];
$db = $dotenv['DB_DATABASE'];
$user = $dotenv['DB_USERNAME'];
$pass = $dotenv['DB_PASSWORD'];

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
