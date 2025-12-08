<?php
$step = $_GET['step'] ?? 1; // Paso 1: pedir user_id. Paso 2: pedir token.
$status = $_GET['status'] ?? '';
$msg = $_GET['msg'] ?? '';
$user_id = $_GET['user_id'] ?? '';

// Redirigir a index.html y activar el modal de recuperación
$redirect_params = "recovery_step={$step}";
if (!empty($user_id)) $redirect_params .= "&user_id=" . urlencode($user_id);
if (!empty($status)) $redirect_params .= "&status=" . urlencode($status);
if (!empty($msg)) $redirect_params .= "&msg=" . urlencode($msg);

header("Location: ../index.html?{$redirect_params}");
exit();
?>