<!-- //                file check_usb.php              
// ===============================================
//          Original Author: fateh kabbani        
// ===============================================

// COMMIT HISTORY:
// ============================================================
// 2025-04-02 - changed folder name removed the space - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-28 - Add USB device management and dashboard functionality - fateh kabbani
//   raspberry pi/web/backend/check_usb.php | 16 ++++++++++++++++
//   1 file changed, 16 insertions(+)
//
// ============================================================ -->

<?php
header('Content-Type: application/json');

function getUsbDevices()
{
  // i have to test it using a raspberry pi does not work in windows only linux
  // hope it work
  $device = [];
  $output = exec('lsblk -o NAME,MOUNTPOINT,LABEL,SIZE | grep "sd"', $output);
  $device = explode("\n", $output);
  return $device;
}

echo json_encode([
  'devices' => getUsbDevices()
]);
