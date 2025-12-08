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
    // Consulta modificada para obtener los nombres de los autores
    $sql = "SELECT p.*,
            (SELECT JSON_ARRAYAGG(JSON_OBJECT('id', u.id, 'name', CONCAT(u.nombres, ' ', u.apa, ' ', u.ama)))
             FROM users u JOIN project_user pu ON u.id = pu.user_id
             WHERE pu.project_id = p.id AND u.perfil_id = 3) AS student_authors_json,
            (SELECT GROUP_CONCAT(CONCAT(u.nombres, ' ', u.apa, ' ', u.ama) SEPARATOR ', ') 
             FROM users u 
             JOIN project_user pu ON u.id = pu.user_id
             WHERE pu.project_id = p.id AND u.perfil_id = 3) AS student_authors,
            (SELECT GROUP_CONCAT(CONCAT(u.nombres, ' ', u.apa, ' ', u.ama))
             FROM users u JOIN project_user pu ON u.id = pu.user_id
             WHERE pu.project_id = p.id AND pu.rol_asesor = 'primario') AS advisor_1,
            (SELECT GROUP_CONCAT(CONCAT(u.nombres, ' ', u.apa, ' ', u.ama))
             FROM users u JOIN project_user pu ON u.id = pu.user_id
             WHERE pu.project_id = p.id AND pu.rol_asesor = 'secundario') AS advisor_2,
            (SELECT GROUP_CONCAT(a.nombre SEPARATOR ', ')
             FROM asignaturas a
             JOIN project_asignatura pa ON a.id = pa.asignatura_id
             WHERE pa.project_id = p.id) AS subjects
            FROM `$table` p WHERE p.id = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
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
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $students = $_POST['students'] ?? []; // Array de IDs de estudiantes
        $created_by = $_SESSION['user_id']; // El admin que está creando el proyecto

        if (empty($title) || empty($students)) {
            header('Location: admin_view.php?status=error&msg=faltan_campos_proyecto');
            exit();
        }

        // Iniciar transacción para asegurar la integridad de los datos
        $conn->begin_transaction();

        try {
            // 1. Insertar el proyecto en la tabla `projects`
            $stmt = $conn->prepare("INSERT INTO projects (title, description, created_by) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $title, $description, $created_by);
            $stmt->execute();

            // 2. Obtener el ID del proyecto recién insertado
            $projectId = $conn->insert_id;

            // 3. Insertar las relaciones en la tabla `project_user`
            $stmt_relation = $conn->prepare("INSERT INTO project_user (project_id, user_id) VALUES (?, ?)");
            foreach ($students as $studentId) {
                $stmt_relation->bind_param("is", $projectId, $studentId);
                $stmt_relation->execute();
            }

            // Si todo fue bien, confirmar la transacción
            $conn->commit();
            header('Location: projects_view.php?status=success&msg=created');

        } catch (mysqli_sql_exception $exception) {
            // Si algo falla, revertir la transacción
            $conn->rollback();
            error_log("Error al registrar proyecto: " . $exception->getMessage());
            header('Location: admin_view.php?status=error&msg=error_db');
        } finally {
            if (isset($stmt)) $stmt->close();
            if (isset($stmt_relation)) $stmt_relation->close();
        }
        exit();
    }

    if ($action === 'update') {
        // Obtener columnas disponibles
        $id = intval($_POST['id'] ?? 0); // Asegurarse de que el ID es un entero
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $students = $_POST['students'] ?? []; // Array de IDs de estudiantes

        if ($id <= 0) {
            header('Location: projects_view.php?status=error&msg=error');
            exit();
        }

        // Iniciar transacción
        $conn->begin_transaction();
        try {
            // 1. Actualizar el proyecto
            $stmt_project = $conn->prepare("UPDATE projects SET title = ?, description = ? WHERE id = ?");
            $stmt_project->bind_param("ssi", $title, $description, $id);
            $stmt_project->execute();

            // 2. Eliminar autores antiguos (solo estudiantes)
            $stmt_delete = $conn->prepare("DELETE pu FROM project_user pu JOIN users u ON pu.user_id = u.id WHERE pu.project_id = ? AND u.perfil_id = 3");
            $stmt_delete->bind_param("i", $id);
            $stmt_delete->execute();

            // 3. Insertar nuevos autores
            if (!empty($students)) {
                $stmt_relation = $conn->prepare("INSERT INTO project_user (project_id, user_id) VALUES (?, ?)");
                foreach ($students as $studentId) {
                    $stmt_relation->bind_param("is", $id, $studentId);
                    $stmt_relation->execute();
                }
            }

            $conn->commit();
            header('Location: projects_view.php?status=success&msg=updated');
        } catch (mysqli_sql_exception $e) {
            $conn->rollback();
            error_log("Error al actualizar proyecto: " . $e->getMessage());
            header('Location: projects_view.php?status=error&msg=error');
        }
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
