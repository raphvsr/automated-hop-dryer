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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="src/css/time_management.css">
    <title>TIME MANAGEMENT Page - Drying Control</title>
  </head>
  <body>
    <div class="container">
      <h1>Gestion de l'heure RTC</h1>

      <div class="time-display">
        <h3>Heure actuelle</h3>
        <p>
          <strong>Système:</strong> <span id="system-time">Chargement...</span>
        </p>
        <p><strong>RTC:</strong> <span id="rtc-time">Chargement...</span></p>
      </div>

      <div class="manual-time">
        <h3>Mettre à jour l'heure manuellement</h3>
        <input type="text" id="manual-time-input" placeholder="AAAA-MM-JJ HH:MM:SS">
        <button class="btn btn-manual" id="setManualTime">Mettre à jour</button>
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
