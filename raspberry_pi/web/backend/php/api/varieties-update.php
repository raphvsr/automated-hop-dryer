//            file varieties-update.php           
// ===============================================
//          Original Author: fateh kabbani        
// ===============================================

// COMMIT HISTORY:
// ============================================================
// 2025-04-02 - changed folder name removed the space - fateh kabbani
//   {raspberry pi => raspberry_pi}/web/backend/php/api/varieties-update.php | 0
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-04-01 - Add variety management functionality: implement create, update, and delete APIs with AJAX integration + changed the database - fateh kabbani
//   .../web/backend/php/api/varieties-update.php       | 45 ++++++++++++++++++++++
//   1 file changed, 45 insertions(+)
//
// ============================================================

<?php
session_start();
require_once '../../database.php';


if (!isset($_SESSION['username']) || $_SESSION['admin'] != 1) {
  echo json_encode(['status' => 'error', 'message' => 'Non autorisé']);
  exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée']);
  exit();
}

$id = $_POST['id'] ?? '';
$name = $_POST['name'] ?? '';
$max_temperature = $_POST['max_temperature'] ?? '';
$min_temperature = $_POST['min_temperature'] ?? '';
$duree_de_sechage = $_POST['duree_de_sechage'] ?? '';

if (empty($id) || empty($name) || empty($max_temperature) || empty($min_temperature) || empty($duree_de_sechage)) {
  echo json_encode(['status' => 'error', 'message' => 'Tous les champs sont requis']);
  exit();
}

try {
  $stmt = $conn->prepare("UPDATE hop_varieties SET name = ?, max_temperature = ?, min_temperature = ?, duree_de_sechage = ? WHERE id = ?");
  $stmt->bind_param("sddsi", $name, $max_temperature, $min_temperature, $duree_de_sechage, $id);

  if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Variété mise à jour avec succès']);
  } else {
    echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la mise à jour de la variété']);
  }
} catch (Exception $e) {
  if ($e->getCode() == 1062) {
    echo json_encode(['status' => 'error', 'message' => 'Une variété avec ce nom existe déjà']);
  } else {
    echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la mise à jour de la variété']);
  }
} finally {
  $stmt->close();
  $conn->close();
}
