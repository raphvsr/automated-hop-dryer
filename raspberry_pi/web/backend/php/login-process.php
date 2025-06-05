<?php
//              file login-process.php
// ===============================================
//          Original Author: fateh kabbani
// ===============================================

// COMMIT HISTORY:
// ============================================================
// 2025-05-23 - Refactor sensor reading logic in read_sensor.py, including improved logging and temperature handling. Update GPIO pin configuration in validate.py. Change API endpoint in users.js for user updates. Enhance error handling and logging in varieties-create.php and login-process.php, including session and request logging. Modify register-process.php to ensure all fields are validated. Add info.php for PHP configuration display. Create log files for login attempts to aid in debugging. - fateh kabbani
//   raspberry_pi/web/backend/php/login-process.php | 119 +++++++++++++++++++++----
//   1 file changed, 104 insertions(+), 15 deletions(-)
//
// 2025-05-13 - mb - Romain Provencel
//   raspberry_pi/web/backend/php/login-process.php | 4 ++++
//   1 file changed, 4 insertions(+)
//
// 2025-05-12 - responsive - Romain Provencel
//   raspberry_pi/web/backend/php/login-process.php | 4 ----
//   1 file changed, 4 deletions(-)
//
// 2025-04-02 - changed folder name removed the space - fateh kabbani
//   {raspberry pi => raspberry_pi}/web/backend/php/login-process.php | 0
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-26 - Enhance dashboard functionality and styling; add admin role check and new CSS for improved layout - fateh kabbani
//   raspberry pi/web/backend/php/login-process.php | 1 +
//   1 file changed, 1 insertion(+)
//
// 2025-03-20 - move the file to raspberry pi - fateh kabbani
//   {web => raspberry pi/web}/backend/php/login-process.php | 0
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-17 - Refactor login process for improved SQL query safety and add temperature data API - fateh kabbani
//   web/backend/php/login-process.php | 4 ++--
//   1 file changed, 2 insertions(+), 2 deletions(-)
//
// 2025-03-17 - Enhance time setting API with improved date validation, error handling, and SQL injection protection - fateh kabbani
//   web/backend/php/login-process.php | 8 ++++++--
//   1 file changed, 6 insertions(+), 2 deletions(-)
//
// 2025-03-13 - Refactor project structure by moving backend files to a new directory and re-implementing login and registration functionality - Romain Provencel
//   {backend => web/backend}/php/login-process.php | 0
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-13 - Add initial project structure with login and registration functionality - fateh kabbani
//   backend/php/login-process.php | 22 ++++++++++++++++++++++
//   1 file changed, 22 insertions(+)
//
// ============================================================

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
    if (password_verify($password, $user['password_hash'])) {
      $_SESSION['username'] = $username;
      $_SESSION['admin'] = $user['role'];
      echo "success";
    } else {
      echo "Invalid password!";
    }
  } else {
    echo "User not found!";
  }
}
