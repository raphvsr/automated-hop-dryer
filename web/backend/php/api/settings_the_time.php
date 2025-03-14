<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'manual') {
        $dateTime = $_POST['datetime'];

        if (DateTime::createFromFormat('Y-m-d H:i:s', $dateTime) !== false) {
            exec("sudo date -s '$dateTime'");
            echo "Time set manually: $dateTime";
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