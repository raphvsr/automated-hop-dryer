<!-- //            file varieties-create.php           
// ===============================================
//          Original Author: fateh kabbani        
// ===============================================

// COMMIT HISTORY:
// ============================================================
// 2025-05-23 - Refactor sensor reading logic in read_sensor.py, including improved logging and temperature handling. Update GPIO pin configuration in validate.py. Change API endpoint in users.js for user updates. Enhance error handling and logging in varieties-create.php and login-process.php, including session and request logging. Modify register-process.php to ensure all fields are validated. Add info.php for PHP configuration display. Create log files for login attempts to aid in debugging. - fateh kabbani
//   .../web/backend/php/api/varieties-create.php       | 84 +++++++++++-----------
//   1 file changed, 42 insertions(+), 42 deletions(-)
//
// 2025-04-02 - changed folder name removed the space - fateh kabbani
//   {raspberry pi => raspberry_pi}/web/backend/php/api/varieties-create.php | 0
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-04-01 - Add variety management functionality: implement create, update, and delete APIs with AJAX integration + changed the database - fateh kabbani
//   .../web/backend/php/api/varieties-create.php       | 46 ++++++++++++++++++++++
//   1 file changed, 46 insertions(+)
//
// ============================================================ -->

  <?php
  session_start();
  require_once '../../database.php';


  if (!isset($_SESSION['username']) || $_SESSION['admin'] != 1) {
    echo json_encode(['status' => 'error', 'message' => 'Non autorisé']);
    exit();
  }

  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée']);
    exit();
  }

  $name = $_POST['name'] ?? '';
  $max_temperature = $_POST['max_temperature'] ?? '';
  $min_temperature = $_POST['min_temperature'] ?? '';
  $duree_de_sechage = $_POST['duree_de_sechage'] ?? '';

  if (empty($name) || empty($max_temperature) || empty($min_temperature) || empty($duree_de_sechage)) {
    echo json_encode(['status' => 'error', 'message' => 'Tous les champs sont requis']);
    exit();
  }

  try {
    $stmt = $conn->prepare("INSERT INTO hop_varieties (name, max_temperature, min_temperature, duree_de_sechage) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdds", $name, $max_temperature, $min_temperature, $duree_de_sechage);

    if ($stmt->execute()) {
      echo json_encode(['status' => 'success', 'message' => 'Variété créée avec succès']);
    } else {
      echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la création de la variété']);
    }
  } catch (Exception $e) {
    if ($e->getCode() == 1062) {
      echo json_encode(['status' => 'error', 'message' => 'Une variété avec ce nom existe déjà']);
    } else {
      echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la création de la variété']);
    }
  }finally{

    $stmt->close();
    $conn->close();

  }
