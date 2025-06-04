
<?php
//            file get_drying_status.php          
// ===============================================
//        Original Author: Romain Provencel       
// ===============================================

// COMMIT HISTORY:
// ============================================================
// 2025-04-02 - changed folder name removed the space - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-27 - Implement drying control status retrieval and enhance existing drying methods - Romain Provencel
//   .../web/backend/php/api/{start_drying.php => get_drying_status.php}  | 5 +++--
//   1 file changed, 3 insertions(+), 2 deletions(-)
//
// 2025-03-26 - edit main.py - Raphael Vasseur
//   raspberry pi/web/backend/php/api/start_drying.php | 6 +++---
//   1 file changed, 3 insertions(+), 3 deletions(-)
//
// 2025-03-25 - utilisation de l'api et creation de data.csv - Raphael Vasseur
//   raspberry pi/web/backend/php/api/start_drying.php | 4 ++--
//   1 file changed, 2 insertions(+), 2 deletions(-)
//
// 2025-03-20 - move the file to raspberry pi - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-14 - Implement start and stop drying API endpoints - Romain Provencel
//   web/backend/php/api/start_drying.php | 11 ++++++++++-
//   1 file changed, 10 insertions(+), 1 deletion(-)
//
// 2025-03-13 - . - Romain Provencel
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-13 - Add drying configuration and control classes with initial database setup + structure - Romain Provencel
//   web/backend/script/start_drying.php | 1 +
//   1 file changed, 1 insertion(+)
//
// ============================================================

header('Content-Type: application/json');
include '../classes/dryingControl.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $dryingControl = new DryingControl();
    $response = $dryingControl->getDryingStatus();

    echo json_encode(["status" => "success", "message" => $response]);
}
?>