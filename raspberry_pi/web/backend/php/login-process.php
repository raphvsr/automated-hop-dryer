<?php
session_start();
include '../database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM users WHERE username = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
      $_SESSION['username'] = $username;
      $_SESSION['admin'] = $user['role'];
      echo "success";
  } else {
    echo "User not found!";
  }
}
