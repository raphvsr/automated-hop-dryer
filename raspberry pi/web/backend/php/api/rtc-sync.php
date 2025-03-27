<?php
// api/rtc_sync.php - Web adapted version

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
$allowedActions = ['sync_system', 'sync_rtc', 'get_time'];

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