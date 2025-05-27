<!-- //                file rtc-sync.php               
// ===============================================
//        Original Author: Romain Provencel       
// ===============================================

// COMMIT HISTORY:
// ============================================================
// 2025-04-02 - changed folder name removed the space - fateh kabbani
//   {raspberry pi => raspberry_pi}/web/backend/php/api/rtc-sync.php | 0
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-27 - Add manual time setting feature with input validation and UI enhancements - Romain Provencel
//   raspberry pi/web/backend/php/api/rtc-sync.php | 45 +++++++++++++++++++++++++--
//   1 file changed, 42 insertions(+), 3 deletions(-)
//
// 2025-03-27 - Add manual time update feature and improve time synchronization UI - Romain Provencel
//   raspberry pi/web/backend/php/api/rtc-sync.php | 3 +--
//   1 file changed, 1 insertion(+), 2 deletions(-)
//
// 2025-03-27 - . - Romain Provencel
//   raspberry pi/web/backend/php/api/rtc-sync.php | 2 +-
//   1 file changed, 1 insertion(+), 1 deletion(-)
//
// 2025-03-27 - get time - Romain Provencel
//   raspberry pi/web/backend/php/api/rtc-sync.php | 9 ++++++++-
//   1 file changed, 8 insertions(+), 1 deletion(-)
//
// 2025-03-26 - Implement time management feature with RTC synchronization and UI enhancements - Romain Provencel
//   raspberry pi/web/backend/php/api/rtc-sync.php | 6 ++++++
//   1 file changed, 6 insertions(+)
//
// 2025-03-26 - Remove obsolete GPIO and time settings scripts; add new RTC synchronization API for improved time management. - Romain Provencel
//   raspberry pi/web/backend/php/api/rtc-sync.php | 75 +++++++++++++++++++++++++++
//   1 file changed, 75 insertions(+)
//
// ============================================================ -->

<?php
header('Content-Type: application/json');

// Check if the script is executed as root
if (posix_getuid() != 0) {
    echo json_encode(['error' => 'Root privileges required']);
    exit;
}

// Function to execute shell commands
function execCommand($cmd) {
    exec($cmd, $output, $return_var);
    return [
        'output' => implode("\n", $output),
        'success' => ($return_var === 0)
    ];
}

$action = $_POST['action'] ?? '';
$allowedActions = ['sync_system', 'sync_rtc', 'get_time', 'set_manual'];

if (!in_array($action, $allowedActions)) {
    echo json_encode(['error' => 'Action not allowed']);
    exit;
}

// Check if the RTC module is detected
$rtcCheck = execCommand('sudo hwclock -r');
if (!$rtcCheck['success']) {
    echo json_encode(['error' => 'RTC module not detected']);
    exit;
}

switch ($action) {
    case 'sync_system':
        $result = execCommand('sudo hwclock -s');
        if ($result['success']) {
            $newTime = execCommand('date');
            echo json_encode([
                'success' => true,
                'message' => 'System synchronized with RTC',
                'new_time' => $newTime['output']
            ]);
        } else {
            echo json_encode([
                'error' => 'Synchronization error',
                'details' => $result['output']
            ]);
        }
        break;
        
    case 'sync_rtc':
        $result = execCommand('sudo hwclock -w');
        if ($result['success']) {
            $newRtcTime = execCommand('sudo hwclock -r');
            echo json_encode([
                'success' => true,
                'message' => 'RTC synchronized with system',
                'new_rtc_time' => $newRtcTime['output']
            ]);
        } else {
            echo json_encode([
                'error' => 'Synchronization error',
                'details' => $result['output']
            ]);
        }
        break;

    case 'set_manual':
        $manualTime = $_POST['datetime'] ?? '';
        
        if (empty($manualTime)) {
            echo json_encode(['error' => 'Manual time is required']);
            exit;
        }
        
        $dateRegex = '/^\d{2}-\d{2}-\d{4} \d{2}:\d{2}:\d{2}$/';
        if (!preg_match($dateRegex, $manualTime)) {
            echo json_encode(['error' => 'Invalid date format. Expected JJ-MM-AAAA HH:MM:SS']);
            exit;
        }
        
        $manualTimeFormatted = DateTime::createFromFormat('d-m-Y H:i:s', $manualTime);
        if ($manualTimeFormatted === false) {
            echo json_encode(['error' => 'Error parsing manual time']);
            exit;
        }
        
        $manualTimeFormatted = $manualTimeFormatted->format('Y-m-d H:i:s');
        $result = execCommand("sudo date --set=\"$manualTimeFormatted\"");
        
        if ($result['success']) {
            // Synchronize system time to RTC
            $syncRtcResult = execCommand("sudo hwclock -w");

            echo json_encode([
                'success' => true,
                'message' => 'Manual time set successfully',
                'new_time' => $manualTimeFormatted
            ]);
        } else {
            echo json_encode([
                'error' => 'Failed to set manual time',
                'details' => $result['output']
            ]);
        }
        break;
        
    case 'get_time':
        $systemTime = execCommand('date');
        $rtcTime = execCommand('sudo hwclock -r');
        
        echo json_encode([
            'system_time' => $systemTime['output'],
            'rtc_time' => $rtcTime['success'] ? $rtcTime['output'] : 'Error reading RTC time'
        ]);
        break;
        
    default:
        $systemTime = execCommand('date');
        echo json_encode([
            'system_time' => $systemTime['output'],
            'rtc_time' => $rtcCheck['output']
        ]);
        break;
}
?>
