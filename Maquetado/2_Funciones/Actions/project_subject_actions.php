<?php
require_once __DIR__ . '/../Servidor/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['perfil_id'] != 1) {
    header('Location: ../index.html?error=unauthorized');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {

    // --- Acción para ASOCIAR una asignatura a un proyecto ---
    if ($_POST['action'] == 'assign') {
        $project_id = $_POST['project_id'] ?? 0;
        $asignatura_id = $_POST['asignatura_id'] ?? 0;

        if (empty($project_id) || empty($asignatura_id)) {
            header('Location: project_subjects_view.php?status=error&msg=error');
            exit();
        }

        try {
            $stmt = $conn->prepare("INSERT INTO project_asignatura (project_id, asignatura_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $project_id, $asignatura_id);
            $stmt->execute();
            header('Location: project_subjects_view.php?status=success&msg=assigned');
        } catch (mysqli_sql_exception $e) {
            // Manejar error de clave duplicada (si ya está asociada)
            if ($conn->errno == 1062) {
                header('Location: project_subjects_view.php?status=error&msg=already_assigned');
            } else {
                header('Location: project_subjects_view.php?status=error&msg=error');
            }
        } finally {
            if (isset($stmt)) $stmt->close();
        }
        exit();
    }

    // --- Acción para DESVINCULAR una asignatura de un proyecto ---
    if ($_POST['action'] == 'remove') {
        $project_id = $_POST['project_id'] ?? 0;
        $asignatura_id = $_POST['asignatura_id'] ?? 0;

        $stmt = $conn->prepare("DELETE FROM project_asignatura WHERE project_id = ? AND asignatura_id = ?");
        $stmt->bind_param("ii", $project_id, $asignatura_id);
        $stmt->execute() ? header('Location: project_subjects_view.php?status=success&msg=removed') : header('Location: project_subjects_view.php?status=error&msg=error');
        $stmt->close();
        exit();
    }
}

header('Location: project_subjects_view.php');
exit();
?>