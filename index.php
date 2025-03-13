<?php
// check session
session_start();
if (!isset($_SESSION['username'])) {
  header('Location: login.php');
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Main page</title>
  <link rel="stylesheet" href="src/css/styles.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
  <div class="dashboard-container">
    <h2>main page</h2>
    <div id="temperatureReadings"></div>
    <button id="startDrying">Start Drying</button>
    <button id="stopDrying">Stop Drying</button>
  </div>

  <script src="src/js/dashboard.js"></script>
</body>

</html>
