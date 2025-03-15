<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'];

  if ($action === 'manual') {
    $dateTime = $_POST['datetime'];

    if (DateTime::createFromFormat('Y-m-d H:i:s', $dateTime) !== false) {
      $output = null;
      $return_var = null;
      exec("sudo date -s '$dateTime'", $output, $return_var);

      if ($return_var === 0) {
        echo "Time set successfully: $dateTime";
      } else {
        echo "Error: Failed to set the date and time.";
        echo "Command output: " . implode("\n", $output);
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
?>
