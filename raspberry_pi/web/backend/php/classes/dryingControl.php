//              file dryingControl.php            
// ===============================================
//        Original Author: Romain Provencel       
// ===============================================

// COMMIT HISTORY:
// ============================================================
// 2025-05-13 - Refactor drying control methods for consistency; update drying time in configuration files to align with new defaults. - fateh kabbani
//   .../web/backend/php/classes/dryingControl.php      | 72 +++++++++++++---------
//   1 file changed, 43 insertions(+), 29 deletions(-)
//
// 2025-04-03 - fix - Romain Provencel
//   raspberry_pi/web/backend/php/classes/dryingControl.php | 2 +-
//   1 file changed, 1 insertion(+), 1 deletion(-)
//
// 2025-04-02 - changed folder name removed the space - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-28 - Add API for saving drying status and enhance session management - Romain Provencel
//   raspberry pi/web/backend/php/classes/dryingControl.php | 5 +++++
//   1 file changed, 5 insertions(+)
//
// 2025-03-27 - Implement drying control status retrieval and enhance existing drying methods - Romain Provencel
//   .../web/backend/php/classes/dryingControl.php      | 46 +++++++++++++---------
//   1 file changed, 28 insertions(+), 18 deletions(-)
//
// 2025-03-20 - move the file to raspberry pi - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-15 - Refactor drying configuration and control classes for improved error handling and code safety against sql injection - fateh kabbani
//   web/backend/php/classes/dryingControl.php | 36 +++++++++++++++++--------------
//   1 file changed, 20 insertions(+), 16 deletions(-)
//
// 2025-03-13 - drying config - Romain Provencel
//   web/backend/php/classes/dryingControl.php | 4 ++--
//   1 file changed, 2 insertions(+), 2 deletions(-)
//
// 2025-03-13 - Refactor drying control script execution and update configuration file structure - Romain Provencel
//   web/backend/php/classes/dryingControl.php | 12 ++++++++++--
//   1 file changed, 10 insertions(+), 2 deletions(-)
//
// 2025-03-13 - Add drying configuration and control classes with initial database setup + structure - Romain Provencel
//   web/backend/php/classes/dryingControl.php | 13 +++++++++++++
//   1 file changed, 13 insertions(+)
//
// ============================================================

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
