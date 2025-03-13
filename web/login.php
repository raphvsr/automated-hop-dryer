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
  <link rel="stylesheet" href="src/css/styles.css">
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
