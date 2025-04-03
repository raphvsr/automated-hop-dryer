<?php
include "../../database.php";
session_start();
$sql = "UPDATE users SET username = ?, password_hash = ?, role = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$username = $_POST['username'];
$password = $_POST['password'];
$password_hash = password_hash($password, PASSWORD_DEFAULT);
$role = $_POST['role'];
$id = $_POST['id'];
$stmt->bind_param("sssi", $username, $password_hash, $role, $id);
try {
  $stmt->execute();
  if ($stmt->affected_rows > 0) {
    echo json_encode([
      'status' => 'success',
      'message' => 'User updated successfully'
    ]);
    http_response_code(200);
  } else {
    echo json_encode([
      'status' => 'error',
      'message' => 'No user was updated'
    ]);
    http_response_code(404);
  }
} catch (Exception $e) {
  echo json_encode([
    'status' => 'error',
    'message' => 'Failed to update user'
  ]);
  http_response_code(500);
  exit;
}
