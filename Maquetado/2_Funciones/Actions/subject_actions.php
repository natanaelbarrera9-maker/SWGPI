<?php
require_once __DIR__ . '/../Servidor/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['perfil_id'] != 1) {
    header('Location: ../index.html?error=unauthorized');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {

    if ($_POST['action'] == 'create') {
        $nombre = $_POST['nombre'] ?? '';
        $clave = !empty($_POST['clave']) ? $_POST['clave'] : null;

        if (empty($nombre)) {
            header('Location: subjects_view.php?status=error&msg=error');
            exit();
        }

        $stmt = $conn->prepare("INSERT INTO asignaturas (nombre, clave) VALUES (?, ?)");
        $stmt->bind_param("ss", $nombre, $clave);
        
        if ($stmt->execute()) {
            header('Location: subjects_view.php?status=success&msg=created');
        } else {
            header('Location: subjects_view.php?status=error&msg=error');
        }
        exit();
    }
}

header('Location: subjects_view.php');
exit();
?>