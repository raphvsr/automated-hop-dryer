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

$id = $_POST['id'] ?? '';

if (empty($id)) {
  echo json_encode(['status' => 'error', 'message' => 'ID de la variété requis']);
  exit();
}

try {
  $stmt = $conn->prepare("DELETE FROM hop_varieties WHERE id = ?");
  $stmt->bind_param("i", $id);

  if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Variété supprimée avec succès']);
  } else {
    echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la suppression de la variété']);
  }
} catch (Exception $e) {
  echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la suppression de la variété']);
} finally {
  $stmt->close();
  $conn->close();
}
