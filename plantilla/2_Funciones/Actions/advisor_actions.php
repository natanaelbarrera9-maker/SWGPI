<?php
require_once __DIR__ . '/../Servidor/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['perfil_id'] != 1) {
    header('Location: ../index.html?error=unauthorized');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {

    if ($_POST['action'] == 'assign') {
        $project_id = $_POST['project_id'] ?? 0;
        $user_id = $_POST['user_id'] ?? '';
        $rol_asesor = $_POST['rol_asesor'] ?? '';

        if (empty($project_id) || empty($user_id) || empty($rol_asesor)) {
            header('Location: advisors_view.php?status=error&msg=error');
            exit();
        }

        // Verificar si ya existe la relación para decidir si es INSERT o UPDATE
        $stmt_check = $conn->prepare("SELECT project_id FROM project_user WHERE project_id = ? AND user_id = ?");
        $stmt_check->bind_param("is", $project_id, $user_id);
        $stmt_check->execute();
        $result = $stmt_check->get_result();

        if ($result->num_rows > 0) {
            // Ya existe, es un UPDATE
            $stmt = $conn->prepare("UPDATE project_user SET rol_asesor = ? WHERE project_id = ? AND user_id = ?");
            $stmt->bind_param("sis", $rol_asesor, $project_id, $user_id);
            $msg = 'updated';
        } else {
            // No existe, es un INSERT
            $stmt = $conn->prepare("INSERT INTO project_user (project_id, user_id, rol_asesor) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $project_id, $user_id, $rol_asesor);
            $msg = 'assigned';
        }

        $stmt->execute() ? header("Location: advisors_view.php?status=success&msg={$msg}") : header('Location: advisors_view.php?status=error&msg=error');
        $stmt->close();
        exit();
    }
}

header('Location: advisors_view.php');
exit();
?>