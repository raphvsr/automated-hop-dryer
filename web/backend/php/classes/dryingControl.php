<?php
class DryingControl {
    public function startDrying() {
        exec("sudo python3 /path/to/start_drying.py");
        return "Séchage démarré.";
    }

    public function stopDrying() {
        exec("sudo python3 /path/to/stop_drying.py");
        return "Séchage arrêté.";
    }
}
?>