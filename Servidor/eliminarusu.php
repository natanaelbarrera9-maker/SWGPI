<?php
// Secure delete with session check and logging
require_once __DIR__ . '/db.php';

// Ensure we're logged in and admin
if (!isset($_SESSION['user_id']) || ($_SESSION['perfil_id'] ?? 0) != 1) {
    header('Location: ../Cliente/index.html?error=unauthorized');
    exit();
}

$conn = $conn ?? null;
if (!$conn) {
    error_log("eliminarusu.php: DB connection missing");
    header('Location: ../Cliente/admin_view.php?status=error&msg=db_missing');
    exit();
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($id <= 0) {
    header('Location: ../Cliente/admin_view.php?status=error&msg=invalid_id');
    exit();
}

// Prepare delete
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
if (!$stmt) {
    $err = $conn->error;
    error_log("eliminarusu.php prepare failed: $err");
    header('Location: ../Cliente/admin_view.php?status=error&msg=prepare_failed');
    exit();
}

$stmt->bind_param('i', $id);
$ok = $stmt->execute();
$affected = $stmt->affected_rows;
$stmt->close();

// Log the deletion attempt
$logDir = __DIR__ . '/logs';
if (!is_dir($logDir)) @mkdir($logDir, 0755, true);
$logFile = $logDir . '/delete.log';
$now = date('Y-m-d H:i:s');
$adminId = $_SESSION['user_id'];
$entry = "$now | admin:$adminId | target_user_id:$id | result:" . ($ok ? "OK" : "FAIL") . " | affected:$affected\n";
@file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);

// Close connection
$conn->close();

// Redirect with status
if ($ok && $affected > 0) {
    header('Location: ../Cliente/admin_view.php?status=success&msg=usuario_eliminado');
    exit();
} else {
    header('Location: ../Cliente/admin_view.php?status=error&msg=eliminacion_fallida');
    exit();
}

?>
