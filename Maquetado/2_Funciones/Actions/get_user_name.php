<?php
require_once __DIR__ . '/../Servidor/db.php'; // Conexión a la BD y sesión

// Solo para usuarios autenticados (preferiblemente admin)
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit();
}

header('Content-Type: application/json');

$userId = $_GET['id'] ?? '';

if (empty($userId)) {
    echo json_encode(['status' => 'error', 'message' => 'ID de usuario no proporcionado.']);
    exit();
}

$stmt = $conn->prepare("SELECT nombres, apa, ama FROM users WHERE id = ?");
$stmt->bind_param("s", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    $nombreCompleto = trim($user['nombres'] . ' ' . $user['apa'] . ' ' . $user['ama']);
    echo json_encode(['status' => 'success', 'name' => $nombreCompleto]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado.']);
}

$stmt->close();
$conn->close();
?>