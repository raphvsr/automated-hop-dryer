
<?php
//               file stop_drying.php             
// ===============================================
//        Original Author: Romain Provencel       
// ===============================================

// COMMIT HISTORY:
// ============================================================
// 2025-04-02 - changed folder name removed the space - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-28 - Add API for saving drying status and enhance session management - Romain Provencel
//   raspberry pi/web/backend/php/api/stop_drying.php | 1 +
//   1 file changed, 1 insertion(+)
//
// 2025-03-27 - Implement drying control status retrieval and enhance existing drying methods - Romain Provencel
//   raspberry pi/web/backend/php/api/stop_drying.php | 5 +++--
//   1 file changed, 3 insertions(+), 2 deletions(-)
//
// 2025-03-26 - Implement time management feature with RTC synchronization and UI enhancements - Romain Provencel
//   raspberry pi/web/backend/php/api/stop_drying.php | 2 +-
//   1 file changed, 1 insertion(+), 1 deletion(-)
//
// 2025-03-26 - Enhance dashboard functionality and styling; add admin role check and new CSS for improved layout - fateh kabbani
//   raspberry pi/web/backend/php/api/stop_drying.php | 3 +--
//   1 file changed, 1 insertion(+), 2 deletions(-)
//
// 2025-03-20 - move the file to raspberry pi - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-14 - Implement start and stop drying API endpoints - Romain Provencel
//   web/backend/php/api/stop_drying.php | 11 ++++++++++-
//   1 file changed, 10 insertions(+), 1 deletion(-)
//
// 2025-03-13 - . - Romain Provencel
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-13 - Add drying configuration and control classes with initial database setup + structure - Romain Provencel
//   web/backend/script/stop_drying.php | 1 +
//   1 file changed, 1 insertion(+)
//
// ============================================================

header('Content-Type: application/json');
include './classes/dryingControl.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $dryingControl = new DryingControl();
    $response = $dryingControl->stopDrying();

    echo json_encode(["status" => "success", "message" => $response]);
}
?>