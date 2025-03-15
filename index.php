<!-- redirect the user to web page -->
<?php
session_start();
if (!isset($_SESSION['username'])) {
  header('Location: web/login.php');
  exit();
} else {
  header('Location: web/index.php');
  exit();
}
