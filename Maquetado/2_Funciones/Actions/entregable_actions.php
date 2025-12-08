<?php
require_once __DIR__ . '/../Servidor/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['perfil_id'] != 3) { // Solo estudiantes
    header('Location: index.html?error=unauthorized');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'submit_entregable') {
    
    $user_id = $_SESSION['user_id'];
    $entregable_id = $_POST['entregable_id'] ?? 0;
    $project_id = $_POST['project_id'] ?? 0;
    $redirect_url = "entregables_view.php";

    if (empty($entregable_id) || empty($project_id) || !isset($_FILES['archivo_entrega']) || $_FILES['archivo_entrega']['error'] != UPLOAD_ERR_OK) {
        header("Location: {$redirect_url}?status=error&msg=error_subida");
        exit();
    }

    $file = $_FILES['archivo_entrega'];
    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'zip', 'rar', 'jpg', 'png'];

    if (!in_array($fileExt, $allowed)) {
        header("Location: {$redirect_url}?status=error&msg=tipo_invalido");
        exit();
    }

    if ($file['size'] > 10000000) { // Límite de 10MB
        header("Location: {$redirect_url}?status=error&msg=archivo_grande");
        exit();
    }

    // Crear un nombre de archivo único
    $fileNameNew = "entrega_{$project_id}_{$user_id}_{$entregable_id}_" . time() . "." . $fileExt;
    $uploadDir = __DIR__ . '/uploads/entregas/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    $fileDestination = $uploadDir . $fileNameNew;

    // Borrar archivo antiguo si existe (para re-entregas)
    $stmt_old = $conn->prepare("SELECT ruta_archivo FROM entregas_estudiantes WHERE entregable_id = ? AND user_id = ? AND project_id = ?");
    $stmt_old->bind_param("isi", $entregable_id, $user_id, $project_id);
    $stmt_old->execute();
    $result_old = $stmt_old->get_result();
    if ($old_file = $result_old->fetch_assoc()) {
        if (file_exists($old_file['ruta_archivo'])) {
            @unlink($old_file['ruta_archivo']);
        }
    }

    if (move_uploaded_file($file['tmp_name'], $fileDestination)) {
        // Usar INSERT ... ON DUPLICATE KEY UPDATE para manejar tanto nuevas entregas como re-entregas
        $stmt = $conn->prepare(
            "INSERT INTO entregas_estudiantes (entregable_id, user_id, project_id, nombre_archivo, ruta_archivo, fecha_entrega) 
             VALUES (?, ?, ?, ?, ?, NOW())
             ON DUPLICATE KEY UPDATE nombre_archivo = VALUES(nombre_archivo), ruta_archivo = VALUES(ruta_archivo), fecha_entrega = NOW()"
        );
        $stmt->bind_param("isiss", $entregable_id, $user_id, $project_id, $fileNameNew, $fileDestination);
        
        if ($stmt->execute()) {
            header("Location: {$redirect_url}?status=success&msg=entrega_exitosa");
        } else {
            header("Location: {$redirect_url}?status=error&msg=error_db");
        }
    } else {
        header("Location: {$redirect_url}?status=error&msg=error_subida");
    }
    exit();
}

header('Location: entregables_view.php');
exit();