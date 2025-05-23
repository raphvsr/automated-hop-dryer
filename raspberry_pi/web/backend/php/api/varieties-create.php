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
