<?php
require_once __DIR__ . '/../Servidor/db.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['perfil_id'], [1, 2])) { // Admin o Docente
    header('Location: index.html?error=unauthorized');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action'])) {
    header('Content-Type: application/json');
    $id = $_GET['id'] ?? 0;

    if ($_GET['action'] == 'get_competencia' && $id > 0) {
        $stmt = $conn->prepare("SELECT nombre, fecha_inicio, fecha_fin FROM competencias WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($data = $result->fetch_assoc()) {
            echo json_encode(['status' => 'success', 'data' => $data]);
        } else {
            echo json_encode(['status' => 'error', 'msg' => 'not_found']);
        }
        exit();
    }

    if ($_GET['action'] == 'get_entregable' && $id > 0) {
        $stmt = $conn->prepare("SELECT nombre, fecha_limite, formatos_aceptados FROM entregables WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($data = $result->fetch_assoc()) {
            echo json_encode(['status' => 'success', 'data' => $data]);
        } else {
            echo json_encode(['status' => 'error', 'msg' => 'not_found']);
        }
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {

    $asignatura_id = $_POST['asignatura_id'] ?? 0;
    $redirect_url = "gestionar_competencias.php?asignatura_id={$asignatura_id}";

    if ($_POST['action'] == 'create_competencia') {
        $nombre = $_POST['nombre'] ?? '';
        $fecha_inicio = $_POST['fecha_inicio'] ?? '';
        $fecha_fin = $_POST['fecha_fin'] ?? '';

        if (empty($nombre) || empty($fecha_inicio) || empty($fecha_fin)) {
            header("Location: {$redirect_url}&status=error&msg=missing_fields");
            exit();
        }

        $stmt = $conn->prepare("INSERT INTO competencias (asignatura_id, nombre, fecha_inicio, fecha_fin) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $asignatura_id, $nombre, $fecha_inicio, $fecha_fin);
        
        if ($stmt->execute()) {
            header("Location: {$redirect_url}&status=success&msg=competencia_created");
        } else {
            header("Location: {$redirect_url}&status=error&msg=db_error");
        }
        exit();
    }

    if ($_POST['action'] == 'create_entregable') {
        $competencia_id = $_POST['competencia_id'] ?? 0;
        $nombre = $_POST['nombre'] ?? '';
        $fecha_limite = $_POST['fecha_limite'] ?? '';
        $formatos = isset($_POST['formatos']) ? implode(',', $_POST['formatos']) : null;

        if (empty($competencia_id) || empty($nombre) || empty($fecha_limite)) {
            header("Location: {$redirect_url}&status=error&msg=missing_fields");
            exit();
        }

        $stmt = $conn->prepare("INSERT INTO entregables (competencia_id, nombre, fecha_limite, formatos_aceptados) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $competencia_id, $nombre, $fecha_limite, $formatos);

        if ($stmt->execute()) {
            header("Location: {$redirect_url}&status=success&msg=entregable_created");
        } else {
            header("Location: {$redirect_url}&status=error&msg=db_error");
        }
        exit();
    }

    if ($_POST['action'] == 'update_competencia') {
        $id = $_POST['id'] ?? 0;
        $nombre = $_POST['nombre'] ?? '';
        $fecha_inicio = $_POST['fecha_inicio'] ?? '';
        $fecha_fin = $_POST['fecha_fin'] ?? '';

        if (empty($id) || empty($nombre) || empty($fecha_inicio) || empty($fecha_fin)) {
            header("Location: {$redirect_url}&status=error&msg=missing_fields");
            exit();
        }

        $stmt = $conn->prepare("UPDATE competencias SET nombre = ?, fecha_inicio = ?, fecha_fin = ? WHERE id = ?");
        $stmt->bind_param("sssi", $nombre, $fecha_inicio, $fecha_fin, $id);
        $stmt->execute() ? header("Location: {$redirect_url}&status=success&msg=competencia_updated") : header("Location: {$redirect_url}&status=error&msg=db_error");
        exit();
    }

    if ($_POST['action'] == 'delete_competencia') {
        $id = $_POST['id'] ?? 0;
        if (empty($id)) { header("Location: {$redirect_url}&status=error&msg=missing_fields"); exit(); }

        $stmt = $conn->prepare("DELETE FROM competencias WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute() ? header("Location: {$redirect_url}&status=success&msg=competencia_deleted") : header("Location: {$redirect_url}&status=error&msg=db_error");
        exit();
    }

    if ($_POST['action'] == 'update_entregable') {
        $id = $_POST['id'] ?? 0;
        $nombre = $_POST['nombre'] ?? '';
        $fecha_limite = $_POST['fecha_limite'] ?? '';
        $formatos = isset($_POST['formatos']) ? implode(',', $_POST['formatos']) : null;

        if (empty($id) || empty($nombre) || empty($fecha_limite)) {
            header("Location: {$redirect_url}&status=error&msg=missing_fields");
            exit();
        }

        $stmt = $conn->prepare("UPDATE entregables SET nombre = ?, fecha_limite = ?, formatos_aceptados = ? WHERE id = ?");
        $stmt->bind_param("sssi", $nombre, $fecha_limite, $formatos, $id);
        $stmt->execute() ? header("Location: {$redirect_url}&status=success&msg=entregable_updated") : header("Location: {$redirect_url}&status=error&msg=db_error");
        exit();
    }

    if ($_POST['action'] == 'delete_entregable') {
        $id = $_POST['id'] ?? 0;
        if (empty($id)) { header("Location: {$redirect_url}&status=error&msg=missing_fields"); exit(); }

        $stmt = $conn->prepare("DELETE FROM entregables WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute() ? header("Location: {$redirect_url}&status=success&msg=entregable_deleted") : header("Location: {$redirect_url}&status=error&msg=db_error");
        exit();
    }
}

header('Location: subjects_view.php');
exit();