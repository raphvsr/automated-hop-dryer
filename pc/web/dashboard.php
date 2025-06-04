<?php
// session_start();
// if (!isset($_SESSION['username'])) {
//   header('Location: login.php');
//   exit();
// }
// if ($_SESSION['admin'] != 1) {
//   header('Location: index.php');
//   exit();
// }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="src/css/dashboard.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
</head>

<body>

  <!-- Navigation Bar -->
  <nav class="main-nav">
    <div class="nav-container">
      <div class="nav-brand">
        <span>🌿 système de séchage du houblon</span>
      </div>
      <ul class="nav-links">
        <li><a href="dashboard.php" class="active">Dashboard</a></li>
        <li><a href="advancedState.php">Advanced Statistics</a></li>
      </ul>
    </div>
  </nav>

  <!-- Interface de filtrage pour le tableau de bord -->
  <section class="filter-section">
    <h1>Tableau de Bord</h1>
    <form id="filter-form" class="filter-form">
      <div class="form-group">
        <label for="variety-filter">Variété :</label>
        <select id="variety-filter" name="variety" class="form-control">
          <option value="">Toutes les variétés</option>
        </select>
      </div>
      <div class="form-group">
        <label for="start-date">Date de début :</label>
        <input type="date" id="start-date" name="startDate" class="form-control">
      </div>
      <div class="form-group">
        <label for="end-date">Date de fin :</label>
        <input type="date" id="end-date" name="endDate" class="form-control">
      </div>
      <div class="form-group">
        <label for="etage-filter">Étage :</label>
        <select id="etage-filter" name="etage" class="form-control">
          <option value="">Tous les étages</option>
          <option value="1">Étage 1</option>
          <option value="2">Étage 2</option>
          <option value="3">Étage 3</option>
          <option value="4">Étage 4</option>
        </select>
      </div>
      <!-- submit button -->
    </form>
  </section>

  <main class="dashboard-content">

    <!-- Conteneur pour les messages -->
    <div id="message-container"></div>

    <section class="graphs">
      <div class="graph-container">
        <h2>Variété historique séchage 1er chargement</h2>
        <canvas id="chart1"></canvas>
      </div>
      <div class="graph-container">
        <h2>Variété date N°chargement</h2>
        <canvas id="chart2"></canvas>
      </div>

    </section>

    <!-- Tableau des statistiques -->
    <section class="statistics">
      <h2>Statistiques</h2>
      <div id="statistics-container"></div>
    </section>
  </main>






  <script src="src/js/dashboard.js"></script>


  <script src="https://kit.fontawesome.com/0e4bc9cea5.js" crossorigin="anonymous"></script>
</body>

</html>
