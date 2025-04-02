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
