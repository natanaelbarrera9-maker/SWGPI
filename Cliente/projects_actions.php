<?php
require_once __DIR__ . '/../Servidor/db.php';

// Only admin
if (!isset($_SESSION['user_id']) || $_SESSION['perfil_id'] != 1) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'msg' => 'unauthorized']);
    exit();
}

$table = 'projects';

// Helper: obtener columnas de la tabla
function get_table_columns($conn, $table) {
    $cols = [];
    $q = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$table' AND TABLE_SCHEMA = DATABASE()";
    $res = $conn->query($q);
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $cols[] = $row['COLUMN_NAME'];
        }
    }
    return $cols;
}

// Helper: check table exists
function table_exists($conn, $table) {
    $t = $conn->real_escape_string($table);
    $res = $conn->query("SHOW TABLES LIKE '$t'");
    return ($res && $res->num_rows > 0);
}

if (!table_exists($conn, $table)) {
    // Return a helpful error for AJAX requests
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'msg' => "Tabla '$table' no encontrada. Importa projects_schema.sql o crea la tabla."]);
        exit();
    }
    // For POST requests redirect back with an error
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Location: projects_view.php?status=error&msg=error');
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_project') {
    $id = $_GET['id'] ?? null;
    if (!$id) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'msg' => 'id_missing']);
        exit();
    }
    $stmt = $conn->prepare("SELECT * FROM `$table` WHERE id = ? LIMIT 1");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'data' => $row]);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'msg' => 'not_found']);
    }
    $stmt->close();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action === 'register') {
        // Obtener columnas disponibles
        $cols = get_table_columns($conn, $table);
        $insert_fields = [];
        $insert_values = [];
        $bind_types = '';
        $bind_vars = [];

        // title (siempre intentar)
        if (in_array('title', $cols)) {
            $insert_fields[] = 'title';
            $insert_values[] = '?';
            $bind_types .= 's';
            $bind_vars[] = $_POST['title'] ?? '';
        }

        // description (siempre intentar)
        if (in_array('description', $cols)) {
            $insert_fields[] = 'description';
            $insert_values[] = '?';
            $bind_types .= 's';
            $bind_vars[] = $_POST['description'] ?? '';
        }

        // authors (si existe)
        if (in_array('authors', $cols) && !empty($_POST['authors'])) {
            $insert_fields[] = 'authors';
            $insert_values[] = '?';
            $bind_types .= 's';
            $bind_vars[] = $_POST['authors'];
        }

        // year (si existe)
        if (in_array('year', $cols) && !empty($_POST['year'])) {
            $insert_fields[] = 'year';
            $insert_values[] = '?';
            $bind_types .= 'i';
            $bind_vars[] = intval($_POST['year']);
        }

        if (empty($insert_fields)) {
            header('Location: projects_view.php?status=error&msg=error');
            exit();
        }

        $sql = "INSERT INTO `$table` (" . implode(',', $insert_fields) . ") VALUES (" . implode(',', $insert_values) . ")";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            error_log("projects_actions.php prepare register failed: " . $conn->error);
            header('Location: projects_view.php?status=error&msg=error');
            exit();
        }
        $stmt->bind_param($bind_types, ...$bind_vars);
        if ($stmt->execute()) {
            header('Location: projects_view.php?status=success&msg=created');
        } else {
            error_log("projects_actions.php register execute failed: " . $stmt->error);
            header('Location: projects_view.php?status=error&msg=error');
        }
        $stmt->close();
        exit();
    }

    if ($action === 'update') {
        // Obtener columnas disponibles
        $cols = get_table_columns($conn, $table);
        $id = intval($_POST['id'] ?? 0);
        if ($id <= 0) {
            header('Location: projects_view.php?status=error&msg=error');
            exit();
        }

        $update_parts = [];
        $bind_types = '';
        $bind_vars = [];

        // title
        if (in_array('title', $cols)) {
            $update_parts[] = 'title = ?';
            $bind_types .= 's';
            $bind_vars[] = $_POST['title'] ?? '';
        }

        // description
        if (in_array('description', $cols)) {
            $update_parts[] = 'description = ?';
            $bind_types .= 's';
            $bind_vars[] = $_POST['description'] ?? '';
        }

        // authors
        if (in_array('authors', $cols)) {
            $update_parts[] = 'authors = ?';
            $bind_types .= 's';
            $bind_vars[] = $_POST['authors'] ?? '';
        }

        // year
        if (in_array('year', $cols)) {
            $update_parts[] = 'year = ?';
            $bind_types .= 'i';
            $bind_vars[] = intval($_POST['year'] ?? 0);
        }

        if (empty($update_parts)) {
            header('Location: projects_view.php?status=error&msg=error');
            exit();
        }

        $bind_types .= 'i'; // id
        $bind_vars[] = $id;

        $sql = "UPDATE `$table` SET " . implode(', ', $update_parts) . " WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            error_log("projects_actions.php prepare update failed: " . $conn->error);
            header('Location: projects_view.php?status=error&msg=error');
            exit();
        }
        $stmt->bind_param($bind_types, ...$bind_vars);
        if ($stmt->execute()) {
            header('Location: projects_view.php?status=success&msg=updated');
        } else {
            error_log("projects_actions.php update execute failed: " . $stmt->error);
            header('Location: projects_view.php?status=error&msg=error');
        }
        $stmt->close();
        exit();
    }

    if ($action === 'delete') {
        $id = intval($_POST['id'] ?? 0);
        $stmt = $conn->prepare("DELETE FROM `$table` WHERE id = ?");
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            header('Location: projects_view.php?status=success&msg=deleted');
        } else {
            header('Location: projects_view.php?status=error&msg=error');
        }
        $stmt->close();
        exit();
    }
}

// Default fallback
header('Content-Type: application/json');
echo json_encode(['status' => 'error', 'msg' => 'invalid_request']);
exit();

?>
