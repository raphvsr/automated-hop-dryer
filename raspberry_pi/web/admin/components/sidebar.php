//                 file sidebar.php               
// ===============================================
//          Original Author: fateh kabbani        
// ===============================================

// COMMIT HISTORY:
// ============================================================
// 2025-04-02 - changed folder name removed the space - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-04-01 - Add varieties page and update sidebar navigation: include varieties link and enhance drying duration display (it display hours now) (: - fateh kabbani
//   raspberry pi/web/admin/components/sidebar.php | 77 +++++++++++++++------------
//   1 file changed, 43 insertions(+), 34 deletions(-)
//
// 2025-03-30 - Refactor sidebar navigation: extract to a separate component and update links for consistency - fateh kabbani
//   raspberry pi/web/admin/components/sidebar.php | 41 +++++++++++++++++++++++++++
//   1 file changed, 41 insertions(+)
//
// ============================================================

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
