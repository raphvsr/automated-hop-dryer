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
  <nav class="sidebar-navigation">
    <ul>
      <li class="active">
        <i class="fa fa-home"></i>
        <span class="tooltip">Accueil</span>
      </li>
      <li>
        <i class="fa fa-file-o"></i>
        <span class="tooltip">Csv</span>
      </li>
      <li>
        <i class="fa fa-user-o"></i>
        <span class="tooltip">Utilisateur</span>
      </li>
      <li>
        <i class="fa fa-sliders"></i>
        <span class="tooltip">Paramètres</span>
      </li>
    </ul>
  </nav>
  <!-- Interface de filtrage pour le tableau de bord -->
  <section class="filter-section">
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
    <h1>Tableau de Bord</h1>

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
