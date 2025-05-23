<?php

include '../database.php';

header('Content-Type: application/json');

try {
  if (empty($_POST['username']) || empty($_POST['password']) || !isset($_POST['role'])) {
    echo json_encode([
      'status' => 'error',
      'message' => 'All fields are required'
    ]);
    exit;
  }
  $sql = "INSERT INTO users (username, password_hash, role) VALUES (?, ?, ?)";
  $username = $_POST['username'];
  $password = $_POST['password'];
  $role = $_POST['role'];
  $password_hash = password_hash($password, PASSWORD_BCRYPT);

  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sss", $username, $password_hash, $role);

  if (!$stmt->execute()) {
    throw new Exception('Failed to create user');
  }

  if ($stmt->affected_rows > 0) {
    echo json_encode([
      'status' => 'success',
      'message' => 'User created successfully'
    ]);
    http_response_code(200);
  } else {
    throw new Exception('No user was created');
  }

} catch (Exception $e) {
  $status = 'error';
  $code = 500;

  if ($e->getCode() == 1062) {
    $message = 'Username already exists';
    $code = 409;
  } else {
    $message = $e->getMessage();
  }

  echo json_encode([
    'status' => $status,
    'message' => $message
  ]);
  http_response_code($code);
} finally {
  if (isset($stmt)) {
    $stmt->close();
  }
  if (isset($conn)) {
    $conn->close();
  }
}
