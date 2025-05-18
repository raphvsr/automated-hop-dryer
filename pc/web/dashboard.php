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

  <main class="dashboard-content">
    <h1>Tableau de Bord</h1>
    <section class="graphs">
      <div class="graph-container">
        <canvas id="chart1"></canvas>
      </div>
      <div class="graph-container">
        <canvas id="chart2"></canvas>
      </div>
      <div class="graph-container">
        <canvas id="chart3"></canvas>
      </div>
      <div class="graph-container">
        <canvas id="chart4"></canvas>
      </div>
    </section>
  </main>

  <script src="src/js/dashboard.js"></script>


  <script src="https://kit.fontawesome.com/0e4bc9cea5.js" crossorigin="anonymous"></script>
</body>

</html>
