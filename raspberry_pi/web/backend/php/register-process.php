<!-- //            file register-process.php           
// ===============================================
//          Original Author: fateh kabbani        
// ===============================================

// COMMIT HISTORY:
// ============================================================
// 2025-05-23 - Refactor sensor reading logic in read_sensor.py, including improved logging and temperature handling. Update GPIO pin configuration in validate.py. Change API endpoint in users.js for user updates. Enhance error handling and logging in varieties-create.php and login-process.php, including session and request logging. Modify register-process.php to ensure all fields are validated. Add info.php for PHP configuration display. Create log files for login attempts to aid in debugging. - fateh kabbani
//   raspberry_pi/web/backend/php/register-process.php | 8 ++++++--
//   1 file changed, 6 insertions(+), 2 deletions(-)
//
// 2025-04-02 - changed folder name removed the space - fateh kabbani
//   {raspberry pi => raspberry_pi}/web/backend/php/register-process.php | 0
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-04-01 - Add variety management functionality: implement create, update, and delete APIs with AJAX integration + changed the database - fateh kabbani
//   raspberry pi/web/backend/php/register-process.php | 91 +++++++++++------------
//   1 file changed, 45 insertions(+), 46 deletions(-)
//
// 2025-03-30 - Add user creation functionality: implement form validation, AJAX submission, and backend processing - fateh kabbani
//   raspberry pi/web/backend/php/register-process.php | 58 +++++++++++++++++++++++
//   1 file changed, 58 insertions(+)
//
// 2025-03-20 - move the file to raspberry pi - fateh kabbani
//   {web => raspberry pi/web}/backend/php/register-process.php | 0
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-13 - Refactor project structure by moving backend files to a new directory and re-implementing login and registration functionality - Romain Provencel
//   {backend => web/backend}/php/register-process.php | 0
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-13 - Add initial project structure with login and registration functionality - fateh kabbani
//   backend/php/register-process.php | 0
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// ============================================================ -->

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
