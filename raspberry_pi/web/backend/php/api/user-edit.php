<?php
//                file user-edit.php              
// ===============================================
//          Original Author: fateh kabbani        
// ===============================================

// COMMIT HISTORY:
// ============================================================
// 2025-04-02 - changed folder name removed the space - fateh kabbani
//   {raspberry pi => raspberry_pi}/web/backend/php/api/user-edit.php | 0
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-04-01 - Add variety management functionality: implement create, update, and delete APIs with AJAX integration + changed the database - fateh kabbani
//   raspberry pi/web/backend/php/api/user-edit.php | 34 ++++++++++++++++++++++++++
//   1 file changed, 34 insertions(+)
//
// ============================================================

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
