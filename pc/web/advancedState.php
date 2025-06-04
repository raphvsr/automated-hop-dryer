<?php
// Additional statistics functions for comprehensive hop drying analysis
include_once 'backend/database.php';
function getAdvancedStatistics($conn, $variety = null)
{
  $stats = [];

  // Efficiency Statistics
  $efficiencySql = "
        SELECT
            e.variety_name,
            COUNT(DISTINCT dc.id) as total_cycles,
            ROUND(AVG(dc.total_burner_minutes * 100.0 / dc.total_cycle_minutes), 2) as avg_burner_efficiency,
            ROUND(MIN(dc.total_burner_minutes * 100.0 / dc.total_cycle_minutes), 2) as min_burner_efficiency,
            ROUND(MAX(dc.total_burner_minutes * 100.0 / dc.total_cycle_minutes), 2) as max_burner_efficiency,
            ROUND(AVG(dc.total_cycle_minutes / 60.0), 2) as avg_total_time_hours,
            ROUND(AVG(dc.total_burner_minutes / 60.0), 2) as avg_burner_time_hours
        FROM drying_cycles dc
        JOIN etages e ON e.cycle_id = dc.id
        WHERE dc.total_cycle_minutes > 0";

  if ($variety) {
    $efficiencySql .= " AND e.variety_name = ?";
    $efficiencySql .= " GROUP BY e.variety_name";
    $stmt = $conn->prepare($efficiencySql);
    $stmt->bind_param('s', $variety);
  } else {
    $efficiencySql .= " GROUP BY e.variety_name ORDER BY avg_burner_efficiency DESC";
    $stmt = $conn->prepare($efficiencySql);
  }

  $stmt->execute();
  $result = $stmt->get_result();
  $stats['efficiency_stats'] = [];
  while ($row = $result->fetch_assoc()) {
    $stats['efficiency_stats'][] = $row;
  }

  return $stats;
}

