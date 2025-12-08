<?php
require_once __DIR__ . '/../Servidor/db.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['perfil_id'], [1, 2])) { // Admin o Docente
    header('Location: index.html?error=unauthorized');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['entrega_id'])) {
    $entrega_id = $_POST['entrega_id'];
    $calificacion = $_POST['calificacion'] ?? null;

    if ($calificacion !== null && is_numeric($calificacion)) {
        $stmt = $conn->prepare("UPDATE entregas_estudiantes SET calificacion = ? WHERE id = ?");
        $stmt->bind_param("di", $calificacion, $entrega_id);
        $stmt->execute();
    }

    // Redirigir de vuelta a la página de revisión
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}

header("Location: docente_view.php");
exit();
?>