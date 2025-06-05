<?php
require_once 'database.php';

// Set proper content type
header('Content-Type: application/json');

try {
  // Get filter parameters
  $variety = isset($_GET['variety']) ? $_GET['variety'] : null;
  $startDate = isset($_GET['startDate']) ? $_GET['startDate'] : null;
  $endDate = isset($_GET['endDate']) ? $_GET['endDate'] : null;
  $campaignId = isset($_GET['campaign_id']) ? (int) $_GET['campaign_id'] : null;
  $chartType = isset($_GET['chart_type']) ? $_GET['chart_type'] : 'historique';
  $etage = isset($_GET['etage']) ? $_GET['etage'] : null;
  $response = ['status' => 'success', 'data' => []];
  switch ($chartType) {
    case 'historique_1er_chargement':
      $response['data'] = getFirstChargment($conn, $variety, $startDate, $endDate, $etage, $campaignId);
      break;
    case 'graph_timeline':
      if (!$variety) {
        throw new Exception("La variété est requise pour le graphique de la timeline.");
      }
      $response['data'] = graphTimeLine($conn, $variety, $startDate, $endDate, $etage);
      break;


    case 'statistics':
      $response['data'] = getStatistics($conn, $variety);
      break;

    default:
      $response['data'] = getStatistics($conn, $variety);
      break;
  }

  echo json_encode($response);

} catch (Exception $e) {
  http_response_code(500);
  echo json_encode([
    'status' => 'error',
    'message' => $e->getMessage()
  ]);
} finally {
  $conn->close();
}

