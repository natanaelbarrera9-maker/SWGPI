<?php
// Adapted from PI/Servidor/insertarusu.php
// Inserts a user into the main_view database (table `users`).
require_once __DIR__ . '/config/conexion.php';
require_once __DIR__ . '/config/AuthValidator.php';

$conexion = dbConectar();

$c1 = $_POST['nombre'] ?? '';
$c2 = $_POST['apa'] ?? '';
$c3 = $_POST['ama'] ?? '';
$c5 = $_POST['telefono'] ?? '';
$rawPass = $_POST['pass'] ?? '';
$c7 = isset($_POST['idtipo']) ? intval($_POST['idtipo']) : 2; // default perfil

// Hash the password using central AuthValidator
$hashed = AuthValidator::hashPassword($rawPass);

// Insert into main_view `users` table: nombres, apa, ama, password, telefonos, perfil_id
$sql = $conexion->prepare("INSERT INTO users (nombres, apa, ama, password, telefonos, perfil_id) VALUES (?, ?, ?, ?, ?, ?)");
if (!$sql) {
    error_log('Prepare failed: ' . $conexion->error);
    header('Location: ../Cliente/usuarios.php?error=prepare');
    exit;
}

$sql->bind_param("sssssi", $c1, $c2, $c3, $hashed, $c5, $c7);
$sql->execute();
$sql->close();

// Redirect back to the UI
header("Location: ../Cliente/usuarios.php");

$conexion->close();
?>
