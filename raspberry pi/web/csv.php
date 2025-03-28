<?php
session_start();
if (!isset($_SESSION['username'])) {
  header('Location: login.php');
  exit();
}

function getCSVFiles()
{
  $csvDir = 'data/';
  $files = glob($csvDir . "*.csv");
  return $files;
}

if (isset($_POST['download']) && !empty($_POST['file'])) {
  $file = 'data/' . basename($_POST['file']);
  if (file_exists($file)) {
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="' . basename($file) . '"');
    readfile($file);
    exit();
  }
}


?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion des Données CSV - Contrôle du Séchage</title>
  <link rel="stylesheet" href="src/css/styles.css">
  <link rel="stylesheet" href="src/css/dashboard.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="src/css/csv.css">
</head>

<body>
  <nav class="sidebar-navigation">
    <ul>
      <li>
        <a href="dashboard.php">
          <i class="fa fa-home"></i>
          <span class="tooltip">Accueil</span>
        </a>
      </li>
      <li class="active">
        <a href="csv.php">
          <i class="fa fa-file-o"></i>
          <span class="tooltip">Csv</span>
        </a>
      </li>
      <li>
        <a href="users.php">
          <i class="fa fa-user-o"></i>
          <span class="tooltip">Utilisateur</span>
        </a>
      </li>
      <li>
        <a href="settings.php">
          <i class="fa fa-sliders"></i>
          <span class="tooltip">Paramètres</span>
        </a>
      </li>
    </ul>
  </nav>

  <div class="csv-container">
    <h1>Gestion des Données CSV</h1>

    <div class="csv-card">
      <h2 class="section-title">Fichiers disponibles</h2>
      <div class="file-list">
        <?php
        $files = getCSVFiles();
        foreach ($files as $file) {
          $filename = basename($file);
          $filesize = round(filesize($file) / 1024, 2); // Taille en KB
          ?>
          <div class="file-item">
            <div class="file-info">
              <i class="fas fa-file-csv"></i>
              <span><?php echo $filename; ?> (<?php echo $filesize; ?> KB)</span>
            </div>
            <div class="action-buttons">
              <form method="post" style="display: inline;">
                <input type="hidden" name="file" value="<?php echo $filename; ?>">
                <button type="submit" name="download" class="btn btn-download">
                  <i class="fas fa-download"></i> Télécharger
                </button>
              </form>
              <button class="btn btn-usb" onclick="exportToUsb('<?php echo $filename; ?>')">
                <i class="fas fa-usb"></i> Exporter vers USB
              </button>
            </div>
          </div>
          <?php
        }
        if (empty($files)) {
          echo "<p>Aucun fichier CSV disponible</p>";
        }
        ?>
      </div>
    </div>

    <div class="csv-card">
      <h2 class="section-title">État des périphériques USB</h2>
      <div id="usbStatus">
        Recherche des périphériques USB...
      </div>
    </div>
  </div>

  <script src="src/js/csv.js"></script>

  <script src="https://kit.fontawesome.com/0e4bc9cea5.js" crossorigin="anonymous"></script>
</body>

</html>
