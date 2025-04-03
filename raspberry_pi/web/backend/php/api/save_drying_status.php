<?php
header('Content-Type: application/json');
include './classes/dryingControl.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $dryingControl = new DryingControl();
    $status = $_POST["status"];
    $dryingControl->saveDryingStatus($status);

    echo json_encode(["status" => "success", "message" => $response]);
}
?>