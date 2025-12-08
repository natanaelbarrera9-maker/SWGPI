<?php
require_once __DIR__ . '/../Servidor/db.php';

// Proteger el script: solo los administradores pueden realizar acciones
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['perfil_id'], [1, 2])) { // Admin o Docente
    header('Location: index.html?error=unauthorized');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {

    // --- Acción para Cargar un Nuevo Grafo ---
    if ($_POST['action'] == 'upload') {
        $titulo = $_POST['titulo'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';

        // Validar que los campos no estén vacíos
        if (empty($titulo) || !isset($_FILES['grafo_file']) || $_FILES['grafo_file']['error'] != UPLOAD_ERR_OK) {
            header('Location: grafos_view.php?status=error&msg=faltan_datos_grafo');
            exit();
        }

        $file = $_FILES['grafo_file'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];
        $fileType = $file['type'];

        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg'];

        if (in_array($fileExt, $allowed)) {
            if ($fileError === 0) {
                if ($fileSize < 5000000) { // Límite de 5MB
                    // Crear un nombre de archivo único para evitar sobreescrituras
                    $fileNameNew = "grafo_" . uniqid('', true) . "." . $fileExt;
                    $uploadDir = __DIR__ . '/uploads/grafos/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    $fileDestination = $uploadDir . $fileNameNew;

                    if (move_uploaded_file($fileTmpName, $fileDestination)) {
                        // Insertar en la base de datos
                        $stmt = $conn->prepare("INSERT INTO grafos (titulo, descripcion, nombre_archivo) VALUES (?, ?, ?)");
                        $stmt->bind_param("sss", $titulo, $descripcion, $fileNameNew);
                        $stmt->execute();
                        $stmt->close();
                        header('Location: grafos_view.php?status=success&msg=grafo_cargado');
                    } else {
                        header('Location: grafos_view.php?status=error&msg=error_subida');
                    }
                } else {
                    header('Location: grafos_view.php?status=error&msg=archivo_muy_grande');
                }
            }
        } else {
            header('Location: grafos_view.php?status=error&msg=tipo_archivo_invalido');
        }
        exit();
    }

    // --- Acción para Actualizar un Grafo Existente ---
    if ($_POST['action'] == 'update') {
        $id = $_POST['id'] ?? 0;
        $titulo = $_POST['titulo'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';

        if (empty($id) || empty($titulo)) {
            header('Location: grafos_view.php?status=error&msg=faltan_datos_actualizar');
            exit();
        }

        $newFileName = null;
        // Verificar si se subió un nuevo archivo
        if (isset($_FILES['grafo_file']) && $_FILES['grafo_file']['error'] == UPLOAD_ERR_OK) {
            $file = $_FILES['grafo_file'];
            $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg'];

            if (in_array($fileExt, $allowed) && $file['size'] < 5000000) {
                // Obtener el nombre del archivo antiguo para borrarlo
                $stmt_old = $conn->prepare("SELECT nombre_archivo FROM grafos WHERE id = ?");
                $stmt_old->bind_param("i", $id);
                $stmt_old->execute();
                $result_old = $stmt_old->get_result();
                if ($old_file = $result_old->fetch_assoc()) {
                    $oldFilePath = __DIR__ . '/uploads/grafos/' . $old_file['nombre_archivo'];
                    if (file_exists($oldFilePath)) {
                        @unlink($oldFilePath); // Borrar el archivo antiguo
                    }
                }
                $stmt_old->close();

                // Subir el nuevo archivo
                $newFileName = "grafo_" . uniqid('', true) . "." . $fileExt;
                $uploadDir = __DIR__ . '/uploads/grafos/';
                $fileDestination = $uploadDir . $newFileName;
                if (!move_uploaded_file($file['tmp_name'], $fileDestination)) {
                    header('Location: grafos_view.php?status=error&msg=error_subida');
                    exit();
                }
            } else {
                header('Location: grafos_view.php?status=error&msg=tipo_archivo_invalido');
                exit();
            }
        }

        // Actualizar la base de datos
        if ($newFileName) {
            $stmt = $conn->prepare("UPDATE grafos SET titulo = ?, descripcion = ?, nombre_archivo = ? WHERE id = ?");
            $stmt->bind_param("sssi", $titulo, $descripcion, $newFileName, $id);
        } else {
            $stmt = $conn->prepare("UPDATE grafos SET titulo = ?, descripcion = ? WHERE id = ?");
            $stmt->bind_param("ssi", $titulo, $descripcion, $id);
        }

        if ($stmt->execute()) {
            header('Location: grafos_view.php?status=success&msg=grafo_actualizado');
        } else {
            // En caso de un error real de base de datos, redirigir con error.
            header('Location: grafos_view.php?status=error&msg=error_actualizar');
        }
        exit();
    }
}
?>