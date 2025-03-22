<!-- redirect the user to web page -->
<?php
session_start();
if (!isset($_SESSION['username'])) {
  header('Location: raspberry pi/web/login.php');
  exit();
} else {
  header('Location: raspberry pi/web/index.php');
  exit();
}
