<?php
session_start();
include '../database.php';

// Create logs directory if it doesn't exist
$logDir = __DIR__ . '/logs';
if (!file_exists($logDir)) {
  mkdir($logDir, 0777, true);
}

// Log file path with date
$logFile = $logDir . '/login_debug_' . date('Y-m-d') . '.log';

// Function to write to log file
function writeLog($message, $logFile)
{
  $timestamp = date('Y-m-d H:i:s');
  $logMessage = "[$timestamp] $message" . PHP_EOL;
  file_put_contents($logFile, $logMessage, FILE_APPEND);
}

// Log initial request information
writeLog("=== New Login Request ===", $logFile);
writeLog("Request Method: " . $_SERVER['REQUEST_METHOD'], $logFile);
writeLog("User Agent: " . $_SERVER['HTTP_USER_AGENT'], $logFile);
writeLog("Remote IP: " . $_SERVER['REMOTE_ADDR'], $logFile);
writeLog("Request URI: " . $_SERVER['REQUEST_URI'], $logFile);
writeLog("Session ID: " . session_id(), $logFile);

// Log POST data (excluding password for security)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  writeLog("POST Data Received:", $logFile);
  writeLog("Username: " . (isset($_POST['username']) ? $_POST['username'] : 'NOT SET'), $logFile);
  writeLog("Password field: " . (isset($_POST['password']) ? 'SET (hidden)' : 'NOT SET'), $logFile);

  // Log all POST fields (for debugging form issues)
  writeLog("All POST fields: " . implode(', ', array_keys($_POST)), $logFile);
}

// Check if database connection exists
if (!isset($conn)) {
  writeLog("ERROR: Database connection not established!", $logFile);
  echo "Database connection error!";
  exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Check if required fields exist
  if (!isset($_POST['username']) || !isset($_POST['password'])) {
    writeLog("ERROR: Missing required fields!", $logFile);
    echo "Missing username or password!";
    exit;
  }

  $username = $_POST['username'];
  $password = $_POST['password'];

  writeLog("Processing login for username: $username", $logFile);

  try {
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
      writeLog("ERROR: Failed to prepare statement: " . $conn->error, $logFile);
      echo "Database error!";
      exit;
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    writeLog("Query executed. Rows found: " . $result->num_rows, $logFile);

    if ($result->num_rows > 0) {
      $user = $result->fetch_assoc();
      writeLog("User found. Role: " . $user['role'], $logFile);

      // Log password verification attempt
      $verifyResult = password_verify($password, $user['password_hash']);
      writeLog("Password verification result: " . ($verifyResult ? 'TRUE' : 'FALSE'), $logFile);

      if ($verifyResult) {
        $_SESSION['username'] = $username;
        $_SESSION['admin'] = $user['role'];

        // Log session data
        writeLog("Login successful! Session data set.", $logFile);
        writeLog("Session username: " . $_SESSION['username'], $logFile);
        writeLog("Session admin: " . $_SESSION['admin'], $logFile);

        echo "success";
      } else {
        writeLog("Login failed: Invalid password", $logFile);
        echo "Invalid password!";
      }
    } else {
      writeLog("Login failed: User not found", $logFile);
      echo "User not found!";
    }

    $stmt->close();

  } catch (Exception $e) {
    writeLog("ERROR: Exception occurred: " . $e->getMessage(), $logFile);
    echo "An error occurred!";
  }
} else {
  writeLog("Non-POST request received", $logFile);
}

// Log session status at end
writeLog("Final session status: " . (isset($_SESSION['username']) ? 'Active' : 'Not active'), $logFile);
writeLog("=== End of Request ===\n", $logFile);
?>
