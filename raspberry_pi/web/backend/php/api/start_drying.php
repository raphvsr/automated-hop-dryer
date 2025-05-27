
<?php
//              file start_drying.php             
// ===============================================
//        Original Author: Romain Provencel       
// ===============================================

// COMMIT HISTORY:
// ============================================================
// 2025-04-09 - Refactor drying control logic to load drying time from configuration; update start_drying.php to remove debug output and enhance drying_control.py to manage drying duration with a timer. Update config.py to define a default drying time. - Romain Provencel
//   raspberry_pi/web/backend/php/api/start_drying.php | 3 ---
//   1 file changed, 3 deletions(-)
//
// 2025-04-03 - fix - Romain Provencel
//   raspberry_pi/web/backend/php/api/start_drying.php | 15 +++++++++++----
//   1 file changed, 11 insertions(+), 4 deletions(-)
//
// 2025-04-03 - fix - Romain Provencel
//   raspberry_pi/web/backend/php/api/start_drying.php | 2 +-
//   1 file changed, 1 insertion(+), 1 deletion(-)
//
// 2025-04-03 - . - Romain Provencel
//   raspberry_pi/web/backend/php/api/start_drying.php | 2 +-
//   1 file changed, 1 insertion(+), 1 deletion(-)
//
// 2025-04-03 - Enhance drying process by calculating minimum drying time for selected varieties; update start_drying.php to include drying time in configuration and modify response handling. Update index.js to pass drying time data for selected varieties. - Romain Provencel
//   raspberry_pi/web/backend/php/api/start_drying.php | 21 +++++++++++++++------
//   1 file changed, 15 insertions(+), 6 deletions(-)
//
// 2025-04-03 - Implement variety temperature handling in drying process; update start_drying.php to validate and store temperature data, and enhance index.js to pass selected variety temperatures during drying initiation. - Romain Provencel
//   raspberry_pi/web/backend/php/api/start_drying.php | 43 ++++++++++++++++++++++-
//   1 file changed, 42 insertions(+), 1 deletion(-)
//
// 2025-04-02 - changed folder name removed the space - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-27 - Implement drying control status retrieval and enhance existing drying methods - Romain Provencel
//   raspberry pi/web/backend/php/api/start_drying.php | 2 +-
//   1 file changed, 1 insertion(+), 1 deletion(-)
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

include '../classes/dryingControl.php';
$file_path = $_SERVER['DOCUMENT_ROOT'] . '/skl-project/raspberry_pi/web/config/config-drying.json';

$config = json_decode(file_get_contents($file_path), true);
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $variety = $_POST["variety"];

    if (empty($variety)) {
        echo json_encode(["status" => "error", "message" => "Variety is required"]);
        exit;
    }

    $varieties = json_decode($variety, true);
    
    if (!is_array($varieties) || empty($varieties)) {
        echo json_encode(["status" => "error", "message" => "Invalid variety data"]);
        exit;
    }

    $min_max_temp = null;
    foreach ($varieties as $v) {
        $max_temp = floatval($v["max_temperature"]);
        if ($min_max_temp === null || $max_temp < $min_max_temp) {
            $min_max_temp = $max_temp;
        }
    }

    $max_min_temp = null;
    foreach ($varieties as $v) {
        $min_temp = floatval($v["min_temperature"]);
        if ($max_min_temp === null || $min_temp > $max_min_temp) {
            $max_min_temp = $min_temp;
        }
    }

    $min_drying_time = null;
    foreach ($varieties as $v) {
        $current_time = floatval($v["drying_time"]);
    
        if ($min_drying_time === null || $current_time < $min_drying_time) {
            $min_drying_time = $current_time;
        }
    }    
    print_r($config);
    $config["max-temperature"] = $min_max_temp;
    $config["min-temperature"] = $max_min_temp;
    $config["drying-time"] = $min_drying_time;
    print_r($config);
    file_put_contents($file_path, json_encode($config));

    $dryingControl = new DryingControl();
    $response = $dryingControl->startDrying();

    if ($response === "Drying started: Burner on") {
        echo json_encode(["status" => "success", "message" => "Drying started"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to start drying"]);
    }
}

echo json_encode(["status" => "error", "message" => "Invalid request method"]);
exit;
?>
