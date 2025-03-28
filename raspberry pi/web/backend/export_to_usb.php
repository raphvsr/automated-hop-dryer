<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$filename = $data['filename'] ?? '';

exec('findmnt -n -o TARGET /dev/sd*', $usbMounts);

if(empty($usbMounts)) {
    echo json_encode([
        'success' => false,
        'message' => 'Aucun périphérique USB détecté'
    ]);
    exit;
}

$usbPath = $usbMounts[0]; 
$sourcePath = "../data/" . basename($filename);
$destPath = $usbPath . "/" . basename($filename);

if(copy($sourcePath, $destPath)) {
    echo json_encode([
        'success' => true,
        'message' => 'Fichier exporté avec succès'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur lors de la copie du fichier'
    ]);
}
