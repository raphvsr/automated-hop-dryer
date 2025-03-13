<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $output = shell_exec('sudo /sbin/shutdown -h now');
    
    echo "Le Raspberry Pi va s'éteindre...";
}
?>
