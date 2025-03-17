<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'];

  if ($action === 'manual') {
    $dateTime = $_POST['datetime'];

    $date = DateTime::createFromFormat('Y-m-d H:i:s', $dateTime);
    if ($date !== false) {
      $formattedDate = $date->format('Y-m-d H:i:s');

      if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $formattedDate)) {
        $safeDateTime = escapeshellarg($formattedDate);
        $output = [];
        $return_var = null;

        exec("sudo date -s {$safeDateTime}", $output, $return_var);

        if ($return_var === 0) {
          echo "Time set successfully: " . htmlspecialchars($dateTime, ENT_QUOTES, 'UTF-8');
        } else {
          echo "Error: Failed to set the date and time.";
          echo "Command output: " . htmlspecialchars(implode("\n", $output), ENT_QUOTES, 'UTF-8');
        }
      } else {
        echo "Invalid date and time format. Use YYYY-MM-DD HH:MM:SS.";
      }
    } else {
      echo "Invalid date and time format. Use YYYY-MM-DD HH:MM:SS.";
    }
  } elseif ($action === 'ntp') {
    exec("sudo timedatectl set-ntp true");
    exec("sudo systemctl restart systemd-timesyncd");
    echo "NTP synchronization enabled.";
  } else {
    echo "Unrecognized action.";
  }
}
