<!-- //              file dryingConfig.php             
// ===============================================
//        Original Author: Romain Provencel       
// ===============================================

// COMMIT HISTORY:
// ============================================================
// 2025-04-02 - changed folder name removed the space - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-20 - move the file to raspberry pi - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-15 - Enhance drying control interface with real-time data visualization, improved layout, and new temperature configuration options - fateh kabbani
//   web/backend/php/classes/dryingConfig.php | 1 -
//   1 file changed, 1 deletion(-)
//
// 2025-03-15 - Refactor drying configuration and control classes for improved error handling and code safety against sql injection - fateh kabbani
//   web/backend/php/classes/dryingConfig.php | 64 +++++++++++++++++++-------------
//   1 file changed, 39 insertions(+), 25 deletions(-)
//
// 2025-03-13 - drying config - Romain Provencel
//   web/backend/php/classes/dryingConfig.php | 30 ++++++++++++++++++++++++------
//   1 file changed, 24 insertions(+), 6 deletions(-)
//
// 2025-03-13 - . - Romain Provencel
//   web/backend/php/classes/dryingConfig.php | 12 ++++--------
//   1 file changed, 4 insertions(+), 8 deletions(-)
//
// 2025-03-13 - Add drying configuration and control classes with initial database setup + structure - Romain Provencel
//   web/backend/php/classes/dryingConfig.php | 19 +++++++++++++++++++
//   1 file changed, 19 insertions(+)
//
// ============================================================ -->

<?php
include '../database.php';

class DryingConfig
{
  private $conn;

  public function __construct($conn)
  {
    $this->conn = $conn;
  }

  public function saveConfig($key, $value)
  {
    if (empty($key) || empty($value)) {
      throw new InvalidArgumentException("Key and value must not be empty.");
    }

    $key = (string) $key;
    $value = (string) $value;

    $sql = "INSERT INTO system_config (`key`, `value`) VALUES (:key, :value)
            ON DUPLICATE KEY UPDATE `value` = :value";
    $stmt = $this->conn->prepare($sql);

    if ($stmt === false) {
      $errorInfo = $this->conn->errorInfo();
      throw new RuntimeException("Failed to prepare SQL statement: " . $errorInfo[2]);
    }

    $stmt->bindValue(':key', $key, PDO::PARAM_STR);
    $stmt->bindValue(':value', $value, PDO::PARAM_STR);

    try {
      if ($stmt->execute()) {
        return "Configuration saved successfully.";
      } else {
        $errorInfo = $stmt->errorInfo();
        throw new RuntimeException("Error saving configuration: " . $errorInfo[2]);
      }
    } catch (PDOException $e) {
      throw new RuntimeException("Database error: " . $e->getMessage());
    }
  }
}
?>
