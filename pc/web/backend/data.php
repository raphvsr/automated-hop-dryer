<?php
require_once 'database.php';

// Récupérer les paramètres de la requête
$variety = isset($_GET['variety']) ? $_GET['variety'] : null;
$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : null;
$endDate = isset($_GET['endDate']) ? $_GET['endDate'] : null;
$etageNumber = isset($_GET['etage']) ? (int) $_GET['etage'] : null;


// Construction de la requête de base
$sql = "
    SELECT
        dc.id AS campaign_id,
        v.name AS variety_name,
        dc.start_time,
        dc.end_time,
        de.etage_number,
        de.start_time AS etage_start,
        de.end_time AS etage_end,
        bs.status AS burner_status,
        bs.changed_at
    FROM
        drying_campaigns dc
    JOIN
        varieties v ON dc.variety_id = v.id
    LEFT JOIN
        drying_etages de ON dc.id = de.campaign_id
    LEFT JOIN
        burner_status bs ON dc.id = bs.campaign_id AND de.etage_number = bs.etage_number
    WHERE 1=1
";

if ($variety) {
  $sql .= " AND v.name = '" . $conn->real_escape_string($variety) . "'";
}

if ($startDate) {
  $sql .= " AND dc.start_time >= '" . $conn->real_escape_string($startDate) . "'";
}

if ($endDate) {
  $sql .= " AND dc.start_time <= '" . $conn->real_escape_string($endDate) . "'";
}

if ($etageNumber) {
  $sql .= " AND de.etage_number = " . $etageNumber;
}

$sql .= " ORDER BY dc.start_time, de.etage_number, bs.changed_at";

// Exécuter la requête
$result = $conn->query($sql);

if (!$result) {
  http_response_code(500);
  echo json_encode(['error' => 'Erreur de requête : ' . $conn->error]);
  exit;
}

// Structurer les données pour la visualisation
$data = [];
while ($row = $result->fetch_assoc()) {
  $data[] = $row;
}

// Fermer la connexion
$conn->close();

// Envoyer les données au format JSON
header('Content-Type: application/json');
echo json_encode($data);
