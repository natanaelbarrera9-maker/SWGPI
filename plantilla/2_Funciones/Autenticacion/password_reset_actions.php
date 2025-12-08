<?php
require_once __DIR__ . '/../Servidor/db.php';
require_once __DIR__ . '/../Servidor/config/AuthValidator.php';
require_once __DIR__ . '/../vendor/autoload.php'; // Carga PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {

    // --- ACCIÓN 1: Solicitar reseteo de contraseña ---
    if ($_POST['action'] == 'request_reset') {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

        if (!$email) {
            header('Location: ../index.html?recovery_step=1&status=error&msg=invalid_email');
            exit();
        }

        // Buscar el ID del usuario a partir de su correo
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            $user_id = $user['id']; // Obtenemos el ID del usuario encontrado
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Si el campo no es un email válido, no podemos enviar.
                // Redirigimos con un mensaje genérico para no revelar información.
                header('Location: ../index.html?recovery_step=2&status=success'); // Generic success message
                exit();
            }

            // Generar un token seguro
            $token = bin2hex(random_bytes(32));
            $expires = new DateTime('now + 1 hour');
            $expires_at = $expires->format('Y-m-d H:i:s');

            // Eliminar tokens antiguos para este user_id para evitar acumulación
            $stmt_delete_old = $conn->prepare("DELETE FROM password_resets WHERE user_id = ?");
            $stmt_delete_old->bind_param("s", $user_id);
            $stmt_delete_old->execute();

            // Guardar el token en la base de datos
            $stmt_insert = $conn->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
            $stmt_insert->bind_param("sss", $user_id, $token, $expires_at);
            $stmt_insert->execute();

            // Enviar el correo electrónico
            $mail = new PHPMailer(true);
            try {
                
                $mail->isSMTP();
                $mail->Host       = 'smtp.sendgrid.net';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'apikey'; // Esto es literal, no lo cambies.
                // Load SendGrid API key from environment or configuration.
                // Do NOT hardcode secrets in source. Set SENDGRID_API_KEY in your server environment.
                $mail->Password   = getenv('SENDGRID_API_KEY') ?: 'REPLACE_WITH_SENDGRID_KEY';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                // Remitente y destinatario
                $mail->setFrom('pruebastecnm7@gmail.com', 'Soporte SWGPI'); // USA EL CORREO QUE VERIFICASTE EN SENDGRID
                $mail->addAddress($email);

                // Contenido del correo
                $mail->CharSet = 'UTF-8'; // Asegurar la codificación correcta para tildes y eñes
                $mail->isHTML(true);
                $mail->Subject = 'Recuperacion de Contrasena - SWGPI';
                $reset_link = "http://{$_SERVER['HTTP_HOST']}/PI/Cliente/reset_password.php?token={$token}&user_id={$user_id}";
                $mail->Body    = "Hola,<br><br>Has solicitado restablecer tu contraseña. Aquí está tu código de verificación:<br><br><b>{$token}</b><br><br>Puedes hacer clic en el siguiente enlace para ir directamente a la página de reseteo:<br><a href='{$reset_link}'>Restablecer mi contraseña</a><br><br>Si no solicitaste esto, puedes ignorar este correo.<br><br>Saludos,<br>Equipo de SWGPI";
                $mail->AltBody = "Hola,\n\nTu código de verificación es: {$token}\n\nPuedes usar este enlace para restablecer tu contraseña: {$reset_link}";

                $mail->send();
                header('Location: ../index.html?recovery_step=2&user_id=' . urlencode($user['id']) . '&status=success');

            } catch (Exception $e) {
                // Log del error para depuración, no mostrar al usuario
                error_log("Mailer Error: {$mail->ErrorInfo}");
                header('Location: ../index.html?recovery_step=1&status=error&msg=email_error');
            }
            exit();
        } else {
            // Usuario no encontrado, redirigir con mensaje genérico para seguridad
            // Para no revelar si un email existe, mostramos el mismo mensaje que si se hubiera enviado.
            // Pasamos el email para mantener la consistencia en la URL, aunque no se use si el usuario no existe.
            header('Location: ../index.html?recovery_step=2&user_id=&status=success');
        }
        exit();
    }

    // --- ACCIÓN 2: Verificar el token ingresado por el usuario ---
    if ($_POST['action'] == 'verify_token') {
        $token = $_POST['token'] ?? '';
        $user_id = $_POST['user_id'] ?? '';

        $redirect_url_error = "../index.html?recovery_step=2&user_id=" . urlencode($user_id);

        // Validar el token
        $stmt = $conn->prepare("SELECT * FROM password_resets WHERE token = ? AND user_id = ? AND expires_at > NOW()");
        $stmt->bind_param("ss", $token, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->fetch_assoc()) {
            // Token válido, redirigir a la página de reseteo final
            header("Location: reset_password.php?token={$token}&user_id={$user_id}");
        } else {
            // Token inválido o expirado
            header("Location: {$redirect_url_error}&status=error&msg=invalid_token");
        }
        exit();
    }

    // --- ACCIÓN 3: Restablecer la contraseña (paso final) ---
    if ($_POST['action'] == 'reset_password') {
        $token = $_POST['token'] ?? '';
        $user_id = $_POST['user_id'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        $redirect_url = "reset_password.php?token={$token}&user_id={$user_id}";

        if (empty($token) || empty($user_id) || empty($new_password) || empty($confirm_password)) {
            header("Location: {$redirect_url_error}&status=error&msg=invalid_request");
            exit();
        }

        if ($new_password !== $confirm_password) {
            header("Location: {$redirect_url}&status=error&msg=password_mismatch");
            exit();
        }

        // Validar el token
        $stmt = $conn->prepare("SELECT * FROM password_resets WHERE token = ? AND user_id = ? AND expires_at > NOW()");
        $stmt->bind_param("ss", $token, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($reset_request = $result->fetch_assoc()) {
            // Token válido, actualizar la contraseña del usuario
            $hashed_password = AuthValidator::hashPassword($new_password);

            $stmt_update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt_update->bind_param("ss", $hashed_password, $user_id);
            $stmt_update->execute();

            // Invalidar el token para que no se pueda volver a usar
            $stmt_delete = $conn->prepare("DELETE FROM password_resets WHERE id = ?");
            $stmt_delete->bind_param("i", $reset_request['id']);
            $stmt_delete->execute();
            
            header('Location: reset_password.php?status=success&msg=password_updated');

        } else {
            // Token inválido o expirado
            header("Location: {$redirect_url}&status=error&msg=invalid_token");
        }
        exit();
    }
}

// Redirigir si no se especifica una acción válida
header('Location: ../index.html');
exit();
?>
