<?php
require_once __DIR__ . '/../Servidor/db.php';
require_once __DIR__ . '/../Servidor/config/AuthValidator.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['userId']; // Usaremos el ID (matrícula/nómina) para iniciar sesión
    $password = $_POST['password'];

    if (empty($id) || empty($password)) {
        // Redirigir con error si los campos están vacíos
        header("Location: ../index.html?error=emptyfields");
        exit();
    }

    // Se añade el campo 'activo' a la consulta
    $stmt = $conn->prepare("SELECT id, password, perfil_id, activo FROM users WHERE id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        $validationResult = AuthValidator::validatePassword($password, $user['password']);
        if ($validationResult['valid']) {
            // Contraseña correcta, ahora verificar si el usuario está activo
            if ($user['activo'] == 1) {
                // Usuario activo, iniciar sesión
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['perfil_id'] = $user['perfil_id'];

                // Redirigir según el nivel de perfil
                switch ($user['perfil_id']) {
                    case 1: // Administrador
                        header("Location: admin_welcome.php?status=loginsuccess");
                        break;
                    case 2: // Docente
                        header("Location: docente_view.php");
                        break;
                    case 3: // Estudiante
                        header("Location: estudiante_view.php");
                        break;
                    default:
                        header("Location: ../index.html");
                        break;
                }
                exit();
            } else {
                // Usuario inactivo, redirigir con error
                header("Location: ../index.html?error=user_inactive");
                exit();
            }
        }
    }

    // Si las credenciales son incorrectas
    header("Location: ../index.html?error=invalidcredentials");
    exit();
}
?>