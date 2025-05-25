//                file database.php               
// ===============================================
//          Original Author: fateh kabbani        
// ===============================================

// COMMIT HISTORY:
// ============================================================
// 2025-04-02 - changed folder name removed the space - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-04-02 - Refactor database connection to use environment variables from .env file - fateh kabbani
//   raspberry pi/web/backend/database.php | 10 +++++-----
//   1 file changed, 5 insertions(+), 5 deletions(-)
//
// 2025-04-01 - Add variety management functionality: implement create, update, and delete APIs with AJAX integration + changed the database - fateh kabbani
//   raspberry pi/web/backend/database.php | 2 +-
//   1 file changed, 1 insertion(+), 1 deletion(-)
//
// 2025-03-20 - move the file to raspberry pi - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-13 - Refactor project structure by moving backend files to a new directory and re-implementing login and registration functionality - Romain Provencel
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-13 - Add initial project structure with login and registration functionality - fateh kabbani
//   backend/database.php | 13 +++++++++++++
//   1 file changed, 13 insertions(+)
//
// ============================================================

<?php
$dotenv = parse_ini_file(__DIR__ . '/.env', true);
$host = $dotenv['DB_HOST'];
$db = $dotenv['DB_DATABASE'];
$user = $dotenv['DB_USERNAME'];
$pass = $dotenv['DB_PASSWORD'];

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
