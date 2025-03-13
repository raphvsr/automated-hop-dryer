<?php
include '../database.php';

class DryingConfig {
    public function saveConfig($variety, $temperature, $duration) {
        if ($temperature > 60 || $temperature < 50) {
            return "Température invalide. Doit être entre 50°C et 60°C.";
        }

        $sql = "UPDATE drying_config SET variety='$variety', temperature=$temperature, duration=$duration WHERE id=1";
        $conn->query($sql);
        return "Configuration enregistrée avec succès.";
    }
}
?>