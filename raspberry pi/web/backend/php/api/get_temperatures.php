<?php
// header('Content-Type: application/json');

$data = [
  "sensor1" => rand(50, 61),
  "sensor2" => rand(50, 61),
  "sensor3" => rand(50, 61),
  "sensor4" => rand(50, 61),
  "sensor5" => rand(50, 61),
  "sensor6" => rand(50, 61),
];

echo json_encode($data);
