<?php
require_once 'database.php';

// Set proper content type
header('Content-Type: application/json');

try {
  // Get filter parameters
  $variety = isset($_GET['variety']) ? $_GET['variety'] : null;
  $startDate = isset($_GET['startDate']) ? $_GET['startDate'] : null;
  $endDate = isset($_GET['endDate']) ? $_GET['endDate'] : null;
  $etageNumber = isset($_GET['etage']) ? (int) $_GET['etage'] : null;

  // Build base query with proper joins
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
            burner_status bs ON dc.id = bs.campaign_id
            AND (de.etage_number = bs.etage_number OR bs.etage_number IS NULL)
        WHERE 1=1
    ";

  // Add filters with prepared statements for security
  $params = [];
  $types = "";

  if ($variety) {
    $sql .= " AND v.name = ?";
    $params[] = $variety;
    $types .= "s";
  }

  if ($startDate) {
    $sql .= " AND dc.start_time >= ?";
    $params[] = $startDate;
    $types .= "s";
  }

  if ($endDate) {
    $sql .= " AND dc.start_time <= ?";
    $params[] = $endDate;
    $types .= "s";
  }

  if ($etageNumber) {
    $sql .= " AND de.etage_number = ?";
    $params[] = $etageNumber;
    $types .= "i";
  }

  $sql .= " ORDER BY dc.start_time, de.etage_number, bs.changed_at";

  // Prepare and execute query
  if (!empty($params)) {
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
      throw new Exception("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
  } else {
    $result = $conn->query($sql);
  }

  if (!$result) {
    throw new Exception("Query failed: " . $conn->error);
  }

  // Process and structure the data
  $data = [];
  while ($row = $result->fetch_assoc()) {
    // Convert burner status to consistent format
    if ($row['burner_status'] !== null) {
      $row['burner_status_numeric'] = ($row['burner_status'] === 'on') ? 1 : 0;
    } else {
      $row['burner_status_numeric'] = null;
    }

    $data[] = $row;
  }

  // Return structured response
  echo json_encode([
    'status' => 'success',
    'count' => count($data),
    'data' => $data
  ]);

} catch (Exception $e) {
  http_response_code(500);
  echo json_encode([
    'status' => 'error',
    'message' => $e->getMessage()
  ]);
} finally {
  if (isset($stmt)) {
    $stmt->close();
  }
  $conn->close();
}
?>
