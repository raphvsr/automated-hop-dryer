<?php
session_start();
include '../database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM users WHERE username='$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password_hash'])) {
      $_SESSION['username'] = $username;
      echo "success";
    } else {
    }
  } else {
    echo "User not found!";
  }
}
