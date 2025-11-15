<?php
// Adapted login handler. Uses phone (telefonos) or username to authenticate against main_view `users` table.
session_start();
require_once __DIR__ . '/config/conexion.php';
require_once __DIR__ . '/config/AuthValidator.php';

$conexion = dbConectar();

$identifier = trim($_POST['correo'] ?? $_POST['usuario'] ?? ''); // keep 'correo' name for compatibility
$password = $_POST['contra'] ?? '';

function mostrarSweetAlert($titulo, $mensaje, $icono, $redireccion) {
    $titulo = htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8');
    $mensaje = htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8');
    $icono = htmlspecialchars($icono, ENT_QUOTES, 'UTF-8');
    $redireccion = htmlspecialchars($redireccion, ENT_QUOTES, 'UTF-8');
    echo "
    <!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <title>Mensaje</title>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
        <script>
            Swal.fire({
                title: '$titulo',
                text: '$mensaje',
                icon: '$icono',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                window.location.href = '$redireccion';
            });
        </script>
    </body>
    </html>
    ";
}

if ($identifier !== '' && $password !== '') {
    // Try to find the user by telefonos (phone) or nombres (username)
    $sql = $conexion->prepare("SELECT * FROM users WHERE telefonos = ? OR nombres = ? LIMIT 1");
    $sql->bind_param("ss", $identifier, $identifier);
    $sql->execute();
    $result = $sql->get_result();

    if ($result && $result->num_rows === 1) {
        $usuario = $result->fetch_assoc();

        // Validate password using AuthValidator (supports bcrypt + plaintext fallback)
        $stored = $usuario['password'] ?? $usuario['pass'] ?? '';
        if (AuthValidator::validatePassword($password, $stored)) {
            // Set session values consistent with main_view
            $_SESSION['usuario'] = $usuario['nombres'] ?? '';
            $_SESSION['apa'] = $usuario['apa'] ?? '';
            $_SESSION['ama'] = $usuario['ama'] ?? '';
            $_SESSION['idtipo'] = $usuario['perfil_id'] ?? $usuario['idtipo'] ?? 2;
            $_SESSION['active'] = true;

            $nombreCompleto = trim( ($usuario['nombres'] ?? '') . ' ' . ($usuario['apa'] ?? '') . ' ' . ($usuario['ama'] ?? '') );
            mostrarSweetAlert('Bienvenido', ' ' . $nombreCompleto, 'success', '../Cliente/lobby.php');
            exit;
        } else {
            mostrarSweetAlert('Error', 'Usuario o contraseña incorrectos', 'error', '../index.php');
            exit;
        }
    } else {
        mostrarSweetAlert('Error', 'Usuario o contraseña incorrectos', 'error', '../index.php');
        exit;
    }
} else {
    mostrarSweetAlert('Campos Vacíos', 'Por favor, complete todos los campos', 'warning', '../index.php');
    exit;
}

?>
