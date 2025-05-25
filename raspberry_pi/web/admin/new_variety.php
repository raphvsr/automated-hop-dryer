//               file new_variety.php             
// ===============================================
//          Original Author: fateh kabbani        
// ===============================================

// COMMIT HISTORY:
// ============================================================
// 2025-04-02 - changed folder name removed the space - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-04-01 - Add variety management functionality: implement create, update, and delete APIs with AJAX integration + changed the database - fateh kabbani
//   raspberry pi/web/admin/new_variety.php | 63 ++++++++++++++++++++++++++++++++++
//   1 file changed, 63 insertions(+)
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
  <title>Nouvelle Variété de Houblon</title>
  <link rel="stylesheet" href="src/css/dashboard.css">
  <link rel="stylesheet" href="src/css/users.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
  <?php include 'components/sidebar.php'; ?>

  <div class="users-container">
    <h1>Nouvelle Variété de Houblon</h1>

    <div class="users-card">
      <form id="newVarietyForm" class="form-container">
        <div class="form-group">
          <label for="name">Nom de la variété</label>
          <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
          <label for="max_temperature">Température maximale (°C)</label>
          <input type="number" step="0.01" id="max_temperature" name="max_temperature" required>
        </div>
        <div class="form-group">
          <label for="min_temperature">Température minimale (°C)</label>
          <input type="number" step="0.01" id="min_temperature" name="min_temperature" required>
        </div>
        <div class="form-group">
          <label for="duree_de_sechage">Durée de séchage</label>
          <input type="text" id="duree_de_sechage" name="duree_de_sechage" required>
        </div>
        <div class="form-actions">
          <button type="submit" class="btn btn-save">Enregistrer</button>
          <a href="varieties.php" class="btn btn-cancel">Annuler</a>
        </div>
      </form>
    </div>
  </div>

  <script src="src/js/new_variety.js"></script>
  <script src="https://kit.fontawesome.com/0e4bc9cea5.js" crossorigin="anonymous"></script>
</body>

</html> 