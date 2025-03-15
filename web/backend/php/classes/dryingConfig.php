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
