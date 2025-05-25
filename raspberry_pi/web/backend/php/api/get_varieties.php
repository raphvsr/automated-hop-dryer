//              file get_varieties.php            
// ===============================================
//        Original Author: Romain Provencel       
// ===============================================

// COMMIT HISTORY:
// ============================================================
// 2025-04-03 - Add user variety selection modal and implement variety management functionality - Romain Provencel
//   raspberry_pi/web/backend/php/api/get_varieties.php | 25 ++++++++++++++++++++++
//   1 file changed, 25 insertions(+)
//
// ============================================================

<?php
session_start();
require_once '../../database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée']);
    exit();
}  

try {
    $stmt = $conn->prepare("SELECT * FROM hop_varieties");
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $varieties = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode(['status' => 'success', 'varieties' => $varieties]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Aucune variété trouvée']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la récupération des variétés']);
    exit();
}
?>