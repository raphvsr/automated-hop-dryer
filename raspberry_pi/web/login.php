//                  file login.php                
// ===============================================
//          Original Author: fateh kabbani        
// ===============================================

// COMMIT HISTORY:
// ============================================================
// 2025-04-02 - changed folder name removed the space - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-20 - move the file to raspberry pi - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-16 - Add login page styles - fateh kabbani
//   web/login.php | 1 -
//   1 file changed, 1 deletion(-)
//
// 2025-03-13 - Refactor project structure by moving backend files to a new directory and re-implementing login and registration functionality - Romain Provencel
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-13 - Add initial project structure with login and registration functionality - fateh kabbani
//   login.php | 34 ++++++++++++++++++++++++++++++++++
//   1 file changed, 34 insertions(+)
//
// ============================================================

<?php
// check session
session_start();
if (isset($_SESSION['username'])) {
  header('Location: index.php');
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="src/css/login.css">

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>
  <div class="login-container">
    <h2>Login</h2>
    <div id="login_form">
      <input type="text" id="username" name="username" placeholder="Username">
      <input type="password" id="password" name="password" placeholder="Password">
      <button id="login_submit" type="submit">Login</button>
    </div>
  </div>
  <script src="src/js/login.js"></script>
</body>

</html>
