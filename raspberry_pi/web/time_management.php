<?php
//             file time_management.php           
// ===============================================
//        Original Author: Romain Provencel       
// ===============================================

// COMMIT HISTORY:
// ============================================================
// 2025-04-02 - Add time management features: include time management button in index, enhance time_management page layout, and implement back button functionality - Romain Provencel
//   raspberry_pi/web/time_management.php | 5 ++++-
//   1 file changed, 4 insertions(+), 1 deletion(-)
//
// 2025-04-02 - changed folder name removed the space - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-28 - Add API for saving drying status and enhance session management - Romain Provencel
//   raspberry pi/web/time_management.php | 10 +++++-----
//   1 file changed, 5 insertions(+), 5 deletions(-)
//
// 2025-03-27 - Add manual time setting feature with input validation and UI enhancements - Romain Provencel
//   raspberry pi/web/time_management.php | 16 +++++++++-------
//   1 file changed, 9 insertions(+), 7 deletions(-)
//
// 2025-03-27 - Enhance manual time update feature with input validation and styling improvements - Romain Provencel
//   raspberry pi/web/time_management.php | 2 +-
//   1 file changed, 1 insertion(+), 1 deletion(-)
//
// 2025-03-27 - Add manual time update feature and improve time synchronization UI - Romain Provencel
//   raspberry pi/web/time_management.php | 6 ++++++
//   1 file changed, 6 insertions(+)
//
// 2025-03-26 - Implement time management feature with RTC synchronization and UI enhancements - Romain Provencel
//   raspberry pi/web/time_management.php | 44 ++++++++++++++++++++++++++++++++++++
//   1 file changed, 44 insertions(+)
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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="src/css/time_management.css">
    <title>TIME MANAGEMENT Page - Drying Control</title>
  </head>
  <body>
    <div class="container">
      <header>
        <h1>Gestion de l'heure RTC</h1>
        <button class="btn btn-sync" id="back">Retour</button>
      </header>

      <div class="time-display">
        <h3>Heure actuelle</h3>
        <p>
          <strong>Système:</strong> <span id="system-time">Chargement...</span>
        </p>
        <p><strong>RTC:</strong> <span id="rtc-time">Chargement...</span></p>
      </div>

      <div class="manual-time">
        <h3>Mettre à jour l'heure manuellement du système et RTC</h3>
        <div class="manual-time-input-container">
          <input type="text" id="manual-time-input" placeholder="JJ-MM-AAAA HH:MM:SS">
          <button class="btn" id="setManualTime">Mettre à jour</button>
        </div>
      </div>

      <button class="btn btn-sync" id="syncSystemTime">
        Synchroniser système avec RTC
      </button>

      <button class="btn btn-sync" id="syncRtcTime">
        Synchroniser RTC avec système
      </button>

      <button class="btn" id="refreshTime">Actualiser</button>

      <div id="status-message" class="status" style="display: none"></div>
    </div>

    <script src="src/js/time_management.js"></script>
  </body>
</html>
