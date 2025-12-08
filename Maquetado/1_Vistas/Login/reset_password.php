<?php
$token = $_GET['token'] ?? '';
$user_id = $_GET['user_id'] ?? '';
// Lógica para mostrar mensajes de estado
$status_msg = '';
if (isset($_GET['status'])) {
    $alert_type = $_GET['status'] == 'success' ? 'success' : 'danger';
    $msg_code = $_GET['msg'] ?? 'operacion_desconocida';
    $messages = [
        'password_updated' => 'Tu contraseña ha sido actualizada exitosamente. Ya puedes iniciar sesión.',
        'invalid_token' => 'El código de verificación es inválido o ha expirado. Por favor, solicita uno nuevo.',
        'password_mismatch' => 'Las contraseñas no coinciden.',
        'invalid_request' => 'Solicitud no válida.',
    ];
    $message = $messages[$msg_code] ?? 'Ocurrió un error.';
    $status_msg = "
    <div class='alert alert-{$alert_type} alert-dismissible fade show' role='alert'>
        {$message}
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>
    ";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Restablecer Contraseña - SWGPI</title>
    <meta name="description" content="Restablecer contraseña para el Sistema de Gestión de Proyectos Integradores.">

    <!-- Favicons -->
    <link href="assets/img/ITSSMT/ITSSMT.png" rel="icon">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="assets/css/main.css" rel="stylesheet">
</head>
<body class="news-page">

<header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">
        <a href="../index.html" class="logo d-flex align-items-center">
            <i class="bi bi-buildings"></i>
            <h1 class="sitename">Gestion de Proyectos Integradores ITSSMT</h1>
        </a>
    </div>
</header>

<main class="main">
    <section id="login-section" class="login-section section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-8">
                    <div class="card shadow-lg">
                        <div class="card-body p-5">
                            <h2 class="card-title text-center mb-4">Restablecer Contraseña</h2>

                            <!-- Mensajes de estado -->
                            <div id="status-messages"><?php echo $status_msg; ?></div>

                            <form action="password_reset_actions.php" method="post">
                                <input type="hidden" name="action" value="reset_password">
                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">

                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="token" name="token" value="<?php echo htmlspecialchars($token); ?>" placeholder="Pega aquí el código de tu correo" required>
                                    <label for="token">Código de Verificación</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Nueva Contraseña" required>
                                    <label for="new_password">Nueva Contraseña</label>
                                </div>

                                <div class="form-floating mb-4">
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirmar Nueva Contraseña" required>
                                    <label for="confirm_password">Confirmar Nueva Contraseña</label>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
                                </div>
                            </form>
                            <div class="text-center mt-3">
                                <a href="../index.html">Volver al inicio de sesión</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>