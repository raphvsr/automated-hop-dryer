<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $output = shell_exec('sudo /sbin/shutdown -h now');
    
    echo "The Raspberry Pi is going to shut down...";
}
?>
