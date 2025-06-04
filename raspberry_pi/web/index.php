<?php
//                  file index.php                
// ===============================================
//          Original Author: fateh kabbani        
// ===============================================

// COMMIT HISTORY:
// ============================================================
// 2025-04-03 - Enhance UI by adding button styles and new delete functionality for varieties; update CSS for button classes and improve JavaScript for variety management. - Romain Provencel
//   raspberry_pi/web/index.php | 13 +++++++------
//   1 file changed, 7 insertions(+), 6 deletions(-)
//
// 2025-04-03 - Remove temperature chart from index.php and clean up commented code - fateh kabbani
//   raspberry_pi/web/index.php | 3 +--
//   1 file changed, 1 insertion(+), 2 deletions(-)
//
// 2025-04-03 - Add user variety selection modal and implement variety management functionality - Romain Provencel
//   raspberry_pi/web/index.php | 28 ++++++++++++++++++++++++++++
//   1 file changed, 28 insertions(+)
//
// 2025-04-02 - Add time management features: include time management button in index, enhance time_management page layout, and implement back button functionality - Romain Provencel
//   raspberry_pi/web/index.php | 1 +
//   1 file changed, 1 insertion(+)
//
// 2025-04-02 - changed folder name removed the space - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-29 - Refactor user management: remove admin dashboard, update links, and add new user creation functionality with password generation - fateh kabbani
//   raspberry pi/web/index.php | 2 +-
//   1 file changed, 1 insertion(+), 1 deletion(-)
//
// 2025-03-28 - Add USB device management and dashboard functionality - fateh kabbani
//   raspberry pi/web/index.php | 2 +-
//   1 file changed, 1 insertion(+), 1 deletion(-)
//
// 2025-03-27 - Translate user interface text to French for improved localization - fateh kabbani
//   raspberry pi/web/index.php | 30 +++++++++++++++---------------
//   1 file changed, 15 insertions(+), 15 deletions(-)
//
// 2025-03-26 - Enhance dashboard functionality and styling; add admin role check and new CSS for improved layout - fateh kabbani
//   raspberry pi/web/index.php | 8 +++++++-
//   1 file changed, 7 insertions(+), 1 deletion(-)
//
// 2025-03-26 - Remove max temperature configuration from web interface and add sensor reading script for DS18B20 temperature sensor - fateh kabbani
//   raspberry pi/web/index.php | 9 ---------
//   1 file changed, 9 deletions(-)
//
// 2025-03-20 - move the file to raspberry pi - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-15 - Enhance drying control interface with real-time data visualization, improved layout, and new temperature configuration options - fateh kabbani
//   web/index.php | 55 +++++++++++++++++++++++++++++++++++++++++++++++--------
//   1 file changed, 47 insertions(+), 8 deletions(-)
//
// 2025-03-13 - Refactor project structure by moving backend files to a new directory and re-implementing login and registration functionality - Romain Provencel
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-13 - Add initial project structure with login and registration functionality - fateh kabbani
//   index.php | 30 ++++++++++++++++++++++++++++++
//   1 file changed, 30 insertions(+)
//
// ============================================================

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
        <a href="admin/csv.php"><button id="dashboard" class="btn">Dashboard</button></a>
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
      <p id="dryingStatus">État : En attente</p>
      <button id="startDrying" class="btn">Démarrer le Séchage</button>
      <button id="stopDrying" class="btn">Arrêter le Séchage</button>
    </div>
    <div class="section">
      <h3>Visualisation des Données de Séchage</h3>
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
      <a href="time_management.php"><button id="timeManagement" class="btn">Gérer l'heure Système</button></a>
      <button id="shutdownSystem" class="btn">Arrêter le Système</button>
      <p id="shutdownStatus"></p>
    </div>
  </div>

  <div id="userModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2 id="modalTitle">Sélection des variétés</h2>
      <div id="userForm">
        <div class="form-group">
          <label for="varieties">Variétés</label>
          <div class="form-field">
            <select id="varietiesSelect" name="varieties"></select>
            <button id="addVariety" class="btn" style="margin-bottom: 0;">Ajouter</button>
            <button id="deleteAllVariety">Supprimer les varietés</button>
          </div>
        </div>
        <div class="varieties-list">
          <p id="varietiesNone">Aucune variétés ajouter</p>
          <!-- <div class="varieties-badge">
              <p>varietie</p>
              <span class="deleteVariety">&times;</span>
           </div> -->
        </div>
        <div class="form-actions">
          <button id="save" class="btn">Démarrer</button>
          <button id="cancel" class="btn btn-cancel">Annuler</button>
        </div>
      </div>
    </div>
  </div>

  <script src="src/js/index.js"></script>
</body>

</html>
