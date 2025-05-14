<?php
class DryingControl
{
  private $pythonScriptPath;

  public function __construct()
  {
    $this->pythonScriptPath = __DIR__ . "/../../python/drying_control.py";
  }

  public function startDrying()
  {
    $output = [];
    $return_var = 0;
    exec("sudo python3 " . escapeshellarg($this->pythonScriptPath), $output, $return_var);
    return implode("\n", $output);
  }

  public function stopDrying()
  {
    $output = [];
    $return_var = 0;
    exec("sudo python3 -c 'from drying_control import stop_drying; stop_drying()'", $output, $return_var);
    return implode("\n", $output);
  }

  public function getDryingStatus()
  {
    $output = [];
    $return_var = 0;
    exec("sudo python3 -c 'from drying_control import get_status; print(get_status())'", $output, $return_var);
    return trim(end($output)) === 'True';
  }

  public function saveDryingStatus($status)
  {
    if (!is_bool($status)) {
      throw new InvalidArgumentException("Status must be a boolean value.");
    }
    try {
      require_once(__DIR__ . '/../../backend/database.php');

      $stmt = $conn->prepare("INSERT INTO drying_status (status, timestamp) VALUES (?, NOW())");
      $stmt->bind_param("i", $status);
      $stmt->execute();

      return true;
    } catch (Exception $e) {
      error_log("Error saving drying status: " . $e->getMessage());
      return false;
    }
  }
}
?>
