//            file get_temperatures.php           
// ===============================================
//          Original Author: fateh kabbani        
// ===============================================

// COMMIT HISTORY:
// ============================================================
// 2025-05-20 - feat: Implement CSV import functionality with GUI; add database update and preview features - Raphael Vasseur
//   raspberry_pi/web/backend/php/api/get_temperatures.php | 12 ++++++------
//   1 file changed, 6 insertions(+), 6 deletions(-)
//
// 2025-04-03 - Refactor temperature data fetching and visualization; update get_temperatures.php to return numeric values and enhance index.js for improved charting and table display. - fateh kabbani
//   raspberry_pi/web/backend/php/api/get_temperatures.php | 13 ++++++-------
//   1 file changed, 6 insertions(+), 7 deletions(-)
//
// 2025-04-03 - Remove get_drying_data.php and update get_temperatures.php to return sensor data with temperature; enhance index.js to fetch and display temperature data in the table. - fateh kabbani
//   raspberry_pi/web/backend/php/api/get_temperatures.php | 13 +++++++------
//   1 file changed, 7 insertions(+), 6 deletions(-)
//
// 2025-04-02 - changed folder name removed the space - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-25 - utilisation de l'api et creation de data.csv - Raphael Vasseur
//   raspberry pi/web/backend/php/api/get_temperatures.php | 12 ++++++------
//   1 file changed, 6 insertions(+), 6 deletions(-)
//
// 2025-03-20 - move the file to raspberry pi - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-17 - Refactor login process for improved SQL query safety and add temperature data API - fateh kabbani
//   web/backend/php/api/get_temperatures.php | 13 +++++++++++++
//   1 file changed, 13 insertions(+)
//
// ============================================================

<?php
// header('Content-Type: application/json');

$data = [
  ['sensor' => 'sensor 1', 'temperature' => rand(50, 688)],
  ['sensor' => 'sensor 2', 'temperature' => rand(50, 688)],
  ['sensor' => 'sensor 3', 'temperature' => rand(50, 688)],
  ['sensor' => 'sensor 4', 'temperature' => rand(50, 688)],
  ['sensor' => 'sensor 5', 'temperature' => rand(50, 688)],
  ['sensor' => 'sensor 6', 'temperature' => rand(50, 688)],
];

echo json_encode($data);
