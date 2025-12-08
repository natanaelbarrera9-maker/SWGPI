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
    $stmt = $conn->prepare("SELECT id, nombres, apa, ama, email, curp, direccion, telefonos, perfil_id, created_at FROM users WHERE id = ?");
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
        $email = $_POST['email'];
        $perfil_id = $_POST['perfil_id'];
        $curp = !empty($_POST['curp']) ? $_POST['curp'] : null;
        $direccion = !empty($_POST['direccion']) ? $_POST['direccion'] : null;
        $telefonos = !empty($_POST['telefonos']) ? $_POST['telefonos'] : null;

        // Validación básica de campos obligatorios
        if (empty($id) || empty($nombres) || empty($apa) || empty($password) || empty($email) || empty($perfil_id)) {
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

        // Verificar si el email ya existe
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->close();
            header('Location: admin_view.php?status=error&msg=email_existente');
            exit();
        }
        $stmt->close();

        // Hashear la contraseña usando el validador centralizado
        $hashed_password = AuthValidator::hashPassword($password);

        // Preparar la inserción en la base de datos
        $stmt = $conn->prepare("INSERT INTO users (id, password, nombres, apa, ama, direccion, telefonos, curp, email, perfil_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssi", $id, $hashed_password, $nombres, $apa, $ama, $direccion, $telefonos, $curp, $email, $perfil_id);

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
        $email = $_POST['email'] ?? null;
        $curp = $_POST['curp'] ?? null;
        $direccion = $_POST['direccion'] ?? null;
        $telefonos = $_POST['telefonos'] ?? null;
        $password = $_POST['password'] ?? null; // opcional

        if (empty($id) || empty($nombres) || empty($apa) || empty($email) || empty($perfil_id)) {
            header('Location: admin_view.php?status=error&msg=faltan_campos');
            exit();
        }

        // Verificar si el nuevo email ya está en uso por OTRO usuario
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->bind_param("ss", $email, $id);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->close();
            header('Location: admin_view.php?status=error&msg=email_existente');
            exit();
        }
        $stmt->close();


        // Si se proporciona contraseña, hashearla
        if (!empty($password)) {
            $hashed_password = AuthValidator::hashPassword($password);
            $stmt = $conn->prepare("UPDATE users SET password = ?, nombres = ?, apa = ?, ama = ?, direccion = ?, telefonos = ?, curp = ?, email = ?, perfil_id = ? WHERE id = ? AND activo = 1");
            $stmt->bind_param("ssssssssis", $hashed_password, $nombres, $apa, $ama, $direccion, $telefonos, $curp, $email, $perfil_id, $id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET nombres = ?, apa = ?, ama = ?, direccion = ?, telefonos = ?, curp = ?, email = ?, perfil_id = ? WHERE id = ? AND activo = 1");
            $stmt->bind_param("sssssssis", $nombres, $apa, $ama, $direccion, $telefonos, $curp, $email, $perfil_id, $id);
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

        // Iniciar transacción para una operación segura
        $conn->begin_transaction();

        try {
            // 1. Obtener los IDs de los proyectos donde el usuario es autor (perfil 3)
            $stmt_projects = $conn->prepare("SELECT project_id FROM project_user WHERE user_id = ?");
            $stmt_projects->bind_param("s", $id);
            $stmt_projects->execute();
            $result_projects = $stmt_projects->get_result();
            $project_ids = [];
            while ($row = $result_projects->fetch_assoc()) { $project_ids[] = $row['project_id']; }
            $stmt_projects->close();

            // 2. Desactivar al usuario (soft delete)
            $stmt_deactivate_user = $conn->prepare("UPDATE users SET activo = 0 WHERE id = ?");
            $stmt_deactivate_user->bind_param("s", $id);
            $stmt_deactivate_user->execute();

            // 3. Desactivar todos los entregables subidos por este usuario
            $stmt_deactivate_deliverables = $conn->prepare("UPDATE entregas_estudiantes SET activo = 0 WHERE user_id = ?");
            $stmt_deactivate_deliverables->bind_param("s", $id);
            $stmt_deactivate_deliverables->execute();

            // 3. Verificar cada proyecto afectado
            foreach ($project_ids as $project_id) {
                // Contar cuántos usuarios ACTIVOS quedan en el proyecto
                $stmt_count = $conn->prepare(
                    "SELECT COUNT(pu.user_id) as active_count 
                     FROM project_user pu 
                     JOIN users u ON pu.user_id = u.id 
                     WHERE pu.project_id = ? AND u.activo = 1 AND u.perfil_id = 3"
                );
                $stmt_count->bind_param("i", $project_id);
                $stmt_count->execute();
                $active_count = $stmt_count->get_result()->fetch_assoc()['active_count'];

                // Si no quedan usuarios activos, desactivar el proyecto
                if ($active_count == 0) {
                    $stmt_deactivate_project = $conn->prepare("UPDATE projects SET activo = 0 WHERE id = ?");
                    $stmt_deactivate_project->bind_param("i", $project_id);
                    $stmt_deactivate_project->execute();
                }
            }

            // Si todo fue bien, confirmar la transacción
            $conn->commit();
            header('Location: admin_view.php?status=success&msg=usuario_desactivado');
        } catch (Exception $e) {
            $conn->rollback(); // Revertir cambios si algo falla
            header('Location: admin_view.php?status=error&msg=operacion_fallida');
        } finally {
            if (isset($stmt_deactivate_user)) $stmt_deactivate_user->close();
            if (isset($stmt_count)) $stmt_count->close();
            if (isset($stmt_deactivate_deliverables)) $stmt_deactivate_deliverables->close();
            if (isset($stmt_deactivate_project)) $stmt_deactivate_project->close();
        }
        $conn->close();
        exit();
    }

    // --- Acción para reactivar usuario ---
    if ($_POST['action'] == 'reactivate') {
        $id = $_POST['id'] ?? null;
        if (empty($id)) {
            header('Location: inactive_users_view.php?status=error&msg=faltan_campos');
            exit();
        }

        $conn->begin_transaction();
        try {
            // 1. Reactivar al usuario
            $stmt_reactivate_user = $conn->prepare("UPDATE users SET activo = 1 WHERE id = ?");
            $stmt_reactivate_user->bind_param("s", $id);
            $stmt_reactivate_user->execute();

            // 2. Reactivar todos los entregables del usuario
            $stmt_reactivate_deliverables = $conn->prepare("UPDATE entregas_estudiantes SET activo = 1 WHERE user_id = ?");
            $stmt_reactivate_deliverables->bind_param("s", $id);
            $stmt_reactivate_deliverables->execute();

            // 3. Obtener los IDs de los proyectos INACTIVOS donde el usuario participa (como autor o asesor)
            $stmt_projects = $conn->prepare(
                "SELECT p.id FROM projects p 
                 JOIN project_user pu ON p.id = pu.project_id 
                 WHERE pu.user_id = ? AND p.activo = 0"
            );
            $stmt_projects->bind_param("s", $id);
            $stmt_projects->execute();
            $result_projects = $stmt_projects->get_result();

            // 4. Reactivar dichos proyectos si existen
            if ($result_projects->num_rows > 0) {
                $stmt_reactivate_project = $conn->prepare("UPDATE projects SET activo = 1 WHERE id = ?");
                while ($row = $result_projects->fetch_assoc()) {
                    $stmt_reactivate_project->bind_param("i", $row['id']);
                    $stmt_reactivate_project->execute();
                }
            }

            $conn->commit();
            header('Location: inactive_users_view.php?status=success&msg=usuario_reactivado');
        } catch (Exception $e) {
            $conn->rollback();
            header('Location: inactive_users_view.php?status=error&msg=operacion_fallida');
        } finally {
            if (isset($stmt_reactivate_user)) $stmt_reactivate_user->close();
            if (isset($stmt_reactivate_deliverables)) $stmt_reactivate_deliverables->close();
            if (isset($stmt_projects)) $stmt_projects->close();
            if (isset($stmt_reactivate_project)) $stmt_reactivate_project->close();
        }
        $conn->close();
        exit();
    }
}

// Redirigir si no se especifica una acción válida
header('Location: admin_view.php');
exit();