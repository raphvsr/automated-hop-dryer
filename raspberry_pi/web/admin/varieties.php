//                file varieties.php              
// ===============================================
//          Original Author: fateh kabbani        
// ===============================================

// COMMIT HISTORY:
// ============================================================
// 2025-04-02 - changed folder name removed the space - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-04-01 - Add varieties page and update sidebar navigation: include varieties link and enhance drying duration display (it display hours now) (: - fateh kabbani
//   raspberry pi/web/admin/varieties.php | 15 +++++++++++----
//   1 file changed, 11 insertions(+), 4 deletions(-)
//
// 2025-04-01 - Add variety management functionality: implement create, update, and delete APIs with AJAX integration + changed the database - fateh kabbani
//   .../web/admin/{users.php => varieties.php}         | 62 +++++++++++-----------
//   1 file changed, 31 insertions(+), 31 deletions(-)
//
// 2025-04-01 - Implement user editing functionality: add modal for editing user details and AJAX request for updates - fateh kabbani
//   raspberry pi/web/admin/users.php | 42 ++++++++++++++++++++--------------------
//   1 file changed, 21 insertions(+), 21 deletions(-)
//
// 2025-03-31 - Add user deletion functionality: implement AJAX request and backend processing for user removal - fateh kabbani
//   raspberry pi/web/admin/users.php | 34 ++++++++++++++++++----------------
//   1 file changed, 18 insertions(+), 16 deletions(-)
//
// 2025-03-30 - Refactor sidebar navigation: extract to a separate component and update links for consistency - fateh kabbani
//   raspberry pi/web/admin/users.php | 35 +++--------------------------------
//   1 file changed, 3 insertions(+), 32 deletions(-)
//
// 2025-03-29 - Refactor user management: remove admin dashboard, update links, and add new user creation functionality with password generation - fateh kabbani
//   raspberry pi/web/admin/users.php | 112 +++++++++++++++++++++++++++++++++++++--
//   1 file changed, 107 insertions(+), 5 deletions(-)
//
// 2025-03-29 - Refactor admin dashboard and user management: move files to admin directory and update CSS/JS - fateh kabbani
//   raspberry pi/web/admin/users.php | 44 ++++++++++++++++++++++++++++++++++++++++
//   1 file changed, 44 insertions(+)
//
// ============================================================

<?php
session_start();
if (!isset($_SESSION['username'])) {
  header('Location: ../../login.php');
  exit();
}
if ($_SESSION['admin'] != 1) {
  header('Location: ../../index.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion des Variétés de Houblon</title>
  <link rel="stylesheet" href="src/css/dashboard.css">
  <link rel="stylesheet" href="src/css/users.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
  <?php include 'components/sidebar.php'; ?>

  <div class="users-container">
    <h1>Gestion des Variétés de Houblon</h1>

    <div class="users-card">
      <div class="card-header">
        <h2 class="section-title">Liste des Variétés</h2>
        <a href="new_variety.php" class="btn btn-add">
          <i class="fas fa-plus"></i> Ajouter une variété
        </a>
      </div>

      <div class="table-container">
        <table class="users-table">
          <thead>
            <tr>
              <th>Nom de la variété</th>
              <th>Température maximale</th>
              <th>Température minimale</th>
              <th>Durée de séchage</th>
              <th>Date de création</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            require_once '../backend/database.php';

            $sql = "SELECT * FROM hop_varieties ORDER BY created_at DESC";
            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc()) {
              $created_at = date('d/m/Y', strtotime($row['created_at']));
              ?>
              <tr id="variety-<?php echo $row['id']; ?>">
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['max_temperature']); ?>°C</td>
                <td><?php echo htmlspecialchars($row['min_temperature']); ?>°C</td>
                <td>
                  <?php
                  $hours = floor($row['duree_de_sechage'] / 60);
                  $minutes = $row['duree_de_sechage'] % 60;
                  echo htmlspecialchars($row['duree_de_sechage']); ?> (ou <?php echo $hours . ':' . str_pad($minutes, 2, '0', STR_PAD_LEFT);
                     ?>h)
                </td>
                </td>
                <td><?php echo $created_at; ?></td>
                <td class="actions">
                  <button class="btn btn-edit" data-id="<?php echo $row['id']; ?>">
                    <i class="fas fa-edit"></i>
                  </button>
                  <button class="btn btn-delete" onclick="deleteVariety(<?php echo $row['id']; ?>)">
                    <i class="fas fa-trash"></i>
                  </button>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div id="varietyModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2 id="modalTitle">Modifier une variété</h2>
      <div id="varietyForm">
        <input type="hidden" id="varietyId" name="varietyId">
        <div class="form-group">
          <label for="name">Nom de la variété</label>
          <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
          <label for="max_temperature">Température maximale (°C)</label>
          <input type="number" step="1" id="max_temperature" name="max_temperature" required>
        </div>
        <div class="form-group">
          <label for="min_temperature">Température minimale (°C)</label>
          <input type="number" step="1" id="min_temperature" name="min_temperature" required>
        </div>
        <div class="form-group">
          <label for="duree_de_sechage">Durée de séchage</label>
          <input type="number" step="1" id="duree_de_sechage" name="duree_de_sechage" required>
        </div>
        <div class="form-actions">
          <button id="save" class="btn btn-save">Enregistrer</button>
          <button id="cancel" class="btn btn-cancel">Annuler</button>
        </div>
      </div>
    </div>
  </div>

  <script src="src/js/varieties.js"></script>
  <script src="https://kit.fontawesome.com/0e4bc9cea5.js" crossorigin="anonymous"></script>
</body>

</html>
