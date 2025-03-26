<?php
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Main Page - Drying Control</title>
  <link rel="stylesheet" href="src/css/styles.css">
  <!-- TODO install this in local instead of cdn (j'ai la flemme de le faire mtn) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
  <div class="dashboard-container">
    <h2>Drying Control System</h2>
    <p>Welcome, <?php echo $_SESSION['username']; ?>!</p>
    <div class="section">
      <h3>Real-Time Temperature Readings</h3>
      <div id="temperatureReadings">
        <p>Loading temperatures...</p>
      </div>
    </div>
    <div class="section">
      <h3>Drying Control</h3>
      <button id="startDrying">Start Drying</button>
      <button id="stopDrying">Stop Drying</button>
      <p id="dryingStatus">Status: Idle</p>
    </div>
    <div class="section">
      <h3>Drying Data Visualization</h3>
      <canvas id="temperatureChart"></canvas>
      <table id="dataTable">
        <thead>
          <tr>
            <th>Time</th>
            <th>Temperature (°C)</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
    <div class="section">
      <h3>System Control</h3>
      <button id="shutdownSystem">Shutdown System</button>
      <p id="shutdownStatus"></p>
    </div>
  </div>
  <script src="src/js/index.js"></script>
</body>

</html>
