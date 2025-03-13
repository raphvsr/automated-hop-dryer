<?php
class DryingControl {
    private $pythonScriptPath;

    public function __construct() {
        $this->pythonScriptPath = __DIR__ . "/../../python/";
    }

    public function startDrying() {
        $scriptPath = $this->pythonScriptPath . "drying_control.py";
        exec("sudo python3 $scriptPath start");
        return "Séchage démarré.";
    }

    public function stopDrying() {
        $scriptPath = $this->pythonScriptPath . "drying_control.py";
        exec("sudo python3 $scriptPath stop");
        return "Séchage arrêté.";
    }
}
?>