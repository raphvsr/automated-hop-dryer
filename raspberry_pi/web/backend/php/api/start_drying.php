<?php
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
