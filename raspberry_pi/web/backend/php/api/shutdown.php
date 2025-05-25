//                file shutdown.php               
// ===============================================
//        Original Author: Romain Provencel       
// ===============================================

// COMMIT HISTORY:
// ============================================================
// 2025-04-02 - changed folder name removed the space - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-28 - Simplify shutdown API by removing unnecessary variable assignment - fateh kabbani
//   raspberry pi/web/backend/php/api/shutdown.php | 4 ++--
//   1 file changed, 2 insertions(+), 2 deletions(-)
//
// 2025-03-26 - Implement time management feature with RTC synchronization and UI enhancements - Romain Provencel
//   raspberry pi/web/backend/php/api/shutdown.php | 2 +-
//   1 file changed, 1 insertion(+), 1 deletion(-)
//
// 2025-03-20 - move the file to raspberry pi - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-15 - Refactor drying configuration and control classes for improved error handling and code safety against sql injection - fateh kabbani
//   web/backend/php/api/shutdown.php | 4 ++--
//   1 file changed, 2 insertions(+), 2 deletions(-)
//
// 2025-03-13 - drying config - Romain Provencel
//   web/backend/php/api/shutdown.php | 2 +-
//   1 file changed, 1 insertion(+), 1 deletion(-)
//
// 2025-03-13 - Replace shutdown script with a new API endpoint for Raspberry Pi shutdown functionality - Romain Provencel
//   web/backend/php/api/shutdown.php | 7 +++++++
//   1 file changed, 7 insertions(+)
//
// ============================================================

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  shell_exec('sudo /sbin/shutdown -h now');

  echo "The Raspberry Pi is going to shut down...";
}
?>
