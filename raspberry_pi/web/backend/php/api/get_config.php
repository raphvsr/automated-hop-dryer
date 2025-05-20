<?php
$configPath = __DIR__ . '/../../../config/config-drying.json';

if (file_exists($configPath)) {
    $config = file_get_contents($configPath);
    echo $config;
} else {
    echo json_encode(['error' => 'Config file not found']);
}
