<?php
session_start();

// Usar la configuración central dentro de config/
require_once __DIR__ . '/config/conexion.php';
require_once __DIR__ . '/config/AuthValidator.php';

$conn = dbConectar();

if ($conn === false) {
    die("Error de conexión: no se pudo conectar a la base de datos.");
}

// --- Creación del usuario administrador por defecto ---
// Comprobar si el usuario admin ya existe
$admin_id = '0000000001';
$check_admin = $conn->query("SELECT id FROM users WHERE id = '$admin_id'");

if ($check_admin->num_rows == 0) {
    // El admin no existe, vamos a crearlo
    $admin_pass = 'admin123';
    $hashed_password = password_hash($admin_pass, PASSWORD_DEFAULT);
    $nombres = 'Administrador';
    $apa = 'Sistema';
    $perfil_id = 1; // 1 para Administrador

    $stmt = $conn->prepare("INSERT INTO users (id, password, nombres, apa, perfil_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $admin_id, $hashed_password, $nombres, $apa, $perfil_id);
    $stmt->execute();
    $stmt->close();
}
?>