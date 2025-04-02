<?php
header('Content-Type: application/json');
include '../classes/dryingControl.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $dryingControl = new DryingControl();
    $response = $dryingControl->startDrying();

    echo json_encode(["status" => "success", "message" => $response]);
}
