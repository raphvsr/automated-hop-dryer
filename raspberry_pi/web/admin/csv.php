<?php
//                   file csv.php
// ===============================================
//          Original Author: fateh kabbani
// ===============================================

// COMMIT HISTORY:
// ============================================================
// 2025-04-02 - changed folder name removed the space - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-30 - Refactor sidebar navigation: extract to a separate component and update links for consistency - fateh kabbani
//   raspberry pi/web/admin/csv.php | 29 +----------------------------
//   1 file changed, 1 insertion(+), 28 deletions(-)
//
// 2025-03-29 - Refactor admin dashboard and user management: move files to admin directory and update CSS/JS - fateh kabbani
//   raspberry pi/web/{ => admin}/csv.php | 2 +-
//   1 file changed, 1 insertion(+), 1 deletion(-)
//
// 2025-03-29 -  improve CSV file sorting, display the newest first - fateh kabbani
//   raspberry pi/web/csv.php | 14 +++++++++++++-
//   1 file changed, 13 insertions(+), 1 deletion(-)
//
// 2025-03-28 - Add USB device management and dashboard functionality - fateh kabbani
//   raspberry pi/web/csv.php | 123 +++++++++++++++++++++++++++++++++++++++++++++++
//   1 file changed, 123 insertions(+)
//
// ============================================================

session_start();
if (!isset($_SESSION['username'])) {
  header('Location: login.php');
  exit();
}
function sortFile($files)
{

  usort($files, function ($a, $b) {
    return filemtime($b) - filemtime($a);
  });

  return $files;
}



function getCSVFiles()
{
  $csvDir = 'data/';
  $files = glob($csvDir . "*.csv");
  return sortFile($files);
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="src/css/csv.css">
</head>

<body>
  <?php include 'components/sidebar.php'; ?>

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
