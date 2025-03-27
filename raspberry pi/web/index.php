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
  <title>Page Principale - Contrôle du Séchage</title>
  <link rel="stylesheet" href="src/css/styles.css">
  <!-- TODO install this in local instead of cdn (j'ai la flemme de le faire mtn) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
  <div class="dashboard-container">
    <h2>Système de Contrôle du Séchage</h2>
    <div id="dashboardHeader">
      <p>Bienvenue, <?php echo $_SESSION['username']; ?>!</p>
      <!-- check if user admin -->
      <?php if ($_SESSION['admin'] == 1): ?>
        <button id="dashboard"><a href="dashboard.php">Dashboard</a></button>
      <?php endif; ?>
    </div>
    <div class="section">
      <h3>Températures en Temps Réel</h3>
      <div id="temperatureReadings">
        <p>Chargement des températures...</p>
      </div>
    </div>
    <div class="section">
      <h3>Contrôle du Séchage</h3>
      <button id="startDrying">Démarrer le Séchage</button>
      <button id="stopDrying">Arrêter le Séchage</button>
      <p id="dryingStatus">État : En attente</p>
    </div>
    <div class="section">
      <h3>Visualisation des Données de Séchage</h3>
      <canvas id="temperatureChart"></canvas>
      <table id="dataTable">
        <thead>
          <tr>
            <th>Heure</th>
            <th>Température (°C)</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
    <div class="section">
      <h3>Contrôle du Système</h3>
      <button id="shutdownSystem">Arrêter le Système</button>
      <p id="shutdownStatus"></p>
    </div>
  </div>
  <script src="src/js/index.js"></script>
</body>

</html>
