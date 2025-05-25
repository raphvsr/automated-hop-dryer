//              file export_to_usb.php            
// ===============================================
//          Original Author: fateh kabbani        
// ===============================================

// COMMIT HISTORY:
// ============================================================
// 2025-04-02 - changed folder name removed the space - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-29 -  improve CSV file sorting, display the newest first - fateh kabbani
//   raspberry pi/web/backend/export_to_usb.php | 32 +++++++++++++++---------------
//   1 file changed, 16 insertions(+), 16 deletions(-)
//
// 2025-03-28 - Add USB device management and dashboard functionality - fateh kabbani
//   raspberry pi/web/backend/export_to_usb.php | 31 ++++++++++++++++++++++++++++++
//   1 file changed, 31 insertions(+)
//
// ============================================================

<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$filename = $data['filename'] ?? '';

exec('findmnt -n -o TARGET /dev/sd*', $usbMounts);

if (empty($usbMounts)) {
  echo json_encode([
    'success' => false,
    'message' => 'Aucun périphérique USB détecté'
  ]);
  exit;
}

$usbPath = $usbMounts[0];
$sourcePath = "../data/" . basename($filename);
$destPath = $usbPath . "/" . basename($filename);

if (copy($sourcePath, $destPath)) {
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
