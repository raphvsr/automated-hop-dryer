<?php
function getCurrentPage()
{
  $currentFile = basename($_SERVER['PHP_SELF']);

  $pages = [
    'dashboard.php' => 'dashboard',
    'csv.php' => 'csv',
    'users.php' => 'users',
    'new_user.php' => 'users',
    'settings.php' => 'settings',
    'varieties.php' => 'varieties',
  ];

  return isset($pages[$currentFile]) ? $pages[$currentFile] : 'dashboard';
}

$currentPage = getCurrentPage();
?>

<nav class="sidebar-navigation">
  <ul>

    <li class="<?php echo $currentPage === 'csv' ? 'active' : ''; ?>">
      <a href="csv.php">
        <i class="fa fa-file-o"></i>
        <span class="tooltip">Csv</span>
      </a>
    </li>
    <li class="<?php echo $currentPage === 'users' ? 'active' : ''; ?>">
      <a href="users.php">
        <i class="fa fa-user-o"></i>
        <span class="tooltip">Utilisateur</span>
      </a>
    </li>
    <li class="<?php echo $currentPage === 'varieties' ? 'active' : ''; ?>">
      <a href="varieties.php">
        <i class="fa fa-leaf"></i>
        <span class="tooltip">varieties</span>
      </a>
    </li>
    <li class="<?php echo $currentPage === 'settings' ? 'active' : ''; ?>">
      <a href="settings.php">
        <i class="fa fa-sliders"></i>
        <span class="tooltip">Paramètres</span>
      </a>
    </li>

  </ul>
</nav>
