<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  shell_exec('sudo /sbin/shutdown -h now');

  echo "The Raspberry Pi is going to shut down...";
}
?>
