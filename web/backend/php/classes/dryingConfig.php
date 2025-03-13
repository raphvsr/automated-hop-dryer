<?php
include '../database.php';

class DryingConfig {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function saveConfig($key, $value) {
        if (empty($key) || empty($value)) {
            throw new InvalidArgumentException("Key and value must not be empty.");
        }

        $sql = "INSERT INTO system_config (`key`, `value`) VALUES (?, ?) 
                ON DUPLICATE KEY UPDATE `value` = ?";
        
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            throw new RuntimeException("Failed to prepare SQL statement: " . $this->conn->error);
        }

        $stmt->bind_param("sss", $key, $value, $value);

        if ($stmt->execute()) {
            return "Configuration saved successfully.";
        } else {
            throw new RuntimeException("Error saving configuration: " . $stmt->error);
        }
    }
}
?>