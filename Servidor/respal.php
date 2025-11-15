<?php
// Database backup script adapted to main_view
require_once __DIR__ . '/config/conexion.php';

// We need credentials for mysqldump; read them from config.ini
$configFile = __DIR__ . '/config/config.ini';
if (!file_exists($configFile)) {
    echo json_encode(['success' => false, 'message' => 'Configuration file not found']);
    exit;
}

$config = parse_ini_file($configFile, true);
if ($config === false || !isset($config['database'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid configuration']);
    exit;
}

$db = $config['database'];
$db_host = $db['servidor'] ?? '127.0.0.1';
$db_user = $db['usuario'] ?? 'root';
$db_pass = $db['pass'] ?? '';
$db_name = $db['bbdd'] ?? '';

// backup directory
$backup_dir = __DIR__ . '/backups/';
if (!is_dir($backup_dir)) {
    if (!mkdir($backup_dir, 0777, true)) {
        echo json_encode(['success' => false, 'message' => 'Could not create backup directory']);
        exit;
    }
}

$filename = 'backup_' . date('Ymd_His') . '.sql';
$filepath = $backup_dir . $filename;

// Use mysqldump from PATH; you can hardcode a path if needed.
$mysqldump_cmd = 'mysqldump';

$passPart = '';
if ($db_pass !== '') {
    // On many shells, --password=pass is accepted; escapeshellarg to be safe
    $passPart = ' --password=' . escapeshellarg($db_pass);
}

$hostPart = ' --host=' . escapeshellarg($db_host);
$userPart = ' --user=' . escapeshellarg($db_user);

$command = "$mysqldump_cmd $userPart $passPart $hostPart " . escapeshellarg($db_name) . " > " . escapeshellarg($filepath);

$output = [];
$return_var = 0;
exec($command, $output, $return_var);

if ($return_var === 0) {
    echo json_encode(['success' => true, 'message' => 'Backup created: ' . $filename]);
} else {
    error_log("Backup error: " . implode("\n", $output));
    echo json_encode(['success' => false, 'message' => 'Backup failed', 'code' => $return_var, 'output' => $output]);
}

?>
