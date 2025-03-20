<?php
// header('Content-Type: application/json');

$data = [
  "sensor1" => rand(50, 60),
  "sensor2" => rand(50, 60),
  "sensor3" => rand(50, 60),
  "sensor4" => rand(50, 60),
  "sensor5" => rand(50, 60),
  "sensor6" => rand(50, 60),
];

echo json_encode($data);
