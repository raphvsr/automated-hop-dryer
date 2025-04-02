<?php

$data = [
  ["time" => "10:00", "temperature" => 50],
  ["time" => "10:05", "temperature" => 52],
  ["time" => "10:10", "temperature" => 54],
  ["time" => "10:15", "temperature" => 55],
  ["time" => "10:20", "temperature" => 50],
  ["time" => "10:25", "temperature" => 57],
  ["time" => "10:30", "temperature" => 58],
  ["time" => "10:35", "temperature" => 59],
  ["time" => "10:40", "temperature" => 40],
  ["time" => "10:45", "temperature" => 45],
];

echo json_encode($data);
?>
