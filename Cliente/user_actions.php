<?php
require_once __DIR__ . '/../Servidor/db.php'; // Incluye la conexión a la BD y el inicio de sesión
require_once __DIR__ . '/../Servidor/config/AuthValidator.php'; // Incluye el validador centralizado

// Proteger el script: solo los administradores pueden realizar acciones
if (!isset($_SESSION['user_id']) || $_SESSION['perfil_id'] != 1) {
    header('Location: index.html?error=unauthorized');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action']) && $_GET['action'] == 'get_user') {
    if (!isset($_GET['id'])) {
        echo json_encode(['status' => 'error', 'msg' => 'ID de usuario no proporcionado.']);
        exit();
    }

    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT id, nombres, apa, ama, curp, direccion, telefonos, perfil_id, created_at FROM users WHERE id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'data' => $user]);
    } else {
        echo json_encode(['status' => 'error', 'msg' => 'Usuario no encontrado.']);
    }
    $stmt->close();
    $conn->close();
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {

    // --- Acción para Registrar un Nuevo Usuario ---
    if ($_POST['action'] == 'register') {
        $id = $_POST['id'];
        $nombres = $_POST['nombres'];
        $apa = $_POST['apa'];
        $ama = $_POST['ama'];
        $password = $_POST['password'];
        $perfil_id = $_POST['perfil_id'];
        $curp = !empty($_POST['curp']) ? $_POST['curp'] : null;
        $direccion = !empty($_POST['direccion']) ? $_POST['direccion'] : null;
        $telefonos = !empty($_POST['telefonos']) ? $_POST['telefonos'] : null;

        // Validación básica de campos obligatorios
        if (empty($id) || empty($nombres) || empty($apa) || empty($password) || empty($perfil_id)) {
            header('Location: admin_view.php?status=error&msg=faltan_campos');
            exit();
        }

        // Verificar si el ID de usuario ya existe
        $stmt = $conn->prepare("SELECT id FROM users WHERE id = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->close();
            header('Location: admin_view.php?status=error&msg=id_existente');
            exit();
        }
        $stmt->close();

        // Hashear la contraseña usando el validador centralizado
        $hashed_password = AuthValidator::hashPassword($password);

        // Preparar la inserción en la base de datos
        $stmt = $conn->prepare("INSERT INTO users (id, password, nombres, apa, ama, direccion, telefonos, curp, perfil_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssi", $id, $hashed_password, $nombres, $apa, $ama, $direccion, $telefonos, $curp, $perfil_id);

        if ($stmt->execute()) {
            header('Location: admin_view.php?status=success&msg=usuario_registrado');
        } else {
            header('Location: admin_view.php?status=error&msg=registro_fallido');
        }
        
        $stmt->close();
        $conn->close();
        exit();
    }

    // --- Acción para actualizar usuario ---
    if ($_POST['action'] == 'update') {
        $id = $_POST['id'] ?? null;
        $nombres = $_POST['nombres'] ?? null;
        $apa = $_POST['apa'] ?? null;
        $ama = $_POST['ama'] ?? null;
        $perfil_id = isset($_POST['perfil_id']) ? intval($_POST['perfil_id']) : null;
        $curp = $_POST['curp'] ?? null;
        $direccion = $_POST['direccion'] ?? null;
        $telefonos = $_POST['telefonos'] ?? null;
        $password = $_POST['password'] ?? null; // opcional

        if (empty($id) || empty($nombres) || empty($apa) || empty($perfil_id)) {
            header('Location: admin_view.php?status=error&msg=faltan_campos');
            exit();
        }

        // Si se proporciona contraseña, hashearla
        if (!empty($password)) {
            $hashed_password = AuthValidator::hashPassword($password);
            $stmt = $conn->prepare("UPDATE users SET password = ?, nombres = ?, apa = ?, ama = ?, direccion = ?, telefonos = ?, curp = ?, perfil_id = ? WHERE id = ?");
            $stmt->bind_param("sssssissi", $hashed_password, $nombres, $apa, $ama, $direccion, $telefonos, $curp, $perfil_id, $id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET nombres = ?, apa = ?, ama = ?, direccion = ?, telefonos = ?, curp = ?, perfil_id = ? WHERE id = ?");
            $stmt->bind_param("sssssisi", $nombres, $apa, $ama, $direccion, $telefonos, $curp, $perfil_id, $id);
        }

        if ($stmt->execute()) {
            header('Location: admin_view.php?status=success&msg=usuario_actualizado');
        } else {
            header('Location: admin_view.php?status=error&msg=actualizacion_fallida');
        }
        $stmt->close();
        $conn->close();
        exit();
    }

    // --- Acción para eliminar usuario ---
    if ($_POST['action'] == 'delete') {
        $id = $_POST['id'] ?? null;
        if (empty($id)) {
            header('Location: admin_view.php?status=error&msg=faltan_campos');
            exit();
        }
        // Debug: log incoming POST and session for troubleshooting
        $debugDir = __DIR__ . '/../Servidor/logs';
        if (!is_dir($debugDir)) @mkdir($debugDir, 0755, true);
        $debugFile = $debugDir . '/delete_debug.log';
        $dbgEntry = date('Y-m-d H:i:s') . " | DELETE_REQUEST | POST:" . json_encode($_POST) . " | SESSION:" . json_encode(array_intersect_key($_SESSION, array_flip(['user_id','perfil_id']))) . "\n";
        @file_put_contents($debugFile, $dbgEntry, FILE_APPEND | LOCK_EX);
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("s", $id);
        $ok = $stmt->execute();
        $affected = $stmt->affected_rows;

        // Log deletion to Servidor/logs/delete.log
        $logDir = __DIR__ . '/../Servidor/logs';
        if (!is_dir($logDir)) @mkdir($logDir, 0755, true);
        $logFile = $logDir . '/delete.log';
        $now = date('Y-m-d H:i:s');
        $adminId = $_SESSION['user_id'] ?? 'unknown';
        $entry = "$now | admin:$adminId | target_user_id:$id | result:" . ($ok ? "OK" : "FAIL") . " | affected:$affected\n";
        @file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);

        if ($ok && $affected > 0) {
            header('Location: admin_view.php?status=success&msg=usuario_eliminado');
        } else {
            header('Location: admin_view.php?status=error&msg=eliminacion_fallida');
        }
        $stmt->close();
        $conn->close();
        exit();
    }
}

// Redirigir si no se especifica una acción válida
header('Location: admin_view.php');
exit();