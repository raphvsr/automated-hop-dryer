<?php
// header('Content-Type: application/json');

$data = [
  ['sensor' => 'sensor 1', 'temperature' => rand(50, 61) . " C°"],
  ['sensor' => 'sensor 2', 'temperature' => rand(50, 61) . " C°"],
  ['sensor' => 'sensor 3', 'temperature' => rand(50, 61) . " C°"],
  ['sensor' => 'sensor 4', 'temperature' => rand(50, 61) . " C°"],
  ['sensor' => 'sensor 5', 'temperature' => rand(50, 61) . " C°"],
  ['sensor' => 'sensor 6', 'temperature' => rand(50, 61) . " C°"],
];


echo json_encode($data);
