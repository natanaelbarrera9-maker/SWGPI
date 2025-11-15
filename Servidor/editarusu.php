<?php
// Adapted from PI/Servidor/editarusu.php
// Updates a user in the main_view database schema (table `users`).
require_once __DIR__ . '/config/conexion.php';
$conexion = dbConectar();

$data = json_decode(file_get_contents('php://input'), true);

// Debug: save incoming payload
file_put_contents(__DIR__ . '/debug_actualizar.txt', print_r($data, true));

if ($data) {
    $id = intval($data['id']);
    $nombres = $data['nombre'] ?? '';
    $apa = $data['apa'] ?? '';
    $ama = $data['ama'] ?? '';
    // PI used both correo and telefono; main_view uses only telefonos
    $telefonos = $data['telefono'] ?? '';

    // Use main_view table/column names: users (id, nombres, apa, ama, telefonos)
    $sql = $conexion->prepare("UPDATE users SET nombres = ?, apa = ?, ama = ?, telefonos = ? WHERE id = ?");
    if (!$sql) {
        echo json_encode(['success' => false, 'error' => $conexion->error]);
        exit;
    }

    $sql->bind_param("ssssi", $nombres, $apa, $ama, $telefonos, $id);
    $success = $sql->execute();

    if ($sql->error) {
        echo json_encode(['success' => false, 'error' => $sql->error]);
    } else {
        echo json_encode(['success' => (bool)$success]);
    }

    $sql->close();
    $conexion->close();
} else {
    echo json_encode(['success' => false, 'error' => 'No data']);
}

?>
