<?php
include_once 'database.php';

$response = ['status' => 'success', 'data' => []];

$sql = "SELECT DISTINCT variety_name FROM etages";
$stmt = $conn->prepare($sql);

if ($stmt->execute()) {
  $result = $stmt->get_result();
  $response['data'] = $result->fetch_all(MYSQLI_ASSOC);
} else {
  $response['status'] = 'error';
  $response['message'] = 'Query execution failed';
}

echo json_encode($response);
$stmt->close();
$conn->close();
