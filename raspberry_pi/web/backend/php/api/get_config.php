
<?php
//               file get_config.php              
// ===============================================
//         Original Author: Raphael Vasseur       
// ===============================================

// COMMIT HISTORY:
// ============================================================
// 2025-05-20 - feat: Implement CSV import functionality with GUI; add database update and preview features - Raphael Vasseur
//   raspberry_pi/web/backend/php/api/get_config.php | 9 +++++++++
//   1 file changed, 9 insertions(+)
//
// ============================================================

$configPath = __DIR__ . '/../../../config/config-drying.json';

if (file_exists($configPath)) {
    $config = file_get_contents($configPath);
    echo $config;
} else {
    echo json_encode(['error' => 'Config file not found']);
}