// Chart 1: Historical comparison for first basket (Page 11)
function getFirstChargment($conn, $variety, $startDate, $endDate, $etage, $campaignId)
{
  $sql = "SELECT
        dc.campaign_id,
        dc.cycle_date,
        ROUND(AVG(dc.total_cycle_minutes) / 60.0, 2) AS total_cycle_hours,
        ROUND(AVG(dc.total_burner_minutes) / 60.0, 2) AS total_burner_hours
    FROM drying_cycles dc
    JOIN etages e ON e.cycle_id = dc.id
    WHERE dc.cycle_status IS NOT NULL";

  $params = array();
  $paramTypes = '';

  // Add variety filter (required for this query logic)
  if ($variety) {
    $sql .= " AND e.variety_name = ?";
    $params[] = $variety;
    $paramTypes .= 's';
  }

  // Add date filters
  if ($startDate) {
    $sql .= " AND dc.cycle_date >= ?";
    $params[] = $startDate;
    $paramTypes .= 's';
  }

  if ($endDate) {
    $sql .= " AND dc.cycle_date <= ?";
    $params[] = $endDate;
    $paramTypes .= 's';
  }

  // Add the subquery to get only the first cycle with the specified variety for each campaign
  $sql .= " AND dc.id = (
        SELECT dc2.id
        FROM drying_cycles dc2
        JOIN etages e2 ON e2.cycle_id = dc2.id
        WHERE dc2.campaign_id = dc.campaign_id
          AND dc2.cycle_status IS NOT NULL";

  // Apply the same variety filter in the subquery
  if ($variety) {
    $sql .= " AND e2.variety_name = ?";
    $params[] = $variety;
    $paramTypes .= 's';
  }

  // Apply the same date filters in the subquery
  if ($startDate) {
    $sql .= " AND dc2.cycle_date >= ?";
    $params[] = $startDate;
    $paramTypes .= 's';
  }

  if ($endDate) {
    $sql .= " AND dc2.cycle_date <= ?";
    $params[] = $endDate;
    $paramTypes .= 's';
  }

  $sql .= " ORDER BY dc2.cycle_date ASC, dc2.cycle_start_time ASC
        LIMIT 1
    )
    GROUP BY dc.campaign_id, dc.cycle_date
    HAVING COUNT(e.floor_number) = 4
    ORDER BY dc.campaign_id, dc.cycle_date";

  try {
    $stmt = $conn->prepare($sql);

    if (!empty($params)) {
      $stmt->bind_param($paramTypes, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $labels = array();
    $dryingDuration = array();
    $burnerDuration = array();

    while ($row = $result->fetch_assoc()) {
      $label = date('d/m/y', strtotime($row['cycle_date'])) . ' - C' . $row['campaign_id'];

      $labels[] = $label;
      $dryingDuration[] = (float) $row['total_cycle_hours'];
      $burnerDuration[] = (float) $row['total_burner_hours'];
    }

    return array(
      'success' => true,
      'labels' => $labels,
      'dryingDuration' => $dryingDuration,
      'burnerDuration' => $burnerDuration
    );

  } catch (Exception $e) {
    return array(
      'success' => false,
      'error' => 'Database error: ' . $e->getMessage()
    );
  }
}



function graphTimeLine($conn, $variety, $startDate = null, $endDate = null, $etage = null)
{
  // 🔸 Etage changes (floor start and end)
  $floorSql = "
    WITH latest_cycle AS (
      SELECT MAX(dc.id) AS max_cycle_id
      FROM drying_cycles dc
      INNER JOIN etages e ON e.cycle_id = dc.id
      WHERE e.variety_name = ?";

  $params = [$variety];
  $types = "s";

  if ($startDate) {
    $floorSql .= " AND dc.cycle_date >= ?";
    $params[] = $startDate;
    $types .= "s";
  }

  if ($endDate) {
    $floorSql .= " AND dc.cycle_date <= ?";
    $params[] = $endDate;
    $types .= "s";
  }



  $floorSql .= ")
    SELECT
      e.floor_start_time AS timestamp,
      e.floor_number AS etage_number
    FROM etages e
    INNER JOIN latest_cycle lc ON e.cycle_id = lc.max_cycle_id
    WHERE e.variety_name = ?
      AND e.floor_number IS NOT NULL";

  $params[] = $variety;
  $types .= "s";



  $floorSql .= " ORDER BY timestamp";

  $floorChanges = executeQuery($conn, $floorSql, $params, $types);

  // 🔸 Burner state
  $burnerSql = "
    SELECT burner_state, temps_bruleur
    FROM burner_states bs
    INNER JOIN drying_cycles dr ON bs.cycle_id = dr.id
    INNER JOIN etages e ON dr.id = e.cycle_id
    WHERE e.variety_name = ?";

  $params = [$variety];
  $types = "s";

  if ($startDate) {
    $burnerSql .= " AND dr.cycle_date >= ?";
    $params[] = $startDate;
    $types .= "s";
  }

  if ($endDate) {
    $burnerSql .= " AND dr.cycle_date <= ?";
    $params[] = $endDate;
    $types .= "s";
  }



  $burnerSql .= " AND bs.cycle_id = (
      SELECT MAX(dc.id)
      FROM drying_cycles dc
      INNER JOIN etages e ON e.cycle_id = dc.id
      INNER JOIN burner_states bs ON bs.cycle_id = dc.id
      WHERE e.variety_name = ?";

  $params[] = $variety;
  $types .= "s";

  if ($startDate) {
    $burnerSql .= " AND dc.cycle_date >= ?";
    $params[] = $startDate;
    $types .= "s";
  }

  if ($endDate) {
    $burnerSql .= " AND dc.cycle_date <= ?";
    $params[] = $endDate;
    $types .= "s";
  }



  $burnerSql .= ")";

  $burnerStates = executeQuery($conn, $burnerSql, $params, $types);

  // 🔸 Format data for frontend
  $label = [];
  $burnerState = [];
  $etageChange = [];

  foreach ($burnerStates as $b) {
    $label[] = $b['temps_bruleur'];
    $burnerState[] = $b['burner_state'] === 'on' ? 1 : 0;
  }

  foreach ($floorChanges as $f) {
    if (!is_null($f['timestamp']) && !is_null($f['etage_number'])) {
      $timeOnly = date('H:i:s', strtotime($f['timestamp']));
      $etageChange[] = [
        'timestamp' => $timeOnly,
        'etage_number' => (int) $f['etage_number']
      ];
    }
  }

  return [
    'label' => $label,
    'burner_state' => $burnerState,
    'etage_change' => $etageChange
  ];
}


// Helper function to execute queries
function executeQuery($conn, $sql, $params = [], $types = "")
{
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

  $data = [];
  while ($row = $result->fetch_assoc()) {
    $data[] = $row;
  }

  if (isset($stmt)) {
    $stmt->close();
  }

  return $data;
}

function getStatistics($conn, $variety = null)
{
  $stats = [];

  // Get variety statistics
  $varietySql = "
    SELECT
      e.variety_name,
      COUNT(DISTINCT dc.id) as cycle_count,
      MIN(dc.cycle_date) as first_cycle,
      MAX(dc.cycle_date) as last_cycle,
      ROUND(AVG(dc.total_cycle_minutes) / 60.0, 2) as avg_cycle_hours,
      ROUND(AVG(dc.total_burner_minutes) / 60.0, 2) as avg_burner_hours
    FROM drying_cycles dc
    JOIN etages e ON e.cycle_id = dc.id
    WHERE 1=1";

  if ($variety) {
    $varietySql .= " AND e.variety_name = ?";
    $varietySql .= " GROUP BY e.variety_name";
    $stmt = $conn->prepare($varietySql);
    $stmt->bind_param('s', $variety);
  } else {
    $varietySql .= " GROUP BY e.variety_name";
    $stmt = $conn->prepare($varietySql);
  }

  $stmt->execute();
  $result = $stmt->get_result();
  $stats['variety_stats'] = $result->fetch_assoc();

  // Get floor statistics
  $floorSql = "
    SELECT
      variety_name,
      ROUND(AVG(CASE WHEN floor_number = '1' THEN floor_duration_min ELSE NULL END) / 60, 2) AS floor_1_avg,
      ROUND(AVG(CASE WHEN floor_number = '2' THEN floor_duration_min ELSE NULL END) / 60, 2) AS floor_2_avg,
      ROUND(AVG(CASE WHEN floor_number = '3' THEN floor_duration_min ELSE NULL END) / 60, 2) AS floor_3_avg,
      ROUND(AVG(CASE WHEN floor_number = '4' THEN floor_duration_min ELSE NULL END) / 60, 2) AS floor_4_avg
    FROM etages
    WHERE 1=1";

  if ($variety) {
    $floorSql .= " AND variety_name = ?";
    $floorSql .= " GROUP BY variety_name";
    $stmt = $conn->prepare($floorSql);
    $stmt->bind_param('s', $variety);
  } else {
    $floorSql .= " GROUP BY variety_name";
    $stmt = $conn->prepare($floorSql);
  }

  $stmt->execute();
  $result = $stmt->get_result();
  $stats['floor_stats'] = $result->fetch_assoc();

  // Get campaign statistics - REFACTORED without c.total_cycles
  $campaignSql = "
    SELECT
      COUNT(DISTINCT c.id) as total_campaigns,
      MIN(c.start_date) as first_campaign,
      MAX(c.end_date) as last_campaign,
      ROUND(AVG(campaign_cycles.cycle_count), 2) as avg_cycles_per_campaign
    FROM campaigns c
    JOIN (
      SELECT
        dc.campaign_id,
        COUNT(DISTINCT dc.id) as cycle_count
      FROM drying_cycles dc
      JOIN etages e ON e.cycle_id = dc.id
      WHERE 1=1";

  if ($variety) {
    $campaignSql .= " AND e.variety_name = ?";
  }

  $campaignSql .= "
      GROUP BY dc.campaign_id
    ) campaign_cycles ON campaign_cycles.campaign_id = c.id";

  if ($variety) {
    $stmt = $conn->prepare($campaignSql);
    $stmt->bind_param('s', $variety);
  } else {
    $stmt = $conn->prepare($campaignSql);
  }

  $stmt->execute();
  $result = $stmt->get_result();
  $stats['campaign_stats'] = $result->fetch_assoc();

  // Get table record counts (FIXED - now filters by variety)
  if ($variety) {
    // Filtered counts for specific variety
    $tableCountSql = "
      SELECT
        'Campaigns' as table_name,
        COUNT(DISTINCT c.id) as record_count
      FROM campaigns c
      JOIN drying_cycles dc ON dc.campaign_id = c.id
      JOIN etages e ON e.cycle_id = dc.id
      WHERE e.variety_name = ?

      UNION ALL

      SELECT
        'Drying Cycles' as table_name,
        COUNT(DISTINCT dc.id) as record_count
      FROM drying_cycles dc
      JOIN etages e ON e.cycle_id = dc.id
      WHERE e.variety_name = ?

      UNION ALL

      SELECT
        'Etages' as table_name,
        COUNT(*) as record_count
      FROM etages
      WHERE variety_name = ?

      UNION ALL

      SELECT
        'Burner States' as table_name,
        COUNT(DISTINCT bs.id) as record_count
      FROM burner_states bs
      JOIN drying_cycles dc ON dc.id = bs.cycle_id
      JOIN etages e ON e.cycle_id = dc.id
      WHERE e.variety_name = ?";

    $stmt = $conn->prepare($tableCountSql);
    $stmt->bind_param('ssss', $variety, $variety, $variety, $variety);
    $stmt->execute();
    $result = $stmt->get_result();
  } else {
    // Total counts for all varieties
    $tableCountSql = "
      SELECT
        'Campaigns' as table_name,
        COUNT(*) as record_count
      FROM
        campaigns
      UNION ALL
      SELECT
        'Drying Cycles' as table_name,
        COUNT(*) as record_count
      FROM
        drying_cycles
      UNION ALL
      SELECT
        'Etages' as table_name,
        COUNT(*) as record_count
      FROM
        etages
      UNION ALL
      SELECT
        'Burner States' as table_name,
        COUNT(*) as record_count
      FROM
        burner_states";

    $result = $conn->query($tableCountSql);
  }

  $tableCounts = [];
  while ($row = $result->fetch_assoc()) {
    $tableCounts[$row['table_name']] = $row['record_count'];
  }
  $stats['table_counts'] = $tableCounts;

  return $stats;
}
?>
