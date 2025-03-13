<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $output = shell_exec('sudo /sbin/shutdown -h now');
    
    echo "Le Raspberry Pi va s'éteindre...";
} else {
    echo '<form method="post">
            <button type="submit" style="padding: 10px 20px; font-size: 16px;">Éteindre le Raspberry Pi</button>
          </form>';
}
?>
