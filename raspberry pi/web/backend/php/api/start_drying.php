<?php
include './classes/dryingControl.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $dryingControl = new DryingControl();
    $response = $dryingControl->startDrying();
    
    echo $response;
}
?>