function getBurnerAnalysis($conn, $variety = null)
{
  $stats = [];

  // Burner State Analysis
  $burnerSql = "
        SELECT
            bs.cycle_id,
            dc.campaign_id,
            e.variety_name,
            COUNT(*) as total_states,
            SUM(CASE WHEN bs.burner_state = 'on' THEN 1 ELSE 0 END) as on_states,
            SUM(CASE WHEN bs.burner_state = 'off' THEN 1 ELSE 0 END) as off_states,
            ROUND(SUM(CASE WHEN bs.burner_state = 'on' THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 2) as on_percentage,
            COUNT(DISTINCT CASE WHEN bs.etage1 = 1 THEN bs.id END) as etage1_usage,
            COUNT(DISTINCT CASE WHEN bs.etage2 = 1 THEN bs.id END) as etage2_usage,
            COUNT(DISTINCT CASE WHEN bs.etage3 = 1 THEN bs.id END) as etage3_usage,
            COUNT(DISTINCT CASE WHEN bs.etage4 = 1 THEN bs.id END) as etage4_usage
        FROM burner_states bs
        JOIN drying_cycles dc ON dc.id = bs.cycle_id
        JOIN etages e ON e.cycle_id = dc.id
        WHERE 1=1";

  if ($variety) {
    $burnerSql .= " AND e.variety_name = ?";
    $burnerSql .= " GROUP BY bs.cycle_id, dc.campaign_id, e.variety_name";
    $stmt = $conn->prepare($burnerSql);
    $stmt->bind_param('s', $variety);
  } else {
    $burnerSql .= " GROUP BY bs.cycle_id, dc.campaign_id, e.variety_name ORDER BY e.variety_name";
    $stmt = $conn->prepare($burnerSql);
  }

  $stmt->execute();
  $result = $stmt->get_result();
  $stats['burner_analysis'] = [];
  while ($row = $result->fetch_assoc()) {
    $stats['burner_analysis'][] = $row;
  }

  return $stats;
}



function getPerformanceMetrics($conn, $variety = null)
{
  $stats = [];

  // Performance comparison across campaigns
  $performanceSql = "
        SELECT
            c.id as campaign_id,
            c.start_date,
            c.end_date,
            c.total_cycles as campaign_total_cycles,
            e.variety_name,
            COUNT(DISTINCT dc.id) as actual_cycles,
            ROUND(AVG(dc.total_cycle_minutes / 60.0), 2) as avg_cycle_duration_hours,
            ROUND(AVG(dc.total_burner_minutes / 60.0), 2) as avg_burner_duration_hours,
            ROUND(MIN(dc.total_cycle_minutes / 60.0), 2) as min_cycle_duration_hours,
            ROUND(MAX(dc.total_cycle_minutes / 60.0), 2) as max_cycle_duration_hours,
            ROUND(STDDEV(dc.total_cycle_minutes / 60.0), 2) as cycle_duration_stddev
        FROM campaigns c
        JOIN drying_cycles dc ON dc.campaign_id = c.id
        JOIN etages e ON e.cycle_id = dc.id
        WHERE 1=1";

  if ($variety) {
    $performanceSql .= " AND e.variety_name = ?";
    $performanceSql .= " GROUP BY c.id, c.start_date, c.end_date, c.total_cycles, e.variety_name";
    $performanceSql .= " ORDER BY c.start_date DESC";
    $stmt = $conn->prepare($performanceSql);
    $stmt->bind_param('s', $variety);
  } else {
    $performanceSql .= " GROUP BY c.id, c.start_date, c.end_date, c.total_cycles, e.variety_name";
    $performanceSql .= " ORDER BY c.start_date DESC, e.variety_name";
    $stmt = $conn->prepare($performanceSql);
  }

  $stmt->execute();
  $result = $stmt->get_result();
  $stats['performance_metrics'] = [];
  while ($row = $result->fetch_assoc()) {
    $stats['performance_metrics'][] = $row;
  }

  return $stats;
}

function getFloorUtilization($conn, $variety = null)
{
  $stats = [];

  // Floor utilization analysis
  $floorSql = "
        SELECT
            variety_name,
            floor_number,
            COUNT(*) as usage_count,
            ROUND(AVG(floor_duration_min / 60.0), 2) as avg_duration_hours,
            ROUND(MIN(floor_duration_min / 60.0), 2) as min_duration_hours,
            ROUND(MAX(floor_duration_min / 60.0), 2) as max_duration_hours,
            ROUND(AVG(CAST(SUBSTRING(floor_burner_duration_min, 1,
                LOCATE(':', floor_burner_duration_min) - 1) AS UNSIGNED) * 60 +
                CAST(SUBSTRING(floor_burner_duration_min,
                LOCATE(':', floor_burner_duration_min) + 1) AS UNSIGNED)), 2) as avg_burner_minutes
        FROM etages
        WHERE 1=1";

  if ($variety) {
    $floorSql .= " AND variety_name = ?";
    $floorSql .= " GROUP BY variety_name, floor_number ORDER BY floor_number";
    $stmt = $conn->prepare($floorSql);
    $stmt->bind_param('s', $variety);
  } else {
    $floorSql .= " GROUP BY variety_name, floor_number ORDER BY variety_name, floor_number";
    $stmt = $conn->prepare($floorSql);
  }

  $stmt->execute();
  $result = $stmt->get_result();
  $stats['floor_utilization'] = [];
  while ($row = $result->fetch_assoc()) {
    $stats['floor_utilization'][] = $row;
  }

  return $stats;
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

  // Get campaign statistics
  $campaignSql = "
    SELECT
      COUNT(DISTINCT c.id) as total_campaigns,
      MIN(c.start_date) as first_campaign,
      MAX(c.end_date) as last_campaign,
      ROUND(AVG(c.total_cycles), 2) as avg_cycles_per_campaign
    FROM campaigns c
    JOIN drying_cycles dc ON dc.campaign_id = c.id
    JOIN etages e ON e.cycle_id = dc.id
    WHERE 1=1";

  if ($variety) {
    $campaignSql .= " AND e.variety_name = ?";
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
function renderSummaryCards($stats, $selectedVariety)
{
  $totalRecords = array_sum($stats['basic']['table_counts']);
  $dryingCycles = $stats['basic']['table_counts']['Drying Cycles'] ?? 0;
  $campaigns = $stats['basic']['table_counts']['Campaigns'] ?? 0;
  $floorRecords = $stats['basic']['table_counts']['Etages'] ?? 0;
  $burnerStates = $stats['basic']['table_counts']['Burner States'] ?? 0;

  echo '<div class="summary-cards">';

  if ($selectedVariety) {
    echo '<div class="summary-card">
            <h4>Total Records for ' . htmlspecialchars($selectedVariety) . '</h4>
            <div class="value">' . $totalRecords . '</div>
          </div>';
  } else {
    echo '<div class="summary-card">
            <h4>Total Records</h4>
            <div class="value">' . $totalRecords . '</div>
          </div>';
  }

  echo '<div class="summary-card">
          <h4>Drying Cycles</h4>
          <div class="value">' . $dryingCycles . '</div>
        </div>
        <div class="summary-card">
          <h4>Campaigns</h4>
          <div class="value">' . $campaigns . '</div>
        </div>
        <div class="summary-card">
          <h4>Floor Records</h4>
          <div class="value">' . $floorRecords . '</div>
        </div>';

  if ($burnerStates > 0) {
    echo '<div class="summary-card">
            <h4>Burner State Records</h4>
            <div class="value">' . $burnerStates . '</div>
          </div>';
  }

  echo '</div>';
}

function getQualityMetrics($conn, $variety = null)
{
  $stats = [];

  // Quality and consistency metrics
  $qualitySql = "
        SELECT
            e.variety_name,
            COUNT(DISTINCT dc.id) as total_cycles,
            ROUND(AVG(dc.total_cycle_minutes / 60.0), 2) as avg_total_hours,
            ROUND(STDDEV(dc.total_cycle_minutes / 60.0), 2) as time_consistency,
            ROUND(AVG(dc.total_burner_minutes * 100.0 / dc.total_cycle_minutes), 2) as avg_burner_efficiency,
            ROUND(STDDEV(dc.total_burner_minutes * 100.0 / dc.total_cycle_minutes), 2) as efficiency_consistency,
            CASE
                WHEN STDDEV(dc.total_cycle_minutes / 60.0) < 1 THEN 'Excellent'
                WHEN STDDEV(dc.total_cycle_minutes / 60.0) < 2 THEN 'Good'
                WHEN STDDEV(dc.total_cycle_minutes / 60.0) < 3 THEN 'Fair'
                ELSE 'Needs Improvement'
            END as consistency_rating
        FROM drying_cycles dc
        JOIN etages e ON e.cycle_id = dc.id
        WHERE dc.total_cycle_minutes > 0";

  if ($variety) {
    $qualitySql .= " AND e.variety_name = ?";
    $qualitySql .= " GROUP BY e.variety_name";
    $stmt = $conn->prepare($qualitySql);
    $stmt->bind_param('s', $variety);
  } else {
    $qualitySql .= " GROUP BY e.variety_name ORDER BY time_consistency ASC";
    $stmt = $conn->prepare($qualitySql);
  }

  $stmt->execute();
  $result = $stmt->get_result();
  $stats['quality_metrics'] = [];
  while ($row = $result->fetch_assoc()) {
    $stats['quality_metrics'][] = $row;
  }

  return $stats;
}

// Main function to get all statistics
function getAllStatistics($conn, $variety = null)
{
  $allStats = [];

  // Get basic statistics
  $allStats['basic'] = getStatistics($conn, $variety);

  // Get advanced statistics
  $allStats['advanced'] = getAdvancedStatistics($conn, $variety);

  // Get burner analysis
  $allStats['burner'] = getBurnerAnalysis($conn, $variety);

  // Get seasonal trends

  // Get performance metrics
  $allStats['performance'] = getPerformanceMetrics($conn, $variety);

  // Get floor utilization
  $allStats['floor'] = getFloorUtilization($conn, $variety);

  // Get quality metrics
  $allStats['quality'] = getQualityMetrics($conn, $variety);

  return $allStats;
}

// Helper function to get all varieties for dropdown
function getAllVarieties($conn)
{
  $sql = "SELECT DISTINCT variety_name FROM etages ORDER BY variety_name";
  $result = $conn->query($sql);
  $varieties = [];
  while ($row = $result->fetch_assoc()) {
    $varieties[] = $row['variety_name'];
  }
  return $varieties;
}

// Example usage and HTML display
if (isset($_GET['variety']) && !empty($_GET['variety'])) {
  $selectedVariety = $_GET['variety'];
  $stats = getAllStatistics($conn, $selectedVariety);
} else {
  $selectedVariety = null;
  $stats = getAllStatistics($conn);
}
function getVarietySummaryStats($conn, $variety = null)
{
  $stats = [];

  if ($variety) {
    // Get specific stats for the selected variety
    $sql = "
      SELECT
        e.variety_name,
        COUNT(DISTINCT dc.id) as total_cycles,
        COUNT(DISTINCT dc.campaign_id) as campaigns_involved,
        COUNT(DISTINCT e.id) as total_floor_records,
        MIN(dc.cycle_date) as earliest_cycle,
        MAX(dc.cycle_date) as latest_cycle,
        ROUND(AVG(dc.total_cycle_minutes / 60.0), 2) as avg_cycle_hours,
        ROUND(AVG(dc.total_burner_minutes / 60.0), 2) as avg_burner_hours,
        ROUND(AVG(dc.total_burner_minutes * 100.0 / NULLIF(dc.total_cycle_minutes, 0)), 2) as avg_efficiency
      FROM etages e
      JOIN drying_cycles dc ON dc.id = e.cycle_id
      WHERE e.variety_name = ?
      GROUP BY e.variety_name";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $variety);
    $stmt->execute();
    $result = $stmt->get_result();
    $stats = $result->fetch_assoc();

    // Get burner state count for this variety
    $burnerSql = "
      SELECT COUNT(DISTINCT bs.id) as burner_state_count
      FROM burner_states bs
      JOIN drying_cycles dc ON dc.id = bs.cycle_id
      JOIN etages e ON e.cycle_id = dc.id
      WHERE e.variety_name = ?";

    $stmt = $conn->prepare($burnerSql);
    $stmt->bind_param('s', $variety);
    $stmt->execute();
    $result = $stmt->get_result();
    $burnerData = $result->fetch_assoc();
    $stats['burner_state_count'] = $burnerData['burner_state_count'];

  } else {
    // Get overall stats
    $sql = "
      SELECT
        COUNT(DISTINCT dc.id) as total_cycles,
        COUNT(DISTINCT dc.campaign_id) as campaigns_involved,
        COUNT(DISTINCT e.id) as total_floor_records,
        COUNT(DISTINCT e.variety_name) as total_varieties,
        MIN(dc.cycle_date) as earliest_cycle,
        MAX(dc.cycle_date) as latest_cycle,
        ROUND(AVG(dc.total_cycle_minutes / 60.0), 2) as avg_cycle_hours,
        ROUND(AVG(dc.total_burner_minutes / 60.0), 2) as avg_burner_hours,
        ROUND(AVG(dc.total_burner_minutes * 100.0 / NULLIF(dc.total_cycle_minutes, 0)), 2) as avg_efficiency
      FROM etages e
      JOIN drying_cycles dc ON dc.id = e.cycle_id";

    $result = $conn->query($sql);
    $stats = $result->fetch_assoc();

    // Get total burner state count
    $burnerSql = "SELECT COUNT(*) as burner_state_count FROM burner_states";
    $result = $conn->query($burnerSql);
    $burnerData = $result->fetch_assoc();
    $stats['burner_state_count'] = $burnerData['burner_state_count'];
  }

  return $stats;
}
$varieties = getAllVarieties($conn);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Statistiques de Séchage du Houblon</title>
  <link rel="stylesheet" href="src/css/advanced-states.css">
</head>

<body>
  <div class="container">
    <div class="header">
      <h1>🌿 Tableau de Bord des Statistiques de Séchage du Houblon</h1>
      <p>Analyse complète des opérations de séchage du houblon et des indicateurs de performance</p>
    </div>

    <div class="variety-selector">
      <form method="GET">
        <label for="variety"><strong>Filtrer par Variété de Houblon :</strong></label>
        <select name="variety" id="variety" onchange="this.form.submit()">
          <option value="">Toutes les Variétés</option>
          <?php foreach ($varieties as $variety): ?>
            <option value="<?= htmlspecialchars($variety) ?>" <?= $selectedVariety === $variety ? 'selected' : '' ?>>
              <?= htmlspecialchars($variety) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </form>
    </div>

    <?php if ($selectedVariety): ?>
      <div class="section-header">
        <h2>Statistiques pour : <?= htmlspecialchars($selectedVariety) ?></h2>
      </div>
    <?php endif; ?>

    <!-- Summary Cards -->
    <div class="summary-cards">
      <?php
      $summaryStats = getVarietySummaryStats($conn, $selectedVariety);
      if ($selectedVariety): ?>
        <div class="summary-card">
          <h4>Cycles pour <?= htmlspecialchars($selectedVariety) ?></h4>
          <div class="value"><?= $summaryStats['total_cycles'] ?? 0 ?></div>
        </div>
        <div class="summary-card">
          <h4>Campagnes</h4>
          <div class="value"><?= $summaryStats['campaigns_involved'] ?? 0 ?></div>
        </div>
        <div class="summary-card">
          <h4>Enregistrements d'Étage</h4>
          <div class="value"><?= $summaryStats['total_floor_records'] ?? 0 ?></div>
        </div>
        <div class="summary-card">
          <h4>Efficacité Moyenne</h4>
          <div class="value"><?= $summaryStats['avg_efficiency'] ?? 0 ?>%</div>
        </div>
      <?php else: ?>
        <div class="summary-card">
          <h4>Total des Cycles</h4>
          <div class="value"><?= $summaryStats['total_cycles'] ?? 0 ?></div>
        </div>
        <div class="summary-card">
          <h4>Total des Campagnes</h4>
          <div class="value"><?= $summaryStats['campaigns_involved'] ?? 0 ?></div>
        </div>
        <div class="summary-card">
          <h4>Variétés de Houblon</h4>
          <div class="value"><?= $summaryStats['total_varieties'] ?? 0 ?></div>
        </div>
        <div class="summary-card">
          <h4>Enregistrements d'Étage</h4>
          <div class="value"><?= $summaryStats['total_floor_records'] ?? 0 ?></div>
        </div>
      <?php endif; ?>
    </div>

    <!-- Efficiency Statistics -->
    <div class="section-header">
      <h2>⚡ Analyse d'Efficacité</h2>
    </div>

    <div class="table-responsive">
      <table>
        <thead>
          <tr>
            <th>Variété</th>
            <th>Total des Cycles</th>
            <th>Efficacité Moyenne (%)</th>
            <th>Efficacité Minimale (%)</th>
            <th>Efficacité Maximale (%)</th>
            <th>Temps Total Moyen (h)</th>
            <th>Temps Moyen du Brûleur (h)</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($stats['advanced']['efficiency_stats'] as $efficiency): ?>
            <tr>
              <td><strong><?= htmlspecialchars($efficiency['variety_name']) ?></strong></td>
              <td><?= $efficiency['total_cycles'] ?></td>
              <td><?= $efficiency['avg_burner_efficiency'] ?>%</td>
              <td><?= $efficiency['min_burner_efficiency'] ?>%</td>
              <td><?= $efficiency['max_burner_efficiency'] ?>%</td>
              <td><?= $efficiency['avg_total_time_hours'] ?></td>
              <td><?= $efficiency['avg_burner_time_hours'] ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Quality Metrics -->
    <div class="section-header">
      <h2>🎯 Métriques de Qualité et Cohérence</h2>
    </div>

    <div class="table-responsive">
      <table>
        <thead>
          <tr>
            <th>Variété</th>
            <th>Total des Cycles</th>
            <th>Durée Moyenne (h)</th>
            <th>Cohérence Temporelle</th>
            <th>Efficacité Moyenne (%)</th>
            <th>Cohérence d'Efficacité</th>
            <th>Évaluation</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($stats['quality']['quality_metrics'] as $quality): ?>
            <tr>
              <td><strong><?= htmlspecialchars($quality['variety_name']) ?></strong></td>
              <td><?= $quality['total_cycles'] ?></td>
              <td><?= $quality['avg_total_hours'] ?></td>
              <td><?= $quality['time_consistency'] ?? 'N/A' ?></td>
              <td><?= $quality['avg_burner_efficiency'] ?>%</td>
              <td><?= $quality['efficiency_consistency'] ?? 'N/A' ?></td>
              <td class="rating-<?= strtolower(str_replace(' ', '-', $quality['consistency_rating'])) ?>">
                <?= $quality['consistency_rating'] ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <div class="section-header">
      <h2>🏢 Analyse d'Utilisation des Étages</h2>
    </div>

    <div class="table-responsive">
      <table>
        <thead>
          <tr>
            <th>Variété</th>
            <th>Numéro d'Étage</th>
            <th>Nombre d'Utilisations</th>
            <th>Durée Moyenne (h)</th>
            <th>Durée Minimale (h)</th>
            <th>Durée Maximale (h)</th>
            <th>Temps Moyen du Brûleur (min)</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($stats['floor']['floor_utilization'] as $floor): ?>
            <tr>
              <td><strong><?= htmlspecialchars($floor['variety_name']) ?></strong></td>
              <td>Étage <?= $floor['floor_number'] ?></td>
              <td><?= $floor['usage_count'] ?></td>
              <td><?= $floor['avg_duration_hours'] ?></td>
              <td><?= $floor['min_duration_hours'] ?></td>
              <td><?= $floor['max_duration_hours'] ?></td>
              <td><?= $floor['avg_burner_minutes'] ?? 'N/A' ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Performance Metrics -->
    <div class="section-header">
      <h2>📈 Indicateurs de Performance des Campagnes</h2>
    </div>

    <div class="table-responsive">
      <table>
        <thead>
          <tr>
            <th>ID Campagne</th>
            <th>Variété</th>
            <th>Date de Début</th>
            <th>Date de Fin</th>
            <th>Cycles Planifiés</th>
            <th>Cycles Réels</th>
            <th>Durée Moyenne des Cycles (h)</th>
            <th>Durée Moyenne du Brûleur (h)</th>
            <th>Écart-Type de Durée</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($stats['performance']['performance_metrics'] as $performance): ?>
            <tr>
              <td><?= $performance['campaign_id'] ?></td>
              <td><strong><?= htmlspecialchars($performance['variety_name']) ?></strong></td>
              <td><?= $performance['start_date'] ?></td>
              <td><?= $performance['end_date'] ?></td>
              <td><?= $performance['campaign_total_cycles'] ?></td>
              <td><?= $performance['actual_cycles'] ?></td>
              <td><?= $performance['avg_cycle_duration_hours'] ?></td>
              <td><?= $performance['avg_burner_duration_hours'] ?></td>
              <td><?= $performance['cycle_duration_stddev'] ?? 'N/A' ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>


  </div>
</body>

</html>
