<?php
class DryingConfig {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function saveConfig($variety, $temperature, $duration) {
        if ($temperature > 60 || $temperature < 50) {
            return "Température invalide. Doit être entre 50°C et 60°C.";
        }

        $stmt = $this->db->prepare("INSERT INTO drying_config (variety, temperature, duration) VALUES (?, ?, ?)");
        $stmt->execute([$variety, $temperature, $duration]);
        return "Configuration enregistrée avec succès.";
    }
}
?>