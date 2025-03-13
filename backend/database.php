<?php

$host = '127.0.0.1:3306';
$db = 'rpsklproject';
$user = 'root';
$pass = '123123Fatih';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
