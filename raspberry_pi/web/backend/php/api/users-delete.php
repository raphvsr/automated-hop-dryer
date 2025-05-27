<?php
//              file users-delete.php             
// ===============================================
//          Original Author: fateh kabbani        
// ===============================================

// COMMIT HISTORY:
// ============================================================
// 2025-04-02 - changed folder name removed the space - fateh kabbani
//   {raspberry pi => raspberry_pi}/web/backend/php/api/users-delete.php | 0
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-31 - Add user deletion functionality: implement AJAX request and backend processing for user removal - fateh kabbani
//   raspberry pi/web/backend/php/api/users-delete.php | 64 +++++++++++++++++++++++
//   1 file changed, 64 insertions(+)
//
// ============================================================

include '../../database.php';
session_start();

if (!isset($_POST['id']) || empty($_POST['id'])) {
  echo json_encode([
    'status' => 'error',
    'message' => 'Invalid user ID'
  ]);
  http_response_code(400);
  exit;
}

$idToDelete = $_POST['id'];

$sql = "SELECT id FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();
$currentUser = $result->fetch_assoc();



$currentUserId = $currentUser['id'];

if ($currentUserId == $idToDelete) {
  echo json_encode([
    'status' => 'error',
    'message' => 'You cannot delete yourself'
  ]);
  http_response_code(403);
  exit;
}

$sql = "DELETE FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $idToDelete);

try {
  $stmt->execute();
  if ($stmt->affected_rows > 0) {
    echo json_encode([
      'status' => 'success',
      'message' => 'User deleted successfully'
    ]);
    http_response_code(200);
  } else {
    echo json_encode([
      'status' => 'error',
      'message' => 'No user was deleted'
    ]);
    http_response_code(404);
  }
} catch (Exception $e) {
  echo json_encode([
    'status' => 'error',
    'message' => 'Failed to delete user'
  ]);
  http_response_code(500);
} finally {
  $stmt->close();
  $conn->close();
}
